<x-mail.layout>
    <h1 style="margin: 0 0 12px; font-size: 24px; line-height: 1.3; color: #ffffff;">Reset your password</h1>

    <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
        Hi {{ $user->name }}, we received a request to reset the password for your SalusPrep account.
    </p>

    <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
        Click the button below to choose a new password. This link expires in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.
    </p>

    <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #16a34a; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 20px; border-radius: 12px;">
        Reset password
    </a>

    <p style="margin: 24px 0 0; font-size: 14px; line-height: 1.6; color: #94a3b8;">
        If you did not request a password reset, you can safely ignore this email.
    </p>
</x-mail.layout>
