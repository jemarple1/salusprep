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
@endsection
