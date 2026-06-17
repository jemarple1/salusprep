@extends('layouts.app')

@section('title', $sectionLabel)

@section('content')
    @if (request('checkout') === 'success')
        <div class="mb-6 rounded-xl border border-medic/40 bg-medic/10 px-4 py-3 text-sm font-medium text-medic-light">
            Payment successful! If access isn’t active yet, wait a moment and refresh.
        </div>
    @endif

    <section class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
        <div class="space-y-6">
            <p class="inline-flex rounded-full border border-medic/40 bg-medic/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-medic-light">
                {{ $sectionLabel }} only
            </p>
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                Adaptive NREMT practice for {{ $sectionLabel }}.
            </h1>
            <p class="max-w-xl text-lg leading-relaxed text-slate-300">
                {{ $sectionDescription }}
                Start right away — no account needed for your first 25 questions.
            </p>

            <div class="flex flex-wrap gap-3">
                @if ($activeSession)
                    @if ($activeSession->requiresPayment())
                        <a href="{{ route('exam.paywall', [$sectionSlug, $activeSession]) }}" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                            Continue
                        </a>
                    @else
                        <a href="{{ route('exam.show', [$sectionSlug, $activeSession]) }}" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">
                            Resume quiz
                        </a>
                    @endif
                @elseif ($unlocked || $freeRemaining > 0)
                    <form method="POST" action="{{ route('exam.start', $sectionSlug) }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-medic px-6 py-3 font-bold text-white hover:bg-medic-dark">
                            Start adaptive quiz
                        </button>
                    </form>
                @elseif (auth()->check())
                    <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}">
                        @csrf
                        <button type="submit" class="rounded-xl bg-safety px-6 py-3 font-bold text-navy hover:bg-safety-light">
                            Unlock unlimited — $8.99
                        </button>
                    </form>
                @endif

                @auth
                    <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-xl border border-white/10 px-6 py-3 font-semibold text-slate-200 hover:bg-white/5">
                        Dashboard
                    </a>
                @else
                    <p class="self-center text-sm text-slate-500">No signup required until question 25.</p>
                @endauth
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 ring-1 ring-medic/20">
            <h2 class="text-lg font-bold text-white">Your {{ $sectionLabel }} access</h2>

            @if ($unlocked)
                <p class="mt-3 text-2xl font-bold text-medic-light">Unlimited quizzes</p>
                <p class="mt-1 text-sm text-slate-400">Full access to this platform.</p>
            @else
                <p class="mt-3 text-2xl font-bold text-safety-light">{{ $freeRemaining }} <span class="text-lg font-semibold text-slate-400">free questions left</span></p>
                <p class="mt-1 text-sm text-slate-400">Create an account at question 25, then $8.99 for unlimited quizzing.</p>
            @endif

            <ul class="mt-6 space-y-3 text-sm text-slate-300">
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Start immediately — no account</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Adaptive difficulty 1–5</li>
                <li class="flex gap-2"><span class="text-medic-light">✓</span> Instant feedback &amp; rationales</li>
            </ul>

            <p class="mt-6 text-xs text-slate-500">
                Use the ⛨ menu to switch to EMT-Advanced or Paramedic — each is a separate platform.
            </p>
        </div>
    </section>
@endsection
