@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-sm font-semibold text-slate-400">{{ $scenario['instruction'] ?? 'Select the correct score for each category.' }}</p>

    <div class="space-y-6">
        @foreach ($scenario['subscales'] as $key => $subscale)
            <div>
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-medic-light">{{ $subscale['label'] }}</h3>
                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($subscale['options'] as $score => $label)
                        <button
                            type="button"
                            data-scale-key="{{ $key }}"
                            data-scale-score="{{ $score }}"
                            class="scale-option rounded-xl border border-white/10 bg-navy/50 px-4 py-3 text-left text-sm text-slate-200 transition hover:border-medic/40"
                        >
                            <span class="font-bold text-medic-light">{{ $score }}</span>
                            <span class="ml-2">{{ $label }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <button type="button" id="scale-submit" class="mt-8 rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">Check scores</button>

    <script>
        (function () {
            var selections = {};

            document.querySelectorAll('.scale-option').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var key = btn.dataset.scaleKey;
                    selections[key] = parseInt(btn.dataset.scaleScore, 10);
                    document.querySelectorAll('.scale-option[data-scale-key="' + key + '"]').forEach(function (b) {
                        b.classList.remove('border-medic', 'bg-medic/20');
                    });
                    btn.classList.add('border-medic', 'bg-medic/20');
                });
            });

            document.getElementById('scale-submit').addEventListener('click', function () {
                var keys = @json(array_keys($scenario['subscales']));
                for (var i = 0; i < keys.length; i++) {
                    if (!selections[keys[i]]) {
                        alert('Select a score for every category.');
                        return;
                    }
                }

                var payload = { scenario: window.SalusExercise.scenarioIndex, scores: selections };
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
                        el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Correct' : 'Not quite') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
                    });
                });
            });
        })();
    </script>
@endsection
