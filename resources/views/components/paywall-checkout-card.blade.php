@props([
    'requiresAuth' => false,
    'firstName' => null,
    'topWeakCategory' => null,
    'totalMissed' => 0,
    'overallStats' => null,
    'previewExpired' => true,
    'previewRemainingMinutes' => 0,
])

@php
    use App\Support\SectionPricing;

    $price = SectionPricing::formatted();
@endphp

<section
    id="paywall-checkout"
    {{ $attributes->class([
        'relative overflow-hidden rounded-3xl border border-safety/35 bg-gradient-to-br from-[#243044] via-navy-light to-[#0f172a]',
        'shadow-2xl shadow-safety/10 ring-1 ring-safety/20',
        'sticky bottom-4 z-20 mb-10 backdrop-blur-sm sm:static sm:shadow-none',
    ]) }}
>
    <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-safety/20 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-10 h-48 w-48 rounded-full bg-medic/10 blur-3xl"></div>

    <div class="relative grid gap-10 p-8 sm:p-10 lg:grid-cols-[1fr_15rem] lg:items-center lg:gap-12">
        <div class="space-y-6">
            <p class="text-sm font-semibold text-safety-light">{{ $sectionLabel }} · Full Access</p>

            <div class="space-y-3">
                <h2 class="text-2xl font-bold tracking-tight text-white sm:text-[1.75rem]">
                    @if ($firstName)
                        {{ $firstName }}, keep your progress going
                    @else
                        Keep your progress going
                    @endif
                </h2>

                <p class="max-w-lg text-base leading-relaxed text-slate-300">
                    @if ($topWeakCategory)
                        Pick up with <strong class="text-white">{{ $topWeakCategory->category }}</strong> and every topic Preview mapped for you.
                    @elseif ($totalMissed > 0)
                        Your <strong class="text-white">{{ number_format($totalMissed) }} missed questions</strong> are ready as flashcards — plus unlimited quizzes and skills.
                    @else
                        Unlimited quizzes, flashcards, skills, and Test Center — tailored to how you learn.
                    @endif
                </p>
            </div>

            <ul class="space-y-3 text-sm text-slate-300">
                <li class="flex gap-3"><span class="text-medic-light">✓</span> Unlimited adaptive &amp; focus quizzes</li>
                <li class="flex gap-3"><span class="text-medic-light">✓</span> Flashcards built from your misses</li>
                <li class="flex gap-3"><span class="text-medic-light">✓</span> Skills, rationales &amp; Test Center</li>
            </ul>

            <p class="text-sm text-slate-500">
                One-time purchase · no subscription · good through every recertification
            </p>
        </div>

        <div class="flex flex-col items-center gap-6">
            <x-paywall-checkout-graphic />

            <div class="w-full space-y-5 text-center">
                <div>
                    <p class="text-4xl font-bold tracking-tight text-safety-light">{{ $price }}</p>
                    <p class="mt-2 text-sm text-slate-400">{{ $sectionLabel }}</p>
                </div>

                @if ($requiresAuth)
                    <div class="space-y-3">
                        <a
                            href="{{ route('register', ['section' => $sectionSlug, 'unlock' => 1]) }}"
                            class="block w-full rounded-xl bg-safety py-3.5 text-base font-bold text-navy shadow-lg shadow-safety/25 transition hover:bg-safety-light"
                        >
                            Sign up &amp; unlock
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="block text-sm font-medium text-slate-400 transition hover:text-slate-200"
                        >
                            Log in
                        </a>
                    </div>
                @else
                    <form method="POST" action="{{ route('platform.unlock', $sectionSlug) }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full rounded-xl bg-safety py-3.5 text-base font-bold text-navy shadow-lg shadow-safety/25 transition hover:bg-safety-light"
                        >
                            Get Full Access
                        </button>
                    </form>
                @endif

                <p class="text-xs text-slate-500">Secure checkout · no auto-renew</p>

                @unless (config('services.stripe.secret'))
                    <p class="text-[10px] text-slate-600">Mock checkout locally.</p>
                @endunless
            </div>
        </div>
    </div>
</section>
