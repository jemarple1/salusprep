@props([
    'preview',
    'index' => 0,
    'total' => 1,
])

@php
    $question = $preview->question;
    $rotation = match ($index % 4) {
        0 => '-rotate-2',
        1 => 'rotate-1',
        2 => '-rotate-1',
        default => 'rotate-2',
    };
    $offset = match ($index % 4) {
        0 => 'translate-x-0',
        1 => 'translate-x-3 sm:translate-x-5',
        2 => 'translate-x-6 sm:translate-x-10',
        default => 'translate-x-9 sm:translate-x-[3.5rem]',
    };
    $zIndex = 10 + $index;
@endphp

<div
    class="absolute top-0 w-[min(100%,15rem)] {{ $rotation }} {{ $offset }}"
    style="z-index: {{ $zIndex }}; left: {{ $index * 6 }}px;"
>
    <div class="overflow-hidden rounded-xl border border-white/15 bg-navy shadow-lg">
        <div class="border-b border-white/10 px-4 py-2">
            <span class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">{{ $question->category }}</span>
        </div>

        <div class="p-4">
            <p class="line-clamp-3 text-sm leading-snug text-white">{{ $question->stem }}</p>

            @if ($preview->wrong_option)
                <p class="mt-2 text-xs text-slate-400">
                    Last try: <span class="font-semibold text-slate-200">{{ $preview->wrong_option }}</span>
                </p>
            @endif

            <div class="mt-3 space-y-1">
                @foreach (array_slice($question->options(), 0, 2, true) as $letter => $text)
                    <div class="truncate rounded border border-white/10 bg-navy-light px-2 py-1 text-[11px] text-slate-400">
                        <span class="font-bold text-slate-300">{{ $letter }}.</span> {{ Str::limit($text, 42) }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
