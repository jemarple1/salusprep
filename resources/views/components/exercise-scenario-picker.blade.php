@props([
    'scenarioLinks',
    'scenarioIndex',
    'exerciseSlug',
    'sectionSlug',
])

@if (count($scenarioLinks) > 1)
    <div {{ $attributes->class(['mb-6 flex flex-wrap gap-2']) }}>
        @foreach ($scenarioLinks as $link)
            @if ($link['accessible'])
                <a
                    href="{{ $link['url'] }}"
                    @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-medic text-white' => $link['index'] === $scenarioIndex,
                        'border border-white/10 text-slate-300 hover:border-medic/30 hover:text-white' => $link['index'] !== $scenarioIndex,
                    ])
                >
                    Scenario {{ $link['index'] + 1 }}
                </a>
            @else
                <a
                    href="{{ route('exercises.show', ['section' => $sectionSlug, 'exercise' => $exerciseSlug, 'scenario' => $link['index']]) }}"
                    class="rounded-full border border-white/10 px-4 py-2 text-sm font-semibold text-slate-600 opacity-60 transition hover:border-safety/30 hover:opacity-80"
                >
                    Scenario {{ $link['index'] + 1 }}
                </a>
            @endif
        @endforeach
    </div>
@endif
