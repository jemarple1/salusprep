@extends('layouts.app')

@push('structured_data')
    <script type="application/ld+json">
        @json(\App\Support\PageSeo::platformStructuredData($sectionLevel, url()->current()))
    </script>
@endpush

@section('content')
    <section class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
        <div class="space-y-6">
            <p class="inline-flex rounded-full border border-medic/40 bg-medic/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-medic-light">
                {{ $sectionLabel }} only
            </p>
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                {{ $sectionPracticeHeadline }}
            </h1>
            <p class="max-w-xl text-lg leading-relaxed text-slate-300">
                {{ $sectionDescription }}
                Start right away — no account required.
            </p>

            <ul class="max-w-xl space-y-2.5 text-sm text-slate-300">
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Start immediately — no account</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> 25-question focus &amp; adaptive quizzes</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Skill exercises &amp; flashcards</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Review — basic concepts from government health sources</li>
            </ul>

            @if ($errors->has('mock_exam'))
                <p class="text-sm font-medium text-red-200">{{ $errors->first('mock_exam') }}</p>
            @endif

            @php
                $mockActive = $mockExamState['activeSession'] ?? null;
                $mockCanStart = $mockExamState['canStart'] ?? false;
                $mockCompletedToday = $mockExamState['completedToday'] ?? false;
            @endphp

            <div class="flex flex-wrap items-center gap-3">
                @if ($mockActive)
                    <a href="{{ route('mock-exam.show', [$sectionSlug, $mockActive]) }}" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                        Continue mock exam
                    </a>
                @elseif ($hasAccess && $mockCanStart)
                    <form method="POST" action="{{ route('mock-exam.start', $sectionSlug) }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                            Start the daily mock exam
                        </button>
                    </form>
                @elseif ($mockCompletedToday)
                    <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-xl bg-safety/40 px-6 py-3 font-bold text-slate-300">
                        Mock exam complete today
                    </a>
                @elseif (! $hasAccess)
                    <a href="{{ route('platform.paywall', $sectionSlug) }}" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                        Start the daily mock exam
                    </a>
                @else
                    <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                        Start the daily mock exam
                    </a>
                @endif
            </div>

            <p class="text-xs text-slate-500">{{ \App\Support\CertificationLevel::mockExamLandingHint($sectionLevel) }}</p>
        </div>

        <div>
            @if ($activeSession)
                <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 ring-1 ring-medic/20">
                    <p class="text-sm font-bold uppercase tracking-wider text-medic-light">Quiz in progress</p>
                    <p class="mt-3 text-2xl font-bold text-white">Question {{ min($activeSession->questions_answered + 1, $previewQuestionTotal) }} of {{ $previewQuestionTotal }}</p>
                    <p class="mt-2 text-sm text-slate-400">
                        You have an active quiz. Pick up where you left off — your answer on question 1 is already saved.
                    </p>
                    <a href="{{ route('exam.show', [$sectionSlug, $activeSession]) }}" class="mt-6 inline-flex rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">
                        Continue quiz →
                    </a>
                </div>
            @elseif ($previewQuestion)
                <x-platform-preview-question
                    :question="$previewQuestion"
                    :section-slug="$sectionSlug"
                    :question-number="$previewQuestionNumber"
                    :total-questions="$previewQuestionTotal"
                    :pinned-focus="$pinnedFocus"
                />
            @elseif (! $hasAccess)
                <div class="rounded-2xl border border-safety/30 bg-safety/5 p-6 ring-1 ring-safety/20">
                    <p class="text-sm font-bold uppercase tracking-wider text-safety-light">Free preview ended</p>
                    <p class="mt-3 text-lg font-bold text-white">Your prep already started — don&rsquo;t reset now</p>
                    <p class="mt-2 text-sm text-slate-400">See what Preview mapped for you and unlock Full Access to keep practicing without limits.</p>
                    <a href="{{ route('platform.paywall', $sectionSlug) }}" class="mt-6 inline-flex rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                        View your study plan
                    </a>
                </div>
            @else
                <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 text-sm text-slate-400">
                    Questions are loading for this platform. Check back shortly.
                </div>
            @endif
        </div>
    </section>

    @if (($exercises ?? []) !== [])
        <section class="mt-16 border-t border-white/10 pt-12">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Skill exercises</h2>
                    <p class="mt-2 max-w-2xl text-slate-400">
                        @if ($sectionSlug === 'nclex-pn')
                            Prioritization, ADPIE, delegation, isolation precautions, clinical scales, and more.
                        @else
                            SOAP charting, triage, GCS, burns, stroke scales, vitals, pharmacology, and more.
                        @endif
                    </p>
                </div>
                <a href="{{ route('skills.index', $sectionSlug) }}" class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-200 hover:bg-white/5">
                    View all skills →
                </a>
            </div>
            <div class="relative left-1/2 w-screen -translate-x-1/2">
                <x-exercise-carousel :exercises="$exercises" />
            </div>
        </section>
    @endif
@endsection
