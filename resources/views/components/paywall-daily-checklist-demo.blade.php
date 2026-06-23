@props([
    'skillLabels' => [],
])

@php
    $skills = collect($skillLabels)->filter()->take(3)->values();

    if ($skills->count() < 3) {
        $fallbacks = ['SOAP charting', 'Triage START', 'Primary assessment'];
        $skills = $skills->pad(3, null)->map(fn ($label, $index) => $label ?? $fallbacks[$index]);
    }

    $items = [
        ['type' => 'skill', 'label' => $skills[0], 'description' => 'Complete at least one scenario in this skill drill.'],
        ['type' => 'skill', 'label' => $skills[1], 'description' => 'Complete at least one scenario in this skill drill.'],
        ['type' => 'skill', 'label' => $skills[2], 'description' => 'Complete at least one scenario in this skill drill.'],
        ['type' => 'quiz', 'label' => 'Adaptive quiz 1', 'description' => 'Take your first 25-question focus or adaptive quiz today.'],
        ['type' => 'quiz', 'label' => 'Adaptive quiz 2', 'description' => 'Take a second quiz to reinforce weak categories.'],
        ['type' => 'mock', 'label' => 'Daily mock exam', 'description' => 'One timed pass/fail mock exam per day — same pressure as test day.'],
    ];

    $totalCount = count($items);
@endphp

<section
    id="paywall-daily-plan-demo"
    class="mb-10 rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8"
    aria-label="Sample daily study checklist"
>
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-medic-light">Sample day · Preview</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Small steps, every day</h2>
            <p class="mt-2 max-w-2xl text-sm text-slate-400">
                Full Access unlocks a welcome page with a fresh daily checklist — three skill drills, two adaptive quizzes, and one mock exam. Bite-sized tasks you can finish, not a vague study to-do list.
            </p>
        </div>
        <div class="rounded-xl border border-medic/30 bg-medic/10 px-5 py-3 text-center min-w-[8rem]">
            <p class="text-3xl font-bold text-medic-light" data-demo-counter>0/{{ $totalCount }}</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">done today</p>
        </div>
    </div>

    <div class="mt-6">
        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
            <span class="font-semibold text-slate-300">Daily progress</span>
            <span class="tabular-nums text-medic-light" data-demo-progress-text>0%</span>
        </div>
        <div class="h-3 overflow-hidden rounded-full bg-white/10">
            <div
                class="h-full rounded-full bg-gradient-to-r from-medic to-medic-light transition-all duration-500"
                data-demo-progress
                style="width: 4%"
            ></div>
        </div>
    </div>

    <ul class="mt-8 space-y-3" data-demo-list>
        @foreach ($items as $index => $item)
            <li
                data-demo-item
                data-index="{{ $index }}"
                class="rounded-xl border border-white/10 bg-navy/40 p-4 transition-colors duration-500"
            >
                <div class="flex flex-wrap items-start gap-4 sm:flex-nowrap">
                    <div
                        data-demo-icon
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/15 bg-navy text-slate-500 transition-colors duration-500"
                    >
                        <span data-demo-pending class="h-2 w-2 rounded-full bg-current" aria-hidden="true"></span>
                        <span data-demo-check class="hidden" aria-hidden="true">✓</span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 data-demo-label class="text-base font-bold text-white transition-colors duration-500">
                                {{ $item['label'] }}
                            </h3>
                            <span class="rounded-full bg-white/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-slate-400">
                                {{ match ($item['type']) {
                                    'skill' => 'Skill',
                                    'quiz' => 'Quiz',
                                    'mock' => 'Mock exam',
                                    default => 'Task',
                                } }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-slate-400">{{ $item['description'] }}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</section>

<script>
    (function () {
        var root = document.getElementById('paywall-daily-plan-demo');
        if (!root) return;

        var items = root.querySelectorAll('[data-demo-item]');
        var progressBar = root.querySelector('[data-demo-progress]');
        var progressText = root.querySelector('[data-demo-progress-text]');
        var counter = root.querySelector('[data-demo-counter]');
        var total = items.length;
        var started = false;
        var completed = 0;
        var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        var stepDelay = prefersReducedMotion ? 0 : 850;

        function markComplete(item) {
            item.classList.remove('border-white/10', 'bg-navy/40');
            item.classList.add('border-medic/30', 'bg-medic/10');

            var icon = item.querySelector('[data-demo-icon]');
            var label = item.querySelector('[data-demo-label]');
            var pending = item.querySelector('[data-demo-pending]');
            var check = item.querySelector('[data-demo-check]');

            if (icon) {
                icon.classList.remove('border-white/15', 'bg-navy', 'text-slate-500');
                icon.classList.add('border-medic', 'bg-medic', 'text-white');
            }

            if (label) {
                label.classList.remove('text-white');
                label.classList.add('text-medic-light');
            }

            if (pending) pending.classList.add('hidden');
            if (check) check.classList.remove('hidden');
        }

        function updateProgress() {
            var percent = total > 0 ? Math.round((completed / total) * 100) : 0;

            if (progressBar) {
                progressBar.style.width = Math.max(percent, completed > 0 ? percent : 4) + '%';
            }

            if (progressText) {
                progressText.textContent = percent + '%';
            }

            if (counter) {
                counter.textContent = completed + '/' + total;
            }
        }

        function runSequence() {
            if (started) return;
            started = true;

            var index = 0;

            function tick() {
                if (index >= total) return;

                markComplete(items[index]);
                completed++;
                updateProgress();
                index++;

                if (index < total) {
                    window.setTimeout(tick, stepDelay);
                }
            }

            window.setTimeout(tick, prefersReducedMotion ? 0 : 350);
        }

        if (prefersReducedMotion) {
            runSequence();
            return;
        }

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        runSequence();
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.35, rootMargin: '0px 0px -8% 0px' });

            observer.observe(root);
            return;
        }

        runSequence();
    })();
</script>
