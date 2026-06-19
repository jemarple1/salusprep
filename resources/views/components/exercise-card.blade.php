@props([
    'exercise',
    'variant' => 'carousel',
    'showcase' => false,
])

@php
    $color = $exercise['color'] ?? 'ems';
    $sizeClass = $variant === 'grid'
        ? 'min-w-0 max-w-none'
        : 'min-w-[16rem] max-w-[16rem] shrink-0 sm:min-w-[18rem] sm:max-w-[18rem]';
    $ring = match ($color) {
        'medic' => 'ring-medic/30 hover:border-medic/40',
        'pharma' => 'ring-pharma/30 hover:border-pharma/40',
        'rescue' => 'ring-rescue/30 hover:border-rescue/40',
        'safety' => 'ring-safety/30 hover:border-safety/40',
        default => 'ring-ems/30 hover:border-ems/40',
    };
    $badge = match ($color) {
        'medic' => 'bg-medic/20 text-medic-light',
        'pharma' => 'bg-pharma/20 text-pharma-light',
        'rescue' => 'bg-rescue/20 text-red-200',
        'safety' => 'bg-safety/20 text-safety-light',
        default => 'bg-ems/20 text-ems-light',
    };
    $hoverTitle = match ($color) {
        'pharma' => 'group-hover:text-pharma-light',
        'rescue' => 'group-hover:text-red-200',
        'safety' => 'group-hover:text-safety-light',
        'medic' => 'group-hover:text-medic-light',
        default => 'group-hover:text-ems-light',
    };
    $tag = $showcase ? 'div' : 'a';
    $linkAttrs = $showcase ? '' : 'href="'.$exercise['url'].'"';
    $surfaceClass = $showcase
        ? 'border-white/10 bg-navy-light'
        : 'border-white/10 bg-navy-light/90 shadow-lg';
@endphp

<{{ $tag }}
    {!! $linkAttrs !!}
    {{ $attributes->class(['group flex h-full flex-col rounded-2xl border p-5 transition', $surfaceClass, $showcase ? '' : 'hover:-translate-y-0.5 hover:bg-navy-light', $sizeClass, $showcase ? '' : $ring]) }}
>
    <div class="mb-3 flex items-start gap-3">
        <div @class([
            'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/15 bg-white/10',
            'text-ems-light' => ($exercise['color'] ?? 'ems') === 'ems',
            'text-red-300' => ($exercise['color'] ?? '') === 'rescue',
            'text-medic-light' => ($exercise['color'] ?? '') === 'medic',
            'text-pharma-light' => ($exercise['color'] ?? '') === 'pharma',
            'text-safety-light' => ($exercise['color'] ?? '') === 'safety',
        ])>
            <x-exercise-icon :icon="$exercise['icon'] ?? 'clipboard'" />
        </div>
        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $badge }}">{{ $exercise['category'] }}</span>
    </div>

    <h3 class="text-lg font-bold leading-snug text-white {{ $showcase ? '' : $hoverTitle }}">{{ $exercise['title'] }}</h3>
    <p class="mt-2 flex-1 text-sm leading-relaxed {{ $showcase ? 'text-slate-300' : 'text-slate-400' }}">{{ $exercise['description'] }}</p>
    @if (! $showcase)
        <p class="mt-4 text-xs font-semibold text-slate-500 group-hover:text-ems-light">Open exercise →</p>
    @endif
</{{ $tag }}>
