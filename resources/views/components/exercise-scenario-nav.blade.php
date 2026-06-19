@props([
    'scenarioLinks',
    'scenarioIndex',
    'scenarioCount' => null,
    'completedCount' => 0,
    'exerciseSlug',
    'sectionSlug',
    'levelLinks' => [],
    'exerciseLevel' => 1,
])

@php
    $total = $scenarioCount ?? count($scenarioLinks);
@endphp

@if ($total > 0)
    <nav
        class="mb-6 overflow-hidden rounded-2xl border border-white/10 bg-navy-light/80"
        aria-label="Exercise scenarios"
    >
        @if (count($levelLinks) > 0)
            <div class="border-b border-white/10 px-4 py-3">
                <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">Level</p>
                <div class="flex w-full gap-1" role="tablist" aria-label="Exercise levels">
                    @foreach ($levelLinks as $levelLink)
                        @php
                            $isCurrentLevel = $levelLink['current'];
                            $levelComplete = $levelLink['completed'];
                            $levelUnlocked = $levelLink['unlocked'];
                        @endphp
                        @if ($levelLink['accessible'] && $levelUnlocked)
                            <a
                                href="{{ $levelLink['url'] }}"
                                role="tab"
                                aria-selected="{{ $isCurrentLevel ? 'true' : 'false' }}"
                                aria-label="Level {{ $levelLink['level'] }}{{ $levelComplete ? ', completed' : '' }}"
                                @class([
                                    'flex flex-1 flex-col items-center rounded-lg border px-2 py-2 text-center transition',
                                    'border-medic/50 bg-medic/20 text-medic-light ring-1 ring-medic/30' => $isCurrentLevel,
                                    'border-medic/30 bg-medic/10 text-medic-light hover:bg-medic/15' => $levelComplete && ! $isCurrentLevel,
                                    'border-white/10 text-slate-400 hover:border-white/20 hover:text-slate-200' => ! $levelComplete && ! $isCurrentLevel,
                                ])
                            >
                                <span class="text-xs font-bold uppercase tracking-wide">L{{ $levelLink['level'] }}</span>
                                @if ($levelComplete)
                                    <svg class="mt-0.5 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                        <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                @else
                                    <span class="mt-1 h-1 w-1 rounded-full bg-current opacity-40"></span>
                                @endif
                            </a>
                        @else
                            <span
                                class="flex flex-1 flex-col items-center rounded-lg border border-white/10 px-2 py-2 text-center text-slate-600 opacity-50"
                                aria-label="Level {{ $levelLink['level'] }}, locked"
                            >
                                <span class="text-xs font-bold uppercase tracking-wide">L{{ $levelLink['level'] }}</span>
                                <svg class="mt-0.5 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M7 11V7a5 5 0 0110 0v4M6 11h12v10H6z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between gap-3 border-b border-white/10 px-4 py-3">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Scenarios</p>
                <p class="text-sm font-semibold text-white">
                    {{ $completedCount }} of {{ $total }} complete
                    @if (count($levelLinks) > 0)
                        <span class="text-slate-500">· Level {{ $exerciseLevel }}</span>
                    @endif
                </p>
            </div>
            <p class="text-xs text-slate-400">Scenario {{ $scenarioIndex + 1 }}</p>
        </div>

        <div class="flex w-full gap-1 p-2" role="tablist">
            @foreach ($scenarioLinks as $link)
                @php
                    $isCurrent = $link['index'] === $scenarioIndex;
                    $isComplete = $link['completed'] ?? false;
                @endphp
                @if ($link['accessible'])
                    <a
                        href="{{ $link['url'] }}"
                        role="tab"
                        aria-selected="{{ $isCurrent ? 'true' : 'false' }}"
                        aria-label="Scenario {{ $link['index'] + 1 }}{{ $isComplete ? ', completed' : '' }}"
                        @class([
                            'flex flex-1 flex-col items-center rounded-lg border px-1 py-2 text-center transition',
                            'border-medic/50 bg-medic/20 text-medic-light ring-1 ring-medic/30' => $isCurrent,
                            'border-medic/30 bg-medic/10 text-medic-light hover:bg-medic/15' => $isComplete && ! $isCurrent,
                            'border-white/10 text-slate-400 hover:border-white/20 hover:text-slate-200' => ! $isComplete && ! $isCurrent,
                        ])
                    >
                        <span class="text-sm font-bold">{{ $link['index'] + 1 }}</span>
                        @if ($isComplete)
                            <svg class="mt-0.5 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        @else
                            <span class="mt-1 h-1 w-1 rounded-full bg-current opacity-40"></span>
                        @endif
                    </a>
                @else
                    <a
                        href="{{ route('exercises.show', ['section' => $sectionSlug, 'exercise' => $exerciseSlug, 'scenario' => $link['index']]) }}"
                        class="flex flex-1 flex-col items-center rounded-lg border border-white/10 px-1 py-2 text-center text-slate-600 opacity-60"
                    >
                        <span class="text-sm font-bold">{{ $link['index'] + 1 }}</span>
                    </a>
                @endif
            @endforeach
        </div>

        @if ($scenarioLinks[$scenarioIndex]['title'] ?? null)
            <p class="border-t border-white/10 px-4 py-3 text-sm text-slate-400">
                {{ $scenarioLinks[$scenarioIndex]['title'] }}
            </p>
        @endif
    </nav>
@endif
