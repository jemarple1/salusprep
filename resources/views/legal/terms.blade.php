@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
    <article class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold text-white">Terms of Service</h1>
        <p class="mt-2 text-sm text-slate-500">Last updated: June 17, 2026</p>

        <div class="prose prose-invert mt-8 max-w-none space-y-6 text-slate-300 prose-headings:text-white prose-a:text-medic-light">
            <section>
                <h2 class="text-xl font-bold text-white">1. Agreement</h2>
                <p class="leading-relaxed">By accessing or using SalusPrep ("Service"), you agree to these Terms of Service. If you do not agree, do not use the Service.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">2. Eligibility</h2>
                <p class="leading-relaxed">You must be at least 13 years old (or the minimum age required in your jurisdiction) to use the Service. If you are under the age of majority, you represent that you have permission from a parent or legal guardian.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">3. Description of Service</h2>
                <p class="leading-relaxed">SalusPrep provides adaptive practice quizzes and study tools for NREMT® and NCLEX-PN® exam preparation. The Service is for educational practice only. SalusPrep is not affiliated with, endorsed by, or sponsored by the National Registry of EMTs, NCSBN, or any licensing body.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">4. Not Medical or Professional Advice</h2>
                <p class="leading-relaxed">Content on SalusPrep does not constitute medical, nursing, or emergency care advice. Do not rely on the Service for clinical decisions or patient care. Always follow your local protocols, scope of practice, and applicable laws.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">5. Accounts and Guest Use</h2>
                <p class="leading-relaxed">You may use limited features without an account. Creating an account requires accurate information and safeguarding your credentials. You are responsible for activity under your account.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">6. Payments and Refunds</h2>
                <p class="leading-relaxed">Paid section unlocks are one-time purchases processed by Stripe. Prices are shown at checkout. Except where required by law, purchases are non-refundable once access is granted.</p>
                <p class="mt-3 leading-relaxed">Each certification platform (e.g., EMT-Basic, NCLEX-PN®) is unlocked separately unless stated otherwise at purchase.</p>
                <p class="mt-3 leading-relaxed">Payment processing services are provided by Stripe and may be subject to Stripe's separate terms and privacy practices. SalusPrep does not store complete payment card information.</p>
                <p class="mt-3 leading-relaxed">Unless otherwise stated at purchase, purchased section unlocks provide lifetime access for as long as the Service remains operational. If SalusPrep permanently discontinues the Service, access to purchased content may end at that time.</p>
                <p class="mt-3 leading-relaxed">If a payment is disputed, reversed, charged back, or determined to be fraudulent, we may suspend or terminate access to purchased content pending resolution.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">7. Acceptable Use</h2>
                <p class="leading-relaxed">You may not misuse the Service, attempt unauthorized access, scrape, copy, redistribute, publish, sell, or share question content, explanations, images, or other materials from the Service. You may not share account access or use the Service in violation of applicable law.</p>
                <p class="mt-3 leading-relaxed">We may suspend, restrict, or terminate access for violations of these Terms or to protect the integrity, security, or operation of the Service.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">8. Intellectual Property</h2>
                <p class="leading-relaxed">SalusPrep content, branding, software, question banks, explanations, and related materials are owned by SalusPrep or its licensors and are protected by applicable intellectual property laws.</p>
                <p class="mt-3 leading-relaxed">You receive a limited, personal, non-transferable, non-exclusive license to use the Service solely for your own exam preparation.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">9. Disclaimer of Warranties</h2>
                <p class="leading-relaxed">THE SERVICE IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED.</p>
                <p class="mt-3 leading-relaxed">SalusPrep does not guarantee the accuracy of every question or explanation, uninterrupted availability of the Service, passage of any examination, receipt of certification or licensure, employment opportunities, academic credit, or any particular score or outcome.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">10. Limitation of Liability</h2>
                <p class="leading-relaxed">To the fullest extent permitted by law, SalusPrep shall not be liable for any indirect, incidental, consequential, special, exemplary, or punitive damages arising from or related to your use of the Service.</p>
                <p class="mt-3 leading-relaxed">To the fullest extent permitted by law, SalusPrep's total liability for any claim arising from or relating to the Service shall not exceed the amount you paid to SalusPrep during the twelve (12) months preceding the event giving rise to the claim.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">11. Service Changes</h2>
                <p class="leading-relaxed">We may modify, discontinue, remove, add, or update features, content, question banks, functionality, or portions of the Service at any time without liability.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">12. Privacy</h2>
                <p class="leading-relaxed">Your use of the Service is also governed by the <a href="{{ route('legal.privacy') }}">Privacy Policy</a>, which is incorporated into these Terms by reference.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">13. Governing Law</h2>
                <p class="leading-relaxed">These Terms shall be governed by and construed in accordance with the laws of the Commonwealth of Massachusetts, without regard to conflict-of-law principles.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">14. Changes</h2>
                <p class="leading-relaxed">We may update these Terms from time to time. Continued use of the Service after changes become effective constitutes acceptance of the revised Terms. Material changes may be noted on the Service.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">15. Contact</h2>
                <p class="leading-relaxed">Questions about these Terms may be directed to:</p>
                <p class="mt-2 leading-relaxed"><a href="mailto:salusprep@mail.com">salusprep@mail.com</a></p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">16. Entire Agreement</h2>
                <p class="leading-relaxed">These Terms, together with the Privacy Policy, constitute the entire agreement between you and SalusPrep regarding the Service and supersede any prior agreements or understandings relating to the Service.</p>
            </section>
        </div>
    </article>
@endsection
