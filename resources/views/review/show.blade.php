@extends('layouts.app')

@section('title', $concept['title'])

@section('content')
    <div class="mb-6">
        <a href="{{ route('review.index', $sectionSlug) }}" class="text-sm font-semibold text-medic-light hover:text-medic hover:underline">← All basic concepts</a>
    </div>

    <article class="overflow-hidden rounded-2xl border border-white/10 bg-navy-light/50">
        <x-review-backdrop :accent="$concept['accent']" class="border-b border-white/10 px-6 py-8 sm:px-10 sm:py-10">
            <p class="text-xs font-bold uppercase tracking-wider text-white/70">{{ $concept['category'] }}</p>
            <h1 class="mt-2 text-3xl font-bold leading-tight text-white sm:text-4xl">{{ $concept['title'] }}</h1>
            <p class="mt-3 max-w-3xl text-lg text-white/85">{{ $concept['excerpt'] }}</p>
        </x-review-backdrop>

        <div class="prose prose-invert max-w-none px-6 py-8 sm:px-10 sm:py-10">
            @foreach ($concept['sections'] as $section)
                @if (! empty($section['heading']))
                    <h2 class="mb-4 mt-8 text-xl font-bold text-white first:mt-0">{{ $section['heading'] }}</h2>
                @endif
                @foreach ($section['paragraphs'] as $paragraph)
                    @php
                        $linked = preg_replace(
                            '/<sup>(\d+)<\/sup>/',
                            '<sup><a href="#source-$1" class="text-medic-light no-underline hover:underline">$1</a></sup>',
                            $paragraph
                        );
                    @endphp
                    <p class="mb-4 text-base leading-relaxed text-slate-300">{!! $linked !!}</p>
                @endforeach
            @endforeach
        </div>

        <footer class="border-t border-white/10 bg-[#0f172a]/60 px-6 py-8 sm:px-10">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Sources</h2>
            <p class="mt-2 text-sm text-slate-500">Educational summaries citing official U.S. government websites. Always follow your local protocols and scope of practice.</p>
            <ol class="mt-4 space-y-3">
                @foreach ($concept['sources'] as $source)
                    <li id="source-{{ $source['id'] }}" class="text-sm">
                        <span class="font-semibold text-slate-300">[{{ $source['id'] }}]</span>
                        <a
                            href="{{ $source['url'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-medic-light hover:text-medic hover:underline"
                        >{{ $source['label'] }}</a>
                        <span class="text-slate-500"> — {{ parse_url($source['url'], PHP_URL_HOST) }}</span>
                    </li>
                @endforeach
            </ol>
        </footer>
    </article>
@endsection
