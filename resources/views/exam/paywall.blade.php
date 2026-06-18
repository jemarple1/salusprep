@extends('layouts.app')

@section('title', 'Unlock '.$sectionLabel)

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="rounded-2xl border border-safety/30 bg-navy-light/90 p-8">
            <p class="text-sm font-bold uppercase tracking-wider text-safety">Free preview complete</p>
            <h1 class="mt-2 text-3xl font-bold text-white">
                @if ($requiresAuth)
                    Create an account to keep going
                @else
                    Unlock unlimited {{ $sectionLabel }} quizzes
                @endif
            </h1>
            <p class="mt-3 text-slate-300">
                You've answered 25 free questions on the <strong class="text-medic-light">{{ $sectionLabel }}</strong> platform
                with {{ $session->scorePercent() }}% accuracy so far.
                @if ($requiresAuth)
                    Sign up free to unlock payment and continue your adaptive quiz.
                @else
                    Pay once for unlimited adaptive quizzing in this section.
                @endif
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-navy p-4 text-center ring-1 ring-white/10">
                    <p class="text-2xl font-bold text-white">25</p>
                    <p class="mt-1 text-xs text-slate-500">Free questions used</p>
                </div>
                <div class="rounded-xl bg-navy p-4 text-center ring-1 ring-white/10">
                    <p class="text-2xl font-bold text-medic-light">{{ $session->scorePercent() }}%</p>
                    <p class="mt-1 text-xs text-slate-500">Preview accuracy</p>
                </div>
                <div class="rounded-xl bg-navy p-4 text-center ring-1 ring-white/10">
                    <p class="text-2xl font-bold text-ems-light">{{ $session->current_difficulty }}/5</p>
                    <p class="mt-1 text-xs text-slate-500">Level reached</p>
                </div>
            </div>

            @if ($requiresAuth)
                <div class="mt-8 rounded-xl border border-medic/30 bg-navy p-6">
                    <p class="text-lg font-bold text-white">Your progress is saved</p>
                    <p class="mt-1 text-sm text-slate-400">Create an account to continue where you left off, then unlock unlimited quizzes for <x-section-price size="inline" />.</p>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-medic py-3.5 text-center font-bold text-white hover:bg-medic-dark">
                            Create free account
                        </a>
                        <a href="{{ route('login') }}" class="flex-1 rounded-xl border border-medic/30 py-3.5 text-center font-semibold text-medic-light hover:bg-medic/10">
                            Log in
                        </a>
                    </div>
                </div>
            @else
                <div class="mt-8 rounded-xl border border-medic/30 bg-navy p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-lg font-bold text-white">{{ $sectionLabel }} — Unlimited</p>
                            <p class="text-sm text-slate-400">One-time · this platform only</p>
                        </div>
                        <x-section-price size="hero" />
                    </div>

                    <form method="POST" action="{{ route('exam.pay', [$sectionSlug, $session]) }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-medic py-3.5 font-bold text-white hover:bg-medic-dark">Pay with Stripe &amp; continue</button>
                    </form>

                @if (config('services.stripe.secret'))
                    <p class="mt-3 text-center text-xs text-slate-500">Secure checkout powered by Stripe.</p>
                @else
                    <p class="mt-3 text-center text-xs text-slate-500">Stripe not configured — using mock checkout. Add STRIPE_SECRET to .env.</p>
                @endif
                </div>
            @endif
        </div>
    </div>
@endsection
