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
use Illuminate\Support\Str;
use Tests\TestCase;

class PreviewAccessServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_actions_are_tracked_by_device(): void
    {
        Setting::set(PreviewAccessService::LIMIT_KEY, '3');

        $service = app(PreviewAccessService::class);
        $deviceId = (string) Str::uuid();

        $request = Request::create('/emt-basic/exam/start', 'POST');
        $request->setLaravelSession($this->app['session.store']);
        $request->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);

        $this->assertTrue($service->hasAccess($request, CertificationLevel::EMT_BASIC));

        $service->recordAction($request, CertificationLevel::EMT_BASIC);
        $service->recordAction($request, CertificationLevel::EMT_BASIC);
        $service->recordAction($request, CertificationLevel::EMT_BASIC);

        $this->assertTrue($service->requiresPaywall($request, CertificationLevel::EMT_BASIC));
        $this->assertSame(3, GuestSectionProgress::query()->where('device_id', $deviceId)->value('preview_actions_used'));
    }

    public function test_preview_persists_across_new_sessions_with_same_device(): void
    {
        Setting::set(PreviewAccessService::LIMIT_KEY, '2');

        $service = app(PreviewAccessService::class);
        $deviceId = (string) Str::uuid();

        $requestOne = Request::create('/emt-basic/exam/start', 'POST');
        $requestOne->setLaravelSession($this->app['session.store']);
        $requestOne->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);
        $requestOne->session()->put(GuestService::SESSION_KEY, (string) Str::uuid());

        $service->recordAction($requestOne, CertificationLevel::EMT_BASIC);
        $service->recordAction($requestOne, CertificationLevel::EMT_BASIC);

        $anotherSession = app('session.store');
        $anotherSession->start();

        $requestTwo = Request::create('/emt-basic', 'GET');
        $requestTwo->setLaravelSession($anotherSession);
        $requestTwo->cookies->set(GuestService::DEVICE_COOKIE_KEY, $deviceId);
        $requestTwo->session()->put(GuestService::SESSION_KEY, (string) Str::uuid());

        $this->assertTrue($service->requiresPaywall($requestTwo, CertificationLevel::EMT_BASIC));
    }

    public function test_unlocked_users_never_require_paywall(): void
    {
        $user = User::factory()->create();
        $user->sectionAccesses()->create([
            'certification_level' => CertificationLevel::EMT_BASIC,
            'preview_actions_used' => 99,
            'unlocked_at' => now(),
        ]);

        $request = Request::create('/emt-basic', 'GET');
        $request->setUserResolver(fn () => $user);

        $service = app(PreviewAccessService::class);

        $this->assertFalse($service->requiresPaywall($request, CertificationLevel::EMT_BASIC));
        $this->assertFalse($service->recordAction($request, CertificationLevel::EMT_BASIC));
    }
}
