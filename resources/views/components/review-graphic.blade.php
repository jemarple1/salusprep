@props([
    'symbols',
    'accent' => 'medic',
])

@php
    $symbolList = preg_split('/\s+/u', trim($symbols), -1, PREG_SPLIT_NO_EMPTY);
    $featureSymbols = array_slice($symbolList !== [] ? $symbolList : ['⚕'], 0, 3);
@endphp

<x-review-backdrop
    :accent="$accent"
    {{ $attributes->merge(['class' => 'review-graphic h-[4.5rem] sm:h-20']) }}
    aria-hidden="true"
>
    <div class="flex h-full items-center justify-center gap-2 sm:gap-3 text-2xl sm:text-[1.75rem] leading-none">
        @foreach ($featureSymbols as $symbol)
            <span class="block">{{ $symbol }}</span>
        @endforeach
    </div>
</x-review-backdrop>
