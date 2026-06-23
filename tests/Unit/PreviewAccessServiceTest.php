<?php

namespace Tests\Unit;

use App\Models\GuestSectionProgress;
use App\Models\Setting;
use App\Models\User;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class PreviewAccessServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_preview_expires_after_minute_limit(): void
    {
        Setting::set(PreviewAccessService::MINUTES_KEY, '20');

        Carbon::setTestNow('2026-06-01 12:00:00');

        $service = app(PreviewAccessService::class);
        $guests = app(GuestService::class);
        $deviceId = (string) Str::uuid();

        $request = Request::create('/emt-basic/exam/start', 'POST');
        $request->setLaravelSession($this->app['session.store']);
        $request->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);

        $guests->ensureSectionPreview($request, CertificationLevel::EMT_BASIC);

        $this->assertTrue($service->hasAccess($request, CertificationLevel::EMT_BASIC));

        Carbon::setTestNow('2026-06-01 12:19:59');
        $this->assertTrue($service->hasAccess($request, CertificationLevel::EMT_BASIC));

        Carbon::setTestNow('2026-06-01 12:20:00');
        $this->assertTrue($service->requiresPaywall($request, CertificationLevel::EMT_BASIC));

        $this->assertNotNull(
            GuestSectionProgress::query()
                ->where('device_id', $deviceId)
                ->where('certification_level', CertificationLevel::EMT_BASIC)
                ->value('preview_started_at')
        );

        Carbon::setTestNow();
    }

    public function test_preview_persists_across_new_sessions_with_same_device(): void
    {
        Setting::set(PreviewAccessService::MINUTES_KEY, '20');

        Carbon::setTestNow('2026-06-01 12:00:00');

        $service = app(PreviewAccessService::class);
        $guests = app(GuestService::class);
        $deviceId = (string) Str::uuid();

        $requestOne = Request::create('/emt-basic/exam/start', 'POST');
        $requestOne->setLaravelSession($this->app['session.store']);
        $requestOne->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);
        $requestOne->session()->put(GuestService::SESSION_KEY, (string) Str::uuid());

        $guests->ensureSectionPreview($requestOne, CertificationLevel::EMT_BASIC);

        Carbon::setTestNow('2026-06-01 12:25:00');

        $anotherSession = app('session.store');
        $anotherSession->start();

        $requestTwo = Request::create('/emt-basic', 'GET');
        $requestTwo->setLaravelSession($anotherSession);
        $requestTwo->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);
        $requestTwo->session()->put(GuestService::SESSION_KEY, (string) Str::uuid());

        $this->assertTrue($service->requiresPaywall($requestTwo, CertificationLevel::EMT_BASIC));

        Carbon::setTestNow();
    }

    public function test_preview_is_tracked_independently_per_platform(): void
    {
        Setting::set(PreviewAccessService::MINUTES_KEY, '20');

        Carbon::setTestNow('2026-06-01 12:00:00');

        $service = app(PreviewAccessService::class);
        $guests = app(GuestService::class);
        $deviceId = (string) Str::uuid();

        $emtRequest = Request::create('/emt-basic', 'GET');
        $emtRequest->setLaravelSession($this->app['session.store']);
        $emtRequest->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);
        $guests->ensureSectionPreview($emtRequest, CertificationLevel::EMT_BASIC);

        Carbon::setTestNow('2026-06-01 12:25:00');
        $this->assertTrue($service->requiresPaywall($emtRequest, CertificationLevel::EMT_BASIC));

        $paramedicRequest = Request::create('/paramedic', 'GET');
        $paramedicRequest->setLaravelSession($this->app['session.store']);
        $paramedicRequest->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);
        $guests->ensureSectionPreview($paramedicRequest, CertificationLevel::PARAMEDIC);

        $this->assertTrue($service->hasAccess($paramedicRequest, CertificationLevel::PARAMEDIC));
        $this->assertFalse($service->hasAccess($paramedicRequest, CertificationLevel::EMT_BASIC));

        Carbon::setTestNow();
    }

    public function test_unlocked_users_never_require_paywall(): void
    {
        $user = User::factory()->create([
            'preview_started_at' => now()->subHours(2),
        ]);
        $user->sectionAccesses()->create([
            'certification_level' => CertificationLevel::EMT_BASIC,
            'preview_actions_used' => 0,
            'unlocked_at' => now(),
        ]);

        $request = Request::create('/emt-basic', 'GET');
        $request->setUserResolver(fn () => $user);

        $service = app(PreviewAccessService::class);

        $this->assertFalse($service->requiresPaywall($request, CertificationLevel::EMT_BASIC));
        $this->assertFalse($service->recordAction($request, CertificationLevel::EMT_BASIC));
    }
}
