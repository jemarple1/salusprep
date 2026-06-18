<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalusPrep</title>
</head>
<body style="margin: 0; padding: 24px; background-color: #f1f5f9; font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: #334155;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 560px; margin: 0 auto;">
        <tr>
            <td style="background-color: #0f172a; border-radius: 16px; padding: 32px; border: 1px solid rgba(255,255,255,0.08);">
                <x-mail.header />

                {{ $slot }}

                <p style="margin: 32px 0 0; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 12px; line-height: 1.6; color: #64748b;">
                    SalusPrep · Amherst, Massachusetts<br>
                    <a href="{{ config('app.url') }}" style="color: #4ade80; text-decoration: none;">{{ config('app.url') }}</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
