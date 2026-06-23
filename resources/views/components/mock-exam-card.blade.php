@props([
    'disabled' => false,
    'mockActive' => null,
    'canStart' => false,
    'completedToday' => false,
    'todaysOutcome' => null,
    'hasAccess' => true,
    'regularQuizActive' => false,
])

@php
    $blocked = $disabled || $regularQuizActive || ($mockActive && ! $canStart && ! $completedToday);
@endphp

<div @class([
    'group flex h-full flex-col rounded-2xl border border-safety/50 bg-safety/35 p-5 transition',
    $blocked && ! $mockActive ? 'opacity-60' : 'hover:brightness-110',
])>
    <span class="inline-block w-fit rounded-full bg-safety/30 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-safety-light">
        Mock exam
    </span>

    <h3 class="mt-3 text-xl font-bold text-white">Daily mock exam</h3>

    @if ($completedToday)
        <div class="mt-3">
            <p class="text-2xl font-bold text-white">
                @if ($todaysOutcome === 'pass')
                    <span class="text-medic-light">Pass</span>
                @else
                    <span class="text-red-200">Did not pass</span>
                @endif
            </p>
            <p class="mt-1 text-xs text-slate-300">Completed today · next available tomorrow</p>
        </div>
    @else
        <p class="mt-3 text-sm leading-relaxed text-slate-200">
            Timed adaptive simulation — 70–140 questions, pass or fail only.
        </p>
    @endif

    <div class="mt-auto border-t border-white/10 pt-4">
        @if ($mockActive)
            <a href="{{ route('mock-exam.show', [$sectionSlug, $mockActive]) }}" class="inline-flex items-center gap-1 text-sm font-bold text-safety-light hover:text-safety hover:underline">
                Continue mock exam →
            </a>
        @elseif ($completedToday)
            <p class="text-xs text-slate-400">One mock exam per day</p>
        @elseif ($regularQuizActive || $disabled)
            <p class="text-xs text-slate-400">Finish your current quiz first</p>
        @elseif ($hasAccess && $canStart)
            <form method="POST" action="{{ route('mock-exam.start', $sectionSlug) }}" class="flex items-center justify-between gap-3">
                @csrf
                <span class="text-xs text-slate-400">2-hour limit · once per day</span>
                <button type="submit" class="text-sm font-bold text-safety-light hover:text-safety hover:underline">
                    Start →
                </button>
            </form>
        @elseif (! $hasAccess)
            <a href="{{ route('platform.paywall', $sectionSlug) }}" class="inline-flex items-center gap-1 text-sm font-bold text-safety-light hover:text-safety hover:underline">
                Unlock to start →
            </a>
        @else
            <p class="text-xs text-slate-400">Unavailable right now</p>
        @endif
    </div>
</div>
