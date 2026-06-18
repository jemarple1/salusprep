@extends('layouts.app')

@section('title', $sectionLabel.' Test Center')

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-medic-light">{{ $sectionLabel }}</p>
            <h1 class="text-3xl font-bold text-white">Test Center</h1>
            <p class="mt-2 text-slate-400">Quiz scores, trends, and category breakdowns.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if ($unlocked && $activeStudySession)
                <a href="{{ route('study.show', [$sectionSlug, $activeStudySession]) }}" class="rounded-xl border border-medic/40 bg-medic/10 px-5 py-3 font-bold text-medic-light hover:bg-medic/20">Resume flashcards</a>
            @elseif ($unlocked && $totalMissed > 0)
                <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-xl border border-ems/40 bg-ems/10 px-5 py-3 font-bold text-ems-light hover:bg-ems/20">Review missed ({{ $totalMissed }})</a>
            @endif

            @if ($activeSession)
                @if ($activeSession->requiresPayment())
                    <a href="{{ route('exam.paywall', [$sectionSlug, $activeSession]) }}" class="rounded-xl bg-safety px-5 py-3 font-bold text-navy hover:bg-safety-light">Unlock &amp; continue</a>
                @else
                    <a href="{{ route('exam.show', [$sectionSlug, $activeSession]) }}" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Resume quiz</a>
                @endif
            @elseif ($unlocked || $freeRemaining > 0)
                <form method="POST" action="{{ route('exam.start', $sectionSlug) }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Start new quiz</button>
                </form>
            @elseif ($requiresAuth ?? false)
                <a href="{{ route('register') }}" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Create free account</a>
            @else
                <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-safety px-5 py-3 font-bold text-navy hover:bg-safety-light">Unlock — <x-section-price tone="safety" /></button>
                </form>
            @endif
        </div>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6">
            @if ($unlocked)
                <p class="text-sm font-bold uppercase text-medic-light">Access</p>
                <p class="mt-1 text-2xl font-bold text-white">Unlimited {{ $sectionLabel }} quizzes</p>
                <p class="mt-2 text-sm text-slate-400">Includes accuracy trends, category breakdowns, and flashcard review.</p>
            @else
                <p class="text-sm font-bold uppercase text-safety-light">Free preview</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ $freeRemaining }} questions remaining</p>
            @endif
        </div>

        @if ($unlocked && $overallStats['total'] > 0)
            <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6">
                <p class="text-sm font-bold uppercase text-ems-light">Overall accuracy</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ $overallStats['accuracy_percent'] }}%</p>
                <p class="mt-2 text-sm text-slate-400">
                    {{ $overallStats['correct'] }} correct · {{ $overallStats['incorrect'] }} missed · {{ $overallStats['total'] }} total
                </p>
            </div>
        @elseif ($unlocked)
            <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6">
                <p class="text-sm font-bold uppercase text-ems-light">Overall accuracy</p>
                <p class="mt-1 text-lg font-semibold text-slate-300">Complete a quiz to see your stats</p>
            </div>
        @endif
    </div>

    <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80">
        <div class="border-b border-white/10 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Recent {{ $sectionLabel }} quizzes</h2>
        </div>

        @if ($sessions->isEmpty())
            <div class="px-6 py-10 text-center text-slate-400">No quizzes yet in this section.</div>
        @else
            <div class="divide-y divide-white/10">
                @foreach ($sessions as $session)
                    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">
                        <div>
                            <p class="font-semibold text-white">
                                Quiz #{{ $quizNumbers[$session->id] ?? '?' }}
                                <span class="ml-2 rounded-full bg-white/10 px-2 py-0.5 text-xs uppercase text-slate-300">{{ str_replace('_', ' ', $session->status) }}</span>
                            </p>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $session->questions_answered }}/{{ $session->targetQuestionCount() }} answered · {{ $session->scorePercent() }}% · difficulty {{ $session->current_difficulty }}/5
                            </p>
                        </div>
                        <div class="flex gap-2">
                            @if ($session->isComplete())
                                <a href="{{ route('exam.results', [$sectionSlug, $session]) }}" class="rounded-lg border border-white/10 px-4 py-2 text-sm hover:bg-white/5">Results</a>
                            @elseif ($session->requiresPayment())
                                <a href="{{ route('exam.paywall', [$sectionSlug, $session]) }}" class="rounded-lg bg-safety/20 px-4 py-2 text-sm font-bold text-safety-light">Unlock</a>
                            @else
                                <a href="{{ route('exam.show', [$sectionSlug, $session]) }}" class="rounded-lg border border-medic/30 px-4 py-2 text-sm text-medic-light hover:bg-medic/10">Continue</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ($unlocked)
        <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80">
            <div class="border-b border-white/10 px-6 py-4">
                <h2 class="text-lg font-bold text-white">Accuracy trend</h2>
                <p class="text-sm text-slate-400">
                    How your quiz scores change over time on {{ $sectionLabel }}.
                    @if (($accuracyTrend['total_quizzes'] ?? 0) > 15)
                        Showing your most recent 15 quizzes.
                    @endif
                </p>
            </div>
            <div class="p-6">
                @if (count($accuracyTrend['points']) === 0)
                    <div class="py-10 text-center text-slate-400">Complete a quiz to start tracking your accuracy trend.</div>
                @else
                    <x-accuracy-trend-chart :trend="$accuracyTrend" />
                @endif
            </div>
        </div>

        <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 px-6 py-4">
                <div>
                    <h2 class="text-lg font-bold text-white">Category proficiency</h2>
                    <p class="text-sm text-slate-400">Accuracy by topic — focus flashcard review where you miss most.</p>
                </div>
                @if ($totalMissed > 0)
                    <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-lg bg-medic/20 px-4 py-2 text-sm font-bold text-medic-light hover:bg-medic/30">Open flashcards</a>
                @endif
            </div>

            @if ($categoryStats->isEmpty())
                <div class="px-6 py-10 text-center text-slate-400">
                    Answer questions in a quiz to build your category breakdown.
                </div>
            @else
                <div class="space-y-5 p-6">
                    @foreach ($categoryStats as $stat)
                        @php
                            $barColor = match (true) {
                                $stat->accuracy_percent >= 80 => 'bg-medic',
                                $stat->accuracy_percent >= 60 => 'bg-safety',
                                default => 'bg-rescue',
                            };
                            $missedInCategory = $wrongByCategory[$stat->category] ?? 0;
                        @endphp
                        <div>
                            <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-white">{{ $stat->category }}</span>
                                    @if ($missedInCategory > 0)
                                        <span class="rounded-full bg-rescue/20 px-2 py-0.5 text-xs font-bold text-red-200">{{ $missedInCategory }} to review</span>
                                    @endif
                                </div>
                                <div class="text-sm text-slate-400">
                                    <span class="font-bold text-white">{{ $stat->accuracy_percent }}%</span> correct
                                    <span class="mx-1 text-slate-600">·</span>
                                    {{ $stat->miss_percent }}% missed
                                    <span class="mx-1 text-slate-600">·</span>
                                    {{ $stat->total }} Q
                                </div>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-rescue/20 ring-1 ring-white/5">
                                <div class="{{ $barColor }} h-full rounded-full transition-all" style="width: {{ max($stat->accuracy_percent, 2) }}%"></div>
                            </div>
                            @if ($missedInCategory > 0)
                                <form method="POST" action="{{ route('study.start', $sectionSlug) }}" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="category" value="{{ $stat->category }}">
                                    <button type="submit" class="text-xs font-semibold text-medic-light hover:text-medic hover:underline">
                                        Study {{ $stat->category }} flashcards →
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="relative mb-8 overflow-hidden rounded-2xl border border-safety/30 bg-navy-light/80">
            <div class="pointer-events-none select-none blur-sm">
                <div class="space-y-6 p-6">
                    <div class="h-32 rounded-xl bg-navy/80 p-4">
                        <div class="mb-3 h-3 w-24 rounded bg-medic/30"></div>
                        <svg viewBox="0 0 400 100" class="h-20 w-full">
                            <polyline points="20,80 100,60 180,50 260,40 340,25" fill="none" stroke="#4ade80" stroke-width="2" opacity="0.5" />
                        </svg>
                    </div>
                    @foreach (['Airway', 'Trauma', 'Cardiology', 'Operations'] as $demo)
                        <div>
                            <div class="mb-2 flex justify-between text-sm">
                                <span class="font-semibold text-white">{{ $demo }}</span>
                                <span class="text-slate-400">—% correct</span>
                            </div>
                            <div class="h-3 rounded-full bg-navy/80">
                                <div class="h-full w-2/3 rounded-full bg-medic/40"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="absolute inset-0 flex flex-col items-center justify-center bg-navy/60 px-6 text-center">
                <p class="text-lg font-bold text-white">Quiz trends &amp; category breakdowns</p>
                <p class="mt-2 max-w-md text-sm text-slate-300">
                    @if ($requiresAuth ?? false)
                        Sign in to track quiz history, accuracy trends, and category proficiency. Unlock {{ $sectionLabel }} for <x-section-price size="inline" tone="overlay" /> to see everything.
                    @else
                        Unlock {{ $sectionLabel }} for <x-section-price size="inline" tone="overlay" /> to see category breakdowns, accuracy trends over time, and review missed questions with flashcards.
                    @endif
                </p>
                @if ($requiresAuth ?? false)
                    <div class="mt-4 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('register') }}" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Create free account</a>
                        <a href="{{ route('login') }}" class="rounded-xl border border-white/15 px-6 py-3 font-semibold text-slate-200 hover:bg-white/5">Log in</a>
                    </div>
                @else
                    <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">Unlock — <x-section-price tone="safety" /></button>
                    </form>
                @endif
            </div>
        </div>
    @endif
@endsection
