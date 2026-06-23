@props([
    'column',
    'label',
])

@php
    $currentSort = request('guest_sort', 'last_seen');
    $currentDirection = strtolower((string) request('guest_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
    $isActive = $currentSort === $column;
    $nextDirection = $isActive && $currentDirection === 'desc' ? 'asc' : 'desc';
    $url = request()->fullUrlWithQuery([
        'guest_sort' => $column,
        'guest_dir' => $nextDirection,
        'guest_page' => 1,
    ]);
@endphp

<a
    href="{{ $url }}"
    class="inline-flex items-center gap-1 hover:text-white {{ $isActive ? 'text-white' : '' }}"
>
    <span>{{ $label }}</span>
    @if ($isActive)
        <span class="text-[10px] text-medic-light" aria-hidden="true">{{ $currentDirection === 'asc' ? '▲' : '▼' }}</span>
    @endif
</a>
