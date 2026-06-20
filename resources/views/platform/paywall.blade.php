@extends('layouts.app')

@section('title', 'Unlock '.$sectionLabel)

@section('content')
    @php
        $firstName = $learnerName ? explode(' ', trim($learnerName))[0] : null;
        $flashcardPreview = $flashcardPreviews->first();
    @endphp

    <div class="relative mx-auto max-w-4xl overflow-hidden">
        {{-- Hero --}}
        <div class="mb-10 rounded-2xl border border-white/10 bg-navy-light p-8 sm:p-10">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div>
                    @if ($previewExpired)
                        <p class="text-sm font-semibold text-safety-light">{{ $sectionLabel }} · Preview ended</p>
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
                        @if ($firstName)
                            {{ $firstName }}, continue studying with personalized resources
                        @else
                            Continue studying with personalized resources
                        @endif
                    </h1>

                    <p class="mt-4 text-base leading-relaxed text-slate-300">
                        @if (! $previewExpired && $previewRemainingMinutes > 0)
                            You still have free preview time left on SalusPrep. When your {{ $previewMinutesLimit }}-minute preview window ends, you'll need Full Access to keep practicing {{ $sectionLabel }}.
                        @elseif ($topWeakCategory)
                            Your Preview mapped your strengths and gaps — we'll keep building from where you left off, starting with <strong class="text-white">{{ $topWeakCategory->category }}</strong>. Pick a focus exam below, review with flashcards, and practice skills until you're exam-ready.
                        @elseif ($totalMissed > 0)
                            Your missed questions become flashcards, your weak categories become focus exams, and your progress stays with you through every recertification. Unlock Full Access to keep going without limits.
                        @else
                            Flashcards from your misses, focus exams weighted to your weak topics, and hands-on skills — all tailored to how you learn. Unlock Full Access to pick up right where Preview left off.
                        @endif
                    </p>
                </div>

                <div class="flex items-center justify-center lg:justify-end">
                    <x-paywall-hero-graphic />
                </div>
            </div>
        </div>

        {{-- Category progress bars --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-white">What needs work</h2>
            <p class="mt-1 text-sm text-slate-400">Your category breakdown from Preview.</p>

            @if ($categoryStats->isEmpty())
                <p class="mt-4 text-sm text-slate-400">Complete a quiz during Preview and we'll map your strengths and opportunities by topic.</p>
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

        {{-- Focus exam selection --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-white">Test what you've studied</h2>
            <p class="mt-2 mb-8 max-w-2xl text-sm leading-relaxed text-slate-400">Choose one focus exam to start next. Each quiz is 25 questions — 75% from that topic, 25% mixed.</p>

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
