@props([
    'question',
    'sectionSlug',
    'questionNumber' => 1,
    'totalQuestions' => 25,
    'pinnedFocus' => null,
])

<div class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 ring-1 ring-medic/20">
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold uppercase tracking-wider text-medic-light">Start a practice quiz</h2>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-500">
                Question {{ $questionNumber }} of {{ $totalQuestions }}
            </p>
        </div>
        <span @class([
            'rounded-full px-3 py-1 text-xs font-bold uppercase',
            \App\Support\QuestionCategory::styles($question->category)['badge'],
        ])>{{ $question->category }}</span>
    </div>

    <p class="text-lg leading-relaxed text-white">{{ $question->stem }}</p>

    <div class="mt-6 space-y-3">
        @foreach ($question->options() as $letter => $text)
            <form method="POST" action="{{ route('exam.preview-answer', $sectionSlug) }}" class="block">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <input type="hidden" name="selected_option" value="{{ $letter }}">
                @if ($pinnedFocus)
                    <input type="hidden" name="focus_category" value="{{ $pinnedFocus }}">
                @endif
                <button
                    type="submit"
                    class="flex w-full cursor-pointer items-start gap-4 rounded-xl border border-white/10 bg-navy/60 px-4 py-4 text-left transition hover:border-medic/40 hover:bg-medic/10"
                >
                    <span class="font-bold text-medic-light">{{ $letter }}.</span>
                    <span class="text-slate-200">{{ $text }}</span>
                </button>
            </form>
        @endforeach
    </div>

    <p class="mt-4 text-xs text-slate-500">Tap an answer to start your quiz.</p>
</div>
