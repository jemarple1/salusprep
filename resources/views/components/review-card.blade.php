@props(['concept'])

@php
    $accent = $concept['accent'] ?? 'medic';
    $accentBorder = match ($accent) {
        'ems' => 'hover:border-ems/40',
        'rescue' => 'hover:border-rescue/40',
        'pharma' => 'hover:border-pharma/40',
        'safety' => 'hover:border-safety/40',
        default => 'hover:border-medic/40',
    };
@endphp

<a
    href="{{ route('review.show', [$sectionSlug, $concept['slug']]) }}"
    class="group flex flex-col overflow-hidden rounded-xl border border-white/10 bg-navy-light/60 shadow-lg transition sm:rounded-2xl {{ $accentBorder }} hover:bg-navy-light/90"
>
    <x-review-backdrop
        :accent="$accent"
        class="h-5 shrink-0 rounded-b-none rounded-t-xl sm:rounded-t-2xl"
        aria-hidden="true"
    />

    <div class="flex flex-1 flex-col p-3 sm:p-5">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 sm:text-xs">{{ $concept['category'] }}</p>
        <h2 class="mt-1.5 text-sm font-bold leading-snug text-white group-hover:text-medic-light sm:mt-2 sm:text-lg">{{ $concept['title'] }}</h2>
        <p class="mt-1.5 line-clamp-3 flex-1 text-xs leading-relaxed text-slate-400 sm:mt-2 sm:text-sm">{{ $concept['excerpt'] }}</p>
        <p class="mt-3 text-xs font-semibold text-medic-light group-hover:underline sm:mt-4 sm:text-sm">Read concept →</p>
    </div>
</a>
