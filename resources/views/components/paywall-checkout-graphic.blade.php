<div class="relative mx-auto w-full max-w-[11rem] select-none sm:max-w-[12.5rem]" aria-hidden="true">
    <div class="absolute inset-0 rounded-full bg-safety/15 blur-2xl"></div>
    <svg viewBox="0 0 200 180" class="relative h-auto w-full" fill="none" xmlns="http://www.w3.org/2000/svg">
        {{-- Shield / unlock --}}
        <path
            d="M100 18 L152 38 V82 C152 118 132 142 100 158 C68 142 48 118 48 82 V38 Z"
            fill="url(#checkout-shield)"
            stroke="rgba(251,191,36,0.45)"
            stroke-width="2"
        />
        <path
            d="M82 88 L94 100 L120 72"
            stroke="#0f172a"
            stroke-width="6"
            stroke-linecap="round"
            stroke-linejoin="round"
        />
        {{-- Mini flashcard stack --}}
        <rect x="24" y="118" width="54" height="38" rx="6" fill="#1e293b" stroke="rgba(255,255,255,0.1)" transform="rotate(-6 51 137)" />
        <rect x="34" y="124" width="54" height="38" rx="6" fill="#162032" stroke="rgba(255,255,255,0.12)" transform="rotate(2 61 143)" />
        <rect x="44" y="130" width="54" height="38" rx="6" fill="#0f172a" stroke="rgba(255,255,255,0.16)" />
        <rect x="52" y="138" width="22" height="4" rx="2" fill="rgba(74,222,128,0.5)" />
        <rect x="52" y="148" width="36" height="3" rx="1.5" fill="rgba(255,255,255,0.1)" />
        {{-- Trend arrow --}}
        <path d="M128 132 L168 92" stroke="#f59e0b" stroke-width="5" stroke-linecap="round" />
        <path d="M168 92 L158 96 M168 92 L164 102" stroke="#fbbf24" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" />
        <defs>
            <linearGradient id="checkout-shield" x1="100" y1="18" x2="100" y2="158" gradientUnits="userSpaceOnUse">
                <stop stop-color="#fbbf24" stop-opacity="0.35" />
                <stop offset="1" stop-color="#d97706" stop-opacity="0.15" />
            </linearGradient>
        </defs>
    </svg>
</div>
