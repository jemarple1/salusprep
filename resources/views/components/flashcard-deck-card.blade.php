@props([
    'deck',
])

@php
    $styles = $deck->is_general
        ? \App\Services\FocusExamService::generalStyles()
        : \App\Support\QuestionCategory::styles($deck->label);
    $badgeLabel = $deck->is_general ? 'General deck' : 'Focus deck';
    $activeSession = $deck->active_session ?? null;
    $shellClasses = [
        'group relative flex min-h-[200px] w-full flex-col overflow-hidden rounded-2xl border p-5 text-left transition',
        $styles['border'],
        $styles['bg'],
        'cursor-pointer hover:brightness-110',
        $deck->completed && ! $activeSession ? 'ring-2 ring-medic/40' : '',
    ];
@endphp

@if ($activeSession)
    <a href="{{ route('study.show', [$sectionSlug, $activeSession]) }}" @class($shellClasses)>
        <span class="inline-block w-fit rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $styles['badge'] }}">
            {{ $badgeLabel }}
        </span>

        <div class="mt-3 flex flex-1 flex-col justify-between">
            <div>
                <h3 class="text-xl font-bold text-white">{{ $deck->label }}</h3>
                <p class="mt-2 text-sm text-slate-400">{{ $activeSession->remainingCount() }} cards left · {{ $activeSession->masteredCount() }} cleared</p>
                @if ($deck->accuracy_percent !== null)
                    <p class="mt-1 text-xs text-slate-500">{{ $deck->accuracy_percent }}% quiz accuracy so far</p>
                @elseif ($deck->is_general)
                    <p class="mt-1 text-xs text-slate-500">Mixed topics from the full question bank</p>
                @endif
            </div>

            <p class="mt-4 text-sm font-semibold {{ $styles['text'] }} group-hover:underline">Continue deck →</p>
        </div>
    </a>
@else
    <form method="POST" action="{{ route('study.public.start', $sectionSlug) }}" class="block h-full">
        @csrf
        <input type="hidden" name="deck_key" value="{{ $deck->key }}">
        <button type="submit" @class($shellClasses)>
            @if ($deck->completed)
                <span class="absolute right-3 top-3 inline-flex h-7 w-7 items-center justify-center rounded-full bg-medic text-sm font-bold text-navy" aria-label="Completed">✓</span>
            @endif

            <span class="inline-block w-fit rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $styles['badge'] }}">
                {{ $badgeLabel }}
            </span>

            <div class="mt-3 flex flex-1 flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $deck->label }}</h3>
                    <p class="mt-2 text-sm text-slate-400">{{ $deck->card_count }} curated cards</p>
                    @if ($deck->accuracy_percent !== null)
                        <p class="mt-1 text-xs text-slate-500">{{ $deck->accuracy_percent }}% quiz accuracy so far</p>
                    @elseif ($deck->is_general)
                        <p class="mt-1 text-xs text-slate-500">Mixed topics from the full question bank</p>
                    @endif
                </div>

                <p class="mt-4 text-sm font-semibold {{ $deck->completed ? 'text-medic-light' : $styles['text'] }} group-hover:underline">
                    @if ($deck->completed)
                        Complete · Study again →
                    @else
                        Start deck →
                    @endif
                </p>
            </div>
        </button>
    </form>
@endif
