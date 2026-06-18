@props([
    'size' => 'default',
    'tone' => 'default',
])

@php
    use App\Support\SectionPricing;

    $price = SectionPricing::formatted();

    $class = match (true) {
        $size === 'hero' => match ($tone) {
            'safety' => 'text-3xl font-bold text-navy',
            default => 'text-3xl font-bold text-safety-light',
        },
        $tone === 'safety' => 'font-bold text-navy',
        $tone === 'overlay' => 'font-semibold text-safety',
        default => 'font-bold text-safety-light',
    };
@endphp

<span {{ $attributes->class([$class]) }}>{{ $price }}</span>
