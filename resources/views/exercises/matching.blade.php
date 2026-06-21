@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-sm font-semibold text-slate-400">{{ $scenario['question'] ?? 'Match each item on the left to the correct option on the right.' }}</p>

    <div class="space-y-4">
        @foreach ($scenario['left'] as $left)
            <div class="flex flex-col gap-2 rounded-xl border border-white/10 bg-navy/40 p-4 sm:flex-row sm:items-center sm:gap-4">
                <p class="flex-1 text-sm font-medium text-slate-200">{{ $left['text'] }}</p>
                <select class="match-select rounded-lg border border-white/10 bg-navy-light px-3 py-2 text-sm text-white" data-left="{{ $left['id'] }}">
                    <option value="">Select match…</option>
                    @foreach ($scenario['right'] as $right)
                        <option value="{{ $right['id'] }}">{{ $right['text'] }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>

    <button type="button" id="match-submit" class="mt-8 rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">Check matches</button>

    <script>
        (function () {
            document.getElementById('match-submit').addEventListener('click', function () {
                var matches = {};
                var complete = true;
                document.querySelectorAll('.match-select').forEach(function (sel) {
                    if (!sel.value) complete = false;
                    matches[sel.dataset.left] = sel.value;
                });
                if (!complete) { alert('Match every item before checking.'); return; }
                var payload = { scenario: window.SalusExercise.scenarioIndex, matches: matches };
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
                        el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'All matches correct' : 'Some matches need review') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
                    });
                });
            });
        })();
    </script>
@endsection
