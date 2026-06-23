@props([
    'email' => null,
])

<div
    id="study-club-gate"
    class="fixed inset-0 z-[100] flex items-end justify-center p-4 sm:items-center"
    role="dialog"
    aria-modal="true"
    aria-labelledby="study-club-title"
>
    <div class="absolute inset-0 bg-navy/70 backdrop-blur-md" aria-hidden="true"></div>

    <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-navy-light shadow-2xl shadow-black/40 sm:max-w-lg">
        <div class="border-b border-white/10 bg-gradient-to-br from-medic/20 via-ems/10 to-transparent px-8 pb-7 pt-8 text-center sm:px-10 sm:pt-10">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-3xl shadow-inner ring-1 ring-white/15" aria-hidden="true">
                <span class="inline-block -rotate-12">🎟️</span>
            </div>
            <p class="text-sm font-semibold text-ems-light">You&rsquo;re on a roll — keep going</p>
            <h2 id="study-club-title" class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                Join Study Pass for free
            </h2>
            <p class="mx-auto mt-3 max-w-sm text-sm leading-relaxed text-slate-300">
                Add your email to unlock the rest of your free preview on SalusPrep. Study Pass is always free and takes just a second.
            </p>
        </div>

        <div class="px-8 py-7 sm:px-10">
            <div class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/8 bg-navy/40 px-3 py-3 text-center">
                    <p class="text-lg" aria-hidden="true">🎯</p>
                    <p class="mt-1 text-xs font-semibold leading-snug text-white">Keep practicing</p>
                    <p class="mt-0.5 text-[11px] leading-snug text-slate-400">Quizzes, flashcards &amp; skills</p>
                </div>
                <div class="rounded-2xl border border-white/8 bg-navy/40 px-3 py-3 text-center">
                    <p class="text-lg" aria-hidden="true">📬</p>
                    <p class="mt-1 text-xs font-semibold leading-snug text-white">Helpful emails</p>
                    <p class="mt-0.5 text-[11px] leading-snug text-slate-400">Free tips &amp; study resources</p>
                </div>
                <div class="rounded-2xl border border-white/8 bg-navy/40 px-3 py-3 text-center">
                    <p class="text-lg" aria-hidden="true">✨</p>
                    <p class="mt-1 text-xs font-semibold leading-snug text-white">Member perks</p>
                    <p class="mt-0.5 text-[11px] leading-snug text-slate-400">Early access &amp; updates</p>
                </div>
            </div>

            <form method="POST" action="{{ route('study-club.join', $sectionSlug) }}" class="mt-7">
                @csrf
                <label for="study-club-email" class="mb-2 block text-sm font-medium text-slate-300">Your email</label>
                <input
                    type="email"
                    name="email"
                    id="study-club-email"
                    value="{{ old('email', $email ?? auth()->user()?->email) }}"
                    required
                    autocomplete="email"
                    placeholder="you@example.com"
                    class="w-full rounded-xl border border-white/12 bg-navy/60 px-4 py-3 text-white placeholder:text-slate-500 focus:border-medic/60 focus:outline-none focus:ring-2 focus:ring-medic/25"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    class="mt-4 w-full rounded-xl bg-medic px-6 py-3.5 text-base font-bold text-white shadow-lg shadow-medic/20 transition hover:bg-medic-dark"
                >
                    Join Study Pass — continue free
                </button>
            </form>

            <p class="mt-4 text-center text-xs leading-relaxed text-slate-500">
                Always free. Unsubscribe anytime with one click.
            </p>
        </div>
    </div>
</div>

<script>
    (function () {
        document.body.classList.add('overflow-hidden');

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                event.preventDefault();
            }
        });

        var emailInput = document.getElementById('study-club-email');
        if (emailInput) {
            emailInput.focus();
        }
    })();
</script>
