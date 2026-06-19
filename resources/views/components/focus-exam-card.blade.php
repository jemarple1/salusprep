@props([
    'category',
    'focusCategory' => null,
    'accuracy' => null,
    'selected' => false,
    'startOnClick' => false,
    'disabled' => false,
    'isGeneral' => false,
])

@php
    $styles = $isGeneral
        ? \App\Services\FocusExamService::generalStyles()
        : \App\Support\QuestionCategory::styles($category);
    $color = $isGeneral ? 'medic' : \App\Support\QuestionCategory::color($category);
    $badgeLabel = $isGeneral ? 'Adaptive quiz' : 'Focus exam';
    $subtitle = $isGeneral
        ? '25 questions · mixed topics'
        : '25 questions · 75% '.$category;
@endphp

@if ($startOnClick)
    <form method="POST" action="{{ route('exam.start', $sectionSlug) }}" class="block h-full">
        @csrf
        @if ($focusCategory)
            <input type="hidden" name="focus_category" value="{{ $focusCategory }}">
        @endif
        <button
            type="submit"
            @class([
                'focus-exam-surface group flex h-full w-full flex-col rounded-2xl border p-5 text-left transition',
                $styles['border'],
                $styles['bg'],
                $disabled ? 'cursor-not-allowed opacity-50' : 'cursor-pointer hover:brightness-110',
            ])
            @disabled($disabled)
        >
            <span class="inline-block w-fit rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $styles['badge'] }}">
                {{ $badgeLabel }}
            </span>

            <h3 class="mt-3 text-xl font-bold text-white">{{ $category }}</h3>

            @if ($accuracy !== null)
                <div class="mt-3 flex items-baseline gap-2">
                    <p class="text-2xl font-bold {{ $styles['text'] }}">{{ $accuracy }}%</p>
                    <p class="text-xs text-slate-400">so far</p>
                </div>
            @elseif ($isGeneral)
                <p class="mt-3 text-sm text-slate-400">Balanced across every topic</p>
            @else
                <p class="mt-3 text-sm text-slate-400">No quiz data yet</p>
            @endif

            <div class="mt-auto flex items-center justify-between gap-3 border-t border-white/10 pt-4">
                <span class="text-xs text-slate-500">{{ $subtitle }}</span>
                <span class="text-sm font-semibold {{ $styles['text'] }} group-hover:underline">Start →</span>
            </div>
        </button>
    </form>
@else
    <label
        class="focus-exam-option block cursor-pointer {{ $selected ? 'is-selected' : '' }}"
        data-focus-option
        data-color="{{ $color }}"
    >
        <input
            type="radio"
            name="focus_category"
            value="{{ $focusCategory ?? $category }}"
            class="sr-only focus-exam-radio"
            @checked($selected)
        >
        <div @class([
            'focus-exam-surface flex h-full flex-col rounded-2xl border p-5 transition',
            $styles['border'],
            $styles['bg'],
        ])>
            <span class="inline-block w-fit rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $styles['badge'] }}">
                {{ $badgeLabel }}
            </span>

            <h3 class="mt-3 text-xl font-bold text-white">{{ $category }}</h3>

            @if ($accuracy !== null)
                <div class="mt-3 flex items-baseline gap-2">
                    <p class="text-2xl font-bold {{ $styles['text'] }}">{{ $accuracy }}%</p>
                    <p class="text-xs text-slate-400">so far</p>
                </div>
            @endif

            <div class="mt-auto flex items-center gap-3 border-t border-white/10 pt-4">
                <span class="focus-exam-check flex h-7 w-7 shrink-0 items-center justify-center rounded-md border-2 border-white/25 bg-navy transition">
                    <svg class="focus-exam-check-icon h-4 w-4 stroke-current opacity-0 transition" viewBox="0 0 24 24" fill="none" stroke-width="3" aria-hidden="true">
                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <span class="text-sm font-semibold text-white">Start next</span>
            </div>
        </div>
    </label>
@endif
