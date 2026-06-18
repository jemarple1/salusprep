@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('study.index', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Study hub</a>
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
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Dashboard, proficiency charts &amp; progress tracking</li>
            </ul>

            <p class="mt-5 text-xs text-slate-500">One-time purchase · this platform only · no subscription</p>

            @if ($requiresAuth)
                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-medic py-3 text-center font-bold text-white hover:bg-medic-dark">Create free account</a>
                    <a href="{{ route('login') }}" class="flex-1 rounded-xl border border-medic/30 py-3 text-center font-semibold text-medic-light hover:bg-medic/10">Log in</a>
                </div>
            @else
                <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}" class="mt-5">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-safety py-3.5 font-bold text-navy hover:bg-safety-light">
                        Unlock full {{ $sectionLabel }} platform — <x-section-price tone="safety" />
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
