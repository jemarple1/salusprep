@extends('layouts.app')

@section('title', 'Terms of Service')

@section('content')
    <article class="mx-auto max-w-3xl">
        <h1 class="text-3xl font-bold text-white">Terms of Service</h1>
        <p class="mt-2 text-sm text-slate-500">Last updated: {{ now()->format('F j, Y') }}</p>

        <div class="prose prose-invert mt-8 max-w-none space-y-6 text-slate-300 prose-headings:text-white prose-a:text-medic-light">
            <section>
                <h2 class="text-xl font-bold text-white">1. Agreement</h2>
                <p class="leading-relaxed">By accessing or using SalusPrep (“Service”), you agree to these Terms of Service. If you do not agree, do not use the Service.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">2. Description of service</h2>
                <p class="leading-relaxed">SalusPrep provides adaptive practice quizzes and study tools for NREMT® and NCLEX-PN® exam preparation. The Service is for educational practice only. SalusPrep is not affiliated with, endorsed by, or sponsored by the National Registry of EMTs, NCSBN, or any licensing body.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">3. Not medical or professional advice</h2>
                <p class="leading-relaxed">Content on SalusPrep does not constitute medical, nursing, or emergency care advice. Do not rely on the Service for clinical decisions or patient care. Always follow your local protocols, scope of practice, and applicable laws.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">4. Accounts and guest use</h2>
                <p class="leading-relaxed">You may use limited features without an account. Creating an account requires accurate information and safeguarding your credentials. You are responsible for activity under your account.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">5. Payments and refunds</h2>
                <p class="leading-relaxed">Paid section unlocks are one-time purchases processed by Stripe. Prices are shown at checkout. Except where required by law, purchases are non-refundable once access is granted. Each certification platform (e.g. EMT-Basic, NCLEX-PN®) is unlocked separately unless stated otherwise at purchase.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">6. Acceptable use</h2>
                <p class="leading-relaxed">You may not misuse the Service, attempt unauthorized access, scrape or redistribute question content, share account access, or use the Service in violation of applicable law. We may suspend or terminate access for violations.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">7. Intellectual property</h2>
                <p class="leading-relaxed">SalusPrep content, branding, and software are owned by SalusPrep or its licensors. You receive a limited, personal, non-transferable license to use the Service for your own exam preparation.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">8. Disclaimer of warranties</h2>
                <p class="leading-relaxed">The Service is provided “as is” without warranties of any kind. We do not guarantee exam passage, accuracy of every question, or uninterrupted availability.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">9. Limitation of liability</h2>
                <p class="leading-relaxed">To the fullest extent permitted by law, SalusPrep shall not be liable for indirect, incidental, or consequential damages arising from your use of the Service.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">10. Changes</h2>
                <p class="leading-relaxed">We may update these Terms from time to time. Continued use after changes constitutes acceptance. Material changes may be noted on the Service.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-white">11. Contact</h2>
                <p class="leading-relaxed">Questions about these Terms: contact us through the email or support channel listed on salusprep.com.</p>
            </section>
        </div>
    </article>
@endsection
