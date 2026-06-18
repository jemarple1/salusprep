@extends('layouts.app')

@section('title', $sectionLabel.' Study complete')

@section('content')
    <div class="mx-auto max-w-lg text-center">
        <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-full bg-medic/20 text-4xl">✓</div>
        <h1 class="text-3xl font-bold text-white">Session complete</h1>
        <p class="mt-3 text-slate-400">
            You reviewed {{ $studySession->cards_studied }} cards
            @if ($studySession->filter_category)
                in <span class="font-semibold text-white">{{ $studySession->filter_category }}</span>
            @endif
            . Great work reinforcing weak areas.
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-xl border border-white/10 px-6 py-3 font-bold text-slate-200 hover:bg-white/5">Flashcards</a>
            <form method="POST" action="{{ route('study.start', $sectionSlug) }}">
                @csrf
                @if ($studySession->filter_category)
                    <input type="hidden" name="category" value="{{ $studySession->filter_category }}">
                @endif
                <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Study again</button>
            </form>
            <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-xl border border-medic/30 px-6 py-3 font-bold text-medic-light hover:bg-medic/10">Test Center</a>
        </div>
    </div>
@endsection
