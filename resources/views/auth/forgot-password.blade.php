@extends('layouts.app')

@section('title', 'Forgot password')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl border border-safety/20 bg-navy-light/80 p-8">
            <h1 class="text-2xl font-bold text-white">Forgot password</h1>
            <p class="mt-2 text-sm text-slate-400">Enter your email and we will send you a reset link.</p>

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <button type="submit" class="w-full rounded-xl bg-safety py-3 font-bold text-navy hover:bg-safety-light">
                    Send reset link
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-slate-400">
                Remember your password?
                <a href="{{ route('login') }}" class="font-semibold text-safety-light hover:text-safety">Log in</a>
            </p>
        </div>
    </div>
@endsection
