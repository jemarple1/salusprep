@props([
    'options' => [],
    'defaultSlug' => 'emt-basic',
])

@php
    $defaultSlug = old('unlock_section', $defaultSlug);
    $defaultIndex = collect($options)->search(fn (array $option) => $option['slug'] === $defaultSlug);
    $defaultIndex = $defaultIndex === false ? 0 : $defaultIndex;

    $accentStyles = [
        'ems' => [
            'ring' => 'ring-ems/50',
            'border' => 'border-ems/40',
            'bg' => 'bg-ems/10',
            'text' => 'text-ems-light',
            'dot' => 'bg-ems-light',
            'glow' => 'shadow-ems/20',
        ],
        'safety' => [
            'ring' => 'ring-safety/50',
            'border' => 'border-safety/40',
            'bg' => 'bg-safety/10',
            'text' => 'text-safety-light',
            'dot' => 'bg-safety-light',
            'glow' => 'shadow-safety/20',
        ],
        'medic' => [
            'ring' => 'ring-medic/50',
            'border' => 'border-medic/40',
            'bg' => 'bg-medic/10',
            'text' => 'text-medic-light',
            'dot' => 'bg-medic-light',
            'glow' => 'shadow-medic/20',
        ],
        'pharma' => [
            'ring' => 'ring-pharma/50',
            'border' => 'border-pharma/40',
            'bg' => 'bg-pharma/10',
            'text' => 'text-pharma-light',
            'dot' => 'bg-pharma-light',
            'glow' => 'shadow-pharma/20',
        ],
    ];
@endphp

<div class="register-platform-picker" id="register-platform-picker" data-default-index="{{ $defaultIndex }}">
    <div class="mb-3 flex items-end justify-between gap-3">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-safety-light">Full Access</p>
            <p class="mt-1 text-sm text-slate-400">Swipe to choose your platform</p>
        </div>
        <div class="flex items-center gap-1.5">
            <button
                type="button"
                class="register-platform-nav flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-navy/80 text-slate-300 transition hover:border-white/20 hover:text-white"
                data-direction="prev"
                aria-label="Previous platform"
            >
                ‹
            </button>
            <button
                type="button"
                class="register-platform-nav flex h-9 w-9 items-center justify-center rounded-full border border-white/10 bg-navy/80 text-slate-300 transition hover:border-white/20 hover:text-white"
                data-direction="next"
                aria-label="Next platform"
            >
                ›
            </button>
        </div>
    </div>

    <div
        class="register-platform-track -mx-2 flex snap-x snap-mandatory gap-3 overflow-x-auto px-2 py-3 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden"
        tabindex="0"
        role="listbox"
        aria-label="Choose platform to unlock"
    >
        @foreach ($options as $index => $option)
            @php
                $accent = $accentStyles[$option['accent']] ?? $accentStyles['medic'];
                $isDefault = $index === $defaultIndex;
            @endphp
            <button
                type="button"
                role="option"
                aria-selected="{{ $isDefault ? 'true' : 'false' }}"
                class="register-platform-card group w-[78%] shrink-0 snap-center text-left transition duration-300 ease-out sm:w-[72%] {{ $isDefault ? 'is-active scale-100 opacity-100' : 'scale-[0.92] opacity-55' }}"
                data-slug="{{ $option['slug'] }}"
                data-index="{{ $index }}"
                data-accent="{{ $option['accent'] }}"
            >
                <div class="register-platform-card-inner relative overflow-hidden rounded-2xl border p-5 shadow-lg transition duration-300 {{ $isDefault ? implode(' ', [$accent['border'], $accent['bg'], $accent['ring'], $accent['glow'], 'ring-2']) : 'border-white/10 bg-gradient-to-br from-navy-light/90 to-navy/90' }}">
                    <div class="pointer-events-none absolute -right-6 -top-8 h-24 w-24 rounded-full bg-white/5 blur-2xl"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Platform</p>
                    <p class="register-platform-label mt-1 text-2xl font-bold text-white">{{ $option['label'] }}</p>
                    <p class="register-platform-exam mt-2 text-sm font-semibold {{ $accent['text'] }}">{{ $option['examMark'] }}</p>
                    <p class="register-platform-detail mt-3 text-sm leading-relaxed text-slate-400">
                        Unlimited quizzes, flashcards, skills, Test Center, and daily mock exams.
                    </p>
                </div>
            </button>
        @endforeach
    </div>

    <div class="mt-2 flex justify-center gap-2">
        @foreach ($options as $index => $option)
            @php
                $accent = $accentStyles[$option['accent']] ?? $accentStyles['medic'];
            @endphp
            <button
                type="button"
                class="register-platform-dot h-2.5 w-2.5 rounded-full transition {{ $index === $defaultIndex ? $accent['dot'].' scale-110' : 'bg-white/20 hover:bg-white/35' }}"
                data-index="{{ $index }}"
                aria-label="Select {{ $option['label'] }}"
            ></button>
        @endforeach
    </div>

    <input
        type="hidden"
        name="unlock_section"
        id="unlock_section"
        value="{{ old('unlock_section', $defaultSlug) }}"
        required
    >

    @error('unlock_section')
        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>

<script>
    (function () {
        var root = document.getElementById('register-platform-picker');
        if (!root) return;

        var track = root.querySelector('.register-platform-track');
        var cards = Array.from(root.querySelectorAll('.register-platform-card'));
        var dots = Array.from(root.querySelectorAll('.register-platform-dot'));
        var input = root.querySelector('#unlock_section');
        var navButtons = Array.from(root.querySelectorAll('.register-platform-nav'));

        if (!track || cards.length === 0 || !input) return;

        var accentClasses = {
            ems: {
                card: ['ring-2', 'ring-ems/50', 'border-ems/40', 'bg-ems/10', 'shadow-ems/20'],
                exam: 'text-ems-light',
                dot: 'bg-ems-light',
            },
            safety: {
                card: ['ring-2', 'ring-safety/50', 'border-safety/40', 'bg-safety/10', 'shadow-safety/20'],
                exam: 'text-safety-light',
                dot: 'bg-safety-light',
            },
            medic: {
                card: ['ring-2', 'ring-medic/50', 'border-medic/40', 'bg-medic/10', 'shadow-medic/20'],
                exam: 'text-medic-light',
                dot: 'bg-medic-light',
            },
            pharma: {
                card: ['ring-2', 'ring-pharma/50', 'border-pharma/40', 'bg-pharma/10', 'shadow-pharma/20'],
                exam: 'text-pharma-light',
                dot: 'bg-pharma-light',
            },
        };

        var inactiveCardClasses = ['border-white/10', 'bg-gradient-to-br', 'from-navy-light/90', 'to-navy/90'];
        var allAccentCardClasses = Object.values(accentClasses).flatMap(function (item) {
            return item.card;
        });
        var allExamClasses = ['text-ems-light', 'text-safety-light', 'text-medic-light', 'text-pharma-light'];
        var allDotClasses = ['bg-ems-light', 'bg-safety-light', 'bg-medic-light', 'bg-pharma-light', 'scale-110'];

        function cardInner(card) {
            return card.querySelector('.register-platform-card-inner');
        }

        function setActive(index, scrollIntoView) {
            if (index < 0) index = cards.length - 1;
            if (index >= cards.length) index = 0;

            cards.forEach(function (card, cardIndex) {
                var isActive = cardIndex === index;
                var inner = cardInner(card);
                var accent = accentClasses[card.dataset.accent] || accentClasses.medic;

                card.classList.toggle('is-active', isActive);
                card.classList.toggle('scale-100', isActive);
                card.classList.toggle('opacity-100', isActive);
                card.classList.toggle('scale-[0.92]', !isActive);
                card.classList.toggle('opacity-55', !isActive);
                card.setAttribute('aria-selected', isActive ? 'true' : 'false');

                if (!inner) return;

                inner.classList.remove.apply(inner.classList, inactiveCardClasses);
                inner.classList.remove.apply(inner.classList, allAccentCardClasses);

                if (isActive) {
                    inner.classList.add.apply(inner.classList, accent.card);
                } else {
                    inner.classList.add.apply(inner.classList, inactiveCardClasses);
                }

                var exam = card.querySelector('.register-platform-exam');
                if (exam) {
                    exam.classList.remove.apply(exam.classList, allExamClasses);
                    if (isActive) {
                        exam.classList.add(accent.exam);
                    }
                }
            });

            dots.forEach(function (dot, dotIndex) {
                var isActive = dotIndex === index;
                dot.classList.remove.apply(dot.classList, allDotClasses);
                dot.classList.toggle('bg-white/20', !isActive);
                dot.classList.toggle('hover:bg-white/35', !isActive);

                if (isActive) {
                    var accent = accentClasses[cards[index].dataset.accent] || accentClasses.medic;
                    dot.classList.add(accent.dot, 'scale-110');
                }
            });

            input.value = cards[index].dataset.slug;

            var previewSection = document.getElementById('preview_section');
            if (previewSection) {
                previewSection.value = cards[index].dataset.slug;
            }

            if (scrollIntoView) {
                cards[index].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        }

        function nearestIndex() {
            var trackRect = track.getBoundingClientRect();
            var trackCenter = trackRect.left + trackRect.width / 2;
            var closestIndex = 0;
            var closestDistance = Infinity;

            cards.forEach(function (card, index) {
                var rect = card.getBoundingClientRect();
                var cardCenter = rect.left + rect.width / 2;
                var distance = Math.abs(cardCenter - trackCenter);

                if (distance < closestDistance) {
                    closestDistance = distance;
                    closestIndex = index;
                }
            });

            return closestIndex;
        }

        cards.forEach(function (card, index) {
            card.addEventListener('click', function () {
                setActive(index, true);
            });
        });

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                setActive(parseInt(dot.dataset.index || '0', 10), true);
            });
        });

        navButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var current = cards.findIndex(function (card) {
                    return card.classList.contains('is-active');
                });
                if (current < 0) current = nearestIndex();

                var next = button.dataset.direction === 'next' ? current + 1 : current - 1;
                setActive(next, true);
            });
        });

        var scrollTimeout;
        track.addEventListener('scroll', function () {
            window.clearTimeout(scrollTimeout);
            scrollTimeout = window.setTimeout(function () {
                setActive(nearestIndex(), false);
            }, 80);
        }, { passive: true });

        setActive(parseInt(root.dataset.defaultIndex || '0', 10), false);
        window.requestAnimationFrame(function () {
            setActive(parseInt(root.dataset.defaultIndex || '0', 10), true);
        });
    })();
</script>
