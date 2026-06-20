@props([
    'answers' => [],
    'platformCorrectPercents' => [],
])

@php
    $cards = collect($answers)->values()->map(function ($answer, $index) use ($platformCorrectPercents) {
        $question = $answer->question;

        return [
            'number' => $index + 1,
            'category' => $question->category,
            'stem' => $question->stem,
            'options' => $question->options(),
            'selected_option' => $answer->selected_option,
            'correct_option' => $question->correct_option,
            'correct_text' => $question->optionFor($question->correct_option),
            'explanation' => $question->explanation,
            'is_correct' => $answer->is_correct,
            'platform_percent' => $platformCorrectPercents[$question->id] ?? null,
        ];
    })->all();

    $total = count($cards);
@endphp

@if ($total > 0)
    <div
        id="results-flashcard-deck"
        class="rounded-2xl border border-white/10 bg-navy-light/80"
        data-cards='@json($cards)'
    >
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-6 py-4">
            <div>
                <h2 class="text-lg font-bold text-white">Review deck</h2>
                <p class="mt-1 text-sm text-slate-400">Flip through each question before reading the full answer list below.</p>
            </div>
            <p class="text-sm font-semibold text-slate-400">
                Card <span id="results-deck-current">1</span> of {{ $total }}
            </p>
        </div>

        <div class="p-6 sm:p-8">
            <div class="mx-auto max-w-2xl">
                <div
                    id="results-flashcard"
                    class="group cursor-pointer perspective-[1200px]"
                    role="button"
                    tabindex="0"
                    aria-label="Flip review card"
                >
                    <div id="results-flashcard-inner" class="grid transition-transform duration-500 [transform-style:preserve-3d]">
                        <div id="results-flashcard-front" class="col-start-1 row-start-1 rounded-2xl border border-white/10 bg-navy/60 p-6 shadow-xl [backface-visibility:hidden] sm:p-8"></div>
                        <div id="results-flashcard-back" class="col-start-1 row-start-1 rounded-2xl border border-medic/30 bg-gradient-to-br from-medic/10 to-navy-light/95 p-6 shadow-xl [backface-visibility:hidden] [transform:rotateY(180deg)] sm:p-8"></div>
                    </div>
                </div>

                <p class="mt-4 text-center text-sm text-slate-500">Tap the card to flip · use arrows to move through the deck</p>

                <div class="mt-6 flex items-center justify-center gap-3">
                    <button
                        type="button"
                        id="results-deck-prev"
                        class="rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/5 disabled:cursor-not-allowed disabled:opacity-40"
                        disabled
                    >
                        ← Previous
                    </button>
                    <button
                        type="button"
                        id="results-deck-next"
                        class="rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-white/5 disabled:cursor-not-allowed disabled:opacity-40"
                    >
                        Next →
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #results-flashcard.is-flipped #results-flashcard-inner {
            transform: rotateY(180deg);
        }
        #results-flashcard.is-flipped {
            cursor: default;
        }
    </style>

    <script>
        (function () {
            var deck = document.getElementById('results-flashcard-deck');
            if (!deck) return;

            var cards = JSON.parse(deck.dataset.cards || '[]');
            if (!cards.length) return;

            var index = 0;
            var card = document.getElementById('results-flashcard');
            var inner = document.getElementById('results-flashcard-inner');
            var front = document.getElementById('results-flashcard-front');
            var back = document.getElementById('results-flashcard-back');
            var current = document.getElementById('results-deck-current');
            var prev = document.getElementById('results-deck-prev');
            var next = document.getElementById('results-deck-next');

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            function statusBadge(isCorrect) {
                return isCorrect
                    ? '<span class="rounded-full bg-medic/15 px-2 py-0.5 text-xs font-bold text-medic-light">Correct</span>'
                    : '<span class="rounded-full bg-rescue/15 px-2 py-0.5 text-xs font-bold text-red-200">Incorrect</span>';
            }

            function renderCard() {
                var data = cards[index];
                var optionsHtml = Object.keys(data.options).map(function (letter) {
                    var classes = 'rounded-lg border px-4 py-3 text-sm ';
                    if (letter === data.selected_option && !data.is_correct) {
                        classes += 'border-safety/40 bg-safety/10 text-slate-200';
                    } else if (letter === data.correct_option) {
                        classes += 'border-medic/30 bg-medic/10 text-slate-200';
                    } else {
                        classes += 'border-white/5 bg-navy/40 text-slate-300';
                    }

                    return '<div class="' + classes + '"><span class="font-bold text-medic-light">' + escapeHtml(letter) + '.</span> ' + escapeHtml(data.options[letter]) + '</div>';
                }).join('');

                var platformHtml = data.platform_percent !== null
                    ? '<span class="text-xs text-slate-500">' + data.platform_percent + '% of learners got this right</span>'
                    : '';

                front.innerHTML =
                    '<div class="mb-4 flex flex-wrap items-center gap-2">' +
                        '<span class="text-sm font-bold text-slate-500">Q' + data.number + '</span>' +
                        '<span class="rounded-full bg-ems/20 px-2 py-0.5 text-xs text-ems-light">' + escapeHtml(data.category) + '</span>' +
                        statusBadge(data.is_correct) +
                        platformHtml +
                    '</div>' +
                    '<p class="text-xl leading-relaxed text-white sm:text-2xl">' + escapeHtml(data.stem) + '</p>' +
                    '<div class="mt-6 space-y-2">' + optionsHtml + '</div>' +
                    '<p class="mt-8 text-center text-sm font-semibold text-medic-light">Tap to reveal answer</p>';

                back.innerHTML =
                    '<div class="mb-4 flex flex-wrap items-center gap-2">' +
                        '<span class="rounded-full bg-medic/20 px-3 py-1 text-xs font-bold uppercase text-medic-light">Answer</span>' +
                        (!data.is_correct ? '<span class="text-xs text-red-200">You chose ' + escapeHtml(data.selected_option) + '</span>' : '') +
                    '</div>' +
                    '<p class="text-lg font-bold text-white">' +
                        'Correct: <span class="text-medic-light">' + escapeHtml(data.correct_option) + '.</span> ' + escapeHtml(data.correct_text) +
                    '</p>' +
                    (data.explanation
                        ? '<div class="mt-6 rounded-xl border border-white/10 bg-navy/50 p-4">' +
                            '<p class="text-xs font-bold uppercase tracking-wider text-slate-500">Explanation</p>' +
                            '<p class="mt-2 text-sm leading-relaxed text-slate-200">' + escapeHtml(data.explanation) + '</p>' +
                          '</div>'
                        : '') +
                    '<p class="mt-8 text-center text-sm text-slate-500">Tap previous or next to continue</p>';

                card.classList.remove('is-flipped');
                current.textContent = String(index + 1);
                prev.disabled = index === 0;
                next.disabled = index === cards.length - 1;
            }

            function flip() {
                if (card.classList.contains('is-flipped')) return;
                card.classList.add('is-flipped');
            }

            card.addEventListener('click', flip);
            card.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    flip();
                }
            });

            prev.addEventListener('click', function () {
                if (index > 0) {
                    index -= 1;
                    renderCard();
                }
            });

            next.addEventListener('click', function () {
                if (index < cards.length - 1) {
                    index += 1;
                    renderCard();
                }
            });

            renderCard();
        })();
    </script>
@endif
