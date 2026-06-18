@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl border border-safety/20 bg-navy-light/80 p-8">
            <h1 class="text-2xl font-bold text-white">Welcome back</h1>
            <p class="mt-2 text-sm text-slate-400">Continue your NREMT® adaptive practice.</p>

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div>
                    <div class="mb-1 flex items-center justify-between gap-4">
                        <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-safety-light hover:text-safety">Forgot password?</a>
                    </div>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-400">
                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-navy text-safety">
                    Remember me
                </label>

                <button type="submit" class="w-full rounded-xl bg-safety py-3 font-bold text-navy hover:bg-safety-light">
                    Log in
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-slate-400">
                New here?
                <a href="{{ route('register') }}" class="font-semibold text-safety-light hover:text-safety">Create an account</a>
            </p>
        </div>
    </div>
@endsection
