@props([
    'compact' => false,
])

@if ($examCountdown ?? null)
    <a
        href="{{ route('platform.welcome', $sectionSlug) }}"
        @class([
            'inline-flex items-center gap-2 rounded-lg border font-bold transition hover:brightness-110',
            $compact ? 'px-2.5 py-1.5 text-[11px]' : 'px-3 py-1.5 text-xs',
            ($examCountdown['is_today'] ?? false)
                ? 'border-safety/50 bg-safety/15 text-safety-light'
                : (($examCountdown['is_past'] ?? false)
                    ? 'border-white/15 bg-white/5 text-slate-400'
                    : 'border-medic/40 bg-medic/10 text-medic-light'),
        ])
        title="{{ $examCountdown['label'] }}"
    >
        <svg class="{{ $compact ? 'h-3.5 w-3.5' : 'h-4 w-4' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
        <span>{{ $examCountdown['short_label'] }}</span>
    </a>
@endif
