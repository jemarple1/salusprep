@props([
    'difficulty',
    'showLevel' => false,
    'showLabel' => true,
])

@php
    $level = max(1, min(5, (int) $difficulty));
    $percent = ($level / 5) * 100;
    $barColor = match (true) {
        $level <= 2 => 'bg-medic',
        $level === 3 => 'bg-safety',
        $level === 4 => 'bg-safety-light',
        default => 'bg-rescue',
    };
@endphp

<div {{ $attributes->class(['flex items-center gap-2.5']) }}>
    @if ($showLabel)
        <span class="shrink-0 text-xs font-semibold uppercase tracking-wide text-slate-400">Difficulty</span>
    @endif
    <div class="h-2 w-24 overflow-hidden rounded-full bg-white/10 ring-1 ring-white/5 sm:w-28">
        <div class="{{ $barColor }} h-full rounded-full transition-all duration-300" style="width: {{ $percent }}%"></div>
    </div>
    @if ($showLevel)
        <span class="shrink-0 text-xs tabular-nums font-semibold text-slate-400">{{ $level }}/5</span>
    @endif
</div>
