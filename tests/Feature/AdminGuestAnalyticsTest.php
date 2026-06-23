<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ExamSession;
use App\Models\GuestDevice;
use App\Models\GuestPageVisit;
use App\Models\GuestSectionProgress;
use App\Models\User;
use App\Services\GuestService;
use App\Support\CertificationLevel;
use App\Support\GuestNickname;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminGuestAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_guest_visitor_section(): void
    {
        $deviceId = (string) Str::uuid();

        GuestDevice::query()->create([
            'device_id' => $deviceId,
            'display_name' => GuestNickname::fromDeviceId($deviceId),
            'first_ip' => '203.0.113.10',
            'country_code' => 'US',
            'country_name' => 'United States',
            'latitude' => 39.8283,
            'longitude' => -98.5795,
            'referrer_host' => 'google.com',
            'landing_path' => 'emt-basic',
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'first_seen_at' => now()->subHour(),
            'last_seen_at' => now(),
            'total_active_seconds' => 900,
        ]);

        GuestSectionProgress::query()->create([
            'device_id' => $deviceId,
            'guest_token' => $deviceId,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'preview_started_at' => now()->subHour(),
            'preview_actions_used' => 0,
        ]);

        ExamSession::query()->create([
            'device_id' => $deviceId,
            'guest_token' => (string) Str::uuid(),
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 3,
            'questions_answered' => 12,
            'status' => ExamSession::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        $nickname = GuestNickname::fromDeviceId($deviceId);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get('/admin')
            ->assertOk()
            ->assertSee('Guest visitors')
            ->assertSee('All guest visitors')
            ->assertSee($nickname, false)
            ->assertSee('google / cpc')
            ->assertSee('United States')
            ->assertSee('12');
    }

    public function test_guest_device_is_tracked_on_first_visit(): void
    {
        $this->withHeader('Referer', 'https://google.com/search?q=emt+practice')
            ->withHeader('CF-IPCountry', 'US')
            ->get('/emt-basic?utm_source=google&utm_medium=cpc')
            ->assertOk();

        $deviceId = GuestDevice::query()->value('device_id');

        $this->assertNotNull($deviceId);

        $device = GuestDevice::query()->find($deviceId);

        $this->assertSame('US', $device->country_code);
        $this->assertSame('google', $device->utm_source);
        $this->assertSame('cpc', $device->utm_medium);
        $this->assertSame('google.com', $device->referrer_host);
        $this->assertNotNull($device->display_name);
        $this->assertSame(GuestNickname::fromDeviceId($deviceId), $device->display_name);

        $this->assertSame(1, GuestPageVisit::query()->where('device_id', $deviceId)->count());
    }

    public function test_guest_visitors_table_paginates_and_sorts(): void
    {
        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        for ($index = 0; $index < 21; $index++) {
            $deviceId = (string) Str::uuid();

            GuestDevice::query()->create([
                'device_id' => $deviceId,
                'display_name' => GuestNickname::fromDeviceId($deviceId),
                'country_name' => $index === 0 ? 'Zimbabwe' : 'United States',
                'first_seen_at' => now()->subDays($index),
                'last_seen_at' => now()->subDays($index),
            ]);
        }

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get('/admin')
            ->assertOk()
            ->assertSee('page 1 of 2', false)
            ->assertSee('guest_sort=location', false);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get('/admin?guest_sort=visitor&guest_dir=asc')
            ->assertOk()
            ->assertSee('guest_sort=visitor', false);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get('/admin?guest_page=2')
            ->assertOk()
            ->assertSee('page 2 of 2', false);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get('/admin?guest_sort=location&guest_dir=desc')
            ->assertOk()
            ->assertSee('Zimbabwe');
    }

    public function test_guest_device_marked_converted_on_signup(): void
    {
        $deviceId = (string) Str::uuid();

        GuestDevice::query()->create([
            'device_id' => $deviceId,
            'first_seen_at' => now()->subDay(),
            'last_seen_at' => now()->subDay(),
        ]);

        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $this->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ])
            ->assertRedirect();

        $device = GuestDevice::query()->find($deviceId);

        $this->assertSame($user->id, $device->converted_user_id);
        $this->assertNotNull($device->converted_at);
    }

    public function test_admin_guest_profile_shows_page_visit_timeline(): void
    {
        $deviceId = (string) Str::uuid();
        $nickname = GuestNickname::fromDeviceId($deviceId);

        GuestDevice::query()->create([
            'device_id' => $deviceId,
            'display_name' => $nickname,
            'country_name' => 'United States',
            'landing_path' => 'emt-basic',
            'first_seen_at' => now()->subHour(),
            'last_seen_at' => now(),
            'total_active_seconds' => 120,
        ]);

        GuestPageVisit::query()->create([
            'device_id' => $deviceId,
            'path' => 'emt-basic',
            'route_name' => 'platform.home',
            'visited_at' => now()->subMinutes(10),
        ]);

        GuestPageVisit::query()->create([
            'device_id' => $deviceId,
            'path' => 'emt-basic/exam/1',
            'route_name' => 'exam.show',
            'visited_at' => now()->subMinutes(5),
        ]);

        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get('/admin/guests/'.$deviceId)
            ->assertOk()
            ->assertSee('Guest profile')
            ->assertSee($nickname, false)
            ->assertSee('Page visits')
            ->assertSee('platform › home', false)
            ->assertSee('exam › show', false)
            ->assertSee('emt-basic/exam/1', false);
    }
}
