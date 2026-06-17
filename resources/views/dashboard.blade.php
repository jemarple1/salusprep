@extends('layouts.app')

@section('title', $sectionLabel.' Dashboard')

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-medic-light">{{ $sectionLabel }}</p>
            <h1 class="text-3xl font-bold text-white">Dashboard</h1>
            <p class="mt-2 text-slate-400">Your quiz history for this platform only.</p>
        </div>

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
        @else
            <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}">
                @csrf
                <button type="submit" class="rounded-xl bg-safety px-5 py-3 font-bold text-navy hover:bg-safety-light">Unlock — $8.99</button>
            </form>
        @endif
    </div>

    <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80 p-6">
        @if ($unlocked)
            <p class="text-sm font-bold uppercase text-medic-light">Access</p>
            <p class="mt-1 text-2xl font-bold text-white">Unlimited {{ $sectionLabel }} quizzes</p>
        @else
            <p class="text-sm font-bold uppercase text-safety-light">Free preview</p>
            <p class="mt-1 text-2xl font-bold text-white">{{ $freeRemaining }} questions remaining</p>
        @endif
    </div>

    <div class="rounded-2xl border border-white/10 bg-navy-light/80">
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
                                Quiz #{{ $session->id }}
                                <span class="ml-2 rounded-full bg-white/10 px-2 py-0.5 text-xs uppercase text-slate-300">{{ str_replace('_', ' ', $session->status) }}</span>
                            </p>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $session->questions_answered }} answered · {{ $session->scorePercent() }}% · difficulty {{ $session->current_difficulty }}/5
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
@endsection
