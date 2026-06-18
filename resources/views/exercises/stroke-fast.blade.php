@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @php
        $actions = [
            'transport' => 'Rapid transport — stroke alert',
            'evaluate' => 'Evaluate other causes, monitor closely',
        ];
    @endphp

    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        @foreach (['face' => 'Face droop', 'arms' => 'Arm drift', 'speech' => 'Speech slurred'] as $key => $label)
            <div class="rounded-2xl border border-white/10 bg-navy/50 p-4 text-center">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-2xl font-black {{ ($scenario['fast'][$key] ?? false) ? 'text-rescue' : 'text-medic-light' }}">
                    {{ ($scenario['fast'][$key] ?? false) ? 'Yes' : 'No' }}
                </p>
            </div>
        @endforeach
    </div>

    <p class="mb-4 text-sm font-semibold text-slate-400">Choose the best action.</p>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($actions as $key => $label)
            <button type="button" data-answer="{{ $key }}" class="stroke-action rounded-2xl border border-white/10 bg-navy-light/80 p-5 text-left font-bold text-white transition hover:border-medic/40">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @include('exercises.partials.interactive-script')
@endsection
