@props([
    'decks',
    'carouselId' => 'flashcard-deck-carousel',
])

@if ($decks->isNotEmpty())
    <div
        class="flashcard-deck-carousel cursor-grab overflow-x-auto overflow-y-hidden overscroll-x-contain py-2 [-ms-overflow-style:none] [scrollbar-width:none] [touch-action:pan-x] [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:hidden"
        id="{{ $carouselId }}"
    >
        <div class="flex w-max gap-4 sm:gap-5">
            @foreach ($decks as $deck)
                <div class="min-w-[16rem] max-w-[16rem] shrink-0 sm:min-w-[18rem] sm:max-w-[18rem]">
                    <x-flashcard-deck-card :deck="$deck" />
                </div>
            @endforeach
        </div>
    </div>

    @once
        <script>
            (function () {
                function initFlashcardDeckCarousel(carousel) {
                    if (!carousel || carousel.dataset.dragInit === '1') return;
                    carousel.dataset.dragInit = '1';

                    var isDragging = false;
                    var startX = 0;
                    var scrollStart = 0;
                    var pointerMoved = false;

                    carousel.addEventListener('mousedown', function (e) {
                        if (e.button !== 0) return;
                        isDragging = true;
                        pointerMoved = false;
                        startX = e.pageX;
                        scrollStart = carousel.scrollLeft;
                        carousel.classList.add('cursor-grabbing');
                        carousel.classList.remove('cursor-grab');
                    });

                    window.addEventListener('mousemove', function (e) {
                        if (!isDragging) return;
                        var delta = e.pageX - startX;
                        if (Math.abs(delta) > 3) pointerMoved = true;
                        carousel.scrollLeft = scrollStart - delta;
                    });

                    window.addEventListener('mouseup', function () {
                        if (!isDragging) return;
                        isDragging = false;
                        carousel.classList.remove('cursor-grabbing');
                        carousel.classList.add('cursor-grab');
                    });

                    carousel.addEventListener('click', function (e) {
                        if (pointerMoved) {
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    }, true);
                }

                function initAll() {
                    document.querySelectorAll('.flashcard-deck-carousel').forEach(initFlashcardDeckCarousel);
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initAll);
                } else {
                    initAll();
                }
            })();
        </script>
    @endonce
@endif
