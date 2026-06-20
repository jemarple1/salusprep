@extends('layouts.app')

@section('title', 'Sign up')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl border border-safety/20 bg-navy-light/80 p-8">
            <h1 class="text-2xl font-bold text-white">Create your account</h1>
            <p class="mt-2 text-sm text-slate-400">Adaptive exam prep for EMT-Basic, EMT-Advanced, Paramedic, and NCLEX-PN®.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-slate-300">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-300">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-300">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div class="space-y-3">
                    <label class="flex items-start gap-3 text-sm text-slate-300">
                        <input type="checkbox" name="terms" value="1" required
                            @checked(old('terms'))
                            class="mt-0.5 rounded border-white/20 bg-navy text-safety focus:ring-safety">
                        <span>
                            I agree to the
                            <a href="{{ route('legal.terms') }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-safety-light hover:text-safety">Terms &amp; Conditions</a>
                            and
                            <a href="{{ route('legal.privacy') }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-safety-light hover:text-safety">Privacy Policy</a>.
                        </span>
                    </label>
                    @error('terms')
                        <p class="text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    <label class="flex items-start gap-3 text-sm text-slate-400">
                        <input type="checkbox" name="marketing_emails_opt_in" value="1"
                            @checked(old('marketing_emails_opt_in', true))
                            class="mt-0.5 rounded border-white/20 bg-navy text-safety focus:ring-safety">
                        <span>Send me resources and emails from SalusPrep.</span>
                    </label>
                </div>

                <fieldset class="space-y-3 border-t border-white/10 pt-4">
                    <legend class="text-sm font-medium text-slate-300">After you sign up</legend>

                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-white/10 bg-navy/40 px-4 py-3 text-sm text-slate-300 has-[:checked]:border-medic/40 has-[:checked]:bg-medic/10">
                        <input
                            type="radio"
                            name="signup_plan"
                            value="free"
                            class="mt-0.5 border-white/20 bg-navy text-medic focus:ring-medic"
                            @checked(old('signup_plan', 'free') === 'free')
                        >
                        <span>
                            <strong class="text-white">Continue with my free preview</strong>
                            <span class="mt-1 block text-slate-400">Keep practicing for up to {{ $previewMinutes }} minutes from when you first started on SalusPrep.</span>
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-white/10 bg-navy/40 px-4 py-3 text-sm text-slate-300 has-[:checked]:border-safety/40 has-[:checked]:bg-safety/10">
                        <input
                            type="radio"
                            name="signup_plan"
                            value="unlock"
                            class="mt-0.5 border-white/20 bg-navy text-safety focus:ring-safety"
                            @checked(old('signup_plan') === 'unlock' || $preselectUnlock)
                        >
                        <span>
                            <strong class="text-white">Get Full Access now</strong>
                            <span class="mt-1 block text-slate-400">Checkout with Stripe after your account is created.</span>
                        </span>
                    </label>

                    <div id="unlock-options" class="@unless(old('signup_plan') === 'unlock' || $preselectUnlock) hidden @endunless space-y-2 rounded-xl border border-safety/30 bg-navy/60 p-4">
                        <label for="unlock_section" class="mb-1 block text-sm font-medium text-slate-300">Platform to unlock</label>
                        <select
                            id="unlock_section"
                            name="unlock_section"
                            class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety"
                        >
                            @foreach ($sectionOptions as $option)
                                <option value="{{ $option['slug'] }}" @selected(old('unlock_section', $defaultSectionSlug) === $option['slug'])>
                                    {{ $option['label'] }} — {{ $option['price'] }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs leading-relaxed text-slate-400">
                            Full Access gives you unlimited quizzes, flashcards, skills, and Test Center for the platform you choose.
                            If you're still inside your free {{ $previewMinutes }}-minute preview window, you can keep previewing until it ends — Full Access takes over automatically when preview time is up.
                        </p>
                    </div>
                    @error('unlock_section')
                        <p class="text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </fieldset>

                <button type="submit" class="w-full rounded-xl bg-safety py-3 font-bold text-navy hover:bg-safety-light">
                    Create account
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-slate-400">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-safety-light hover:text-safety">Log in</a>
            </p>
        </div>
    </div>

    <script>
        (function () {
            const unlockOptions = document.getElementById('unlock-options');
            const planInputs = document.querySelectorAll('input[name="signup_plan"]');

            function syncUnlockOptions() {
                const selected = document.querySelector('input[name="signup_plan"]:checked');
                const showUnlock = selected?.value === 'unlock';
                unlockOptions?.classList.toggle('hidden', !showUnlock);
            }

            planInputs.forEach(function (input) {
                input.addEventListener('change', syncUnlockOptions);
            });

            syncUnlockOptions();
        })();
    </script>
@endsection
