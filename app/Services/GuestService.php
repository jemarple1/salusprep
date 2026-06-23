<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\GuestDevice;
use App\Models\GuestSectionProgress;
use App\Models\SectionAccess;
use App\Models\StudySession;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class GuestService
{
    public const SESSION_KEY = 'guest_token';

    public const DEVICE_COOKIE_KEY = 'salusprep_preview_device';

    public function token(Request $request): string
    {
        $token = $request->session()->get(self::SESSION_KEY);

        if (! is_string($token) || $token === '') {
            $token = (string) Str::uuid();
            $request->session()->put(self::SESSION_KEY, $token);
        }

        return $token;
    }

    public function deviceId(Request $request): string
    {
        $deviceId = $request->cookie(self::DEVICE_COOKIE_KEY);

        if (is_string($deviceId) && Str::isUuid($deviceId)) {
            return $deviceId;
        }

        $deviceId = (string) Str::uuid();

        Cookie::queue($this->deviceCookie($deviceId));

        return $deviceId;
    }

    public function trackDeviceVisit(Request $request): void
    {
        if ($request->user() !== null) {
            return;
        }

        $this->captureMarketingParams($request);

        $deviceId = $this->deviceId($request);
        $now = now();
        $device = GuestDevice::query()->find($deviceId);

        if ($device === null) {
            $geo = app(SignupGeoService::class)->geoFromRequest($request);
            $marketing = $this->marketingParams($request);
            $referrer = $this->externalReferrer($request);

            GuestDevice::query()->create([
                'device_id' => $deviceId,
                'first_ip' => $geo['ip'],
                'country_code' => $geo['country_code'],
                'country_name' => $geo['country_name'],
                'latitude' => $geo['latitude'],
                'longitude' => $geo['longitude'],
                'referrer' => $referrer['referrer'] ?? null,
                'referrer_host' => $referrer['referrer_host'] ?? null,
                'landing_path' => Str::limit($request->path(), 512, ''),
                'utm_source' => $marketing['utm_source'],
                'utm_medium' => $marketing['utm_medium'],
                'utm_campaign' => $marketing['utm_campaign'],
                'first_seen_at' => $now,
                'last_seen_at' => $now,
            ]);

            return;
        }

        $secondsSinceLast = min(300, max(0, (int) $device->last_seen_at->diffInSeconds($now)));

        $device->update([
            'last_seen_at' => $now,
            'total_active_seconds' => $device->total_active_seconds + $secondsSinceLast,
        ]);
    }

    public function activityDeviceId(Request $request): string
    {
        return $this->deviceId($request);
    }

    public function ensureSectionPreview(Request $request, string $certificationLevel): CarbonInterface
    {
        return $this->previewStartedAt($request, $certificationLevel);
    }

    public function previewStartedAt(Request $request, string $certificationLevel): CarbonInterface
    {
        $user = $request->user();

        if ($user !== null && ! $user->hasSectionAccess($certificationLevel)) {
            $access = SectionAccess::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'certification_level' => $certificationLevel,
                ],
                ['preview_actions_used' => 0],
            );

            if ($access->preview_started_at === null) {
                $guestStart = GuestSectionProgress::query()
                    ->where('device_id', $this->deviceId($request))
                    ->where('certification_level', $certificationLevel)
                    ->value('preview_started_at');

                $access->update([
                    'preview_started_at' => $guestStart ?? now(),
                ]);
                $access->refresh();
            }

            return $access->preview_started_at;
        }

        return $this->ensureGuestSectionPreviewStarted($request, $certificationLevel);
    }

    private function ensureGuestSectionPreviewStarted(Request $request, string $certificationLevel): CarbonInterface
    {
        $deviceId = $this->deviceId($request);

        $progress = GuestSectionProgress::firstOrCreate(
            [
                'device_id' => $deviceId,
                'certification_level' => $certificationLevel,
            ],
            [
                'guest_token' => $deviceId,
                'preview_actions_used' => 0,
                'preview_started_at' => now(),
            ],
        );

        if ($progress->preview_started_at === null) {
            $progress->update(['preview_started_at' => now()]);
            $progress->refresh();
        }

        return $progress->preview_started_at;
    }

    public function progressForRequest(Request $request, string $certificationLevel): GuestSectionProgress
    {
        return $this->progressByDevice($this->deviceId($request), $certificationLevel);
    }

    public function progressByDevice(string $deviceId, string $certificationLevel): GuestSectionProgress
    {
        return GuestSectionProgress::firstOrCreate(
            [
                'device_id' => $deviceId,
                'certification_level' => $certificationLevel,
            ],
            [
                'guest_token' => $deviceId,
                'preview_actions_used' => 0,
                'preview_started_at' => now(),
            ],
        );
    }

    /** @return \Symfony\Component\HttpFoundation\Cookie */
    public function deviceCookie(string $deviceId)
    {
        return cookie(
            name: self::DEVICE_COOKIE_KEY,
            value: $deviceId,
            minutes: 60 * 24 * 400,
            path: '/',
            secure: config('session.secure', false),
            httpOnly: true,
            raw: false,
            sameSite: 'lax',
        );
    }

    public function activeExamSession(string $guestToken, string $certificationLevel): ?ExamSession
    {
        return ExamSession::query()
            ->where('guest_token', $guestToken)
            ->whereNull('user_id')
            ->where('certification_level', $certificationLevel)
            ->where('status', ExamSession::STATUS_IN_PROGRESS)
            ->latest()
            ->first();
    }

    public function mergeIntoUser(Request $request, User $user): void
    {
        $deviceId = $request->cookie(self::DEVICE_COOKIE_KEY);
        if (! is_string($deviceId) || ! Str::isUuid($deviceId)) {
            $deviceId = $this->deviceId($request);
        }

        $this->mergeDeviceProgressIntoUser($user, $deviceId);

        $guestToken = $request->session()->get(self::SESSION_KEY);

        if (is_string($guestToken) && $guestToken !== '') {
            ExamSession::query()
                ->where('guest_token', $guestToken)
                ->whereNull('user_id')
                ->update([
                    'user_id' => $user->id,
                    'guest_token' => null,
                    'device_id' => $deviceId,
                ]);

            StudySession::query()
                ->where('guest_token', $guestToken)
                ->whereNull('user_id')
                ->update([
                    'user_id' => $user->id,
                    'guest_token' => null,
                    'device_id' => $deviceId,
                ]);
        }

        app(FocusCategoryService::class)->persistSessionToUser($request, $user);

        if (is_string($guestToken) && $guestToken !== '') {
            app(ExerciseProgressService::class)->mergeGuestIntoUser($guestToken, $user);
        }

        GuestSectionProgress::query()
            ->where('device_id', $deviceId)
            ->delete();

        GuestDevice::query()
            ->where('device_id', $deviceId)
            ->whereNull('converted_user_id')
            ->update([
                'converted_user_id' => $user->id,
                'converted_at' => now(),
            ]);
    }

    private function captureMarketingParams(Request $request): void
    {
        $found = [];

        foreach (['utm_source', 'utm_medium', 'utm_campaign'] as $key) {
            $value = $request->query($key);

            if (is_string($value) && $value !== '') {
                $found[$key] = Str::limit($value, 255, '');
            }
        }

        if ($found !== []) {
            $request->session()->put('guest_marketing', $found);
        }
    }

    /** @return array{utm_source: ?string, utm_medium: ?string, utm_campaign: ?string} */
    private function marketingParams(Request $request): array
    {
        $stored = $request->session()->get('guest_marketing', []);

        if (! is_array($stored)) {
            $stored = [];
        }

        return [
            'utm_source' => isset($stored['utm_source']) ? (string) $stored['utm_source'] : null,
            'utm_medium' => isset($stored['utm_medium']) ? (string) $stored['utm_medium'] : null,
            'utm_campaign' => isset($stored['utm_campaign']) ? (string) $stored['utm_campaign'] : null,
        ];
    }

    /** @return array{referrer: string, referrer_host: string}|null */
    private function externalReferrer(Request $request): ?array
    {
        $referrer = $request->headers->get('referer');

        if (! is_string($referrer) || $referrer === '') {
            return null;
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        if (! is_string($host) || $host === '' || $host === $request->getHost()) {
            return null;
        }

        return [
            'referrer' => Str::limit($referrer, 2048, ''),
            'referrer_host' => Str::limit($host, 255, ''),
        ];
    }

    private function mergeDeviceProgressIntoUser(User $user, string $deviceId): void
    {
        foreach (GuestSectionProgress::where('device_id', $deviceId)->get() as $guestProgress) {
            $access = SectionAccess::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'certification_level' => $guestProgress->certification_level,
                ],
                ['preview_actions_used' => 0],
            );

            if ($guestProgress->preview_started_at !== null && (
                $access->preview_started_at === null
                || $guestProgress->preview_started_at->lt($access->preview_started_at)
            )) {
                $access->update(['preview_started_at' => $guestProgress->preview_started_at]);
            }
        }
    }
}
