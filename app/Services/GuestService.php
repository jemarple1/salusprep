<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\GuestSectionProgress;
use App\Models\SectionAccess;
use App\Models\User;
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

    /**
     * Persistent device identifier (long-lived cookie) so preview usage survives
     * new sessions, logout, and incognito-style session resets.
     */
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
                ]);
        }

        app(FocusCategoryService::class)->persistSessionToUser($request, $user);

        if (is_string($guestToken) && $guestToken !== '') {
            app(ExerciseProgressService::class)->mergeGuestIntoUser($guestToken, $user);
        }

        GuestSectionProgress::query()
            ->where('device_id', $deviceId)
            ->delete();
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

            if ($guestProgress->preview_actions_used > $access->preview_actions_used) {
                $access->update(['preview_actions_used' => $guestProgress->preview_actions_used]);
            }
        }
    }
}
