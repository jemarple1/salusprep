@extends('layouts.app')

@section('title', $sectionLabel.' Quiz')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-medic-light">Question {{ $questionNumber }}</p>
            <h1 class="mt-1 text-2xl font-bold text-white">{{ $sectionLabel }}</h1>
        </div>
        <div class="flex gap-3 text-sm">
            <span class="rounded-full border border-ems/30 bg-ems/10 px-3 py-1 font-semibold text-ems-light">Difficulty {{ $session->current_difficulty }}/5</span>
            <span class="rounded-full border border-medic/30 bg-medic/10 px-3 py-1 font-semibold text-medic-light">{{ $session->scorePercent() }}% correct</span>
        </div>
    </div>

    @if ($lastAnswer)
        <div class="mb-6 rounded-2xl border px-5 py-4 {{ $lastAnswer->is_correct ? 'border-medic/40 bg-medic/10' : 'border-rescue/40 bg-rescue/10' }}">
            <p class="font-bold {{ $lastAnswer->is_correct ? 'text-medic-light' : 'text-red-200' }}">
                {{ $lastAnswer->is_correct ? 'Correct' : 'Incorrect' }} · Difficulty now {{ $session->current_difficulty }}/5
            </p>
            @if ($lastAnswer->question->explanation)
                <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $lastAnswer->question->explanation }}</p>
            @endif
        </div>
    @endif

    <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
        <div class="mb-4 flex items-center justify-between gap-4">
            <span class="rounded-full bg-ems/20 px-3 py-1 text-xs font-bold uppercase text-ems-light">{{ $question->category }}</span>
            @if ($session->sectionIsUnlocked())
                <span class="text-xs font-semibold text-medic-light">Unlimited access</span>
            @else
                <span class="text-xs font-semibold text-safety-light">{{ $session->freeQuestionsRemaining() }} free left in {{ $sectionLabel }}</span>
            @endif
        </div>

        <p class="text-lg leading-relaxed text-white">{{ $question->stem }}</p>

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
                <button type="submit" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm text-slate-400 hover:bg-white/5">End quiz &amp; view results</button>
            </form>
        @endif
    </div>
@endsection
