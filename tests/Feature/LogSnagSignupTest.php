<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LogSnagSignupTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_logsnag_signup_event(): void
    {
        Mail::fake();

        config([
            'services.logsnag.token' => 'test-token',
            'services.logsnag.project' => 'words-of-the-lotus-born',
            'services.logsnag.channel' => 'salusprep',
            'services.logsnag.signup_event' => 'new sign up',
            'services.logsnag.icon' => '🚑',
        ]);

        Http::fake([
            'api.logsnag.com/*' => Http::response(null, 200),
        ]);

        $this->post('/register', [
            'name' => 'Jordan Smith',
            'email' => 'jordan@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
            'signup_plan' => 'free',
        ])->assertRedirect();

        Http::assertSent(function ($request) {
            $data = $request->data();

            return $request->url() === 'https://api.logsnag.com/v1/log'
                && $request->hasHeader('Authorization', 'Bearer test-token')
                && ($data['project'] ?? null) === 'words-of-the-lotus-born'
                && ($data['channel'] ?? null) === 'salusprep'
                && ($data['event'] ?? null) === 'new sign up'
                && ($data['icon'] ?? null) === '🚑'
                && ($data['notify'] ?? null) === true
                && str_contains((string) ($data['description'] ?? ''), 'jordan@example.com');
        });
    }

    public function test_registration_skips_logsnag_when_token_missing(): void
    {
        Mail::fake();

        config(['services.logsnag.token' => null]);

        Http::fake();

        $this->post('/register', [
            'name' => 'Jordan Smith',
            'email' => 'jordan2@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
            'signup_plan' => 'free',
        ])->assertRedirect();

        Http::assertNothingSent();
    }
}
