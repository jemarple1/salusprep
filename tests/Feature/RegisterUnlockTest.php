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

    public function test_register_page_requires_full_access_checkout(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('Full Access')
            ->assertSee('Swipe to choose your platform')
            ->assertSee('Create account &amp; checkout', false)
            ->assertDontSee('Platform to unlock')
            ->assertDontSee('Continue with my free preview');
    }

    public function test_register_page_preselects_section_from_session(): void
    {
        $this->withSession([
            AuthController::REGISTER_SECTION_SESSION_KEY => 'nclex-pn',
        ])
            ->get('/register')
            ->assertOk()
            ->assertSee('value="nclex-pn"', false)
            ->assertSee('Practical Nurse');
    }

    public function test_register_redirects_to_stripe_checkout(): void
    {
        $this->post('/register', [
            'name' => 'Alex Rivera',
            'email' => 'alex@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
            'unlock_section' => 'emt-basic',
        ])
            ->assertRedirect(route('platform.checkout', 'emt-basic'));

        $this->assertAuthenticated();
    }

    public function test_register_requires_unlock_section(): void
    {
        $this->post('/register', [
            'name' => 'Jordan Lee',
            'email' => 'jordan@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ])
            ->assertSessionHasErrors('unlock_section');
    }
}
