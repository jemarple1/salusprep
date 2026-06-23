@extends('admin.layout')

@section('title', $guest->displayName())

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.dashboard') }}#guest-visitors" class="text-sm font-medium text-slate-400 hover:text-white">
            ← Back to guest visitors
        </a>
    </div>

    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-safety-light">Guest profile</p>
            <h1 class="mt-1 text-3xl font-bold capitalize text-white">{{ $guest->displayName() }}</h1>
            <p class="mt-2 font-mono text-xs text-slate-500">{{ $guest->device_id }}</p>
        </div>
        <div class="text-right text-sm text-slate-400">
            <p>First seen {{ $guest->first_seen_at->format('M j, Y g:i A') }}</p>
            <p>Last seen {{ $guest->last_seen_at->diffForHumans() }}</p>
        </div>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-white">{{ number_format($guest->page_visits_count) }}</p>
            <p class="mt-1 text-sm text-slate-400">Page visits</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-ems-light">{{ number_format($profile['unique_pages']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Unique pages</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-medic-light">{{ number_format((int) ($guest->questions_answered ?? 0)) }}</p>
            <p class="mt-1 text-sm text-slate-400">Questions · {{ number_format($guest->completed_quizzes_count) }} quizzes done</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-safety-light">{{ $guest->formattedActiveTime() }}</p>
            <p class="mt-1 text-sm text-slate-400">Active time on site</p>
        </div>
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Visitor details</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4 border-b border-white/5 pb-3">
                    <dt class="text-slate-400">Location</dt>
                    <dd class="font-semibold text-white">{{ $guest->country_name ?? 'Unknown' }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-white/5 pb-3">
                    <dt class="text-slate-400">Referral</dt>
                    <dd class="max-w-[16rem] text-right font-semibold text-white">{{ $guest->referralLabel() }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-white/5 pb-3">
                    <dt class="text-slate-400">Landing page</dt>
                    <dd class="font-semibold text-white">{{ $guest->landing_path ? '/'.$guest->landing_path : '—' }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-white/5 pb-3">
                    <dt class="text-slate-400">Visits (7d / 30d)</dt>
                    <dd class="font-semibold text-white">{{ number_format($profile['visits_7d']) }} / {{ number_format($profile['visits_30d']) }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400">Status</dt>
                    <dd class="font-semibold text-white">
                        @if ($guest->convertedUser)
                            Signed up · {{ $guest->convertedUser->email }}
                        @else
                            Guest
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Activity summary</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4 border-b border-white/5 pb-3">
                    <dt class="text-slate-400">Study sessions</dt>
                    <dd class="font-semibold text-white">{{ number_format($guest->study_sessions_count) }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-white/5 pb-3">
                    <dt class="text-slate-400">Skill exercises</dt>
                    <dd class="font-semibold text-white">{{ number_format($guest->exercise_completions_count) }}</dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-white/5 pb-3">
                    <dt class="text-slate-400">Quizzes started</dt>
                    <dd class="font-semibold text-white">{{ number_format($guest->quizzes_count) }}</dd>
                </div>
                @if ($profile['most_visited_path'])
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-400">Most visited page</dt>
                        <dd class="max-w-[16rem] text-right font-semibold text-white">
                            /{{ $profile['most_visited_path'] }}
                            <span class="block text-xs font-normal text-slate-500">{{ number_format($profile['most_visited_count']) }} visits</span>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5 sm:p-6">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-white">Page visits</h2>
                <p class="mt-1 text-sm text-slate-400">Chronological browsing history for this guest device.</p>
            </div>
            <p class="text-sm text-slate-400">
                {{ number_format($visits->total()) }} total · page {{ $visits->currentPage() }} of {{ $visits->lastPage() }}
            </p>
        </div>

        @if ($visits->isEmpty())
            <p class="py-8 text-center text-sm text-slate-500">No page visits recorded yet.</p>
        @else
            <div class="space-y-8">
                @foreach ($visitDays as $day => $dayVisits)
                    <div>
                        <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">
                            {{ \Illuminate\Support\Carbon::parse($day)->format('l, M j, Y') }}
                        </h3>
                        <ol class="space-y-2 border-l border-white/10 pl-4">
                            @foreach ($dayVisits as $visit)
                                <li class="relative text-sm">
                                    <span class="absolute -left-[1.34rem] top-2 h-2 w-2 rounded-full bg-safety-light ring-2 ring-navy-light"></span>
                                    <div class="rounded-xl border border-white/5 bg-navy/40 px-4 py-3">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div>
                                                <p class="font-semibold text-white">{{ $visit->pathLabel() }}</p>
                                                <p class="mt-0.5 font-mono text-xs text-slate-500">/{{ $visit->path }}</p>
                                            </div>
                                            <time class="whitespace-nowrap text-xs text-slate-400" datetime="{{ $visit->visited_at->toIso8601String() }}">
                                                {{ $visit->visited_at->format('g:i:s A') }}
                                            </time>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $visits->links() }}
            </div>
        @endif
    </div>
@endsection
