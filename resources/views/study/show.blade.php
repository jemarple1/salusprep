@extends('layouts.app')

@section('title', $sectionLabel.' Flashcards')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('study.index', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Flashcards</a>
            <p class="mt-2 text-sm font-bold uppercase tracking-wider text-medic-light">
                Card {{ $cardNumber }} · {{ $studySession->remainingCount() }} remaining
                @if ($studySession->filter_category)
                    · {{ $studySession->filter_category }}
                @else
                    · Complete deck
                @endif
            </p>
        </div>
        <span class="rounded-full border border-ems/30 bg-ems/10 px-3 py-1 text-sm font-semibold text-ems-light">{{ $studySession->masteredCount() }}/{{ $studySession->initial_deck_size }} cleared</span>
    </div>

    <div class="mb-6 h-2 overflow-hidden rounded-full bg-navy-light ring-1 ring-white/10">
        <div class="h-full rounded-full bg-medic transition-all" style="width: {{ $studySession->progressPercent() }}%"></div>
    </div>

    <div id="flashcard" class="group mx-auto max-w-2xl cursor-pointer perspective-[1200px]" role="button" tabindex="0" aria-label="Flip card">
        <div id="flashcard-inner" class="grid transition-transform duration-500 [transform-style:preserve-3d]">
            {{-- Front --}}
            <div class="col-start-1 row-start-1 rounded-2xl border border-white/10 bg-navy-light/90 p-6 shadow-xl [backface-visibility:hidden] sm:p-8">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <span class="rounded-full bg-ems/20 px-3 py-1 text-xs font-bold uppercase text-ems-light">{{ $question->category }}</span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Question</span>
                </div>
                <p class="text-xl leading-relaxed text-white sm:text-2xl">{{ $question->stem }}</p>
                <div class="mt-8 space-y-2">
                    @foreach ($question->options() as $letter => $text)
                        <div class="rounded-lg border border-white/5 bg-navy/40 px-4 py-3 text-sm text-slate-300">
                            <span class="font-bold text-medic-light">{{ $letter }}.</span> {{ $text }}
                        </div>
                    @endforeach
                </div>
                <p class="mt-8 text-center text-sm font-semibold text-medic-light">Tap card to reveal answer</p>
            </div>

            {{-- Back --}}
            <div class="col-start-1 row-start-1 rounded-2xl border border-medic/30 bg-gradient-to-br from-medic/10 to-navy-light/95 p-6 shadow-xl [backface-visibility:hidden] [transform:rotateY(180deg)] sm:p-8">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <span class="rounded-full bg-medic/20 px-3 py-1 text-xs font-bold uppercase text-medic-light">Answer</span>
                    @if ($lastWrong)
                        <span class="text-xs font-semibold text-red-200">You chose {{ $lastWrong->selected_option }}</span>
                    @endif
                </div>

                <p class="text-lg font-bold text-white">
                    Correct: <span class="text-medic-light">{{ $question->correct_option }}.</span>
                    {{ $question->optionFor($question->correct_option) }}
                </p>

                @if ($question->explanation)
                    <div class="mt-6 rounded-xl border border-white/10 bg-navy/50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Explanation</p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-200">{{ $question->explanation }}</p>
                    </div>
                @endif

                <div id="study-rating" class="mt-8 opacity-40 pointer-events-none transition-opacity">
                    <p class="text-center text-sm font-semibold text-slate-300">How well do you know this now?</p>
                    <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                        <form method="POST" action="{{ route('study.advance', [$sectionSlug, $studySession]) }}" class="study-rating-form">
                            @csrf
                            <input type="hidden" name="action" value="weak">
                            <button
                                type="submit"
                                class="group/btn flex flex-col items-center gap-2 rounded-xl border border-safety/40 bg-safety/10 px-6 py-4 font-bold text-safety-light transition hover:bg-safety/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-safety/60"
                                aria-label="Still learning — show this card again soon"
                            >
                                <svg class="h-8 w-8 rotate-180 transition group-hover/btn:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M7 10v12" />
                                    <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z" />
                                </svg>
                                <span class="text-xs uppercase tracking-wider">Still learning</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('study.advance', [$sectionSlug, $studySession]) }}" class="study-rating-form">
                            @csrf
                            <input type="hidden" name="action" value="strong">
                            <button
                                type="submit"
                                class="group/btn flex flex-col items-center gap-2 rounded-xl border border-medic/40 bg-medic/20 px-6 py-4 font-bold text-medic-light transition hover:bg-medic/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-medic/60"
                                aria-label="Got it — clear this card for now"
                            >
                                <svg class="h-8 w-8 transition group-hover/btn:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M7 10v12" />
                                    <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z" />
                                </svg>
                                <span class="text-xs uppercase tracking-wider">Got it</span>
                            </button>
                        </form>
                    </div>
                    <p class="mt-4 text-center text-xs text-slate-500">Cards you mark still learning come back sooner and rise to the top next session.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        #flashcard.is-flipped #flashcard-inner {
            transform: rotateY(180deg);
        }
        #flashcard.is-flipped {
            cursor: default;
        }
        #study-rating.is-ready {
            opacity: 1;
            pointer-events: auto;
        }
    </style>

    <script>
        (function () {
            var card = document.getElementById('flashcard');
            var inner = document.getElementById('flashcard-inner');
            var rating = document.getElementById('study-rating');
            if (!card || !inner || !rating) return;

            function flip() {
                if (card.classList.contains('is-flipped')) return;
                card.classList.add('is-flipped');
                rating.classList.add('is-ready');
            }

            card.addEventListener('click', flip);
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    flip();
                }
            });

            card.querySelectorAll('.study-rating-form').forEach(function (form) {
                form.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });
        })();
    </script>
@endsection
