@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <div class="grid gap-6 lg:grid-cols-[1fr_1.2fr]">
        <div>
            <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-400">Nursing statements</h2>
            <p class="mb-4 text-xs text-slate-500">Drag each statement into the correct ADPIE phase, or into trash if it does not belong.</p>
            <div id="sort-pool" class="min-h-[12rem] space-y-2 rounded-2xl border border-dashed border-white/15 bg-navy/40 p-4">
                @foreach (collect($scenario['sentences'])->shuffle() as $sentence)
                    <div class="sort-chip cursor-grab rounded-xl border border-white/10 bg-navy-light px-4 py-3 text-sm text-slate-200 shadow active:cursor-grabbing" draggable="true" data-id="{{ $sentence['id'] }}">
                        {{ $sentence['text'] }}
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-400">Trash — not part of the process</h3>
                    <span class="text-xs text-slate-500 sort-count" data-section="X">0</span>
                </div>
                <div class="sort-zone min-h-[5rem] space-y-2 rounded-2xl border border-dashed border-slate-600/50 bg-slate-900/40 p-3 transition" data-section="X">
                    <p class="sort-placeholder text-center text-xs text-slate-600">Drop irrelevant statements here</p>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            @foreach ($scenario['sections'] as $key => $label)
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white">{{ $key }} — {{ $label }}</h3>
                        <span class="text-xs text-slate-500 sort-count" data-section="{{ $key }}">0</span>
                    </div>
                    <div class="sort-zone min-h-[4.5rem] space-y-2 rounded-2xl border border-white/10 bg-navy/50 p-3 transition" data-section="{{ $key }}">
                        <p class="sort-placeholder text-center text-xs text-slate-600">Drop statements here</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-8 flex flex-wrap items-center gap-4">
        <button type="button" id="sort-check" class="rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">Check answers</button>
        <button type="button" id="sort-reset" class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-300 hover:bg-white/5">Reset</button>
        <p id="sort-result" class="text-sm font-semibold"></p>
    </div>

    <script>
        (function () {
            var pool = document.getElementById('sort-pool');
            var initialPoolHtml = pool.innerHTML;

            function zones() { return Array.prototype.slice.call(document.querySelectorAll('.sort-zone')); }
            function chips() { return Array.prototype.slice.call(document.querySelectorAll('.sort-chip')); }

            function updatePlaceholders() {
                zones().forEach(function (zone) {
                    var placeholder = zone.querySelector('.sort-placeholder');
                    var hasChips = zone.querySelector('.sort-chip');
                    if (placeholder) placeholder.style.display = hasChips ? 'none' : 'block';
                    var counter = document.querySelector('.sort-count[data-section="' + zone.dataset.section + '"]');
                    if (counter) counter.textContent = String(zone.querySelectorAll('.sort-chip').length);
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
                    var chip = document.querySelector('.sort-chip[data-id="' + e.dataTransfer.getData('text/plain') + '"]');
                    if (chip) { target.appendChild(chip); updatePlaceholders(); }
                });
            }
            zones().concat([pool]).forEach(bindDropTarget);

            document.getElementById('sort-reset').addEventListener('click', function () {
                pool.innerHTML = initialPoolHtml;
                zones().forEach(function (zone) {
                    while (zone.querySelector('.sort-chip')) {
                        pool.appendChild(zone.querySelector('.sort-chip'));
                    }
                });
                chips().forEach(bindChip);
                document.getElementById('sort-result').textContent = '';
                updatePlaceholders();
            });

            document.getElementById('sort-check').addEventListener('click', function () {
                var placements = {};
                chips().forEach(function (chip) {
                    var zone = chip.closest('.sort-zone');
                    if (zone) placements[chip.dataset.id] = zone.dataset.section;
                });

                var payload = { scenario: window.SalusExercise.scenarioIndex, placements: placements };
                if (window.SalusExercise.exerciseLevel > 1) payload.level = window.SalusExercise.exerciseLevel;

                fetch(window.SalusExercise.checkUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.SalusExercise.csrf, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                }).then(function (r) { return r.json(); }).then(function (data) {
                    window.SalusExercise.afterCheck(data, function (data) {
                        chips().forEach(function (chip) {
                            chip.classList.remove('border-medic/50', 'border-rescue/50');
                            var result = data.results[chip.dataset.id];
                            chip.classList.add(result && result.correct ? 'border-medic/50' : 'border-rescue/50');
                        });
                        var el = document.getElementById('sort-result');
                        el.textContent = data.correct ? 'Perfect — all statements placed correctly.' : 'Score: ' + data.score + ' / ' + data.total + '.';
                        el.className = 'text-sm font-semibold ' + (data.correct ? 'text-medic-light' : 'text-safety-light');
                        if (data.explanation) {
                            var fb = document.getElementById('exercise-feedback');
                            if (fb) {
                                fb.classList.remove('hidden', 'border-medic/40', 'bg-medic/10', 'border-rescue/40', 'bg-rescue/10');
                                fb.classList.add(data.correct ? 'border-medic/40' : 'border-rescue/40', data.correct ? 'bg-medic/10' : 'bg-rescue/10');
                                fb.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Correct' : 'Not quite') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
                            }
                        }
                    });
                });
            });
            updatePlaceholders();
        })();
    </script>
@endsection
