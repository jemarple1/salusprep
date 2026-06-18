@props([
    'exercises',
])

@if ($exercises !== [])
    <div class="exercise-carousel cursor-grab overflow-x-auto overflow-y-hidden py-2 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" id="exercise-carousel">
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
            var moved = false;

            carousel.addEventListener('mousedown', function (e) {
                isDragging = true;
                moved = false;
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
                if (Math.abs(dx) > 3) moved = true;
                carousel.scrollLeft = scrollStart - dx;
            });

            carousel.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    if (moved) e.preventDefault();
                });
            });

            var touchStartX = 0;
            var touchScrollStart = 0;

            carousel.addEventListener('touchstart', function (e) {
                touchStartX = e.touches[0].pageX;
                touchScrollStart = carousel.scrollLeft;
            }, { passive: true });

            carousel.addEventListener('touchmove', function (e) {
                carousel.scrollLeft = touchScrollStart - (e.touches[0].pageX - touchStartX);
            }, { passive: true });
        })();
    </script>
@endif
