<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\GuestDevice;
use App\Models\StudyClubMember;
use App\Services\GuestService;
use App\Support\CertificationLevel;
use App\Support\GuestNickname;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class StudyClubAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_lists_study_club_emails_comma_separated(): void
    {
        $deviceId = (string) Str::uuid();

        GuestDevice::query()->create([
            'device_id' => $deviceId,
            'display_name' => GuestNickname::fromDeviceId($deviceId),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        StudyClubMember::query()->create([
            'email' => 'alpha@example.com',
            'device_id' => $deviceId,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'joined_at' => now(),
            'unsubscribe_token' => Str::random(48),
        ]);

        StudyClubMember::query()->create([
            'email' => 'beta@example.com',
            'device_id' => (string) Str::uuid(),
            'certification_level' => CertificationLevel::EMT_BASIC,
            'joined_at' => now(),
            'unsubscribe_token' => Str::random(48),
        ]);

        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get('/admin')
            ->assertOk()
            ->assertSee('Study Pass emails')
            ->assertSee('alpha@example.com, beta@example.com', false)
            ->assertSee(GuestNickname::fromDeviceId($deviceId), false);
    }

    public function test_guest_profile_shows_study_club_email(): void
    {
        $deviceId = (string) Str::uuid();

        $guest = GuestDevice::query()->create([
            'device_id' => $deviceId,
            'display_name' => GuestNickname::fromDeviceId($deviceId),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        StudyClubMember::query()->create([
            'email' => 'guest@example.com',
            'device_id' => $deviceId,
            'joined_at' => now(),
            'unsubscribe_token' => Str::random(48),
        ]);

        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->get(route('admin.guests.show', $guest))
            ->assertOk()
            ->assertSee('guest@example.com');
    }

    public function test_register_prefills_study_club_email_from_device(): void
    {
        $deviceId = (string) Str::uuid();

        StudyClubMember::query()->create([
            'email' => 'preview@example.com',
            'device_id' => $deviceId,
            'joined_at' => now(),
            'unsubscribe_token' => Str::random(48),
        ]);

        $this->withCookie(GuestService::DEVICE_COOKIE_KEY, $deviceId)
            ->get('/register')
            ->assertOk()
            ->assertSee('value="preview@example.com"', false)
            ->assertSee('Pre-filled from your Study Pass signup');
    }
}
