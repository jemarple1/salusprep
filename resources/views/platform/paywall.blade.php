@extends('layouts.app')

@section('meta_robots', 'noindex, follow')
@section('meta_title', \App\Support\PageSeo::platformPageTitle($sectionLevel, 'Full Access'))

@section('content')
    @php
        $firstName = $learnerName ? explode(' ', trim($learnerName))[0] : null;
        $flashcardPreview = $flashcardPreviews->first();
        $focusArea = $pinnedFocus ?? $topWeakCategory?->category ?? null;
    @endphp

    <div class="relative mx-auto max-w-4xl overflow-hidden">
        {{-- Hero --}}
        <div class="mb-10 rounded-2xl border border-white/10 bg-navy-light p-8 sm:p-10">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    @if ($previewExpired)
                        <p class="text-sm font-semibold text-safety-light">{{ $sectionLabel }} · Free preview ended</p>
                    @elseif ($previewRemainingMinutes > 0)
                        <p class="text-sm font-semibold text-ems-light">
                            {{ $sectionLabel }} · Preview ends in
                            <span id="paywall-preview-countdown">{{ $previewRemainingMinutes }}</span>
                            <span id="paywall-preview-countdown-unit">{{ $previewRemainingMinutes === 1 ? 'minute' : 'minutes' }}</span>
                        </p>
                    @else
                        <p class="text-sm font-semibold text-safety-light">{{ $sectionLabel }} · Preview ending soon</p>
                    @endif

                    <h1 class="mt-3 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                        Keep the momentum going
                    </h1>

                    @if ($focusArea)
                        <p class="mt-2 text-lg font-semibold text-slate-300 sm:text-xl">
                            Start with {{ $focusArea }}
                        </p>
                    @endif

                    <p class="mt-4 text-base leading-relaxed text-slate-300">
                        @if (! $previewExpired && $previewRemainingMinutes > 0)
                            You still have free preview time on SalusPrep. When your {{ $previewMinutesLimit }}-minute window ends, Full Access is how you keep unlimited {{ $sectionLabel }} practice — quizzes, flashcards, skills, and Test Center.
                        @elseif ($previewExpired && $topWeakCategory)
                            Preview showed where {{ $sectionLabel }} will test you hardest: <strong class="text-white">{{ $topWeakCategory->category }}</strong>. Full Access turns that into focus exams weighted to your gaps, flashcards from every miss, and skills drills — the same adaptive loop you&rsquo;ll face on exam day.
                        @elseif ($previewExpired && $totalMissed > 0)
                            <strong class="text-white">{{ number_format($totalMissed) }} missed questions</strong> are already in your flashcard deck. Unlock Full Access to keep reviewing them, add focus exams on your weak categories, and practice without a timer running out.
                        @elseif ($previewExpired && $platformInsights)
                            {{ $platformInsights['struggle_intro'] }} Full Access keeps your prep going — unlimited adaptive quizzes, flashcards built from your misses, focus exams on weak topics, and hands-on skills. One payment, no subscription, prep until you&rsquo;re ready.
                        @elseif ($previewExpired)
                            SalusPrep already started mapping how you test under pressure. Full Access keeps that progress going — unlimited adaptive quizzes, flashcards built from your misses, focus exams on weak topics, and hands-on skills. One payment, no subscription, prep until you&rsquo;re ready.
                        @elseif ($topWeakCategory)
                            Preview mapped a clear starting point: <strong class="text-white">{{ $topWeakCategory->category }}</strong>. Pick a focus exam below, review with flashcards, and practice skills until you&rsquo;re exam-ready.
                        @elseif ($totalMissed > 0)
                            Your missed questions are already saved as flashcards, and your weak categories are queued for focus exams. Unlock Full Access to keep that momentum through every recertification.
                        @else
                            Take a quick quiz during Preview and SalusPrep will map your strengths, your gaps, and the focus exams worth doing next.
                        @endif
                    </p>
                </div>

                <div class="flex items-center justify-center lg:justify-end">
                    <x-paywall-hero-graphic />
                </div>
            </div>
        </div>

        @if ($platformInsights)
            <section class="mb-10">
                <h2 class="text-2xl font-bold text-white">Where {{ $sectionLabel }} candidates struggle</h2>
                <p class="mt-1 text-sm text-slate-400">{{ $platformInsights['struggle_intro'] }}</p>

                <div class="mt-4 space-y-3">
                    @foreach ($platformInsights['struggles'] as $struggle)
                        <div class="rounded-lg border border-white/10 bg-navy px-4 py-3">
                            <p class="text-sm font-semibold text-white">{{ $struggle['topic'] }}</p>
                            <p class="mt-1 text-sm text-slate-400">{{ $struggle['detail'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="mb-10">
                <h2 class="text-2xl font-bold text-white">How SalusPrep helps you succeed</h2>
                <p class="mt-1 text-sm text-slate-400">{{ $platformInsights['help_intro'] }}</p>

                <ul class="mt-4 space-y-3 text-sm text-slate-300">
                    @foreach ($platformInsights['helps'] as $help)
                        <li class="flex gap-3">
                            <span class="mt-0.5 font-bold text-medic-light">✓</span>
                            <span>{{ $help }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        {{-- Category progress bars --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-white">What needs work</h2>
            <p class="mt-1 text-sm text-slate-400">Your category breakdown from Preview.</p>

            @if ($categoryStats->isEmpty())
                @if ($platformInsights)
                    <p class="mt-4 text-sm text-slate-400">Take a quiz during Preview and we&rsquo;ll replace these platform insights with your personal category breakdown.</p>
                @else
                    <p class="mt-4 text-sm text-slate-400">Complete a quiz during Preview and we'll map your strengths and opportunities by topic.</p>
                @endif
            @else
                <div class="mt-4 space-y-2">
                    @foreach ($categoryStats as $stat)
                        @php
                            $styles = \App\Support\QuestionCategory::styles($stat->category);
                            $missedInCategory = $wrongByCategory[$stat->category] ?? 0;
                        @endphp
                        <div class="rounded-lg border border-white/10 bg-navy px-3 py-2">
                            <div class="flex items-center gap-3">
                                <span class="w-28 shrink-0 truncate rounded px-1.5 py-0.5 text-[10px] font-bold uppercase {{ $styles['badge'] }}">{{ $stat->category }}</span>
                                <div class="min-w-0 flex-1">
                                    <div class="h-1.5 overflow-hidden rounded-full bg-white/10">
                                        <div class="{{ $styles['bar'] }} h-full rounded-full transition-all" style="width: {{ max($stat->accuracy_percent, 2) }}%"></div>
                                    </div>
                                </div>
                                <span class="shrink-0 text-xs tabular-nums text-slate-400">
                                    <strong class="text-white">{{ $stat->accuracy_percent }}%</strong>
                                    · {{ $stat->total }}q
                                    @if ($missedInCategory > 0)
                                        · {{ $missedInCategory }} review
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Flashcard + benefits --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-white">Study &amp; improve</h2>
            <p class="mt-2 text-sm text-slate-400">Missed questions become flashcards — hover to flip and read the rationales.</p>

            <div class="mt-6 grid gap-8 lg:grid-cols-2 lg:items-center">
                @if ($flashcardPreview)
                    <x-paywall-flashcard
                        :preview="$flashcardPreview"
                        card-id="paywall-card-hero"
                        flip-on-hover
                    />
                @else
                    <div class="rounded-xl border border-dashed border-white/15 bg-navy px-6 py-16 text-center">
                        <p class="text-3xl">🃏</p>
                        <p class="mt-3 text-sm font-semibold text-white">Your deck builds as you quiz</p>
                        <p class="mt-1 text-xs text-slate-400">Every wrong answer becomes a flashcard with the full explanation on the back.</p>
                    </div>
                @endif

                <ul class="space-y-4 text-sm text-slate-300">
                    <li class="flex gap-3">
                        <span class="mt-0.5 font-bold text-medic-light">✓</span>
                        <span><strong class="text-white">Automatic deck building</strong> — every missed quiz question is saved to your personal flashcard deck.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 font-bold text-medic-light">✓</span>
                        <span><strong class="text-white">Full explanations on the back</strong> — rationales written for real exam prep, not just the correct letter.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 font-bold text-medic-light">✓</span>
                        <span><strong class="text-white">Study by category</strong> — filter your deck to the topics that need the most work.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-0.5 font-bold text-medic-light">✓</span>
                        <span><strong class="text-white">Synced to your account</strong> — your deck follows you through every recertification cycle.</span>
                    </li>
                </ul>
            </div>
        </section>

        <x-paywall-daily-checklist-demo :skill-labels="collect($exerciseCards)->pluck('title')->all()" />

        {{-- Focus exam selection --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-white">Let's choose your next quiz</h2>
            <p class="mt-2 mb-8 max-w-2xl text-sm leading-relaxed text-slate-400">Pick a focus topic to start with. Each quiz is 25 questions — 75% from that topic, 25% mixed.</p>

            @if ($weakCategories->isNotEmpty())
                <x-focus-exam-picker
                    :options="$weakCategories"
                    :pinned-focus="$pinnedFocus"
                />
                <p class="mt-3 text-xs text-slate-500">Your selection is saved to your profile when you create an account or unlock Full Access.</p>
            @else
                <div class="mt-6 rounded-2xl border border-white/10 bg-navy-light p-6 text-center">
                    <p class="text-sm text-slate-400">After Preview, we'll recommend focus exams tailored to your weakest topics.</p>
                </div>
            @endif
        </section>
    </div>

    {{-- Full-width auto-scrolling exercise carousel --}}
    @if ($exerciseCards !== [])
        <section class="relative mb-10 mt-2 w-full">
            <div class="mx-auto mb-5 max-w-4xl">
                <h2 class="text-2xl font-bold text-white">Round out your learning</h2>
                <p class="mt-2 text-sm text-slate-400">Hands-on skill exercises for SOAP charting, triage, GCS, burns, stroke scales, vitals, pharmacology, and more.</p>
            </div>
            <div class="relative left-1/2 w-screen max-w-[100vw] -translate-x-1/2 overflow-hidden">
                <x-exercise-carousel
                    :exercises="$exerciseCards"
                    :showcase="true"
                    :autoplay="true"
                    carousel-id="paywall-exercise-carousel"
                />
            </div>
        </section>
    @endif

    <div class="relative mx-auto max-w-4xl overflow-hidden">
        <x-paywall-checkout-card
            :requires-auth="$requiresAuth"
            :first-name="$firstName"
            :top-weak-category="$topWeakCategory"
            :total-missed="$totalMissed"
            :overall-stats="$overallStats"
            :preview-expired="$previewExpired"
            :preview-remaining-minutes="$previewRemainingMinutes"
        />
    </div>

    @if (! $previewExpired)
        <script>
            (function () {
                var countdown = document.getElementById('paywall-preview-countdown');
                var expiresAt = @json($previewExpiresAt);
                if (!countdown || !expiresAt) return;

                function tick() {
                    var remainingSeconds = Math.max(0, Math.ceil((new Date(expiresAt).getTime() - Date.now()) / 1000));
                    var minutes = Math.ceil(remainingSeconds / 60);
                    var unit = document.getElementById('paywall-preview-countdown-unit');
                    countdown.textContent = String(minutes);
                    if (unit) {
                        unit.textContent = minutes === 1 ? 'minute' : 'minutes';
                    }
                }

                tick();
                window.setInterval(tick, 1000);
            })();
        </script>
    @endif
@endsection
