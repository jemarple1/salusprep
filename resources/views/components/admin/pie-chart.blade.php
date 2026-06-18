@props([
    'title',
    'slices' => [],
])

@php
    $total = collect($slices)->sum('value');
    $cx = 100;
    $cy = 100;
    $radius = 78;
    $innerRadius = 46;

    $paths = [];
    $legend = [];
    $startAngle = -90;

    if ($total > 0) {
        foreach ($slices as $slice) {
            if ($slice['value'] <= 0) {
                continue;
            }

            $angle = ($slice['value'] / $total) * 360;
            $endAngle = $startAngle + $angle;

            $startRad = deg2rad($startAngle);
            $endRad = deg2rad($endAngle);

            $x1 = $cx + $radius * cos($startRad);
            $y1 = $cy + $radius * sin($startRad);
            $x2 = $cx + $radius * cos($endRad);
            $y2 = $cy + $radius * sin($endRad);

            $x3 = $cx + $innerRadius * cos($endRad);
            $y3 = $cy + $innerRadius * sin($endRad);
            $x4 = $cx + $innerRadius * cos($startRad);
            $y4 = $cy + $innerRadius * sin($startRad);

            $largeArc = $angle > 180 ? 1 : 0;

            $paths[] = [
                'd' => 'M '.round($x1, 2).','.round($y1, 2)
                    .' A '.$radius.','.$radius.' 0 '.$largeArc.',1 '.round($x2, 2).','.round($y2, 2)
                    .' L '.round($x3, 2).','.round($y3, 2)
                    .' A '.$innerRadius.','.$innerRadius.' 0 '.$largeArc.',0 '.round($x4, 2).','.round($y4, 2)
                    .' Z',
                'color' => $slice['color'],
            ];

            $legend[] = [
                'label' => $slice['label'],
                'value' => $slice['value'],
                'percent' => round(($slice['value'] / $total) * 100),
                'color' => $slice['color'],
            ];

            $startAngle = $endAngle;
        }
    }
@endphp

<div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">{{ $title }}</h3>

    @if ($total === 0)
        <div class="mt-4 flex h-48 items-center justify-center text-sm text-slate-500">No data yet.</div>
    @else
        <div class="mt-4 flex flex-col items-center gap-6 sm:flex-row sm:items-start sm:justify-between">
            <svg viewBox="0 0 200 200" class="h-44 w-44 shrink-0" role="img" aria-label="{{ $title }}">
                <title>{{ $title }}</title>
                @foreach ($paths as $path)
                    <path d="{{ $path['d'] }}" fill="{{ $path['color'] }}" stroke="#0f172a" stroke-width="1.5" />
                @endforeach
                <text x="{{ $cx }}" y="{{ $cy - 4 }}" text-anchor="middle" fill="#ffffff" font-size="18" font-weight="700">{{ number_format($total) }}</text>
                <text x="{{ $cx }}" y="{{ $cy + 14 }}" text-anchor="middle" fill="#94a3b8" font-size="10">total</text>
            </svg>

            <ul class="w-full space-y-2 sm:max-w-[14rem]">
                @foreach ($legend as $item)
                    <li class="flex items-center justify-between gap-3 text-sm">
                        <span class="flex min-w-0 items-center gap-2 text-slate-300">
                            <span class="inline-block h-2.5 w-2.5 shrink-0 rounded-full" style="background-color: {{ $item['color'] }}"></span>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </span>
                        <span class="shrink-0 font-semibold text-white">{{ number_format($item['value']) }} <span class="text-slate-500">({{ $item['percent'] }}%)</span></span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
