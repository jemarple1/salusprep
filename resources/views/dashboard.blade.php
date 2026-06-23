@extends('layouts.app')

@section('meta_title', \App\Support\PageSeo::platformPageTitle($sectionLevel, 'Test Center'))

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-medic-light">{{ $sectionLabel }}</p>
            <h1 class="text-3xl font-bold text-white">Test Center</h1>
            <p class="mt-2 text-slate-400">Quiz scores, trends, and category breakdowns.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if ($mockExamState['activeSession'] ?? null)
                <a href="{{ route('mock-exam.show', [$sectionSlug, $mockExamState['activeSession']]) }}" class="rounded-xl bg-safety px-5 py-3 font-bold text-navy hover:bg-safety-light">Resume mock exam</a>
            @elseif ($activeSession)
                <a href="{{ route('exam.show', [$sectionSlug, $activeSession]) }}" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Resume quiz</a>
            @elseif ($requiresAuth ?? false)
                <a href="{{ route('register') }}" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Create free account</a>
            @elseif (! $hasAccess)
                <a href="{{ route('platform.paywall', $sectionSlug) }}" class="rounded-xl bg-safety px-5 py-3 font-bold text-navy hover:bg-safety-light">Unlock full access</a>
            @endif
        </div>
    </div>

    @if ($errors->has('mock_exam'))
        <div class="mb-6 rounded-xl border border-rescue/40 bg-rescue/10 px-4 py-3 text-sm text-red-200">
            {{ $errors->first('mock_exam') }}
        </div>
    @endif

    @php
        $accuracyTier = \App\Support\AccuracyDisplay::tier(
            $overallStats['accuracy_percent'] ?? null,
            $hasAccess && ($overallStats['total'] ?? 0) > 0,
        );
        $mockActive = $mockExamState['activeSession'] ?? null;
        $mockCompletedToday = $mockExamState['completedToday'] ?? false;
        $mockCanStart = $mockExamState['canStart'] ?? false;
        $mockOutcome = $mockExamState['todaysOutcome'] ?? null;
        $regularQuizActive = $activeSession && ! $activeSession->isMockExam();
    @endphp

    <div class="mb-8 grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border {{ $accuracyTier['border'] }} {{ $accuracyTier['bg'] }} p-6 backdrop-blur-sm">
            <p class="text-sm font-bold uppercase {{ $accuracyTier['label'] }}">Overall accuracy</p>
            @if ($hasAccess && ($overallStats['total'] ?? 0) > 0)
                <p class="mt-1 text-4xl font-bold {{ $accuracyTier['text'] }}">{{ $overallStats['accuracy_percent'] }}%</p>
                <p class="mt-2 text-sm text-slate-300">
                    {{ $overallStats['correct'] }} correct · {{ $overallStats['incorrect'] }} missed · {{ $overallStats['total'] }} total
                </p>
            @elseif ($hasAccess)
                <p class="mt-2 text-lg font-semibold text-slate-200">Complete a quiz to see your score</p>
                <p class="mt-1 text-sm text-slate-400">Your accuracy colors this card as you practice.</p>
            @else
                <p class="mt-2 text-lg font-semibold text-slate-200">Unlock to track accuracy</p>
                <a href="{{ route('platform.paywall', $sectionSlug) }}" class="mt-3 inline-block text-sm font-semibold text-safety-light hover:underline">View unlock options →</a>
            @endif
        </div>

        <div class="rounded-2xl border border-safety/50 bg-safety/35 p-6 backdrop-blur-sm">
            <p class="text-sm font-bold uppercase text-safety-light">Daily mock exam</p>
            <p class="mt-1 text-xl font-bold text-white">Timed adaptive simulation</p>
            <p class="mt-2 text-sm text-slate-200">
                70–140 questions · 2-hour limit · once per day. No scores or answer review — pass or fail only.
            </p>

            <div class="mt-5">
                @if ($requiresAuth ?? false)
                    <a href="{{ route('register') }}" class="inline-block rounded-xl bg-safety px-5 py-2.5 text-sm font-bold text-navy hover:bg-safety-light">Sign up to take mock exam</a>
                @elseif ($mockActive)
                    <a href="{{ route('mock-exam.show', [$sectionSlug, $mockActive]) }}" class="inline-block rounded-xl bg-safety px-5 py-2.5 text-sm font-bold text-navy hover:bg-safety-light">Continue mock exam</a>
                @elseif ($mockCompletedToday)
                    <p class="text-sm font-semibold text-slate-200">
                        Completed today —
                        @if ($mockOutcome === 'pass')
                            <span class="text-medic-light">Pass</span>
                        @else
                            <span class="text-red-200">Did not pass</span>
                        @endif
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Next mock exam available tomorrow.</p>
                @elseif ($regularQuizActive)
                    <p class="text-sm text-slate-300">Finish your current quiz before starting a mock exam.</p>
                @elseif ($hasAccess && $mockCanStart)
                    <form method="POST" action="{{ route('mock-exam.start', $sectionSlug) }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-safety px-5 py-2.5 text-sm font-bold text-navy hover:bg-safety-light">Start mock exam</button>
                    </form>
                @elseif (! $hasAccess)
                    <a href="{{ route('platform.paywall', $sectionSlug) }}" class="inline-block rounded-xl bg-safety px-5 py-2.5 text-sm font-bold text-navy hover:bg-safety-light">Unlock to start</a>
                @endif
            </div>
        </div>
    </div>

    @if ($hasAccess && ($focusExamOptions ?? collect())->isNotEmpty())
        <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80">
            <div class="border-b border-white/10 px-6 py-4">
                <h2 class="text-lg font-bold text-white">Focus exams</h2>
                <p class="text-sm text-slate-400">
                    Click a card to start a 25-question quiz. Weakest topics appear first after General knowledge.
                </p>
                @if ($activeSession || ($mockExamState['activeSession'] ?? null))
                    <p class="mt-2 text-sm text-safety-light">Finish or resume your current exam before starting another.</p>
                @endif
            </div>
            <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($focusExamOptions as $option)
                    <x-focus-exam-card
                        :category="$option->category"
                        :focus-category="$option->focus_category"
                        :accuracy="$option->accuracy_percent"
                        :is-general="$option->is_general"
                        start-on-click
                        :disabled="(bool) ($activeSession || ($mockExamState['activeSession'] ?? null))"
                    />
                @endforeach
            </div>
        </div>
    @endif

    <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80">
        <div class="border-b border-white/10 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Recent {{ $sectionLabel }} quizzes</h2>
        </div>

        @if ($sessions->isEmpty())
            <div class="px-6 py-10 text-center text-slate-400">No quizzes yet in this section.</div>
        @else
            <div class="divide-y divide-white/10">
                @foreach ($sessions as $session)
                    <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4">
                        <div>
                            <p class="font-semibold text-white">
                                Quiz #{{ $quizNumbers[$session->id] ?? '?' }}
                                <span class="ml-2 rounded-full bg-white/10 px-2 py-0.5 text-xs uppercase text-slate-300">{{ str_replace('_', ' ', $session->status) }}</span>
                            </p>
                            <p class="mt-1 text-sm text-slate-400">
                                {{ $session->questions_answered }}/{{ $session->targetQuestionCount() }} answered · {{ $session->scorePercent() }}% · difficulty {{ $session->current_difficulty }}/5
                                @if ($session->hasFocusCategory())
                                    · <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ \App\Support\QuestionCategory::styles($session->focus_category)['badge'] }}">{{ $session->focus_category }} focus</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex gap-2">
                            @if ($session->isComplete())
                                <a href="{{ route('exam.results', [$sectionSlug, $session]) }}" class="rounded-lg border border-white/10 px-4 py-2 text-sm hover:bg-white/5">Results</a>
                            @else
                                <a href="{{ route('exam.show', [$sectionSlug, $session]) }}" class="rounded-lg border border-medic/30 px-4 py-2 text-sm text-medic-light hover:bg-medic/10">Continue</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ($hasAccess)
        <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80">
            <div class="border-b border-white/10 px-6 py-4">
                <h2 class="text-lg font-bold text-white">Accuracy trend</h2>
                <p class="text-sm text-slate-400">
                    How your quiz scores change over time on {{ $sectionLabel }}.
                    @if (($accuracyTrend['total_quizzes'] ?? 0) > 15)
                        Showing your most recent 15 quizzes.
                    @endif
                </p>
            </div>
            <div class="p-6">
                @if (count($accuracyTrend['points']) === 0)
                    <div class="py-10 text-center text-slate-400">Complete a quiz to start tracking your accuracy trend.</div>
                @else
                    <x-accuracy-trend-chart :trend="$accuracyTrend" />
                @endif
            </div>
        </div>

        <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-white/10 px-6 py-4">
                <div>
                    <h2 class="text-lg font-bold text-white">Category proficiency</h2>
                    <p class="text-sm text-slate-400">Accuracy by topic — focus flashcard review where you miss most.</p>
                </div>
                @if ($totalMissed > 0 && auth()->check())
                    <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-lg bg-medic/20 px-4 py-2 text-sm font-bold text-medic-light hover:bg-medic/30">Open flashcards</a>
                @endif
            </div>

            @if ($categoryStats->isEmpty())
                <div class="px-6 py-10 text-center text-slate-400">
                    Answer questions in a quiz to build your category breakdown.
                </div>
            @else
                <div class="space-y-5 p-6">
                    @foreach ($categoryStats as $stat)
                        @php
                            $styles = \App\Support\QuestionCategory::styles($stat->category);
                            $missedInCategory = $wrongByCategory[$stat->category] ?? 0;
                        @endphp
                        <div>
                            <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $styles['badge'] }}">{{ $stat->category }}</span>
                                    @if ($missedInCategory > 0)
                                        <span class="rounded-full bg-white/10 px-2 py-0.5 text-xs text-slate-400">{{ $missedInCategory }} to review</span>
                                    @endif
                                </div>
                                <div class="text-sm text-slate-400">
                                    <span class="font-bold text-white">{{ $stat->accuracy_percent }}%</span> correct
                                    <span class="mx-1 text-slate-600">·</span>
                                    {{ $stat->miss_percent }}% missed
                                    <span class="mx-1 text-slate-600">·</span>
                                    {{ $stat->total }} Q
                                </div>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-white/10 ring-1 ring-white/5">
                                <div class="{{ $styles['bar'] }} h-full rounded-full transition-all" style="width: {{ max($stat->accuracy_percent, 2) }}%"></div>
                            </div>
                            @if ($missedInCategory > 0 && auth()->check())
                                <form method="POST" action="{{ route('study.start', $sectionSlug) }}" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="category" value="{{ $stat->category }}">
                                    <button type="submit" class="text-xs font-semibold text-medic-light hover:text-medic hover:underline">
                                        Study {{ $stat->category }} flashcards →
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @elseif ($requiresAuth ?? false)
        <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80 p-8 text-center">
            <p class="text-lg font-bold text-white">Sign in to track your progress</p>
            <p class="mt-2 text-sm text-slate-400">Create a free account to save quiz history, trends, and flashcard decks.</p>
            <div class="mt-4 flex flex-wrap justify-center gap-3">
                <a href="{{ route('register') }}" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">Create free account</a>
                <a href="{{ route('login') }}" class="rounded-xl border border-white/15 px-6 py-3 font-semibold text-slate-200 hover:bg-white/5">Log in</a>
            </div>
        </div>
    @endif
@endsection
