@props(['trend'])

@php
    $points = $trend['points'];
    $count = count($points);

    $width = 560;
    $height = 200;
    $padX = 36;
    $padY = 24;
    $chartW = $width - $padX * 2;
    $chartH = $height - $padY * 2;

    $coords = [];
    foreach ($points as $i => $point) {
        $x = $count === 1
            ? $padX + $chartW / 2
            : $padX + ($i / ($count - 1)) * $chartW;
        $y = $padY + (1 - $point['accuracy_percent'] / 100) * $chartH;
        $coords[] = ['x' => $x, 'y' => $y, 'point' => $point];
    }

    $linePath = collect($coords)->map(fn ($c, $i) => ($i === 0 ? 'M' : 'L').round($c['x'], 1).','.round($c['y'], 1))->join(' ');

    $areaPath = $linePath
        .' L'.round($coords[$count - 1]['x'], 1).','.($padY + $chartH)
        .' L'.round($coords[0]['x'], 1).','.($padY + $chartH)
        .' Z';

    $trendStyles = match ($trend['trend']) {
        'improving' => ['badge' => 'bg-medic/20 text-medic-light', 'stroke' => '#4ade80', 'fill' => 'rgba(74, 222, 128, 0.12)', 'icon' => '↑'],
        'declining' => ['badge' => 'bg-rescue/20 text-red-200', 'stroke' => '#f87171', 'fill' => 'rgba(248, 113, 113, 0.12)', 'icon' => '↓'],
        'stable' => ['badge' => 'bg-ems/20 text-ems-light', 'stroke' => '#3399cc', 'fill' => 'rgba(51, 153, 204, 0.12)', 'icon' => '→'],
        default => ['badge' => 'bg-white/10 text-slate-400', 'stroke' => '#94a3b8', 'fill' => 'rgba(148, 163, 184, 0.08)', 'icon' => '…'],
    };

    $trendLabel = match ($trend['trend']) {
        'improving' => 'Improving',
        'declining' => 'Needs focus',
        'stable' => 'Steady',
        default => 'Building data',
    };
@endphp

<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $trendStyles['badge'] }}">
                {{ $trendLabel }} {{ $trendStyles['icon'] }}
            </span>
            @if ($trend['trend'] !== 'insufficient' && $trend['trend_delta'] !== 0)
                <span class="text-sm text-slate-400">
                    {{ $trend['trend_delta'] > 0 ? '+' : '' }}{{ $trend['trend_delta'] }} pts vs earlier quizzes
                </span>
            @endif
        </div>
        <p class="text-sm text-slate-400">{{ $trend['trend_message'] }}</p>
    </div>

    @if ($count < 2)
        <div class="flex h-48 items-center justify-center rounded-xl border border-dashed border-white/10 bg-navy/40 text-sm text-slate-500">
            {{ $trend['trend_message'] }}
        </div>
    @else
        <div class="overflow-x-auto">
            <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-full min-w-[320px]" role="img" aria-label="Quiz accuracy trend chart">
                <title>Accuracy trend across {{ $count }} quizzes</title>

                @foreach ([0, 25, 50, 75, 100] as $pct)
                    @php
                        $y = $padY + (1 - $pct / 100) * $chartH;
                    @endphp
                    <line x1="{{ $padX }}" y1="{{ $y }}" x2="{{ $width - $padX }}" y2="{{ $y }}" class="theme-chart-grid" stroke="rgba(255,255,255,0.06)" stroke-width="1" />
                    <text x="{{ $padX - 8 }}" y="{{ $y + 4 }}" text-anchor="end" class="theme-chart-label" fill="#64748b" font-size="10">{{ $pct }}%</text>
                @endforeach

                <path d="{{ $areaPath }}" fill="{{ $trendStyles['fill'] }}" />
                <path d="{{ $linePath }}" fill="none" stroke="{{ $trendStyles['stroke'] }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />

                @foreach ($coords as $coord)
                    <g>
                        <circle cx="{{ $coord['x'] }}" cy="{{ $coord['y'] }}" r="5" class="theme-chart-dot" fill="#0f172a" stroke="{{ $trendStyles['stroke'] }}" stroke-width="2.5" />
                        <title>{{ $coord['point']['label'] }}: {{ $coord['point']['accuracy_percent'] }}% ({{ $coord['point']['date'] }})</title>
                    </g>
                @endforeach

                @foreach ($coords as $coord)
                    @if ($count <= 8 || $loop->first || $loop->last || $loop->iteration % 2 === 0)
                        <text x="{{ $coord['x'] }}" y="{{ $height - 4 }}" text-anchor="middle" class="theme-chart-label" fill="#64748b" font-size="10">
                            #{{ $coord['point']['quiz_number'] }}
                        </text>
                    @endif
                @endforeach
            </svg>
        </div>

        <div class="mt-3 flex flex-wrap gap-3">
            @foreach ($points as $point)
                <div class="rounded-lg border border-white/5 bg-navy/50 px-3 py-2 text-xs">
                    <span class="font-bold text-white">{{ $point['label'] }}</span>
                    <span class="mx-1 text-slate-600">·</span>
                    <span class="font-semibold text-medic-light">{{ $point['accuracy_percent'] }}%</span>
                    <span class="text-slate-500">({{ $point['questions_answered'] }} Q)</span>
                </div>
            @endforeach
        </div>
    @endif
</div>
