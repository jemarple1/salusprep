@props([
    'compact' => false,
    'link' => null,
])

@if ($link)
    <a
        href="{{ route('platform.welcome', $sectionSlug) }}"
        @class([
            'inline-flex items-center gap-2 rounded-lg border border-medic/40 bg-medic/10 font-bold text-medic-light transition hover:brightness-110',
            $compact ? 'px-2.5 py-1.5 text-[11px]' : 'px-3 py-1.5 text-xs',
        ])
        title="{{ $link['label'] }}"
    >
        <svg class="{{ $compact ? 'h-3.5 w-3.5' : 'h-4 w-4' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 11l3 3L22 4" />
            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
        </svg>
        <span>{{ $link['short_label'] }}</span>
    </a>
@endif
