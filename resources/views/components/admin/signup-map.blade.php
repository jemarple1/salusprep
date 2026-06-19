@props([
    'title',
    'points' => [],
    'totalSignups' => 0,
])

@php
    $mapId = 'signup-map-'.substr(md5($title), 0, 8);
    $locatedCount = count($points);
@endphp

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <style>
        #{{ $mapId }} { background: #0b1220; }
        #{{ $mapId }} .leaflet-tile-pane { filter: brightness(0.75) contrast(1.1) saturate(0.85); }
        #{{ $mapId }} .leaflet-control-zoom a {
            background: #1e293b;
            color: #e2e8f0;
            border-color: rgba(255, 255, 255, 0.1);
        }
        #{{ $mapId }} .leaflet-control-attribution {
            background: rgba(15, 23, 42, 0.85);
            color: #94a3b8;
            font-size: 10px;
        }
        #{{ $mapId }} .leaflet-control-attribution a { color: #94a3b8; }
    </style>
@endpush

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
        <div id="{{ $mapId }}" class="mt-4 h-80 w-full min-w-[320px] overflow-hidden rounded-xl border border-white/10" role="img" aria-label="{{ $title }}"></div>
        <p class="mt-2 text-xs text-slate-500">Each marker is one signup. Click for name and country. Country-only signups use approximate country centroids.</p>
    @endif
</div>

@if ($locatedCount > 0)
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            (function () {
                const points = @json($points);
                const map = L.map(@json($mapId), {
                    scrollWheelZoom: false,
                    worldCopyJump: true,
                }).setView([20, 0], 2);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                }).addTo(map);

                const jitter = (id, axis) => ((id * (axis === 'lat' ? 17 : 31)) % 200 - 100) / 2500;

                points.forEach((point) => {
                    const lat = point.lat + jitter(point.id, 'lat');
                    const lon = point.lon + jitter(point.id, 'lon');
                    const country = point.country || 'Unknown';

                    L.circleMarker([lat, lon], {
                        radius: 6,
                        color: '#0f172a',
                        weight: 1.5,
                        fillColor: '#4ade80',
                        fillOpacity: 0.9,
                    })
                        .bindPopup(`<strong>${point.label}</strong><br>${country}`)
                        .addTo(map);
                });

                if (points.length === 1) {
                    map.setView([points[0].lat, points[0].lon], 4);
                }
            })();
        </script>
    @endpush
@endif
