@extends('layouts.app')

@section('title', $sectionLabel.' Skills')

@section('content')
    <div class="mb-8">
        <a href="{{ route('platform.home', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Back</a>
        <h1 class="mt-3 text-3xl font-bold text-white">Skill exercises</h1>
        <p class="mt-2 max-w-2xl text-slate-400">
            Hands-on scenarios for SOAP charting, triage, GCS, burns, stroke scales, vitals, pharmacology, and more.
        </p>
    </div>

    @if ($exercises !== [])
        <x-exercise-grid
            :exercises="$exercises"
            title="All exercises"
            description="Practice real-world skills with interactive scenarios."
        />
    @else
        <div class="rounded-2xl border border-white/10 bg-navy-light/80 px-6 py-10 text-center text-slate-400">
            Skill exercises for this platform are coming soon.
        </div>
    @endif
@endsection
