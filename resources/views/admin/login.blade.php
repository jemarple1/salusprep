@extends('admin.layout')

@section('title', 'Log in')

@section('content')
    <div class="mx-auto max-w-md pt-12">
        <div class="rounded-2xl border border-safety/20 bg-navy-light/80 p-8">
            <h1 class="text-2xl font-bold text-white">Admin sign in</h1>
            <p class="mt-2 text-sm text-slate-400">SalusPrep platform administration.</p>

            <form method="POST" action="{{ route('admin.login') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="username" class="mb-1 block text-sm font-medium text-slate-300">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-300">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-safety">
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-400">
                    <input type="checkbox" name="remember" class="rounded border-white/20 bg-navy text-safety">
                    Remember me
                </label>

                <button type="submit" class="w-full rounded-xl bg-safety py-3 font-bold text-navy hover:bg-safety-light">
                    Sign in
                </button>
            </form>
        </div>
    </div>
@endsection
