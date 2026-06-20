@extends('layouts.app')

@section('title', 'Review')

@section('content')
    <div class="mb-8">
        <a href="{{ route('platform.home', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Back</a>
        <h1 class="mt-3 text-3xl font-bold text-white">Review</h1>
        <p class="mt-2 max-w-2xl text-slate-400">
            Foundational basic concepts sourced from U.S. government health and safety agencies—built for {{ $sectionLabel }} exam prep.
        </p>
    </div>

    <form method="GET" action="{{ route('review.index', $sectionSlug) }}" class="mb-8">
        <label for="review-search" class="sr-only">Search concepts</label>
        <div class="relative">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
            <input
                type="search"
                id="review-search"
                name="q"
                value="{{ $query }}"
                placeholder="Search basic concepts, keywords, or categories…"
                class="w-full rounded-xl border border-white/10 bg-navy-light/80 py-3.5 pl-12 pr-4 text-white placeholder-slate-500 shadow-inner focus:border-medic/50 focus:outline-none focus:ring-2 focus:ring-medic/20"
            />
        </div>
        @if ($query !== '')
            <p class="mt-3 text-sm text-slate-400">
                {{ count($concepts) }} {{ Str::plural('result', count($concepts)) }} for “{{ $query }}”
                · <a href="{{ route('review.index', $sectionSlug) }}" class="font-medium text-medic-light hover:underline">Clear search</a>
            </p>
        @endif
    </form>

    @if ($concepts !== [])
        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3 lg:gap-6">
            @foreach ($concepts as $concept)
                <x-review-card :concept="$concept" />
            @endforeach
        </div>
    @else
        <div class="rounded-2xl border border-white/10 bg-navy-light/80 px-6 py-12 text-center">
            <p class="text-lg font-semibold text-white">No concepts match your search</p>
            <p class="mt-2 text-slate-400">Try a broader term like “airway,” “cardiac,” or “safety.”</p>
        </div>
    @endif
@endsection
