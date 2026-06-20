<?php

namespace Tests\Feature;

use App\Models\PreviewDevice;
use App\Models\User;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class PreviewTimerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_preview_timer_for_active_guest(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $deviceId = (string) Str::uuid();
        PreviewDevice::query()->create([
            'device_id' => $deviceId,
            'preview_started_at' => now(),
        ]);

        $this->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->get('/emt-basic')
            ->assertOk()
            ->assertSee('id="preview-timer"', false)
            ->assertSee('◷')
            ->assertSee(route('platform.paywall', 'emt-basic'), false);

        Carbon::setTestNow();
    }

    public function test_paywall_is_available_during_active_preview(): void
    {
        Carbon::setTestNow('2026-06-01 12:00:00');

        $deviceId = (string) Str::uuid();
        PreviewDevice::query()->create([
            'device_id' => $deviceId,
            'preview_started_at' => now(),
        ]);

        $this->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->get('/emt-basic/unlock')
            ->assertOk()
            ->assertSee('Preview ends in')
            ->assertSee('free preview time left');

        Carbon::setTestNow();
    }

    public function test_unlocked_user_timer_links_to_welcome_page(): void
    {
        $user = User::factory()->create();
        $user->sectionAccesses()->create([
            'certification_level' => CertificationLevel::EMT_BASIC,
            'unlocked_at' => now(),
        ]);

        $this->actingAs($user)
            ->get('/emt-basic')
            ->assertOk()
            ->assertSee(route('platform.welcome', 'emt-basic'), false)
            ->assertSee('✓');
    }
}
