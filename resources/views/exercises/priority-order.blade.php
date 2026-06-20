@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-sm font-semibold text-slate-400">Drag items into priority order — highest priority at the top.</p>

    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-400">Available</h2>
            <div id="priority-pool" class="min-h-[10rem] space-y-2 rounded-2xl border border-dashed border-white/15 bg-navy/40 p-4">
                @foreach (collect($scenario['items'])->shuffle() as $item)
                    <div class="priority-chip cursor-grab rounded-xl border border-white/10 bg-navy-light px-4 py-3 text-sm text-slate-200 shadow active:cursor-grabbing" draggable="true" data-id="{{ $item['id'] }}">
                        {{ $item['text'] }}
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-medic-light">Priority order</h2>
            <div class="space-y-2">
                @foreach (range(1, count($scenario['items'])) as $rank)
                    <div class="priority-slot flex items-stretch gap-2">
                        <span class="flex w-8 shrink-0 items-center justify-center rounded-lg bg-medic/20 text-sm font-bold text-medic-light">{{ $rank }}</span>
                        <div class="priority-zone min-h-[3rem] flex-1 space-y-2 rounded-xl border border-white/10 bg-navy/50 p-2 transition" data-rank="{{ $rank }}">
                            <p class="priority-placeholder text-center text-xs text-slate-600">Drop here</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <button type="button" id="priority-submit" class="mt-8 rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">Check priority order</button>

    <script>
        (function () {
            var pool = document.getElementById('priority-pool');
            var initialPoolHtml = pool.innerHTML;

            function zones() { return Array.prototype.slice.call(document.querySelectorAll('.priority-zone')); }
            function chips() { return Array.prototype.slice.call(document.querySelectorAll('.priority-chip')); }

            function updatePlaceholders() {
                zones().forEach(function (zone) {
                    var placeholder = zone.querySelector('.priority-placeholder');
                    if (placeholder) placeholder.style.display = zone.querySelector('.priority-chip') ? 'none' : 'block';
                });
            }

            function bindChip(chip) {
                chip.addEventListener('dragstart', function (e) { e.dataTransfer.setData('text/plain', chip.dataset.id); chip.classList.add('opacity-50'); });
                chip.addEventListener('dragend', function () { chip.classList.remove('opacity-50'); });
            }
            chips().forEach(bindChip);

            function bindDropTarget(target) {
                target.addEventListener('dragover', function (e) { e.preventDefault(); target.classList.add('ring-2', 'ring-medic/40'); });
                target.addEventListener('dragleave', function () { target.classList.remove('ring-2', 'ring-medic/40'); });
                target.addEventListener('drop', function (e) {
                    e.preventDefault();
                    target.classList.remove('ring-2', 'ring-medic/40');
                    var chip = document.querySelector('.priority-chip[data-id="' + e.dataTransfer.getData('text/plain') + '"]');
                    if (!chip) return;
                    if (target.classList.contains('priority-zone')) {
                        var existing = target.querySelector('.priority-chip');
                        if (existing && existing !== chip) pool.appendChild(existing);
                        target.appendChild(chip);
                    } else {
                        target.appendChild(chip);
                    }
                    updatePlaceholders();
                });
            }
            zones().concat([pool]).forEach(bindDropTarget);

            document.getElementById('priority-submit').addEventListener('click', function () {
                var order = [];
                zones().sort(function (a, b) { return parseInt(a.dataset.rank, 10) - parseInt(b.dataset.rank, 10); }).forEach(function (zone) {
                    var chip = zone.querySelector('.priority-chip');
                    if (chip) order.push(chip.dataset.id);
                });

                if (order.length < zones().length) {
                    alert('Place one item in each priority slot.');
                    return;
                }

                var payload = { scenario: window.SalusExercise.scenarioIndex, order: order };
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
                        el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Correct priority order' : 'Not quite') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
                    });
                });
            });
            updatePlaceholders();
        })();
    </script>
@endsection
