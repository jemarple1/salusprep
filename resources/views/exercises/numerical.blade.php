@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @if (! empty($scenario['drug']))
        <div class="mb-4 inline-flex rounded-full border border-pharma/30 bg-pharma/10 px-4 py-1.5 text-sm font-bold text-pharma-light">
            {{ $scenario['drug'] }}
        </div>
    @endif

    <p class="mb-6 text-lg leading-relaxed text-slate-200">{{ $scenario['question'] ?? $scenario['prompt'] ?? '' }}</p>

    <div class="flex max-w-md items-center gap-3">
        <input type="number" step="any" id="numerical-answer" class="w-full rounded-xl border border-white/10 bg-navy-light px-4 py-3 text-lg font-mono text-white focus:border-medic/50 focus:outline-none focus:ring-2 focus:ring-medic/20" placeholder="Enter value">
        @if (! empty($scenario['unit']))
            <span class="shrink-0 text-sm font-bold text-slate-400">{{ $scenario['unit'] }}</span>
        @endif
    </div>

    <button type="button" id="numerical-submit" class="mt-6 rounded-xl bg-pharma px-8 py-3 font-bold text-white hover:bg-pharma-dark">Check answer</button>

    <script>
        (function () {
            document.getElementById('numerical-submit').addEventListener('click', function () {
                var val = document.getElementById('numerical-answer').value;
                if (val === '') { alert('Enter a numerical answer.'); return; }
                var payload = { scenario: window.SalusExercise.scenarioIndex, answer: parseFloat(val) };
                if (window.SalusExercise.exerciseLevel > 1) payload.level = window.SalusExercise.exerciseLevel;
                fetch(window.SalusExercise.checkUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.SalusExercise.csrf, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                }).then(function (r) { return r.json(); }).then(function (data) {
                    window.SalusExercise.afterCheck(data, function (data) {
                        var el = document.getElementById('exercise-feedback');
                        el.classList.remove('hidden', 'border-medic/40', 'bg-medic/10', 'border-rescue/40', 'bg-rescue/10');
                        el.classList.add(data.correct ? 'border-medic/40' : 'border-rescue/40', data.correct ? 'bg-medic/10' : 'bg-rescue/10');
                        var extra = data.expected !== undefined ? ' Expected: ' + data.expected + (data.unit ? ' ' + data.unit : '') + '.' : '';
                        el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Correct' : 'Not quite') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + extra + '</p>';
                    });
                });
            });
        })();
    </script>
@endsection
