<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_request_sends_reset_email(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'reset-me@example.com']);

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        Mail::assertSent(ResetPasswordMail::class, function (ResetPasswordMail $mail) use ($user): bool {
            return $mail->hasTo($user->email)
                && $mail->user->is($user)
                && $mail->token !== '';
        });
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_forgot_password_does_not_send_email_for_unknown_address(): void
    {
        Mail::fake();

        $response = $this->post('/forgot-password', ['email' => 'missing@example.com']);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        Mail::assertNothingSent();
    }

    public function test_user_does_not_receive_default_laravel_reset_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        Password::sendResetLink(['email' => $user->email]);

        Notification::assertNothingSent();
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        $user = User::factory()->create(['email' => 'reset-me@example.com']);
        $token = Password::createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewPassword123!', $user->fresh()->password));
    }

    public function test_reset_password_form_is_reachable_with_token(): void
    {
        $user = User::factory()->create(['email' => 'reset-me@example.com']);
        $token = Password::createToken($user);

        $this->get('/reset-password/'.$token.'?email='.urlencode($user->email))
            ->assertOk()
            ->assertSee('Reset password')
            ->assertSee($user->email, false);
    }
}
