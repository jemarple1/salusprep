@extends('layouts.app')

@section('title', $sectionLabel.' Results')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-wider text-medic-light">{{ $sectionLabel }}</p>
        <h1 class="mt-1 text-3xl font-bold text-white">Quiz results</h1>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-white">{{ $session->questions_answered }}</p>
            <p class="mt-1 text-sm text-slate-500">of {{ $session->targetQuestionCount() }} total</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-medic-light">{{ $session->correct_count }}</p>
            <p class="mt-1 text-sm text-slate-500">Correct</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-white">{{ $session->scorePercent() }}%</p>
            <p class="mt-1 text-sm text-slate-500">Accuracy</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-ems-light">{{ $session->current_difficulty }}/5</p>
            <p class="mt-1 text-sm text-slate-500">Final difficulty</p>
        </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-navy-light/80">
        <div class="border-b border-white/10 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Answer review</h2>
        </div>
        <div class="divide-y divide-white/10">
            @foreach ($session->answers as $index => $answer)
                <div class="px-6 py-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-bold text-slate-500">Q{{ $index + 1 }}</span>
                        <span class="rounded-full bg-ems/20 px-2 py-0.5 text-xs text-ems-light">{{ $answer->question->category }}</span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $answer->is_correct ? 'bg-medic/15 text-medic-light' : 'bg-rescue/15 text-red-200' }}">
                            {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                        </span>
                    </div>
                    <p class="mt-2 text-white">{{ $answer->question->stem }}</p>
                    <p class="mt-2 text-sm text-slate-400">Your answer: {{ $answer->selected_option }} · Correct: {{ $answer->question->correct_option }}</p>
                    @if ($answer->question->explanation)
                        <p class="mt-2 text-sm text-slate-300">{{ $answer->question->explanation }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        <form method="POST" action="{{ route('exam.start', $sectionSlug) }}">
            @csrf
            <button type="submit" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Start new quiz</button>
        </form>
        @auth
            @if (auth()->user()->hasSectionAccess($sectionLevel) && $session->answers->where('is_correct', false)->isNotEmpty())
                <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-xl border border-ems/40 bg-ems/10 px-5 py-3 font-bold text-ems-light hover:bg-ems/20">Review missed with flashcards</a>
            @endif
        @endauth
        <a href="{{ route('platform.home', $sectionSlug) }}" class="rounded-xl border border-white/10 px-5 py-3 font-medium text-slate-200 hover:bg-white/5">Back to {{ $sectionLabel }}</a>
    </div>
@endsection
