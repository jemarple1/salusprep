@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <article class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold text-white">Privacy Policy</h1>
        <p class="mt-2 text-sm text-slate-500">Last updated: {{ now()->format('F j, Y') }}</p>

        <div class="mt-8 space-y-6 text-slate-300">
            <section>
                <h2 class="text-xl font-bold text-white">1. Overview</h2>
                <p class="mt-2 leading-relaxed">SalusPrep (“we,” “us”) respects your privacy. This policy describes what information we collect, how we use it, and your choices when you use salusprep.com and related services.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">2. Information we collect</h2>
                <ul class="mt-2 list-disc space-y-2 pl-5 leading-relaxed">
                    <li><strong class="text-slate-200">Account information:</strong> name, email address, and password (stored hashed) when you register.</li>
                    <li><strong class="text-slate-200">Usage data:</strong> quiz answers, scores, difficulty progression, study session activity, and section access status.</li>
                    <li><strong class="text-slate-200">Guest data:</strong> a guest token and progress for free preview questions before signup.</li>
                    <li><strong class="text-slate-200">Payment data:</strong> payments are processed by Stripe. We receive transaction identifiers and purchase status, not full card numbers.</li>
                    <li><strong class="text-slate-200">Technical data:</strong> IP address, browser type, and cookies/session data needed to operate the Service.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">3. How we use information</h2>
                <ul class="mt-2 list-disc space-y-2 pl-5 leading-relaxed">
                    <li>Provide adaptive quizzes, dashboards, and study features</li>
                    <li>Process purchases and manage section unlocks</li>
                    <li>Maintain security, prevent abuse, and improve the Service</li>
                    <li>Communicate about your account or support requests when necessary</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">4. Sharing</h2>
                <p class="mt-2 leading-relaxed">We do not sell your personal information. We share data only with service providers that help us operate the Service (such as hosting and Stripe for payments), when required by law, or to protect rights and safety.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">5. Cookies and sessions</h2>
                <p class="mt-2 leading-relaxed">We use cookies and similar technologies for authentication, session management, and guest progress. You can control cookies through your browser, but some features may not work without them.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">6. Data retention</h2>
                <p class="mt-2 leading-relaxed">We retain account and quiz data while your account is active or as needed to provide the Service and meet legal obligations. You may request account deletion by contacting us.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">7. Security</h2>
                <p class="mt-2 leading-relaxed">We use reasonable technical and organizational measures to protect your information. No method of transmission or storage is completely secure.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">8. Children</h2>
                <p class="mt-2 leading-relaxed">The Service is intended for users preparing for professional certification exams and is not directed at children under 13. We do not knowingly collect data from children under 13.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">9. Your rights</h2>
                <p class="mt-2 leading-relaxed">Depending on your location, you may have rights to access, correct, or delete personal data. Contact us to make a request. California and other jurisdictions may provide additional privacy rights.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">10. Changes</h2>
                <p class="mt-2 leading-relaxed">We may update this Privacy Policy from time to time. The “Last updated” date at the top will reflect revisions.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">11. Contact</h2>
                <p class="mt-2 leading-relaxed">Privacy questions: contact us through the email or support channel listed on salusprep.com.</p>
            </section>
        </div>
    </article>
@endsection
