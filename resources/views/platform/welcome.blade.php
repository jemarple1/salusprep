@extends('layouts.app')

@section('title', 'Welcome to '.$sectionLabel)

@section('content')
    {{-- Hero --}}
    <section class="mb-10 overflow-hidden rounded-2xl border border-medic/30 bg-gradient-to-br from-medic/10 via-navy-light to-navy-light p-8 sm:p-10">
        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
                <p class="inline-flex rounded-full border border-medic/40 bg-medic/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-medic-light">
                    Full Access unlocked
                </p>
                <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    @if ($firstName)
                        You're in, {{ $firstName }} — let's get you exam-ready
                    @else
                        You're in — let's get you exam-ready
                    @endif
                </h1>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-300">
                    Your {{ $sectionLabel }} platform is fully unlocked. Set your exam date, then dive into your personalized study plan — focus quizzes, flashcards from your misses, and hands-on skills.
                </p>
                <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-medic-light hover:text-medic hover:underline">
                    Open Test Center →
                </a>
            </div>
            <div class="flex items-center justify-center lg:justify-end">
                <x-paywall-hero-graphic />
            </div>
        </div>
    </section>

    {{-- Exam date --}}
    <section class="mb-10 rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-end">
            <div>
                <h2 class="text-xl font-bold text-white">When is your exam?</h2>
                <p class="mt-2 text-sm text-slate-400">
                    Add your test date and we'll keep a countdown in the header so you always know how much time you have left.
                </p>
            </div>
            @if ($examCountdown)
                <div class="rounded-xl border border-medic/30 bg-medic/10 px-5 py-3 text-center lg:min-w-[10rem]">
                    <p class="text-3xl font-bold text-medic-light">
                        @if ($examCountdown['is_past'])
                            —
                        @elseif ($examCountdown['is_today'])
                            Today
                        @else
                            {{ $examCountdown['days'] }}
                        @endif
                    </p>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        @if ($examCountdown['is_past'])
                            Date passed
                        @elseif ($examCountdown['is_today'])
                            Exam day
                        @else
                            days left
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('platform.welcome.exam-date', $sectionSlug) }}" class="mt-6 flex flex-col gap-4 sm:flex-row sm:items-end">
            @csrf
            <div class="flex-1">
                <label for="exam_date" class="mb-2 block text-sm font-semibold text-slate-300">Exam date</label>
                <input
                    type="date"
                    id="exam_date"
                    name="exam_date"
                    value="{{ old('exam_date', $access->exam_date?->toDateString()) }}"
                    min="{{ now()->toDateString() }}"
                    max="{{ now()->addYears(2)->toDateString() }}"
                    class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white focus:border-medic/50 focus:outline-none focus:ring-2 focus:ring-medic/30"
                >
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">
                    Save date
                </button>
                @if ($access->exam_date)
                    <button
                        type="submit"
                        name="exam_date"
                        value=""
                        class="rounded-xl border border-white/10 px-5 py-3 text-sm font-semibold text-slate-400 hover:bg-white/5 hover:text-slate-200"
                    >
                        Clear
                    </button>
                @endif
            </div>
        </form>
    </section>

    {{-- Next steps --}}
    <section class="mb-10">
        <h2 class="text-2xl font-bold text-white">Your next steps</h2>
        <p class="mt-1 text-sm text-slate-400">Start with what matters most — we've already picked your focus based on Preview.</p>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            {{-- Focus exam --}}
            @if ($focusOption)
                <div class="flex h-full flex-col rounded-2xl border border-white/10 bg-navy-light/60 p-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Step 1 · Test your knowledge</p>
                    <h3 class="mt-2 text-lg font-bold text-white">Start your focus exam</h3>
                    <p class="mt-2 text-sm text-slate-400">
                        @if ($focusOption->is_general)
                            A balanced 25-question quiz across every topic — great for your first full pass.
                        @else
                            25 questions weighted to <strong class="text-white">{{ $focusOption->category }}</strong> — the topic you chose to focus on.
                        @endif
                    </p>
                    <div class="mt-5 flex-1">
                        <x-focus-exam-card
                            :category="$focusOption->category"
                            :focus-category="$focusOption->focus_category"
                            :accuracy="$focusOption->accuracy_percent"
                            :is-general="$focusOption->is_general"
                            :start-on-click="true"
                            :disabled="$activeExamSession !== null"
                        />
                    </div>
                    @if ($activeExamSession)
                        <p class="mt-3 text-xs text-slate-500">Finish or resume your current quiz before starting another.</p>
                    @endif
                </div>
            @endif

            {{-- Flashcards --}}
            <div class="flex h-full flex-col rounded-2xl border border-white/10 bg-navy-light/60 p-6">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Step 2 · Lock it in</p>
                <h3 class="mt-2 text-lg font-bold text-white">Review with flashcards</h3>
                <p class="mt-2 text-sm text-slate-400">
                    Every question you miss becomes a flashcard with the full rationale on the back. Cards you struggle with come back sooner.
                </p>

                <div class="mt-5 flex flex-1 flex-col justify-between rounded-2xl border border-ems/30 bg-ems/10 p-5">
                    @if ($activeStudySession)
                        <div>
                            <p class="text-2xl font-bold text-ems-light">{{ $activeStudySession->remainingCount() }}</p>
                            <p class="text-sm text-slate-400">cards left in your current session</p>
                        </div>
                        <a href="{{ route('study.show', [$sectionSlug, $activeStudySession]) }}" class="mt-6 inline-block rounded-xl bg-ems px-6 py-3 text-center font-bold text-white hover:bg-ems-dark">
                            Continue flashcards →
                        </a>
                    @elseif ($totalMissed > 0)
                        <div>
                            <p class="text-2xl font-bold text-ems-light">{{ $totalMissed }}</p>
                            <p class="text-sm text-slate-400">missed questions ready to review</p>
                        </div>
                        <form method="POST" action="{{ route('study.start', $sectionSlug) }}" class="mt-6">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-ems px-6 py-3 font-bold text-white hover:bg-ems-dark">
                                Start flashcard session →
                            </button>
                        </form>
                    @else
                        <div>
                            <p class="text-lg font-bold text-white">No cards yet</p>
                            <p class="mt-1 text-sm text-slate-400">Complete a focus quiz first — missed questions become your personal deck.</p>
                        </div>
                        @if ($focusOption && ! $activeExamSession)
                            <form method="POST" action="{{ route('exam.start', $sectionSlug) }}" class="mt-6">
                                @csrf
                                @if ($focusOption->focus_category)
                                    <input type="hidden" name="focus_category" value="{{ $focusOption->focus_category }}">
                                @endif
                                <button type="submit" class="w-full rounded-xl border border-ems/40 bg-ems/20 px-6 py-3 font-bold text-ems-light hover:bg-ems/30">
                                    Take a quiz to build your deck →
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Skills --}}
    @if ($hasExercises)
        <section>
            <h2 class="text-2xl font-bold text-white">Sharpen your skills</h2>
            <p class="mt-1 text-sm text-slate-400">Hands-on scenarios to round out your studying — charting, triage, assessments, and more.</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                @foreach ($exercises as $exercise)
                    <a
                        href="{{ $exercise['url'] }}"
                        class="group flex flex-col rounded-2xl border border-safety/25 bg-safety/5 p-5 transition hover:border-safety/40 hover:bg-safety/10"
                    >
                        <span class="inline-block w-fit rounded-full bg-safety/20 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-safety-light">
                            Skill exercise
                        </span>
                        <h3 class="mt-3 text-lg font-bold text-white group-hover:text-safety-light">{{ $exercise['title'] }}</h3>
                        <p class="mt-2 flex-1 text-sm text-slate-400">{{ $exercise['description'] ?? 'Interactive practice scenarios.' }}</p>
                        <span class="mt-4 text-sm font-semibold text-safety-light group-hover:underline">
                            @if (($exercise['scenario_count'] ?? 0) > 0)
                                {{ $exercise['scenario_count'] }} scenarios →
                            @else
                                Start training →
                            @endif
                        </span>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('skills.index', $sectionSlug) }}" class="mt-5 inline-flex text-sm font-semibold text-safety-light hover:text-safety hover:underline">
                View all {{ $exerciseCount }} skill exercises →
            </a>
        </section>
    @endif

    @if ($trackPurchaseConversion ?? false)
        <!-- Event snippet for Purchase (1) conversion page -->
        <script>
            gtag('event', 'conversion', {
                'send_to': 'AW-18250454039/JED9COKs58EcEJeov_5D',
                'transaction_id': @json($purchaseTransactionId ?? '')
                // 'new_customer': true /* calculate dynamically, populate with true/false */,
            });
        </script>
    @endif
@endsection
