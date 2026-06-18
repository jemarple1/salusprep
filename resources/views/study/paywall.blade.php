@extends('layouts.app')

@section('title', 'Unlock '.$sectionLabel.' Study')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ auth()->check() ? route('platform.dashboard', $sectionSlug) : route('platform.home', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Back</a>

        <div class="mt-6 rounded-2xl border border-safety/30 bg-navy-light/90 p-8">
            <p class="text-sm font-bold uppercase tracking-wider text-safety">Flashcard study</p>
            <h1 class="mt-2 text-3xl font-bold text-white">
                @if ($requiresAuth)
                    Sign in to access your study deck
                @else
                    Unlock flashcard study for {{ $sectionLabel }}
                @endif
            </h1>
            <p class="mt-3 text-slate-300">
                @if ($requiresAuth)
                    Missed questions from your quizzes are saved to a personal flashcard deck. Create a free account to keep your deck and unlock full study mode.
                @else
                    Your missed quiz questions are ready for flashcard review. Unlock {{ $sectionLabel }} once to flip through cards, read explanations, and drill weak categories.
                @endif
            </p>

            @if (($totalMissed ?? 0) > 0)
                <div class="mt-8 rounded-xl bg-navy p-5 text-center ring-1 ring-white/10">
                    <p class="text-3xl font-bold text-ems-light">{{ number_format($totalMissed) }}</p>
                    <p class="mt-1 text-sm text-slate-400">Cards waiting in your study deck</p>
                </div>
            @else
                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-xl bg-navy p-4 text-center ring-1 ring-white/10">
                        <p class="text-2xl font-bold text-white">Flip</p>
                        <p class="mt-1 text-xs text-slate-500">Question &amp; answer cards</p>
                    </div>
                    <div class="rounded-xl bg-navy p-4 text-center ring-1 ring-white/10">
                        <p class="text-2xl font-bold text-medic-light">Review</p>
                        <p class="mt-1 text-xs text-slate-500">Full explanations</p>
                    </div>
                    <div class="rounded-xl bg-navy p-4 text-center ring-1 ring-white/10">
                        <p class="text-2xl font-bold text-ems-light">Focus</p>
                        <p class="mt-1 text-xs text-slate-500">Study by category</p>
                    </div>
                </div>
            @endif

            @if ($requiresAuth)
                <div class="mt-8 rounded-xl border border-medic/30 bg-navy p-6">
                    <p class="text-lg font-bold text-white">Your study deck is building</p>
                    <p class="mt-1 text-sm text-slate-400">Every question you miss on a quiz can be added to flashcards. Sign up free, then unlock study tools for $8.99.</p>

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
                            <p class="text-lg font-bold text-white">{{ $sectionLabel }} — Study + quizzes</p>
                            <p class="text-sm text-slate-400">One-time · includes flashcard study mode</p>
                        </div>
                        <p class="text-3xl font-bold text-safety-light">$8.99</p>
                    </div>

                    <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-medic py-3.5 font-bold text-white hover:bg-medic-dark">Pay with Stripe &amp; open study deck</button>
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
