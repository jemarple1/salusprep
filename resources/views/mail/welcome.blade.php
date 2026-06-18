<x-mail.layout>
    <h1 style="margin: 0 0 12px; font-size: 24px; line-height: 1.3; color: #ffffff;">Welcome, {{ $user->name }}!</h1>

    <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
        Thanks for creating your SalusPrep account. You're ready to start adaptive practice for NREMT® and NCLEX-PN® exams.
    </p>

    <p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
        Each certification platform includes 25 free questions to try before you unlock unlimited access. Pick your section and start a 40-question adaptive quiz whenever you're ready.
    </p>

    <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #cbd5e1;">
        Good luck with your studies — we're glad you're here.
    </p>

    <a href="{{ url(route('platform.home', 'emt-basic')) }}" style="display: inline-block; background-color: #16a34a; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 14px; padding: 12px 20px; border-radius: 12px;">
        Start practicing
    </a>
</x-mail.layout>
