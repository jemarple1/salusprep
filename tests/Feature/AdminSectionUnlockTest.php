<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SectionAccess;
use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSectionUnlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_unlock_section_for_user(): void
    {
        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        $user = User::factory()->create();

        $this->actingAs(Admin::query()->first(), 'admin')
            ->post(route('admin.users.unlock', $user), [
                'certification_level' => CertificationLevel::EMT_BASIC,
            ])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('section_accesses', [
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
        ]);

        $access = SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->first();

        $this->assertNotNull($access?->unlocked_at);
    }

    public function test_guest_cannot_unlock_sections(): void
    {
        $user = User::factory()->create();

        $this->post(route('admin.users.unlock', $user), [
            'certification_level' => CertificationLevel::EMT_BASIC,
        ])->assertRedirect(route('admin.login'));
    }

    public function test_admin_unlock_requires_valid_certification_level(): void
    {
        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        $user = User::factory()->create();

        $this->actingAs(Admin::query()->first(), 'admin')
            ->post(route('admin.users.unlock', $user), [
                'certification_level' => 'invalid_level',
            ])
            ->assertSessionHasErrors('certification_level');
    }
}
