@props([
    'exercises',
    'title' => 'Skill exercises',
    'description' => null,
])

@if ($exercises !== [])
    <div {{ $attributes->class(['rounded-2xl border border-white/10 bg-navy-light/80 p-6']) }}>
        <div class="mb-5">
            <h2 class="text-lg font-bold text-white">{{ $title }}</h2>
            @if ($description)
                <p class="mt-1 text-sm text-slate-400">{{ $description }}</p>
            @endif
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($exercises as $exercise)
                <x-exercise-card :exercise="$exercise" variant="grid" />
            @endforeach
        </div>
    </div>
@endif
