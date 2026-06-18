@props([
    'href',
])

<a
    href="{{ $href }}"
    class="group flex w-full shrink-0 flex-col items-center justify-center gap-3 rounded-2xl border border-ems/30 bg-ems/10 px-5 py-4 text-center transition hover:border-ems/50 hover:bg-ems/15 sm:w-44"
>
    <span class="relative block h-14 w-11" aria-hidden="true">
        <span class="absolute inset-x-0 bottom-0 top-2 rounded-lg border border-white/10 bg-navy-light/90 shadow-md"></span>
        <span class="absolute inset-x-0.5 bottom-1 top-1 rounded-lg border border-white/15 bg-navy/80 shadow-sm"></span>
        <span class="absolute inset-x-1 bottom-2 top-0 flex flex-col rounded-lg border border-ems/40 bg-gradient-to-br from-ems/20 to-navy-light/95 px-1.5 py-2 shadow-lg transition group-hover:border-ems/60">
            <span class="h-1 w-4 rounded-full bg-ems-light/80"></span>
            <span class="mt-1.5 h-0.5 w-full rounded-full bg-white/20"></span>
            <span class="mt-1 h-0.5 w-3/4 rounded-full bg-white/15"></span>
        </span>
    </span>
    <span class="text-sm font-bold leading-snug text-ems-light group-hover:text-white">Added to study deck</span>
    <span class="text-xs font-semibold text-slate-400 group-hover:text-slate-300">View flashcards →</span>
</a>
