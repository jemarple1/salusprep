@extends('layouts.app')

@section('title', $sectionLabel)

@section('content')
    @if (request('checkout') === 'success')
        <div class="mb-6 rounded-xl border border-medic/40 bg-medic/10 px-4 py-3 text-sm font-medium text-medic-light">
            Payment successful! If access isn't active yet, wait a moment and refresh.
        </div>
    @endif

    <section class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
        <div class="space-y-6">
            <p class="inline-flex rounded-full border border-medic/40 bg-medic/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-medic-light">
                {{ $sectionLabel }} only
            </p>
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                {{ $sectionPracticeHeadline }}
            </h1>
            <p class="max-w-xl text-lg leading-relaxed text-slate-300">
                {{ $sectionDescription }}
                Start right away — no account required.
            </p>

            <div class="flex flex-wrap gap-3">
                @if ($activeSession)
                    <a href="{{ route('exam.show', [$sectionSlug, $activeSession]) }}" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">
                        Resume quiz
                    </a>
                @elseif ($hasAccess)
                    @php
                        $focusStyles = $pinnedFocus ? \App\Support\QuestionCategory::styles($pinnedFocus) : null;
                    @endphp
                    <form method="POST" action="{{ route('exam.start', $sectionSlug) }}">
                        @csrf
                        @if ($pinnedFocus)
                            <input type="hidden" name="focus_category" value="{{ $pinnedFocus }}">
                        @endif
                        <button type="submit" @class([
                            'rounded-xl px-6 py-3 font-bold text-white',
                            $focusStyles ? $focusStyles['button'].' '.$focusStyles['buttonHover'] : 'bg-medic hover:bg-medic-dark',
                        ])>
                            @if ($pinnedFocus)
                                Start {{ $pinnedFocus }} focus quiz
                            @else
                                Start 25-question quiz
                            @endif
                        </button>
                    </form>
                @else
                    <a href="{{ route('platform.paywall', $sectionSlug) }}" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                        Get Full Access
                    </a>
                @endif

                @auth
                    <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-200 hover:bg-white/5">
                        Test Center
                    </a>
                @endauth
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 ring-1 ring-medic/20">
            <h2 class="text-lg font-bold text-white">Your {{ $sectionLabel }} access</h2>

            @if ($unlocked)
                <p class="mt-3 text-2xl font-bold text-medic-light">Full Access</p>
                <p class="mt-1 text-sm text-slate-400">Unlimited use of this platform.</p>
            @else
                <p class="mt-3 text-2xl font-bold text-medic-light">Preview</p>
                <p class="mt-1 text-sm text-slate-400">Quizzes, skills, flashcards, and Test Center — explore everything.</p>
            @endif

            <ul class="mt-6 space-y-3 text-sm text-slate-300">
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Start immediately — no account</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> 25-question focus &amp; adaptive quizzes</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Skill exercises &amp; flashcards</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Instant feedback &amp; rationales</li>
            </ul>

            <p class="mt-6 text-xs text-slate-500">
                {{ $platformSwitcherHint }}
            </p>
        </div>
    </section>

    @if (($exercises ?? []) !== [])
        <section class="mt-16 border-t border-white/10 pt-12">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Skill exercises</h2>
                    <p class="mt-2 max-w-2xl text-slate-400">
                        SOAP charting, triage, GCS, burns, stroke scales, vitals, pharmacology, and more.
                    </p>
                </div>
                <a href="{{ route('skills.index', $sectionSlug) }}" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5">
                    View all skills →
                </a>
            </div>
            <div class="relative left-1/2 w-screen -translate-x-1/2">
                <x-exercise-carousel :exercises="$exercises" />
            </div>
        </section>
    @endif
@endsection
