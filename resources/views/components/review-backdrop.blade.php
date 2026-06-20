@props([
    'accent' => 'medic',
])

@php
    $gradientStyle = match ($accent) {
        'ems' => 'background: linear-gradient(145deg, #0a3254 0%, #145a82 100%);',
        'rescue' => 'background: linear-gradient(145deg, #5c1818 0%, #8b2525 100%);',
        'pharma' => 'background: linear-gradient(145deg, #3b1d6e 0%, #5b3a9e 100%);',
        'safety' => 'background: linear-gradient(145deg, #6b4420 0%, #92682a 100%);',
        default => 'background: linear-gradient(145deg, #134526 0%, #1f6b38 100%);',
    };
    $textureClass = match ($accent) {
        'ems' => 'review-texture-ems',
        'rescue' => 'review-texture-rescue',
        'pharma' => 'review-texture-pharma',
        'safety' => 'review-texture-safety',
        default => 'review-texture-medic',
    };
@endphp

<div
    {{ $attributes->merge(['class' => "relative overflow-hidden {$textureClass}"]) }}
    style="{{ $gradientStyle }}"
>
    <div class="relative z-10 h-full min-h-0">
        {{ $slot }}
    </div>
</div>
