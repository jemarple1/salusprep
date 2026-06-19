<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController;
use App\Models\Setting;
use App\Services\GuestService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaywallCheckoutRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_from_paywall_redirects_to_checkout(): void
    {
        Setting::set(PreviewAccessService::LIMIT_KEY, '1');

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
            ])
            ->assertRedirect(route('platform.checkout', 'emt-basic'));

        $this->assertAuthenticated();
    }
}
