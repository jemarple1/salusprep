<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;
use Throwable;

class PasswordResetController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::sendResetLink($request->only('email'));
        } catch (Throwable $exception) {
            report($exception);
            Log::error('Password reset email failed to send.', [
                'email' => $request->input('email'),
                'mailer' => config('mail.default'),
            ]);

            return back()
                ->withErrors(['email' => 'We could not send the reset email right now. Please try again in a few minutes.'])
                ->onlyInput('email');
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()
                ->withErrors(['email' => __($status)])
                ->onlyInput('email');
        }

        return back()->with('success', 'If an account exists for that email, we sent a password reset link.');
    }

    public function edit(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Your password has been reset. You can log in now.');
        }

        return back()
            ->withErrors(['email' => __($status)])
            ->onlyInput('email');
    }
}
