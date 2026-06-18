@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @php
        $tags = [
            'immediate' => ['label' => 'Immediate', 'code' => 'Red', 'class' => 'bg-red-600 border-red-400 text-white hover:bg-red-500'],
            'delayed' => ['label' => 'Delayed', 'code' => 'Yellow', 'class' => 'bg-yellow-400 border-yellow-200 text-navy hover:bg-yellow-300'],
            'minor' => ['label' => 'Minor', 'code' => 'Green', 'class' => 'bg-green-500 border-green-300 text-white hover:bg-green-400'],
            'expectant' => ['label' => 'Expectant', 'code' => 'Black', 'class' => 'bg-slate-950 border-slate-600 text-white hover:bg-slate-900'],
        ];
    @endphp

    <p class="mb-4 text-sm font-semibold text-slate-400">Tap the triage tag that matches this patient.</p>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        @foreach ($tags as $key => $tag)
            <button
                type="button"
                data-answer="{{ $key }}"
                class="triage-tag flex min-h-[8.5rem] flex-col items-center justify-center rounded-2xl border-2 p-4 text-center shadow-lg transition {{ $tag['class'] }}"
            >
                <span class="text-lg font-black uppercase tracking-wide">{{ $tag['label'] }}</span>
                <span class="mt-2 text-sm font-bold opacity-90">{{ $tag['code'] }}</span>
            </button>
        @endforeach
    </div>

    @include('exercises.partials.interactive-script')
@endsection
