@props([
    'exercises',
    'showcase' => false,
    'autoplay' => false,
    'carouselId' => 'exercise-carousel',
])

@if ($exercises !== [])
    @if ($autoplay)
        <div
            class="exercise-carousel-autoplay relative w-full overflow-hidden py-3"
            id="{{ $carouselId }}"
        >
            <div class="exercise-carousel-autoplay-track flex w-max gap-4 px-4 sm:gap-5 sm:px-6">
                @foreach ([$exercises, $exercises] as $loopExercises)
                    @foreach ($loopExercises as $exercise)
                        <x-exercise-card :exercise="$exercise" :showcase="$showcase" />
                    @endforeach
                @endforeach
            </div>
        </div>

        <style>
            @keyframes exercise-carousel-marquee {
                from { transform: translateX(0); }
                to { transform: translateX(-50%); }
            }

            #{{ $carouselId }} .exercise-carousel-autoplay-track {
                animation: exercise-carousel-marquee 55s linear infinite;
                will-change: transform;
            }

            #{{ $carouselId }}:hover .exercise-carousel-autoplay-track {
                animation-play-state: paused;
            }

            @media (prefers-reduced-motion: reduce) {
                #{{ $carouselId }} .exercise-carousel-autoplay-track {
                    animation: none;
                }
            }
        </style>
    @else
        <div
            class="exercise-carousel cursor-grab overflow-x-auto overflow-y-hidden overscroll-x-contain py-2 [-ms-overflow-style:none] [scrollbar-width:none] [touch-action:pan-x] [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:hidden"
            id="{{ $carouselId }}"
        >
            <div class="flex w-max gap-4 px-4 sm:px-6">
                @foreach ($exercises as $exercise)
                    <x-exercise-card :exercise="$exercise" :showcase="$showcase" />
                @endforeach
            </div>
        </div>

        @if (! $showcase)
            <script>
                (function () {
                    var carousel = document.getElementById(@json($carouselId));
                    if (!carousel) return;

                    var isDragging = false;
                    var startX = 0;
                    var scrollStart = 0;
                    var pointerMoved = false;

                    var touchStartX = 0;
                    var touchStartY = 0;
                    var touchMoved = false;

                    carousel.addEventListener('mousedown', function (e) {
                        if (e.button !== 0) return;
                        isDragging = true;
                        pointerMoved = false;
                        startX = e.pageX;
                        scrollStart = carousel.scrollLeft;
                        carousel.classList.add('cursor-grabbing');
                        carousel.classList.remove('cursor-grab');
                    });

                    window.addEventListener('mouseup', function () {
                        if (!isDragging) return;
                        isDragging = false;
                        carousel.classList.remove('cursor-grabbing');
                        carousel.classList.add('cursor-grab');
                    });

                    carousel.addEventListener('mousemove', function (e) {
                        if (!isDragging) return;
                        e.preventDefault();
                        var dx = e.pageX - startX;
                        if (Math.abs(dx) > 3) pointerMoved = true;
                        carousel.scrollLeft = scrollStart - dx;
                    });

                    carousel.addEventListener('touchstart', function (e) {
                        touchMoved = false;
                        touchStartX = e.touches[0].clientX;
                        touchStartY = e.touches[0].clientY;
                    }, { passive: true });

                    carousel.addEventListener('touchmove', function (e) {
                        var dx = e.touches[0].clientX - touchStartX;
                        var dy = e.touches[0].clientY - touchStartY;
                        if (Math.abs(dx) > 6 || Math.abs(dy) > 6) {
                            touchMoved = true;
                        }
                    }, { passive: true });

                    carousel.querySelectorAll('a').forEach(function (link) {
                        link.addEventListener('click', function (e) {
                            if (pointerMoved || touchMoved) {
                                e.preventDefault();
                            }
                        });
                    });

                    carousel.addEventListener('touchend', function () {
                        if (!touchMoved) return;
                        window.setTimeout(function () {
                            touchMoved = false;
                        }, 400);
                    }, { passive: true });
                })();
            </script>
        @else
            <script>
                (function () {
                    var carousel = document.getElementById(@json($carouselId));
                    if (!carousel) return;

                    var isDragging = false;
                    var startX = 0;
                    var scrollStart = 0;

                    carousel.addEventListener('mousedown', function (e) {
                        if (e.button !== 0) return;
                        isDragging = true;
                        startX = e.pageX;
                        scrollStart = carousel.scrollLeft;
                        carousel.classList.add('cursor-grabbing');
                    });

                    window.addEventListener('mouseup', function () {
                        isDragging = false;
                        carousel.classList.remove('cursor-grabbing');
                    });

                    carousel.addEventListener('mousemove', function (e) {
                        if (!isDragging) return;
                        e.preventDefault();
                        carousel.scrollLeft = scrollStart - (e.pageX - startX);
                    });
                })();
            </script>
        @endif
    @endif
@endif
