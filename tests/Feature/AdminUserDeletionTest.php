<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Payment;
use App\Models\SectionAccess;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_user_and_cascaded_records(): void
    {
        Admin::query()->create([
            'username' => 'admin',
            'password' => Hash::make('secret'),
        ]);

        $user = User::factory()->create();

        SectionAccess::query()->create([
            'user_id' => $user->id,
            'certification_level' => 'emt_basic',
            'unlocked_at' => now(),
        ]);

        Payment::query()->create([
            'user_id' => $user->id,
            'certification_level' => 'emt_basic',
            'amount_cents' => 2900,
            'status' => Payment::STATUS_COMPLETED,
            'provider' => 'stripe',
            'paid_at' => now(),
        ]);

        $this->actingAs(Admin::query()->first(), 'admin')
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('section_accesses', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('payments', ['user_id' => $user->id]);
    }

    public function test_guest_cannot_delete_users(): void
    {
        $user = User::factory()->create();

        $this->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.login'));
    }
}
