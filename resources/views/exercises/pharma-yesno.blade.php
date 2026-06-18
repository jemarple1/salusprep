@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @if (! empty($scenario['drug']))
        <div class="mb-4 inline-flex rounded-full border border-pharma/30 bg-pharma/10 px-4 py-1.5 text-sm font-bold text-pharma-light">
            {{ $scenario['drug'] }}
        </div>
    @endif

    <p class="mb-6 text-lg leading-relaxed text-slate-200">{{ $scenario['prompt'] ?? $scenario['scenario'] ?? '' }}</p>

    <p class="mb-4 text-sm font-semibold uppercase tracking-wider text-slate-500">Safe to give? Tap your answer.</p>

    <div class="grid grid-cols-2 gap-4 sm:max-w-lg">
        <button type="button" data-answer="yes" class="pharma-yesno-btn rounded-2xl border-2 border-medic/40 bg-medic/10 py-8 text-2xl font-black uppercase tracking-wider text-medic-light transition hover:bg-medic/20 hover:scale-[1.02] active:scale-[0.98]">
            Yes
        </button>
        <button type="button" data-answer="no" class="pharma-yesno-btn rounded-2xl border-2 border-rescue/40 bg-rescue/10 py-8 text-2xl font-black uppercase tracking-wider text-red-200 transition hover:bg-rescue/20 hover:scale-[1.02] active:scale-[0.98]">
            No
        </button>
    </div>

    @include('exercises.partials.pharma-yesno-script')
@endsection
