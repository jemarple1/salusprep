@props([
    'percent',
])

@if ($percent !== null)
    <span {{ $attributes->merge(['class' => 'rounded-full border border-white/10 bg-navy/40 px-2.5 py-1 text-xs font-semibold text-slate-400']) }}>
        {{ $percent }}% of users got this right
    </span>
@endif
