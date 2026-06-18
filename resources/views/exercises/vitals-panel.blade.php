@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @php
        $actions = [
            'oxygen' => 'High-flow oxygen + rapid transport',
            'oxygen_iv' => 'Oxygen, IV access, ECG, rapid transport',
            'comfort_transport' => 'Comfort care + transport if needed',
            'transport_iv' => 'IV, monitor, transport',
        ];
        $v = $scenario['vitals'];
    @endphp

    <div class="mb-6 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
        @foreach (['bp' => 'BP', 'pulse' => 'Pulse', 'rr' => 'RR', 'spo2' => 'SpO₂', 'temp' => 'Temp', 'glucose' => 'Glucose'] as $key => $label)
            <div class="rounded-xl border border-ems/30 bg-ems/10 p-4 text-center">
                <p class="text-[10px] font-bold uppercase tracking-wider text-ems-light">{{ $label }}</p>
                <p class="mt-2 text-xl font-black text-white">{{ $v[$key] ?? '—' }}</p>
            </div>
        @endforeach
    </div>

    <p class="mb-4 text-sm font-semibold text-slate-400">Select the most appropriate intervention.</p>

    <div class="grid gap-3 sm:grid-cols-2">
        @foreach ($actions as $key => $label)
            <button type="button" data-answer="{{ $key }}" class="vitals-action rounded-xl border border-white/10 bg-navy/50 p-4 text-left text-sm font-semibold text-slate-200 transition hover:border-ems/40">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @include('exercises.partials.interactive-script')
@endsection
