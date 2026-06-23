@extends('layouts.app')

@section('meta_title', \App\Support\PageSeo::platformPageTitle($sectionLevel, 'Flashcards'))

@section('content')
    <div class="mb-8">
        <a href="{{ auth()->check() ? route('platform.dashboard', $sectionSlug) : route('platform.home', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Back</a>
        <h1 class="mt-3 text-3xl font-bold text-white">Flashcards</h1>
        <p class="mt-2 max-w-2xl text-slate-400">
            Study curated decks by topic, then review questions you missed on quizzes.
        </p>
    </div>

    @if ($errors->has('study'))
        <div class="mb-6 rounded-xl border border-rescue/40 bg-rescue/10 px-4 py-3 text-sm text-red-200">
            {{ $errors->first('study') }}
        </div>
    @endif

    @if ($flashcardsAvailable && ($publicDecks ?? collect())->isNotEmpty())
        <section class="mb-10">
            <div class="mb-5">
                <h2 class="text-lg font-bold text-white">Focus decks</h2>
                <p class="mt-1 text-sm text-slate-400">
                    General knowledge plus topic decks from the full question bank — swipe to browse. Complete a deck to mark it done.
                </p>
            </div>

            <x-flashcard-deck-carousel
                :decks="$publicDecks"
                carousel-id="study-public-decks"
            />
        </section>
    @endif

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
        @elseif ($totalMissed === 0)
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
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @php
                    $allDeckSession = $activeAllDeckSession ?? null;
                @endphp
                {{-- Complete deck — first card in the stack --}}
                @if ($allDeckSession)
                    <a
                        href="{{ route('study.show', [$sectionSlug, $allDeckSession]) }}"
                        class="group relative flex min-h-[200px] flex-col overflow-hidden rounded-2xl border-2 border-ems/40 bg-gradient-to-br from-ems/15 to-navy shadow-lg transition hover:border-ems/60 hover:shadow-ems/10"
                    >
                        <div class="absolute right-3 top-3 rounded-full bg-ems/30 px-2.5 py-1 text-xs font-bold text-ems-light">Personal</div>
                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-ems-light">Complete deck</p>
                                <p class="mt-2 text-xl font-bold text-white group-hover:text-ems-light">All missed questions</p>
                                <p class="mt-2 text-sm text-slate-400">{{ $allDeckSession->remainingCount() }} cards left · {{ $allDeckSession->masteredCount() }} cleared</p>
                            </div>
                            <p class="mt-4 text-sm font-semibold text-medic-light group-hover:underline">Continue deck →</p>
                        </div>
                        <div class="h-1.5 bg-black/20">
                            <div class="h-full rounded-full bg-ems transition-all" style="width: {{ max($allDeckSession->progressPercent(), 4) }}%"></div>
                        </div>
                    </a>
                @else
                    <a
                        href="{{ route('study.deck', $sectionSlug) }}"
                        class="group relative flex min-h-[200px] flex-col overflow-hidden rounded-2xl border-2 border-ems/40 bg-gradient-to-br from-ems/15 to-navy shadow-lg transition hover:border-ems/60 hover:shadow-ems/10"
                    >
                        <div class="absolute right-3 top-3 rounded-full bg-ems/30 px-2.5 py-1 text-xs font-bold text-ems-light">Personal</div>
                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-ems-light">Complete deck</p>
                                <p class="mt-2 text-xl font-bold text-white group-hover:text-ems-light">All missed questions</p>
                                <p class="mt-2 text-sm text-slate-400">{{ $totalMissed }} cards from every category</p>
                            </div>
                            <p class="mt-4 text-sm font-semibold text-medic-light group-hover:underline">Open deck →</p>
                        </div>
                    </a>
                @endif

                @foreach ($wrongByCategory as $category => $count)
                    @php
                        $stat = $categoryStats->firstWhere('category', $category);
                        $accuracy = $stat->accuracy_percent ?? null;
                        $categorySession = ($activePersonalSessions ?? collect())->get($category);
                    @endphp
                    <div class="group relative flex min-h-[200px] flex-col overflow-hidden rounded-2xl border border-white/10 bg-navy/50 shadow transition hover:border-medic/30">
                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Personal deck</p>
                                <p class="mt-2 text-lg font-bold text-white">{{ $category }}</p>
                                @if ($categorySession)
                                    <p class="mt-2 text-sm text-slate-400">{{ $categorySession->remainingCount() }} cards left · {{ $categorySession->masteredCount() }} cleared</p>
                                @else
                                    <p class="mt-2 text-sm text-slate-400">{{ $count }} missed · {{ $accuracy !== null ? $accuracy.'% quiz accuracy' : 'No quiz data yet' }}</p>
                                @endif
                            </div>
                            @if ($categorySession)
                                <a href="{{ route('study.show', [$sectionSlug, $categorySession]) }}" class="mt-4 block w-full rounded-lg border border-medic/30 py-2.5 text-center text-sm font-bold text-medic-light hover:bg-medic/10">
                                    Continue {{ $category }} deck
                                </a>
                            @else
                                <form method="POST" action="{{ route('study.start', $sectionSlug) }}" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="category" value="{{ $category }}">
                                    <button type="submit" class="w-full rounded-lg border border-medic/30 py-2.5 text-sm font-bold text-medic-light hover:bg-medic/10">
                                        Start {{ $category }} deck
                                    </button>
                                </form>
                            @endif
                        </div>
                        <span class="absolute right-3 top-3 rounded-full bg-rescue/20 px-2.5 py-1 text-xs font-bold text-red-200">{{ $count }}</span>
                        @if ($categorySession)
                            <div class="h-1.5 bg-black/20">
                                <div class="h-full rounded-full bg-medic transition-all" style="width: {{ max($categorySession->progressPercent(), 4) }}%"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
