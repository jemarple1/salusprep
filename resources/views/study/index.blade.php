@extends('layouts.app')

@section('title', $sectionLabel.' Flashcards')

@section('content')
    <div class="mb-8">
        <a href="{{ auth()->check() ? route('platform.dashboard', $sectionSlug) : route('platform.home', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Back</a>
        <h1 class="mt-3 text-3xl font-bold text-white">Flashcards</h1>
        <p class="mt-2 max-w-2xl text-slate-400">
            Review questions you missed on quizzes, organized by category.
        </p>
    </div>

    <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6">
        <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-white">Your deck</h2>
                <p class="mt-1 text-sm text-slate-400">Missed quiz questions saved for spaced review.</p>
            </div>
            @if ($flashcardsAvailable && $totalMissed > 0)
                <span class="rounded-full bg-ems/20 px-3 py-1 text-sm font-bold text-ems-light">{{ $totalMissed }} cards</span>
            @endif
        </div>

        @if (! $flashcardsAvailable)
            <div class="rounded-xl border border-safety/20 bg-navy/60 p-6">
                <p class="font-bold text-white">Unlock flashcards to continue</p>
                <p class="mt-2 text-sm text-slate-400">Your Preview has ended. Get Full Access to keep reviewing missed questions.</p>
                <a href="{{ route('platform.paywall', $sectionSlug) }}" class="mt-5 inline-block rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">View unlock options</a>
            </div>
        @elseif ($requiresAuth)
            <div class="rounded-xl border border-medic/20 bg-navy/60 p-6">
                <p class="font-bold text-white">Sign in to use flashcards</p>
                <p class="mt-2 text-sm text-slate-400">Missed questions are saved to your personal deck. Create a free account to start reviewing.</p>
                <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="flex-1 rounded-xl bg-medic py-3 text-center font-bold text-white hover:bg-medic-dark">Create free account</a>
                    <a href="{{ route('login') }}" class="flex-1 rounded-xl border border-medic/30 py-3 text-center font-semibold text-medic-light hover:bg-medic/10">Log in</a>
                </div>
            </div>
        @elseif ($activeStudySession)
            <div class="mb-6 rounded-xl border border-medic/30 bg-medic/10 px-5 py-4">
                <p class="font-bold text-medic-light">Session in progress</p>
                <p class="mt-1 text-sm text-slate-300">{{ $activeStudySession->remainingCount() }} cards left · {{ $activeStudySession->masteredCount() }} cleared</p>
                <a href="{{ route('study.show', [$sectionSlug, $activeStudySession]) }}" class="mt-3 inline-block rounded-lg bg-medic px-4 py-2 text-sm font-bold text-white hover:bg-medic-dark">Continue studying</a>
            </div>
        @endif

        @if ($flashcardsAvailable && ! $requiresAuth)
            @if ($totalMissed === 0)
                <div class="rounded-xl border border-white/10 bg-navy/50 px-6 py-10 text-center">
                    <p class="text-xl font-bold text-white">Nothing to review yet</p>
                    <p class="mt-2 text-slate-400">Complete a quiz and miss a few questions — they'll appear here for flashcard review.</p>
                    @if ($activeExamSession ?? null)
                        <a href="{{ route('exam.show', [$sectionSlug, $activeExamSession]) }}" class="mt-6 inline-block rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Continue current quiz</a>
                    @else
                        <form method="POST" action="{{ route('exam.start', $sectionSlug) }}" class="mt-6">
                            @csrf
                            <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Start a quiz</button>
                        </form>
                    @endif
                </div>
            @else
                <div class="mb-6 rounded-xl border border-white/10 bg-navy/50 p-5">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold uppercase text-ems-light">All missed questions</p>
                            <p class="mt-1 text-2xl font-bold text-white">{{ $totalMissed }} cards ready</p>
                        </div>
                        <form method="POST" action="{{ route('study.start', $sectionSlug) }}">
                            @csrf
                            <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Study all</button>
                        </form>
                    </div>
                </div>

                @if ($wrongByCategory !== [])
                    <div class="border-t border-white/10 pt-6">
                        <h3 class="text-base font-bold text-white">Study by category</h3>
                        <p class="text-sm text-slate-400">Focus on the topics where you need the most work.</p>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            @foreach ($wrongByCategory as $category => $count)
                                @php
                                    $stat = $categoryStats->firstWhere('category', $category);
                                    $accuracy = $stat->accuracy_percent ?? null;
                                @endphp
                                <div class="rounded-xl border border-white/10 bg-navy/50 p-5 transition hover:border-medic/30">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-bold text-white">{{ $category }}</p>
                                            <p class="mt-1 text-sm text-slate-400">{{ $count }} missed · {{ $accuracy !== null ? $accuracy.'% quiz accuracy' : 'No quiz data yet' }}</p>
                                        </div>
                                        <span class="rounded-full bg-rescue/20 px-2.5 py-1 text-xs font-bold text-red-200">{{ $count }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('study.start', $sectionSlug) }}" class="mt-4">
                                        @csrf
                                        <input type="hidden" name="category" value="{{ $category }}">
                                        <button type="submit" class="w-full rounded-lg border border-medic/30 py-2.5 text-sm font-bold text-medic-light hover:bg-medic/10">
                                            Start {{ $category }} deck
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        @endif
    </div>
@endsection
