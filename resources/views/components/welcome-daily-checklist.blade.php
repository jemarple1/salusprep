@props([
    'plan' => [],
    'sectionSlug' => null,
    'activeExamSession' => null,
    'isFirstDay' => false,
    'hasExamCountdown' => false,
])

@php
    $items = $plan['items'] ?? [];
    $progressPercent = (int) ($plan['progressPercent'] ?? 0);
    $completedCount = (int) ($plan['completedCount'] ?? 0);
    $totalCount = (int) ($plan['totalCount'] ?? 0);
    $isComplete = (bool) ($plan['isComplete'] ?? false);
@endphp

<section class="mb-10 rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-medic-light">
                Day {{ $plan['dayNumber'] ?? 1 }} · {{ $plan['planDate'] ?? today()->format('l, F j') }}
            </p>
            <h2 class="mt-2 text-2xl font-bold text-white">Today's study checklist</h2>
            <p class="mt-2 max-w-2xl text-sm text-slate-400">
                @if ($isFirstDay)
                    Complete three skill drills, two adaptive quizzes, and today's mock exam to finish day one. Tomorrow this page will refresh with a new plan — bookmark it or use the
                    @if ($hasExamCountdown)
                        calendar button
                    @else
                        checklist button
                    @endif
                    in the header to return.
                @else
                    Your welcome page updates every day. Complete three skill drills, two adaptive quizzes, and today's mock exam to stay on track.
                @endif
            </p>
        </div>
        <div class="rounded-xl border border-medic/30 bg-medic/10 px-5 py-3 text-center min-w-[8rem]">
            <p class="text-3xl font-bold text-medic-light">{{ $completedCount }}/{{ $totalCount }}</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">done today</p>
        </div>
    </div>

    <div class="mt-6">
        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
            <span class="font-semibold text-slate-300">Daily progress</span>
            <span class="tabular-nums text-medic-light">{{ $progressPercent }}%</span>
        </div>
        <div class="h-3 overflow-hidden rounded-full bg-white/10">
            <div
                class="h-full rounded-full bg-gradient-to-r from-medic to-medic-light transition-all duration-500"
                style="width: {{ max($progressPercent, $isComplete ? 100 : 4) }}%"
            ></div>
        </div>
        @if ($isComplete)
            <p class="mt-3 text-sm font-semibold text-medic-light">Checklist complete — great work today. Come back tomorrow for a fresh plan.</p>
        @endif
    </div>

    <ul class="mt-8 space-y-3">
        @foreach ($items as $item)
            <li class="rounded-xl border {{ $item['completed'] ? 'border-medic/30 bg-medic/10' : 'border-white/10 bg-navy/40' }} p-4">
                <div class="flex flex-wrap items-start gap-4 sm:flex-nowrap">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border {{ $item['completed'] ? 'border-medic bg-medic text-white' : 'border-white/15 bg-navy text-slate-500' }}">
                        @if ($item['completed'])
                            <span aria-hidden="true">✓</span>
                        @else
                            <span class="h-2 w-2 rounded-full bg-current"></span>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base font-bold {{ $item['completed'] ? 'text-medic-light' : 'text-white' }}">
                                {{ $item['label'] }}
                            </h3>
                            <span class="rounded-full bg-white/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                {{ match ($item['type']) {
                                    'skill' => 'Skill',
                                    'quiz' => 'Quiz',
                                    'mock' => 'Mock exam',
                                    default => 'Task',
                                } }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-400">{{ $item['description'] }}</p>
                    </div>

                    @unless ($item['completed'])
                        @if ($item['type'] === 'quiz')
                            @if ($activeExamSession)
                                <a href="{{ \App\Support\WelcomeReturn::url(route('exam.show', [$sectionSlug, $activeExamSession])) }}" class="shrink-0 rounded-lg bg-medic px-4 py-2 text-sm font-bold text-white hover:bg-medic-dark">
                                    Continue quiz
                                </a>
                            @else
                                <a href="{{ $item['url'] }}" class="shrink-0 rounded-lg border border-white/10 px-4 py-2 text-sm font-semibold text-slate-200 hover:bg-white/5">
                                    Start quiz
                                </a>
                            @endif
                        @else
                            <a href="{{ $item['url'] }}" class="shrink-0 rounded-lg bg-medic px-4 py-2 text-sm font-bold text-white hover:bg-medic-dark">
                                {{ $item['type'] === 'mock' ? 'Start mock' : 'Open skill' }}
                            </a>
                        @endif
                    @endunless
                </div>
            </li>
        @endforeach
    </ul>
</section>
