@extends('layouts.app')

@section('meta_title', \App\Support\PageSeo::platformPageTitle($sectionLevel, 'Daily Mock Exam'))

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-safety-light">Daily mock exam</p>
            <h1 class="mt-1 text-2xl font-bold text-white">Question {{ $questionNumber }}</h1>
            <p class="mt-2 text-sm text-slate-400">
                Adaptive exam — no scores or answer review until the system determines an outcome.
            </p>
        </div>
        <div
            id="mock-exam-timer"
            class="rounded-xl border border-safety/40 bg-safety/15 px-4 py-3 text-center"
            data-remaining="{{ $remainingSeconds }}"
        >
            <p class="text-xs font-bold uppercase tracking-wider text-safety-light">Time remaining</p>
            <p id="mock-exam-timer-value" class="mt-1 font-mono text-2xl font-bold text-white">--:--:--</p>
        </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
        <div class="mb-4">
            <span @class([
                'rounded-full px-3 py-1 text-xs font-bold uppercase',
                \App\Support\QuestionCategory::styles($question->category)['badge'],
            ])>{{ $question->category }}</span>
        </div>

        <p class="text-lg leading-relaxed text-white">{{ $question->stem }}</p>

        <form method="POST" action="{{ route('mock-exam.answer', [$sectionSlug, $session, $question]) }}" class="mt-8 space-y-3" id="mock-answer-form">
            @csrf

            @foreach ($question->options() as $letter => $text)
                <label class="flex cursor-pointer items-start gap-4 rounded-xl border border-white/10 bg-navy/60 px-4 py-4 transition hover:border-safety/40 has-[:checked]:border-safety has-[:checked]:bg-safety/10">
                    <input type="radio" name="selected_option" value="{{ $letter }}" required class="mt-1 text-safety focus:ring-safety">
                    <span><span class="font-bold text-safety-light">{{ $letter }}.</span> <span class="text-slate-200">{{ $text }}</span></span>
                </label>
            @endforeach

            <div class="pt-4">
                <button type="submit" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">Submit answer</button>
            </div>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-500">
        Minimum 70 questions · up to 140 · 2-hour limit. The exam ends when competency is assessed or time expires.
    </p>

    <x-welcome-return-link />

    <script>
        (function () {
            var timerRoot = document.getElementById('mock-exam-timer');
            var timerValue = document.getElementById('mock-exam-timer-value');
            var form = document.getElementById('mock-answer-form');
            if (!timerRoot || !timerValue) return;

            var remaining = parseInt(timerRoot.getAttribute('data-remaining') || '0', 10);

            function format(seconds) {
                var h = Math.floor(seconds / 3600);
                var m = Math.floor((seconds % 3600) / 60);
                var s = seconds % 60;
                return [h, m, s].map(function (n) { return String(n).padStart(2, '0'); }).join(':');
            }

            function tick() {
                timerValue.textContent = format(remaining);
                if (remaining <= 300) {
                    timerRoot.classList.add('border-rescue/50', 'bg-rescue/15');
                }
                if (remaining <= 0) {
                    window.location.reload();
                    return;
                }
                remaining--;
            }

            tick();
            setInterval(tick, 1000);

            if (form) {
                form.addEventListener('submit', function () {
                    form.querySelector('button[type="submit"]').disabled = true;
                });
            }
        })();
    </script>
@endsection
