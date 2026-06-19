@props([
    'exercise',
    'scenario',
    'scenarioIndex',
    'scenarioLinks',
    'scenarioCount' => null,
    'completedCount' => 0,
    'exerciseLevel' => 1,
    'levelLinks' => [],
])

<div class="mb-6">
    <a href="{{ route('skills.index', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← Skills</a>
    <p class="mt-2 text-sm font-bold uppercase tracking-wider text-ems-light">{{ $exercise['category'] }}</p>
    <h1 class="mt-1 text-3xl font-bold text-white">{{ $exercise['title'] }}</h1>
    <p class="mt-2 max-w-3xl text-slate-400">{{ $scenario['title'] ?? $exercise['description'] }}</p>
</div>

<x-exercise-scenario-nav
    :scenario-links="$scenarioLinks"
    :scenario-index="$scenarioIndex"
    :scenario-count="$scenarioCount ?? count($scenarioLinks)"
    :completed-count="$completedCount ?? 0"
    :exercise-slug="$exercise['slug']"
    :section-slug="$sectionSlug"
    :level-links="$levelLinks ?? []"
    :exercise-level="$exerciseLevel ?? 1"
/>

<div class="mb-6 rounded-2xl border border-white/10 bg-navy-light/80 p-6">
    <p class="text-sm font-bold uppercase tracking-wider text-slate-500">Scenario</p>
    <p class="mt-2 text-lg leading-relaxed text-slate-200">{{ $scenario['scenario'] ?? $scenario['prompt'] ?? '' }}</p>
    @if (! empty($scenario['detail']))
        <p class="mt-3 text-sm leading-relaxed text-slate-400">{{ $scenario['detail'] }}</p>
    @endif
</div>

<div id="exercise-feedback" class="mb-6 hidden rounded-2xl border px-6 py-5"></div>

<script>
    window.SalusExercise = {
        checkUrl: @json(route('exercises.check', [$sectionSlug, $exercise['slug']])),
        scenarioIndex: @json($scenarioIndex),
        exerciseLevel: @json($exerciseLevel ?? 1),
        csrf: @json(csrf_token()),
        afterCheck: function (data, callback) {
            if (data.paywall_url) {
                window.location.href = data.paywall_url;
                return;
            }
            if (callback) {
                callback(data);
            }
            if (data.correct && data.next_scenario_url) {
                var el = document.getElementById('exercise-feedback');
                if (el && !el.querySelector('[data-next-scenario]')) {
                    var label = data.level_complete ? 'Level complete — next level →' : 'Next scenario →';
                    el.insertAdjacentHTML('beforeend',
                        '<a href="' + data.next_scenario_url + '" data-next-scenario class="mt-4 inline-flex items-center gap-2 rounded-xl bg-medic px-5 py-2.5 text-sm font-bold text-white hover:bg-medic-dark">' + label + '</a>'
                    );
                }
            }
        },
    };
</script>
