@extends('layouts.app')

@section('title', $sectionLabel.' Flashcards')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <a href="{{ route('study.index', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Study hub</a>
            <p class="mt-2 text-sm font-bold uppercase tracking-wider text-medic-light">
                Card {{ $cardNumber }} · {{ $studySession->remainingCount() }} remaining
                @if ($studySession->filter_category)
                    · {{ $studySession->filter_category }}
                @endif
            </p>
        </div>
        <span class="rounded-full border border-ems/30 bg-ems/10 px-3 py-1 text-sm font-semibold text-ems-light">{{ $studySession->masteredCount() }}/{{ $studySession->initial_deck_size }} cleared</span>
    </div>

    <div class="mb-6 h-2 overflow-hidden rounded-full bg-navy-light ring-1 ring-white/10">
        <div class="h-full rounded-full bg-medic transition-all" style="width: {{ $studySession->progressPercent() }}%"></div>
    </div>

    <div id="flashcard" class="group mx-auto max-w-2xl cursor-pointer perspective-[1200px]" role="button" tabindex="0" aria-label="Flip card">
        <div id="flashcard-inner" class="relative min-h-[22rem] transition-transform duration-500 [transform-style:preserve-3d] sm:min-h-[24rem]">
            {{-- Front --}}
            <div class="absolute inset-0 rounded-2xl border border-white/10 bg-navy-light/90 p-6 shadow-xl [backface-visibility:hidden] sm:p-8">
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
            <div class="absolute inset-0 rounded-2xl border border-medic/30 bg-gradient-to-br from-medic/10 to-navy-light/95 p-6 shadow-xl [backface-visibility:hidden] [transform:rotateY(180deg)] sm:p-8">
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

                <p class="mt-8 text-center text-sm text-slate-400">How well do you know this now?</p>
            </div>
        </div>
    </div>

    <div id="study-actions" class="mx-auto mt-8 flex max-w-2xl flex-wrap justify-center gap-3 opacity-40 pointer-events-none transition-opacity">
        <form method="POST" action="{{ route('study.advance', [$sectionSlug, $studySession]) }}">
            @csrf
            <input type="hidden" name="action" value="review">
            <button type="submit" class="rounded-xl border border-safety/40 bg-safety/10 px-6 py-3 font-bold text-safety-light hover:bg-safety/20">
                Still learning
            </button>
        </form>
        <form method="POST" action="{{ route('study.advance', [$sectionSlug, $studySession]) }}">
            @csrf
            <input type="hidden" name="action" value="mastered">
            <button type="submit" class="rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">
                Got it ✓
            </button>
        </form>
    </div>

    <style>
        #flashcard.is-flipped #flashcard-inner {
            transform: rotateY(180deg);
        }
        #study-actions.is-ready {
            opacity: 1;
            pointer-events: auto;
        }
    </style>

    <script>
        (function () {
            var card = document.getElementById('flashcard');
            var inner = document.getElementById('flashcard-inner');
            var actions = document.getElementById('study-actions');
            if (!card || !inner || !actions) return;

            function flip() {
                card.classList.add('is-flipped');
                actions.classList.add('is-ready');
            }

            card.addEventListener('click', flip);
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    flip();
                }
            });
        })();
    </script>
@endsection
