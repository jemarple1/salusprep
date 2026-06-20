@props([
    'accent' => 'medic',
])

@php
    $gradientStyle = match ($accent) {
        'ems' => 'background: linear-gradient(145deg, #0f172a 0%, #004d84 100%);',
        'rescue' => 'background: linear-gradient(145deg, #0f172a 0%, #991b1b 100%);',
        'pharma' => 'background: linear-gradient(145deg, #0f172a 0%, #7c3aed 100%);',
        'safety' => 'background: linear-gradient(145deg, #0f172a 0%, #d97706 100%);',
        default => 'background: linear-gradient(145deg, #0f172a 0%, #15803d 100%);',
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
