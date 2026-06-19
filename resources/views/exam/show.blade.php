@extends('layouts.app')

@section('title', $sectionLabel.' Quiz')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-medic-light">
                Question {{ $questionNumber }} of {{ $totalQuestions }}
            </p>
            <h1 class="mt-1 text-2xl font-bold text-white">{{ $sectionLabel }}</h1>
            @if ($session->hasFocusCategory())
                @php $focusStyles = \App\Support\QuestionCategory::styles($session->focus_category); @endphp
                <p class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase {{ $focusStyles['badge'] }}">
                    {{ $session->focus_category }} focus · 75% weighted
                </p>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-4 text-sm">
            <x-exam-difficulty-bar :difficulty="$session->current_difficulty" />
            <span class="rounded-full border border-medic/30 bg-medic/10 px-3 py-1 font-semibold text-medic-light">{{ $session->scorePercent() }}% correct</span>
            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 font-semibold text-slate-300">{{ $session->questions_answered }}/{{ $totalQuestions }} answered</span>
        </div>
    </div>

    <div class="mb-6 h-2 overflow-hidden rounded-full bg-navy-light ring-1 ring-white/10">
        <div class="h-full rounded-full bg-medic transition-all" style="width: {{ $session->progressPercent() }}%"></div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
        <div class="mb-4 flex items-center justify-between gap-4">
            <span @class([
                'rounded-full px-3 py-1 text-xs font-bold uppercase',
                \App\Support\QuestionCategory::styles($question->category)['badge'],
            ])>{{ $question->category }}</span>
            @if ($session->sectionIsUnlocked())
                <span class="text-xs font-semibold text-medic-light">Unlimited access</span>
            @endif
        </div>

        <p class="text-lg leading-relaxed text-white">{{ $question->stem }}</p>

        @if ($reviewMode && $lastAnswer)
            <div class="mt-8 space-y-3">
                @foreach ($question->options() as $letter => $text)
                    @php
                        $isCorrectOption = strtoupper($letter) === strtoupper($question->correct_option);
                        $isSelected = strtoupper($letter) === strtoupper($lastAnswer->selected_option);
                        $optionClasses = match (true) {
                            $isCorrectOption => 'border-medic/50 bg-medic/15 ring-1 ring-medic/30',
                            $isSelected => 'border-rescue/50 bg-rescue/10 ring-1 ring-rescue/30',
                            default => 'border-white/5 bg-navy/40 opacity-70',
                        };
                        $letterClasses = match (true) {
                            $isCorrectOption => 'text-medic-light',
                            $isSelected => 'text-red-200',
                            default => 'text-slate-500',
                        };
                        $textClasses = match (true) {
                            $isCorrectOption => 'text-slate-100',
                            $isSelected => 'text-red-100',
                            default => 'text-slate-400',
                        };
                    @endphp
                    <div class="flex items-start gap-4 rounded-xl border px-4 py-4 {{ $optionClasses }}">
                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border text-xs font-bold {{ $isCorrectOption ? 'border-medic/50 bg-medic/20 text-medic-light' : ($isSelected ? 'border-rescue/50 bg-rescue/20 text-red-200' : 'border-white/10 text-slate-500') }}">
                            {{ $isCorrectOption ? '✓' : ($isSelected ? '✗' : '') }}
                        </span>
                        <span><span class="font-bold {{ $letterClasses }}">{{ $letter }}.</span> <span class="{{ $textClasses }}">{{ $text }}</span></span>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('exam.continue', [$sectionSlug, $session]) }}" class="pt-6">
                @csrf
                <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Next question</button>
            </form>
        @else
            <form method="POST" action="{{ route('exam.answer', [$sectionSlug, $session, $question]) }}" class="mt-8 space-y-3">
                @csrf

                @foreach ($question->options() as $letter => $text)
                    <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-white/10 bg-navy/60 px-4 py-4 transition hover:border-medic/40 has-[:checked]:border-medic has-[:checked]:bg-medic/10">
                        <input type="radio" name="selected_option" value="{{ $letter }}" required class="mt-1 text-medic focus:ring-medic">
                        <span><span class="font-bold text-medic-light">{{ $letter }}.</span> <span class="text-slate-200">{{ $text }}</span></span>
                    </label>
                @endforeach

                <div class="pt-4">
                    <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Submit answer</button>
                </div>
            </form>

            @if ($session->questions_answered >= 3)
                <form method="POST" action="{{ route('exam.finish', [$sectionSlug, $session]) }}" class="mt-3">
                    @csrf
                    <button type="submit" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm text-slate-400 hover:bg-white/5">End quiz early &amp; view results</button>
                </form>
            @endif
        @endif
    </div>

    @if ($reviewMode && $lastAnswer)
        <div class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-stretch">
            <div class="min-w-0 flex-1 rounded-2xl border px-5 py-4 {{ $lastAnswer->is_correct ? 'border-medic/40 bg-medic/10' : 'border-rescue/40 bg-rescue/10' }}">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-3">
                    <p class="font-bold {{ $lastAnswer->is_correct ? 'text-medic-light' : 'text-red-200' }}">
                        {{ $lastAnswer->is_correct ? 'Correct' : 'Incorrect' }}
                    </p>
                    <x-exam-difficulty-bar :difficulty="$session->current_difficulty" class="rounded-full border border-white/10 bg-navy/40 px-3 py-1.5" />
                    <x-question-platform-stat :percent="$lastAnswer->question->platformCorrectPercent()" />
                </div>
                @if ($lastAnswer->question->explanation)
                    <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $lastAnswer->question->explanation }}</p>
                @endif
            </div>

            @if (! $lastAnswer->is_correct)
                <x-study-deck-notice :href="$studyDeckUrl" />
            @endif
        </div>
    @endif
@endsection
