@props([
    'user' => null,
    'size' => 'sm',
])

@php
    $user = $user ?? auth()->user();
    $color = \App\Support\UserAvatar::colorFor($user);
    $palette = \App\Support\UserAvatar::palette($color);
    $sizeClass = match ($size) {
        'lg' => 'h-16 w-16 text-3xl',
        'md' => 'h-12 w-12 text-2xl',
        default => 'h-8 w-8 text-lg',
    };
@endphp

<span
    {{ $attributes->merge(['class' => "inline-flex {$sizeClass} shrink-0 items-center justify-center rounded-full border border-white/40 {$palette['bg']} ring-2 {$palette['ring']} {$palette['text']} shadow-inner"]) }}
    aria-hidden="true"
>
    <span class="drop-shadow-[0_1px_1px_rgba(15,23,42,0.12)]">{{ $palette['symbol'] }}</span>
</span>
