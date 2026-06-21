@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-sm font-semibold text-slate-400">{{ $scenario['instruction'] ?? 'Drag each item into the correct category.' }}</p>

    <div class="grid gap-6 lg:grid-cols-[1fr_1.2fr]">
        <div>
            <h2 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-400">Items</h2>
            <div id="sort-pool" class="min-h-[12rem] space-y-2 rounded-2xl border border-dashed border-white/10 bg-navy/40 p-4">
                @foreach (collect($scenario['sentences'])->shuffle() as $sentence)
                    <div class="sort-chip cursor-grab rounded-xl border border-white/10 bg-navy-light px-4 py-3 text-sm text-slate-200 shadow active:cursor-grabbing" draggable="true" data-id="{{ $sentence['id'] }}">
                        {{ $sentence['text'] }}
                    </div>
                @endforeach
            </div>
            @if (isset($scenario['sections']['X']))
                <div class="mt-4">
                    <h3 class="mb-2 text-sm font-bold text-slate-400">Not applicable</h3>
                    <div class="sort-zone min-h-[4rem] space-y-2 rounded-2xl border border-dashed border-slate-600/50 bg-slate-900/40 p-3" data-section="X">
                        <p class="sort-placeholder text-center text-xs text-slate-600">Drop here if not applicable</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-3">
            @foreach ($scenario['sections'] as $key => $label)
                @if ($key === 'X')
                    @continue
                @endif
                <div>
                    <h3 class="mb-2 text-sm font-bold text-white">{{ $label }}</h3>
                    <div class="sort-zone min-h-[4rem] space-y-2 rounded-2xl border border-white/10 bg-navy/50 p-3 transition" data-section="{{ $key }}">
                        <p class="sort-placeholder text-center text-xs text-slate-600">Drop items here</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button type="button" id="sort-check" class="mt-8 rounded-xl bg-medic px-8 py-3 font-bold text-white hover:bg-medic-dark">Check categories</button>

    <script>
        (function () {
            var pool = document.getElementById('sort-pool');
            function zones() { return Array.prototype.slice.call(document.querySelectorAll('.sort-zone')); }
            function chips() { return Array.prototype.slice.call(document.querySelectorAll('.sort-chip')); }
            function updatePlaceholders() {
                zones().forEach(function (zone) {
                    var ph = zone.querySelector('.sort-placeholder');
                    if (ph) ph.style.display = zone.querySelector('.sort-chip') ? 'none' : 'block';
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
                    if (!chip) return;
                    target.appendChild(chip);
                    updatePlaceholders();
                });
            }
            zones().concat([pool]).forEach(bindDropTarget);
            updatePlaceholders();

            document.getElementById('sort-check').addEventListener('click', function () {
                var placements = {};
                document.querySelectorAll('.sort-chip').forEach(function (chip) {
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
                        var el = document.getElementById('exercise-feedback');
                        el.classList.remove('hidden', 'border-medic/40', 'bg-medic/10', 'border-rescue/40', 'bg-rescue/10');
                        el.classList.add(data.correct ? 'border-medic/40' : 'border-rescue/40', data.correct ? 'bg-medic/10' : 'bg-rescue/10');
                        el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'All categorized correctly' : 'Review your categories') + '</p><p class="mt-2 text-sm text-slate-200">' + (data.explanation || '') + '</p>';
                    });
                });
            });
        })();
    </script>
@endsection
