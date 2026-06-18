@props([
    'title',
    'points',
    'valueLabel' => 'Count',
    'stroke' => '#4ade80',
    'fill' => 'rgba(74, 222, 128, 0.12)',
])

@php
    $count = count($points);
    $maxValue = max(1, collect($points)->max('value') ?? 1);

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
            : $padX + ($i / max($count - 1, 1)) * $chartW;
        $y = $padY + (1 - ($point['value'] / $maxValue)) * $chartH;
        $coords[] = ['x' => $x, 'y' => $y, 'point' => $point];
    }

    $linePath = $count > 0
        ? collect($coords)->map(fn ($c, $i) => ($i === 0 ? 'M' : 'L').round($c['x'], 1).','.round($c['y'], 1))->join(' ')
        : '';

    $areaPath = $count > 0
        ? $linePath
            .' L'.round($coords[$count - 1]['x'], 1).','.($padY + $chartH)
            .' L'.round($coords[0]['x'], 1).','.($padY + $chartH)
            .' Z'
        : '';
@endphp

<div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">{{ $title }}</h3>

    @if ($count === 0)
        <div class="mt-4 flex h-48 items-center justify-center text-sm text-slate-500">No data yet.</div>
    @else
        <div class="mt-4 overflow-x-auto">
            <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-full min-w-[320px]" role="img" aria-label="{{ $title }}">
                <title>{{ $title }}</title>

                @foreach ([0, 0.25, 0.5, 0.75, 1] as $ratio)
                    @php $y = $padY + (1 - $ratio) * $chartH; @endphp
                    <line x1="{{ $padX }}" y1="{{ $y }}" x2="{{ $width - $padX }}" y2="{{ $y }}" class="theme-chart-grid" stroke="rgba(255,255,255,0.06)" stroke-width="1" />
                    <text x="{{ $padX - 8 }}" y="{{ $y + 4 }}" text-anchor="end" class="theme-chart-label" fill="#64748b" font-size="10">{{ (int) round($maxValue * $ratio) }}</text>
                @endforeach

                @if ($linePath !== '')
                    <path d="{{ $areaPath }}" fill="{{ $fill }}" />
                    <path d="{{ $linePath }}" fill="none" stroke="{{ $stroke }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                @endif

                @foreach ($coords as $coord)
                    <circle cx="{{ $coord['x'] }}" cy="{{ $coord['y'] }}" r="4" class="theme-chart-dot" fill="#0f172a" stroke="{{ $stroke }}" stroke-width="2" />
                    <title>{{ $coord['point']['label'] }}: {{ $coord['point']['value'] }} {{ $valueLabel }}</title>
                @endforeach

                @foreach ($coords as $coord)
                    @if ($count <= 10 || $loop->first || $loop->last || $loop->iteration % 3 === 0)
                        <text x="{{ $coord['x'] }}" y="{{ $height - 4 }}" text-anchor="middle" class="theme-chart-label" fill="#64748b" font-size="10">{{ $coord['point']['label'] }}</text>
                    @endif
                @endforeach
            </svg>
        </div>
    @endif
</div>
