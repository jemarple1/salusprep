<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Services\GuestService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaywallCheckoutRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_with_full_access_redirects_to_checkout(): void
    {
        $guestToken = (string) Str::uuid();

        $this->withSession([
            GuestService::SESSION_KEY => $guestToken,
            AuthController::PAYWALL_CHECKOUT_SESSION_KEY => 'emt-basic',
        ])
            ->post('/register', [
                'name' => 'Jordan Smith',
                'email' => 'jordan@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'terms' => '1',
                'signup_plan' => 'unlock',
                'unlock_section' => 'emt-basic',
            ])
            ->assertRedirect(route('platform.checkout', 'emt-basic'));

        $this->assertAuthenticated();
    }

    public function test_register_from_paywall_without_unlock_redirects_to_section_home(): void
    {
        $guestToken = (string) Str::uuid();

        $this->withSession([
            GuestService::SESSION_KEY => $guestToken,
            AuthController::PAYWALL_CHECKOUT_SESSION_KEY => 'emt-basic',
        ])
            ->post('/register', [
                'name' => 'Jordan Smith',
                'email' => 'jordan@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'terms' => '1',
                'signup_plan' => 'free',
            ])
            ->assertRedirect(route('platform.home', 'emt-basic'));

        $this->assertAuthenticated();
    }
}
