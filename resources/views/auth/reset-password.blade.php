@extends('layouts.app')

@section('title', 'Reset password')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl border border-safety/20 bg-navy-light/80 p-8">
            <h1 class="text-2xl font-bold text-white">Reset password</h1>
            <p class="mt-2 text-sm text-slate-400">Choose a new password for your account.</p>

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-300">New password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-300">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <button type="submit" class="w-full rounded-xl bg-safety py-3 font-bold text-navy hover:bg-safety-light">
                    Reset password
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-slate-400">
                <a href="{{ route('login') }}" class="font-semibold text-safety-light hover:text-safety">Back to log in</a>
            </p>
        </div>
    </div>
@endsection
