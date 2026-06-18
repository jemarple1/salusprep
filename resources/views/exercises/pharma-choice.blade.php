@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @if (! empty($scenario['drug']))
        <div class="mb-4 inline-flex rounded-full border border-pharma/30 bg-pharma/10 px-4 py-1.5 text-sm font-bold text-pharma-light">
            {{ $scenario['drug'] }}
        </div>
    @endif

    @if (! empty($scenario['scenario']))
        <p class="mb-4 text-lg leading-relaxed text-slate-200">{{ $scenario['scenario'] }}</p>
    @endif

    <p class="mb-4 text-base font-semibold text-white">{{ $scenario['question'] ?? 'Choose the best answer.' }}</p>

    <div class="grid gap-3">
        @foreach ($scenario['options'] as $key => $label)
            <button type="button" data-answer="{{ $key }}" class="pharma-choice rounded-xl border border-white/10 bg-navy/50 p-4 text-left text-sm font-semibold text-slate-200 transition hover:border-pharma/40 hover:bg-navy-light/80">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @include('exercises.partials.interactive-script')
@endsection
