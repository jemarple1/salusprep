@props([
    'exercises',
])

@if ($exercises !== [])
    <div
        class="exercise-carousel cursor-grab overflow-x-auto overflow-y-hidden overscroll-x-contain py-2 [-ms-overflow-style:none] [scrollbar-width:none] [touch-action:pan-x] [-webkit-overflow-scrolling:touch] [&::-webkit-scrollbar]:hidden"
        id="exercise-carousel"
    >
        <div class="flex w-max gap-4 px-4 sm:px-6">
            @foreach ($exercises as $exercise)
                <x-exercise-card :exercise="$exercise" />
            @endforeach
        </div>
    </div>

    <script>
        (function () {
            var carousel = document.getElementById('exercise-carousel');
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
@endif
