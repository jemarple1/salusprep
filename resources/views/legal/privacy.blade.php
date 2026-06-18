@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <article class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold text-white">Privacy Policy</h1>
        <p class="mt-2 text-sm text-slate-500">Last updated: June 17, 2026</p>

        <div class="prose prose-invert mt-8 max-w-none space-y-6 text-slate-300 prose-headings:text-white prose-a:text-medic-light">
            <section>
                <h2 class="text-xl font-bold text-white">1. Overview</h2>
                <p class="leading-relaxed">SalusPrep ("we," "us") respects your privacy. This Privacy Policy describes what information we collect, how we use it, and your choices when you use salusprep.com and related services.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">2. Information We Collect</h2>
                <ul class="mt-2 list-disc space-y-2 pl-5 leading-relaxed">
                    <li><strong class="text-slate-200">Account information:</strong> name, email address, and password (stored in hashed form) when you register.</li>
                    <li><strong class="text-slate-200">Usage data:</strong> quiz answers, scores, difficulty progression, study session activity, and section access status.</li>
                    <li><strong class="text-slate-200">Guest data:</strong> a guest token and progress for free preview questions before signup.</li>
                    <li><strong class="text-slate-200">Payment data:</strong> payments are processed by Stripe. We receive transaction identifiers, purchase information, and payment status, but do not receive or store full payment card numbers.</li>
                    <li><strong class="text-slate-200">Technical data:</strong> IP address, browser type, device information, cookies, and session data necessary to operate, secure, and improve the Service.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">3. How We Use Information</h2>
                <p class="leading-relaxed">We use information to:</p>
                <ul class="mt-2 list-disc space-y-2 pl-5 leading-relaxed">
                    <li>Provide adaptive quizzes, dashboards, and study features</li>
                    <li>Process purchases and manage section unlocks</li>
                    <li>Maintain security, prevent abuse, and detect fraud</li>
                    <li>Improve and operate the Service</li>
                    <li>Communicate regarding your account, purchases, security notices, support requests, or important Service updates</li>
                    <li>Comply with legal obligations</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">4. Sharing</h2>
                <p class="leading-relaxed">We do not sell your personal information.</p>
                <p class="mt-3 leading-relaxed">We may share information with service providers that help us operate the Service, including payment processors, hosting providers, email providers, analytics providers, and similar vendors.</p>
                <p class="mt-3 leading-relaxed">We may also disclose information:</p>
                <ul class="mt-2 list-disc space-y-2 pl-5 leading-relaxed">
                    <li>When required by law or legal process</li>
                    <li>To protect the rights, safety, security, or property of SalusPrep, our users, or others</li>
                    <li>In connection with a merger, acquisition, financing, asset sale, or other business transaction involving SalusPrep</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">5. Cookies and Sessions</h2>
                <p class="leading-relaxed">We use cookies and similar technologies for authentication, session management, security, and guest progress.</p>
                <p class="mt-3 leading-relaxed">You can control cookies through your browser settings, but some features of the Service may not function properly without them.</p>
                <p class="mt-3 leading-relaxed">The Service does not currently respond to browser "Do Not Track" signals.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">6. Data Retention</h2>
                <p class="leading-relaxed">We retain account and quiz data while your account is active or as needed to provide the Service, comply with legal obligations, resolve disputes, prevent fraud, enforce our agreements, and maintain security.</p>
                <p class="mt-3 leading-relaxed">You may request account deletion by contacting us. Upon deletion, we will remove or anonymize personal information unless retention is required for legal, security, fraud-prevention, dispute-resolution, or other legitimate business purposes.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">7. Data Storage</h2>
                <p class="leading-relaxed">Information may be stored and processed in the United States.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">8. Security</h2>
                <p class="leading-relaxed">We use reasonable technical and organizational measures to protect your information. However, no method of transmission over the Internet or electronic storage is completely secure, and we cannot guarantee absolute security.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">9. Children's Privacy</h2>
                <p class="leading-relaxed">The Service is intended for individuals preparing for professional certification examinations and is not directed to children under 13 years of age.</p>
                <p class="mt-3 leading-relaxed">We do not knowingly collect personal information from children under 13. If we learn that we have collected such information, we will take reasonable steps to delete it.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">10. Your Rights</h2>
                <p class="leading-relaxed">Depending on your location and applicable law, you may have rights to access, correct, delete, or receive a copy of your personal information.</p>
                <p class="mt-3 leading-relaxed">To exercise any applicable rights, contact us using the information below.</p>
                <p class="mt-3 leading-relaxed">California residents and residents of certain other jurisdictions may have additional privacy rights under applicable law.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">11. Changes</h2>
                <p class="leading-relaxed">We may update this Privacy Policy from time to time. The "Last updated" date at the top of this policy reflects the effective date of the latest revision.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">12. Contact</h2>
                <p class="leading-relaxed">Privacy questions or requests may be directed to:</p>
                <p class="mt-2 leading-relaxed"><a href="mailto:salusprep@mail.com">salusprep@mail.com</a></p>
            </section>
        </div>
    </article>
@endsection
