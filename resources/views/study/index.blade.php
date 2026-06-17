@extends('layouts.app')

@section('title', $sectionLabel.' Study')

@section('content')
    <div class="mb-8">
        <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Back to dashboard</a>
        <h1 class="mt-3 text-3xl font-bold text-white">Flashcard study</h1>
        <p class="mt-2 max-w-2xl text-slate-400">
            Review questions you missed on quizzes. Flip each card, read the explanation, then mark whether you know it or want to see it again.
        </p>
    </div>

    @if ($activeStudySession)
        <div class="mb-6 rounded-2xl border border-medic/30 bg-medic/10 px-5 py-4">
            <p class="font-bold text-medic-light">Session in progress</p>
            <p class="mt-1 text-sm text-slate-300">{{ $activeStudySession->remainingCount() }} cards left · {{ $activeStudySession->masteredCount() }} cleared</p>
            <a href="{{ route('study.show', [$sectionSlug, $activeStudySession]) }}" class="mt-3 inline-block rounded-lg bg-medic px-4 py-2 text-sm font-bold text-white hover:bg-medic-dark">Continue studying</a>
        </div>
    @endif

    @if ($totalMissed === 0)
        <div class="rounded-2xl border border-white/10 bg-navy-light/80 px-6 py-12 text-center">
            <p class="text-xl font-bold text-white">Nothing to review yet</p>
            <p class="mt-2 text-slate-400">Complete a quiz and miss a few questions — they'll appear here for flashcard review.</p>
            <form method="POST" action="{{ route('exam.start', $sectionSlug) }}" class="mt-6">
                @csrf
                <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Start a quiz</button>
            </form>
        </div>
    @else
        <div class="mb-6 rounded-2xl border border-white/10 bg-navy-light/80 p-6">
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
            <div class="rounded-2xl border border-white/10 bg-navy-light/80">
                <div class="border-b border-white/10 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">Study by category</h2>
                    <p class="text-sm text-slate-400">Focus on the topics where you need the most work.</p>
                </div>
                <div class="grid gap-4 p-6 sm:grid-cols-2">
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
@endsection
