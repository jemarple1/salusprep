@extends('layouts.app')

@section('title', $sectionLabel.' Mock Exam Result')

@section('content')
    <div class="mx-auto max-w-xl text-center">
        <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Test Center</a>

        <div class="mt-8 overflow-hidden rounded-2xl border border-white/10 bg-navy-light/80">
            <div @class([
                'px-8 py-10',
                $session->mockPassed() ? 'bg-medic/20' : 'bg-rescue/20',
            ])>
                @if ($session->mockPassed())
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-medic/30 ring-2 ring-medic/50">
                        <svg class="h-10 w-10 text-medic-light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h1 class="mt-6 text-3xl font-bold text-white">Pass</h1>
                    <p class="mt-3 text-slate-300">
                        Based on your adaptive performance, you met the competency standard for this session.
                    </p>
                @else
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-rescue/30 ring-2 ring-rescue/50">
                        <svg class="h-10 w-10 text-red-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <h1 class="mt-6 text-3xl font-bold text-white">Did not pass</h1>
                    <p class="mt-3 text-slate-300">
                        This session ended below the competency threshold. Keep practicing with focus quizzes and flashcards.
                    </p>
                @endif
            </div>

            <div class="border-t border-white/10 px-8 py-6 text-sm text-slate-400">
                <p>{{ $session->questions_answered }} questions administered</p>
                <p class="mt-2">Like the real {{ \App\Support\CertificationLevel::isNclex($session->certification_level) ? 'NCLEX' : 'NREMT' }} exam, individual answers and scores are not shown.</p>
                <p class="mt-2">You can take another mock exam tomorrow.</p>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Back to Test Center</a>
            <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-xl border border-ems/40 bg-ems/10 px-6 py-3 font-bold text-ems-light hover:bg-ems/20">Review flashcards</a>
        </div>
    </div>
@endsection
