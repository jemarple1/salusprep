<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUnlockTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_always_shows_full_access_option(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('Get Full Access now')
            ->assertSee('Platform to unlock')
            ->assertSee('20-minute preview');
    }

    public function test_register_page_preselects_section_from_session(): void
    {
        $this->withSession([
            AuthController::REGISTER_SECTION_SESSION_KEY => 'nclex-pn',
        ])
            ->get('/register')
            ->assertOk()
            ->assertSee('value="nclex-pn"', false);
    }

    public function test_register_with_unlock_redirects_to_stripe_checkout(): void
    {
        $this->post('/register', [
            'name' => 'Alex Rivera',
            'email' => 'alex@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
            'signup_plan' => 'unlock',
            'unlock_section' => 'emt-basic',
        ])
            ->assertRedirect(route('platform.checkout', 'emt-basic'));

        $this->assertAuthenticated();
    }

    public function test_register_without_unlock_redirects_to_source_section(): void
    {
        $this->withSession([
            AuthController::REGISTER_SECTION_SESSION_KEY => 'nclex-pn',
        ])
            ->post('/register', [
                'name' => 'Jordan Lee',
                'email' => 'jordan@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'terms' => '1',
                'signup_plan' => 'free',
            ])
            ->assertRedirect(route('platform.home', 'nclex-pn'));

        $this->assertAuthenticated();
    }
}
