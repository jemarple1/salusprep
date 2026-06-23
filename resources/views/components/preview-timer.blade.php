@props([
    'timer' => null,
])

@php
    $timer = $timer ?? ($previewTimer ?? null);
@endphp

@if ($timer)
    @php
        $radius = 13;
        $circumference = round(2 * M_PI * $radius, 3);
        $progress = $timer['isUnlocked']
            ? 100
            : ($timer['totalSeconds'] > 0
                ? min(100, max(0, ($timer['remainingSeconds'] / $timer['totalSeconds']) * 100))
                : 0);
        $dashOffset = round($circumference * (1 - ($progress / 100)), 3);
        $ringColor = $timer['isUnlocked'] ? '#4ade80' : '#3399cc';
        $trackColor = $timer['isUnlocked'] ? 'rgba(74, 222, 128, 0.2)' : 'rgba(51, 153, 204, 0.2)';
    @endphp

    <a
        href="{{ $timer['href'] }}"
        id="preview-timer"
        aria-label="{{ $timer['ariaLabel'] }}"
        class="preview-timer relative inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-white/10 bg-navy/60 transition hover:border-ems/40 hover:bg-ems/10"
        data-expires-at="{{ $timer['expiresAt'] }}"
        data-total-seconds="{{ $timer['totalSeconds'] }}"
        data-is-unlocked="{{ $timer['isUnlocked'] ? '1' : '0' }}"
    >
        <svg class="absolute inset-0 h-full w-full rotate-90" viewBox="0 0 32 32" aria-hidden="true">
            <circle
                cx="16"
                cy="16"
                r="{{ $radius }}"
                fill="none"
                stroke="{{ $trackColor }}"
                stroke-width="2.5"
            />
            <circle
                id="preview-timer-progress"
                cx="16"
                cy="16"
                r="{{ $radius }}"
                fill="none"
                stroke="{{ $ringColor }}"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-dasharray="{{ $circumference }}"
                stroke-dashoffset="{{ $dashOffset }}"
                style="transition: stroke-dashoffset 0.35s linear;"
            />
        </svg>
        <span id="preview-timer-symbol" class="relative text-sm leading-none {{ $timer['isUnlocked'] ? 'text-medic-light' : 'text-ems-light' }}">
            {{ $timer['symbol'] }}
        </span>
    </a>

    <script>
        (function () {
            var timer = document.getElementById('preview-timer');
            var progress = document.getElementById('preview-timer-progress');
            if (!timer || !progress) return;

            if (timer.dataset.isUnlocked === '1') {
                return;
            }

            var expiresAt = timer.dataset.expiresAt;
            var totalSeconds = parseInt(timer.dataset.totalSeconds || '0', 10);
            var radius = 13;
            var circumference = 2 * Math.PI * radius;

            function updateTimer() {
                if (!expiresAt || totalSeconds <= 0) {
                    return;
                }

                var remainingMs = new Date(expiresAt).getTime() - Date.now();
                var remainingSeconds = Math.max(0, Math.ceil(remainingMs / 1000));
                var progressPercent = Math.min(100, Math.max(0, (remainingSeconds / totalSeconds) * 100));
                var dashOffset = circumference * (1 - (progressPercent / 100));

                progress.setAttribute('stroke-dashoffset', String(dashOffset));

                var minutes = Math.ceil(remainingSeconds / 60);
                timer.setAttribute(
                    'aria-label',
                    remainingSeconds > 0
                        ? 'Preview ends in ' + minutes + ' minute' + (minutes === 1 ? '' : 's')
                        : 'Preview ended — view unlock options'
                );
            }

            updateTimer();
            window.setInterval(updateTimer, 1000);
        })();
    </script>
@endif
