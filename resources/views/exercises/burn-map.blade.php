@extends('layouts.app')

@section('title', $exercise['title'])

@section('content')
    @include('exercises.partials.header')

    <p class="mb-4 text-sm font-semibold text-slate-400">Tap burned areas on the diagram. Select all regions involved.</p>

    <div class="grid gap-8 lg:grid-cols-2">
        <div class="rounded-2xl border border-white/10 bg-navy/40 p-6">
            <p class="mb-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Anterior</p>
            <svg viewBox="0 0 160 360" class="mx-auto h-auto w-full max-w-[180px]" aria-label="Anterior body diagram">
                <ellipse data-region="head" cx="80" cy="28" rx="26" ry="24" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="chest" x="48" y="54" width="64" height="56" rx="8" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="abdomen" x="52" y="112" width="56" height="48" rx="6" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="arm_l" x="18" y="58" width="24" height="100" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="arm_r" x="118" y="58" width="24" height="100" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="leg_l" x="52" y="168" width="28" height="120" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="leg_r" x="80" y="168" width="28" height="120" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
            </svg>
        </div>
        <div class="rounded-2xl border border-white/10 bg-navy/40 p-6">
            <p class="mb-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500">Posterior</p>
            <svg viewBox="0 0 160 360" class="mx-auto h-auto w-full max-w-[180px]" aria-label="Posterior body diagram">
                <ellipse data-region="head" cx="80" cy="28" rx="26" ry="24" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="back" x="48" y="54" width="64" height="106" rx="8" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="arm_l" x="18" y="58" width="24" height="100" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="arm_r" x="118" y="58" width="24" height="100" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="leg_l" x="52" y="168" width="28" height="120" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
                <rect data-region="leg_r" x="80" y="168" width="28" height="120" rx="10" class="burn-region fill-slate-600/80 stroke-white/30 stroke-2 cursor-pointer transition hover:fill-safety/40" />
            </svg>
        </div>
    </div>

    <div class="mt-8 rounded-2xl border border-white/10 bg-navy-light/80 p-6">
        <p class="mb-4 font-bold text-white">Estimated TBSA (rule of nines)</p>
        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($scenario['percent_options'] as $key => $label)
                <label class="burn-percent flex cursor-pointer items-center gap-3 rounded-xl border border-white/10 bg-navy/40 px-4 py-3 transition hover:border-safety/30 has-[:checked]:border-safety/50 has-[:checked]:bg-safety/10">
                    <input type="radio" name="burn_percent" value="{{ $key }}" class="text-safety">
                    <span class="text-sm font-semibold text-slate-200">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <button type="button" id="burn-submit" class="rounded-xl bg-safety px-8 py-3 font-bold text-navy hover:bg-safety-light">Check answers</button>
        <button type="button" id="burn-clear" class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-300 hover:bg-white/5">Clear</button>
    </div>

    <script>
        (function () {
            var selected = new Set();

            function syncRegion(region) {
                document.querySelectorAll('.burn-region[data-region="' + region + '"]').forEach(function (el) {
                    if (selected.has(region)) {
                        el.classList.add('fill-safety/70', 'stroke-safety');
                        el.classList.remove('fill-slate-600/80');
                    } else {
                        el.classList.remove('fill-safety/70', 'stroke-safety');
                        el.classList.add('fill-slate-600/80');
                    }
                });
            }

            document.querySelectorAll('.burn-region').forEach(function (el) {
                el.addEventListener('click', function () {
                    var region = el.dataset.region;
                    if (selected.has(region)) {
                        selected.delete(region);
                    } else {
                        selected.add(region);
                    }
                    syncRegion(region);
                });
            });

            document.getElementById('burn-clear').addEventListener('click', function () {
                Array.from(selected).forEach(syncRegion);
                selected.clear();
                document.querySelectorAll('input[name="burn_percent"]').forEach(function (input) {
                    input.checked = false;
                });
            });

            document.getElementById('burn-submit').addEventListener('click', function () {
                var percent = document.querySelector('input[name="burn_percent"]:checked');
                if (!percent) {
                    alert('Select a TBSA percentage.');
                    return;
                }

                fetch(window.SalusExercise.checkUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.SalusExercise.csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        scenario: window.SalusExercise.scenarioIndex,
                        regions: Array.from(selected).sort(),
                        answer: percent.value,
                    }),
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        var el = document.getElementById('exercise-feedback');
                        el.classList.remove('hidden', 'border-medic/40', 'bg-medic/10', 'border-rescue/40', 'bg-rescue/10');
                        el.classList.add(data.correct ? 'border-medic/40' : 'border-rescue/40', data.correct ? 'bg-medic/10' : 'bg-rescue/10');
                        el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Correct' : 'Not quite') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
                    });
            });
        })();
    </script>
@endsection
