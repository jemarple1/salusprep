@extends('layouts.app')

@section('title', 'Sign up')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="rounded-2xl border border-safety/20 bg-navy-light/80 p-8">
            <h1 class="text-2xl font-bold text-white">Create your account</h1>
            <p class="mt-2 text-sm text-slate-400">25 free adaptive questions per section — EMT-Basic, EMT-Advanced, and Paramedic.</p>

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
