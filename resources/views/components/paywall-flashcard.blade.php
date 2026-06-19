@props([
    'preview',
    'cardId',
    'flipOnHover' => false,
])

@php
    $question = $preview->question;
@endphp

<div @class([
    'paywall-flashcard w-full max-w-md mx-auto',
    'paywall-flashcard--hover' => $flipOnHover,
    'aspect-[4/3]' => $flipOnHover,
    'aspect-square' => ! $flipOnHover,
]) data-card-id="{{ $cardId }}">
    <div
        class="paywall-flashcard-inner h-full w-full {{ $flipOnHover ? '' : 'cursor-pointer' }}"
        @unless($flipOnHover)
            role="button"
            tabindex="0"
            aria-label="Flip flashcard"
        @endunless
    >
        {{-- Front --}}
        <div class="paywall-flashcard-face paywall-flashcard-face--front flex h-full w-full flex-col overflow-hidden rounded-xl border border-white/15 bg-navy p-5 sm:p-6">
            <div class="mb-3 flex shrink-0 items-center justify-between gap-2">
                <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">{{ $question->category }}</span>
                <span class="text-[10px] font-semibold text-slate-500">{{ $flipOnHover ? 'Hover to flip' : 'Tap to flip' }}</span>
            </div>
            <p class="line-clamp-5 flex-1 text-base leading-snug text-white">{{ $question->stem }}</p>
            <div class="mt-4 shrink-0 space-y-2">
                @foreach ($question->options() as $letter => $text)
                    <div @class([
                        'truncate rounded border px-3 py-1.5 text-sm',
                        $preview->wrong_option && strtoupper($preview->wrong_option) === strtoupper($letter)
                            ? 'border-safety/40 bg-safety/10 text-slate-200'
                            : 'border-white/10 bg-navy-light text-slate-300',
                    ])>
                        <span class="font-bold text-slate-200">{{ $letter }}.</span> {{ Str::limit($text, 48) }}
                    </div>
                @endforeach
            </div>
            @if ($preview->wrong_option)
                <p class="mt-3 shrink-0 text-xs text-slate-500">Last try: <strong class="text-slate-300">{{ $preview->wrong_option }}</strong></p>
            @endif
        </div>

        {{-- Back --}}
        <div class="paywall-flashcard-face paywall-flashcard-face--back flex h-full w-full flex-col overflow-hidden rounded-xl border border-white/15 bg-navy p-5 sm:p-6">
            <div class="mb-3 flex shrink-0 items-center justify-between gap-2">
                <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Answer</span>
            </div>
            <p class="line-clamp-4 shrink-0 text-base font-bold leading-snug text-white">
                <span class="text-medic-light">{{ $question->correct_option }}.</span>
                {{ $question->optionFor($question->correct_option) }}
            </p>
            @if ($question->explanation)
                <div class="mt-4 min-h-0 flex-1 overflow-hidden rounded-lg border border-white/10 bg-navy-light p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Explanation</p>
                    <p class="mt-2 line-clamp-[10] text-sm leading-relaxed text-slate-300">{{ $question->explanation }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .paywall-flashcard {
        perspective: 1200px;
    }

    .paywall-flashcard-inner {
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.45s ease;
    }

    .paywall-flashcard-face {
        position: absolute;
        inset: 0;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }

    .paywall-flashcard-face--back {
        transform: rotateY(180deg);
    }

    .paywall-flashcard.is-flipped .paywall-flashcard-inner,
    .paywall-flashcard--hover:hover .paywall-flashcard-inner {
        transform: rotateY(180deg);
    }
</style>

@if (! $flipOnHover)
    <script>
        (function () {
            var card = document.querySelector('[data-card-id="{{ $cardId }}"]');
            if (!card) return;
            var inner = card.querySelector('.paywall-flashcard-inner');
            if (!inner) return;

            function flip() {
                card.classList.toggle('is-flipped');
            }

            inner.addEventListener('click', flip);
            inner.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    flip();
                }
            });
        })();
    </script>
@endif
