<?php

namespace Tests\Unit;

use App\Models\User;
use App\Support\UserAvatar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_random_color_is_one_of_five_options(): void
    {
        $color = UserAvatar::randomColor();

        $this->assertContains($color, UserAvatar::COLORS);
    }

    public function test_registration_assigns_avatar_color(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'avatar-test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
            'signup_plan' => 'free',
        ])->assertRedirect();

        $user = User::query()->where('email', 'avatar-test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertContains($user->avatar_color, UserAvatar::COLORS);
    }

    public function test_user_avatar_component_renders_health_symbol(): void
    {
        $user = User::factory()->create(['avatar_color' => 'blue']);

        $html = view('components.user-avatar', ['user' => $user])->render();

        $this->assertStringContainsString('🩺', $html);
        $this->assertStringContainsString('text-ems-light', $html);
    }
}
