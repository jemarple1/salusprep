@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-sm font-semibold text-slate-400">Tap the patient who should receive the next transport unit.</p>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($scenario['patients'] as $patient)
            <button
                type="button"
                data-answer="{{ $patient['id'] }}"
                class="mci-patient rounded-2xl border border-white/10 bg-navy/60 p-5 text-left transition hover:border-rescue/40 hover:bg-navy-light/80"
            >
                <p class="text-lg font-bold text-white">{{ $patient['label'] }}</p>
                <p class="mt-2 text-sm leading-relaxed text-slate-300">{{ $patient['detail'] }}</p>
            </button>
        @endforeach
    </div>

    @include('exercises.partials.interactive-script')
@endsection
