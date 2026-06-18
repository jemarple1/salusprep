@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @php
        $steps = [
            'lifesaving' => ['label' => 'Lifesaving', 'sub' => 'Interventions + urgent transport', 'class' => 'bg-red-600 border-red-400 text-white'],
            'assess' => ['label' => 'Assess', 'sub' => 'Sort & assess in place', 'class' => 'bg-blue-600 border-blue-400 text-white'],
            'minor' => ['label' => 'Minor', 'sub' => 'Walking wounded', 'class' => 'bg-green-500 border-green-300 text-white'],
            'expectant' => ['label' => 'Expectant', 'sub' => 'Incompatible with life', 'class' => 'bg-slate-950 border-slate-600 text-white'],
        ];
    @endphp

    <p class="mb-4 text-sm font-semibold text-slate-400">Choose the correct SALT priority for this patient.</p>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($steps as $key => $step)
            <button
                type="button"
                data-answer="{{ $key }}"
                class="salt-tag rounded-2xl border-2 p-5 text-left shadow-lg transition hover:scale-[1.01] {{ $step['class'] }}"
            >
                <span class="text-xl font-black">{{ $step['label'] }}</span>
                <span class="mt-1 block text-sm opacity-90">{{ $step['sub'] }}</span>
            </button>
        @endforeach
    </div>

    @include('exercises.partials.interactive-script')
@endsection
