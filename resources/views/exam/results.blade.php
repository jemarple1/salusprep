@extends('layouts.app')

@section('title', $sectionLabel.' Results')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-bold uppercase tracking-wider text-medic-light">{{ $sectionLabel }}</p>
        <h1 class="mt-1 text-3xl font-bold text-white">Quiz results</h1>
        @if ($session->hasFocusCategory())
            @php $focusStyles = \App\Support\QuestionCategory::styles($session->focus_category); @endphp
            <p class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase {{ $focusStyles['badge'] }}">
                {{ $session->focus_category }} focus exam · 75% weighted
            </p>
        @endif
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-4">
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-white">{{ $session->questions_answered }}</p>
            <p class="mt-1 text-sm text-slate-500">of {{ $session->targetQuestionCount() }} total</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-medic-light">{{ $session->correct_count }}</p>
            <p class="mt-1 text-sm text-slate-500">Correct</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-white">{{ $session->scorePercent() }}%</p>
            <p class="mt-1 text-sm text-slate-500">Accuracy</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-sm text-slate-500">Final difficulty</p>
            <x-exam-difficulty-bar
                :difficulty="$session->current_difficulty"
                :show-level="true"
                :show-label="false"
                class="mt-3"
            />
        </div>
    </div>

    <div class="mb-8 space-y-8">
        <x-exam-results-flashcard-deck
            :answers="$session->answers"
            :platform-correct-percents="$platformCorrectPercents"
        />

        <section class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
            @php
                $examBlocked = (bool) ($activeExamSession ?? null);
                $mockActive = $mockExamState['activeSession'] ?? null;
                $mockBlocked = $examBlocked || (bool) $mockActive;
                $regularQuizActive = $activeExamSession && ! $activeExamSession->isMockExam();
            @endphp

            <h2 class="text-lg font-bold text-white">What's next</h2>
            @if ($focusOption)
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                    You missed <strong class="text-white">{{ $focusOption->miss_count }}</strong>
                    {{ $focusOption->miss_count === 1 ? 'question' : 'questions' }}
                    in <strong class="text-white">{{ $focusOption->category }}</strong>.
                    Drill that gap with a focus exam, mix topics with a general quiz, or try today's timed mock exam.
                </p>
            @else
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                    Strong round — every answer was correct. Keep momentum with another adaptive quiz or today's timed mock exam.
                </p>
            @endif

            <div @class([
                'mt-6 grid gap-4',
                $focusOption ? 'sm:grid-cols-2 lg:grid-cols-3' : 'sm:grid-cols-2',
            ])>
                @if ($focusOption)
                    <x-focus-exam-card
                        :category="$focusOption->category"
                        :focus-category="$focusOption->focus_category"
                        :accuracy="$focusOption->accuracy_percent"
                        :is-general="false"
                        :start-on-click="true"
                        :disabled="$mockBlocked"
                    />
                @endif

                <x-focus-exam-card
                    :category="$generalExamOption->category"
                    :focus-category="$generalExamOption->focus_category"
                    :accuracy="$generalExamOption->accuracy_percent"
                    :is-general="true"
                    :start-on-click="true"
                    :disabled="$mockBlocked"
                />

                <x-mock-exam-card
                    :disabled="$examBlocked"
                    :mock-active="$mockActive"
                    :can-start="$mockExamState['canStart'] ?? false"
                    :completed-today="$mockExamState['completedToday'] ?? false"
                    :todays-outcome="$mockExamState['todaysOutcome'] ?? null"
                    :has-access="$hasAccess ?? false"
                    :regular-quiz-active="$regularQuizActive"
                />
            </div>
        </section>

        @if ($suggestedExercises !== [])
            <section class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-white">Practice before your next quiz</h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-400">
                    You missed questions in
                    @foreach ($weakCategories->keys()->take(3) as $category)
                        <strong class="text-white">{{ $category }}</strong>@if (! $loop->last), @endif
                    @endforeach
                    @if ($weakCategories->count() > 3)
                        and {{ $weakCategories->count() - 3 }} more {{ $weakCategories->count() - 3 === 1 ? 'topic' : 'topics' }}
                    @endif
                    . Try a hands-on exercise in those areas before starting another exam.
                </p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($suggestedExercises as $suggestion)
                        <x-exercise-card :exercise="$suggestion['exercise']" variant="grid" />
                    @endforeach
                </div>

                <p class="mt-5 text-sm text-slate-500">
                    <a href="{{ route('skills.index', $sectionSlug) }}" class="font-semibold text-medic-light hover:text-medic hover:underline">Browse all skill exercises →</a>
                </p>
            </section>
        @endif
    </div>

    <div class="rounded-2xl border border-white/10 bg-navy-light/80">
        <div class="border-b border-white/10 px-6 py-4">
            <h2 class="text-lg font-bold text-white">Answer review</h2>
        </div>
        <div class="divide-y divide-white/10">
            @foreach ($session->answers as $index => $answer)
                <div class="px-6 py-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-bold text-slate-500">Q{{ $index + 1 }}</span>
                        <span class="rounded-full bg-ems/20 px-2 py-0.5 text-xs text-ems-light">{{ $answer->question->category }}</span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $answer->is_correct ? 'bg-medic/15 text-medic-light' : 'bg-rescue/15 text-red-200' }}">
                            {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                        </span>
                        <x-question-platform-stat :percent="$platformCorrectPercents[$answer->question_id] ?? null" />
                    </div>
                    <p class="mt-2 text-white">{{ $answer->question->stem }}</p>
                    <p class="mt-2 text-sm text-slate-400">Your answer: {{ $answer->selected_option }} · Correct: {{ $answer->question->correct_option }}</p>
                    @if ($answer->question->explanation)
                        <p class="mt-2 text-sm text-slate-300">{{ $answer->question->explanation }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        @if ($activeExamSession ?? null)
            <a href="{{ route('exam.show', [$sectionSlug, $activeExamSession]) }}" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Continue current quiz</a>
        @else
            <form method="POST" action="{{ route('exam.start', $sectionSlug) }}">
                @csrf
                <button type="submit" class="rounded-xl bg-medic px-5 py-3 font-bold text-white hover:bg-medic-dark">Start new quiz</button>
            </form>
        @endif
        @if ($session->answers->where('is_correct', false)->isNotEmpty())
            <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-xl border border-ems/40 bg-ems/10 px-5 py-3 font-bold text-ems-light hover:bg-ems/20">Review all missed flashcards</a>
        @endif
        <a href="{{ route('platform.home', $sectionSlug) }}" class="rounded-xl border border-white/10 px-5 py-3 font-medium text-slate-200 hover:bg-white/5">Back to {{ $sectionLabel }}</a>
    </div>
@endsection
