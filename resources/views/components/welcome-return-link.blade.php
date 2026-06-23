@if ($showWelcomeReturn ?? false)
    <div class="mt-8 flex justify-center sm:justify-start">
        <a
            href="{{ route('platform.welcome', $sectionSlug) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-medic/40 bg-medic/10 px-4 py-2.5 text-sm font-bold text-medic-light transition hover:brightness-110"
            title="Return to today's study checklist"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            <span>Return to today's checklist</span>
        </a>
    </div>
@endif
