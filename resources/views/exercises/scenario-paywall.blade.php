@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('skills.index', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Skills</a>
        <p class="mt-2 text-sm font-bold uppercase tracking-wider text-ems-light">{{ $exercise['category'] }}</p>
        <h1 class="mt-1 text-3xl font-bold text-white">{{ $exercise['title'] }}</h1>
        <p class="mt-2 text-lg text-slate-300">{{ $scenario['title'] ?? 'Additional scenario' }}</p>

        <x-exercise-scenario-picker class="mt-6" :scenario-links="$scenarioLinks" :scenario-index="$scenarioIndex" :exercise-slug="$exercise['slug']" :section-slug="$sectionSlug" />

        <div class="mt-6 rounded-2xl border border-safety/30 bg-navy-light/90 p-8">
            <p class="text-sm font-bold uppercase tracking-wider text-safety">Unlock the full {{ $sectionLabel }} platform</p>
            <p class="mt-3 text-lg font-semibold text-white">
                One payment of <x-section-price size="inline" /> unlocks everything for {{ $sectionLabel }} — not just this exercise.
            </p>
            <p class="mt-2 text-slate-400">
                @if ($requiresAuth)
                    The first scenario in every skill exercise is free. Create an account, then unlock the platform once to open it all.
                @else
                    You've used the free scenario for this exercise. Unlock the platform once to continue here and everywhere else on {{ $sectionLabel }}.
                @endif
            </p>

            <ul class="mt-6 space-y-2.5 text-sm text-slate-300">
                <li class="flex gap-2"><span class="text-medic-light">✓</span> All skill exercises &amp; scenarios (SOAP, triage, GCS, burns, stroke, vitals)</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Unlimited adaptive quizzes</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Flashcard study deck for missed questions</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Test Center charts, trends &amp; progress tracking</li>
            </ul>

            <p class="mt-5 text-xs text-slate-500">One-time purchase · this platform only · no subscription</p>

            @if ($requiresAuth)
                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-safety py-3 text-center font-bold text-navy hover:bg-safety-light">Create free account</a>
                    <a href="{{ route('login') }}" class="flex-1 rounded-xl border border-white/15 py-3 text-center font-semibold text-slate-200 hover:bg-white/5">Log in</a>
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-medic/40 bg-navy-light/95 p-6 ring-1 ring-medic/20">
                    <p class="text-center text-sm font-semibold uppercase tracking-wider text-slate-400">One time</p>
                    <p class="mt-1 text-center text-4xl font-bold text-medic-light"><x-section-price size="hero" tone="checkout" /></p>
                    <p class="mt-3 text-center text-sm text-slate-300">Full {{ $sectionLabel }} platform — all exercises, quizzes, and flashcards</p>

                    <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}" class="mt-5">
                        @csrf
                        <button type="submit" class="w-full rounded-xl bg-medic py-3.5 font-bold text-white shadow-lg shadow-medic/25 hover:bg-medic-dark">
                            Unlock full {{ $sectionLabel }} platform
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
