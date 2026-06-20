@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    @php
        $components = [
            'eye' => [
                4 => 'Spontaneous',
                3 => 'To voice',
                2 => 'To pain',
                1 => 'None',
            ],
            'verbal' => [
                5 => 'Oriented',
                4 => 'Confused',
                3 => 'Inappropriate words',
                2 => 'Incomprehensible',
                1 => 'None',
            ],
            'motor' => [
                6 => 'Obeys commands',
                5 => 'Localizes pain',
                4 => 'Withdraws',
                3 => 'Flexion',
                2 => 'Extension',
                1 => 'None',
            ],
        ];
    @endphp

    <p class="mb-4 text-sm font-semibold text-slate-400">Select the best eye, verbal, and motor score.</p>

    <div class="space-y-6">
        @foreach ($components as $group => $options)
            <div>
                <h3 class="mb-3 text-sm font-bold uppercase tracking-wider text-medic-light">{{ ucfirst($group) }}</h3>
                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($options as $score => $label)
                        <button
                            type="button"
                            data-gcs-group="{{ $group }}"
                            data-gcs-score="{{ $score }}"
                            class="gcs-option rounded-xl border border-white/10 bg-navy/50 px-4 py-3 text-left text-sm text-slate-200 transition hover:border-medic/40"
                        >
                            <span class="font-bold text-medic-light">{{ $score }}</span>
                            <span class="ml-2">{{ $label }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <button type="button" id="gcs-submit" class="mt-8 rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">Check GCS</button>

    <script>
        (function () {
            var selections = { eye: null, verbal: null, motor: null };

            document.querySelectorAll('.gcs-option').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var group = btn.dataset.gcsGroup;
                    selections[group] = parseInt(btn.dataset.gcsScore, 10);
                    document.querySelectorAll('.gcs-option[data-gcs-group="' + group + '"]').forEach(function (b) {
                        b.classList.remove('border-medic', 'bg-medic/20');
                    });
                    btn.classList.add('border-medic', 'bg-medic/20');
                });
            });

            document.getElementById('gcs-submit').addEventListener('click', function () {
                if (!selections.eye || !selections.verbal || !selections.motor) {
                    alert('Select a score for eye, verbal, and motor.');
                    return;
                }

                fetch(window.SalusExercise.checkUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.SalusExercise.csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(Object.assign({
                        scenario: window.SalusExercise.scenarioIndex,
                        eye: selections.eye,
                        verbal: selections.verbal,
                        motor: selections.motor,
                    }, window.SalusExercise.exerciseLevel > 1 ? { level: window.SalusExercise.exerciseLevel } : {})),
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
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
