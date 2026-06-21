@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-base font-semibold text-white">{{ $scenario['question'] ?? 'Select all that apply.' }}</p>

    <div class="grid gap-3" id="multi-options">
        @foreach ($scenario['options'] as $key => $label)
            <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-white/10 bg-navy/50 p-4 transition has-[:checked]:border-medic has-[:checked]:bg-medic/10">
                <input type="checkbox" name="multi" value="{{ $key }}" class="mt-1 rounded text-medic focus:ring-medic">
                <span class="text-sm text-slate-200">{{ $label }}</span>
            </label>
        @endforeach
    </div>

    <button type="button" id="multi-submit" class="mt-8 rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">Check answers</button>

    <script>
        (function () {
            document.getElementById('multi-submit').addEventListener('click', function () {
                var answers = [];
                document.querySelectorAll('#multi-options input:checked').forEach(function (cb) { answers.push(cb.value); });
                if (answers.length === 0) { alert('Select at least one option.'); return; }
                var payload = { scenario: window.SalusExercise.scenarioIndex, answers: answers };
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
