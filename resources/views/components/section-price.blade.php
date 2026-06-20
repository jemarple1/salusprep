@props([
    'size' => 'default',
    'tone' => 'default',
])

@php
    use App\Support\SectionPricing;

    $price = SectionPricing::formatted();

    $class = match (true) {
        $size === 'hero' => match ($tone) {
            'button' => 'text-3xl font-bold text-white',
            'checkout' => 'text-3xl font-bold text-medic-light',
            default => 'text-3xl font-bold text-safety-light',
        },
        $tone === 'button' => 'font-bold text-white',
        $tone === 'checkout' => 'font-bold text-medic-light',
        default => 'font-bold text-safety-light',
    };
@endphp

<span {{ $attributes->class([$class]) }}>{{ $price }}</span>
