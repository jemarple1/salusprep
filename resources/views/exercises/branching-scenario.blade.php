@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-sm font-semibold text-slate-400">Work through each decision — the patient responds to your choices.</p>

    <div id="branch-progress" class="mb-4 flex gap-1">
        @foreach ($scenario['steps'] as $i => $step)
            <div class="branch-dot h-2 flex-1 rounded-full bg-white/10" data-step="{{ $i }}"></div>
        @endforeach
    </div>

    <div id="branch-outcome" class="mb-4 hidden rounded-xl border border-ems/30 bg-ems/10 px-4 py-3 text-sm text-slate-200"></div>

    <div id="branch-step-container"></div>

    <script>
        (function () {
            var steps = @json($scenario['steps']);
            var current = 0;
            var path = [];
            var container = document.getElementById('branch-step-container');
            var outcomeEl = document.getElementById('branch-outcome');

            function updateDots() {
                document.querySelectorAll('.branch-dot').forEach(function (dot, i) {
                    dot.classList.remove('bg-medic', 'bg-rescue', 'bg-white/10');
                    if (i < current) dot.classList.add('bg-medic');
                    else if (i === current) dot.classList.add('bg-ems');
                    else dot.classList.add('bg-white/10');
                });
            }

            function renderStep() {
                if (current >= steps.length) return;
                var step = steps[current];
                outcomeEl.classList.add('hidden');
                container.innerHTML = '<p class="mb-4 text-base font-semibold text-white">' + (step.prompt || step.question || 'What is your next action?') + '</p><div class="grid gap-3" id="branch-options"></div>';
                var opts = document.getElementById('branch-options');
                Object.keys(step.options).forEach(function (key) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'branch-opt rounded-xl border border-white/10 bg-navy/50 p-4 text-left text-sm font-semibold text-slate-200 transition hover:border-medic/40';
                    btn.textContent = step.options[key];
                    btn.dataset.key = key;
                    btn.addEventListener('click', function () { choose(key, step); });
                    opts.appendChild(btn);
                });
                updateDots();
            }

            function choose(key, step) {
                var outcomes = step.outcomes || {};
                var text = outcomes[key] || '';
                if (key !== step.correct) {
                    outcomeEl.classList.remove('hidden', 'border-medic/30', 'bg-medic/10');
                    outcomeEl.classList.add('border-rescue/30', 'bg-rescue/10');
                    outcomeEl.innerHTML = '<p class="font-bold text-red-200">Not the best choice</p><p class="mt-1">' + text + '</p>';
                    return;
                }
                path.push(key);
                if (text) {
                    outcomeEl.classList.remove('hidden', 'border-rescue/30', 'bg-rescue/10');
                    outcomeEl.classList.add('border-medic/30', 'bg-medic/10');
                    outcomeEl.innerHTML = '<p class="font-bold text-medic-light">Good choice</p><p class="mt-1">' + text + '</p>';
                }
                current++;
                if (current >= steps.length) {
                    submitPath();
                    return;
                }
                setTimeout(renderStep, text ? 1200 : 0);
            }

            function submitPath() {
                var payload = { scenario: window.SalusExercise.scenarioIndex, path: path };
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
                        el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Scenario complete' : 'Review your path') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
                        container.innerHTML = '';
                    });
                });
            }

            renderStep();
        })();
    </script>
@endsection
