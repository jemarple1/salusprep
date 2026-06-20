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
    {{ $attributes->merge(['class' => "inline-flex {$sizeClass} shrink-0 items-center justify-center rounded-full {$palette['bg']} ring-1 {$palette['ring']} {$palette['text']}"]) }}
    aria-hidden="true"
>
    {{ $palette['symbol'] }}
</span>
