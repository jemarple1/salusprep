@props([
    'title',
    'points' => [],
    'totalSignups' => 0,
])

@php
    $width = 720;
    $height = 360;
    $pad = 16;

    $project = function (float $lat, float $lon) use ($width, $height, $pad): array {
        $x = $pad + (($lon + 180) / 360) * ($width - $pad * 2);
        $y = $pad + ((90 - $lat) / 180) * ($height - $pad * 2);

        return ['x' => round($x, 1), 'y' => round($y, 1)];
    };

    $mapped = [];
    foreach ($points as $point) {
        $jitterLat = (crc32($point['id'].'lat') % 200 - 100) / 2500;
        $jitterLon = (crc32($point['id'].'lon') % 200 - 100) / 2500;
        $mapped[] = array_merge($point, $project($point['lat'] + $jitterLat, $point['lon'] + $jitterLon));
    }

    $locatedCount = count($mapped);
@endphp

<div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">{{ $title }}</h3>
        <p class="text-xs text-slate-500">
            {{ number_format($locatedCount) }} of {{ number_format($totalSignups) }} signups plotted
        </p>
    </div>

    @if ($locatedCount === 0)
        <div class="mt-4 flex h-64 items-center justify-center rounded-xl border border-dashed border-white/10 text-sm text-slate-500">
            Location data will appear for new signups once country detection is available.
        </div>
    @else
        <div class="mt-4 overflow-x-auto">
            <svg viewBox="0 0 {{ $width }} {{ $height }}" class="w-full min-w-[320px] rounded-xl bg-[#0b1220]" role="img" aria-label="{{ $title }}">
                <title>{{ $title }}</title>

                <rect x="{{ $pad }}" y="{{ $pad }}" width="{{ $width - $pad * 2 }}" height="{{ $height - $pad * 2 }}" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="1" rx="4" />

                @foreach ([-60, -30, 0, 30, 60] as $lat)
                    @php $coords = $project($lat, -180); $coords2 = $project($lat, 180); @endphp
                    <line x1="{{ $coords['x'] }}" y1="{{ $coords['y'] }}" x2="{{ $coords2['x'] }}" y2="{{ $coords2['y'] }}" stroke="rgba(255,255,255,0.06)" stroke-width="1" />
                @endforeach

                @foreach ([-120, -60, 0, 60, 120] as $lon)
                    @php $coords = $project(90, $lon); $coords2 = $project(-90, $lon); @endphp
                    <line x1="{{ $coords['x'] }}" y1="{{ $coords['y'] }}" x2="{{ $coords2['x'] }}" y2="{{ $coords2['y'] }}" stroke="rgba(255,255,255,0.06)" stroke-width="1" />
                @endforeach

                @php
                    $equator = $project(0, -180);
                    $equatorEnd = $project(0, 180);
                    $prime = $project(90, 0);
                    $primeEnd = $project(-90, 0);
                @endphp
                <line x1="{{ $equator['x'] }}" y1="{{ $equator['y'] }}" x2="{{ $equatorEnd['x'] }}" y2="{{ $equatorEnd['y'] }}" stroke="rgba(51,153,204,0.25)" stroke-width="1.5" stroke-dasharray="4 4" />
                <line x1="{{ $prime['x'] }}" y1="{{ $prime['y'] }}" x2="{{ $primeEnd['x'] }}" y2="{{ $primeEnd['y'] }}" stroke="rgba(51,153,204,0.25)" stroke-width="1.5" stroke-dasharray="4 4" />

                @foreach ($mapped as $dot)
                    <g>
                        <circle cx="{{ $dot['x'] }}" cy="{{ $dot['y'] }}" r="5" fill="#4ade80" fill-opacity="0.9" stroke="#0f172a" stroke-width="1.5" />
                        <title>{{ $dot['label'] }} · {{ $dot['country'] ?? 'Unknown' }}</title>
                    </g>
                @endforeach
            </svg>
        </div>
        <p class="mt-2 text-xs text-slate-500">Each dot is one signup. Hover for name and country. Slight jitter prevents overlapping dots in the same region.</p>
    @endif
</div>
