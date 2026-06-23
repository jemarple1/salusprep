@extends('layouts.app')

@section('title', 'Account settings')

@section('content')
    <div class="mx-auto max-w-2xl space-y-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Account settings</h1>
            <p class="mt-2 text-sm text-slate-400">Update your profile, exam dates, email preferences, change your password, or delete your account.</p>
        </div>

        @if ($user->sectionAccesses()->whereNotNull('unlocked_at')->exists())
            <section class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-white">Daily study emails</h2>
                <p class="mt-1 text-sm text-slate-400">
                    A morning checklist at 9:00 AM Eastern with today's tasks, a skill spotlight, and a bite-sized review from your missed questions. Emails stop on your exam date.
                </p>

                <form method="POST" action="{{ route('settings.daily-study-email.update') }}" class="mt-6">
                    @csrf
                    @method('PUT')
                    <label class="flex items-start gap-3 text-sm text-slate-300">
                        <input
                            type="checkbox"
                            name="daily_study_email_opt_in"
                            value="1"
                            @checked(old('daily_study_email_opt_in', $user->daily_study_email_opt_in))
                            class="mt-1 rounded border-white/20 bg-navy text-medic focus:ring-medic"
                        >
                        <span>Send me daily study checklist emails</span>
                    </label>
                    <button type="submit" class="mt-4 rounded-xl bg-medic px-5 py-2.5 font-bold text-white hover:bg-medic-dark">
                        Save email preference
                    </button>
                </form>
            </section>
        @endif

        @if ($examDateSections->isNotEmpty())
            <section class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-white">Exam dates</h2>
                <p class="mt-1 text-sm text-slate-400">
                    Set or update your test date for each unlocked platform. The countdown appears in the header when a date is saved.
                </p>

                <div class="mt-6 space-y-6">
                    @foreach ($examDateSections as $section)
                        <div class="rounded-xl border border-white/10 bg-navy/40 p-5">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-base font-bold text-white">{{ $section['label'] }}</h3>
                                    @if ($section['examCountdown'])
                                        <p class="mt-1 text-sm text-medic-light">{{ $section['examCountdown']['label'] }}</p>
                                    @else
                                        <p class="mt-1 text-sm text-slate-500">No exam date set</p>
                                    @endif
                                </div>
                            </div>

                            <form method="POST" action="{{ route('settings.exam-date.update', $section['slug']) }}" class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-end">
                                @csrf
                                @method('PUT')
                                <div class="flex-1">
                                    <label for="exam_date_{{ $section['slug'] }}" class="mb-1 block text-sm font-medium text-slate-300">Exam date</label>
                                    <input
                                        id="exam_date_{{ $section['slug'] }}"
                                        name="exam_date"
                                        type="date"
                                        value="{{ old('exam_date', $section['access']->exam_date?->toDateString()) }}"
                                        min="{{ now()->toDateString() }}"
                                        max="{{ now()->addYears(2)->toDateString() }}"
                                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-medic"
                                    >
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <button type="submit" class="rounded-xl bg-medic px-5 py-2.5 font-bold text-white hover:bg-medic-dark">
                                        Save date
                                    </button>
                                    @if ($section['access']->exam_date)
                                        <button
                                            type="submit"
                                            name="exam_date"
                                            value=""
                                            class="rounded-xl border border-white/10 px-5 py-2.5 text-sm font-semibold text-slate-400 hover:bg-white/5 hover:text-slate-200"
                                        >
                                            Clear
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
            <div class="flex items-center gap-4">
                <x-user-avatar :user="$user" size="lg" />
                <div>
                    <h2 class="text-lg font-bold text-white">Profile</h2>
                    <p class="mt-1 text-sm text-slate-400">Update your name and email address.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('settings.profile.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-slate-300">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-medic">
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-medic">
                </div>

                <div>
                    <label for="profile_current_password" class="mb-1 block text-sm font-medium text-slate-300">Current password</label>
                    <input id="profile_current_password" name="current_password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-medic">
                    <p class="mt-1 text-xs text-slate-500">Required to save profile changes.</p>
                </div>

                <button type="submit" class="rounded-xl bg-medic px-5 py-2.5 font-bold text-white hover:bg-medic-dark">
                    Save profile
                </button>
            </form>
        </section>

        <section class="rounded-2xl border border-white/10 bg-navy-light/80 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-white">Password</h2>
            <p class="mt-1 text-sm text-slate-400">Choose a new password for your account.</p>

            <form method="POST" action="{{ route('settings.password.update') }}" class="mt-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="password_current_password" class="mb-1 block text-sm font-medium text-slate-300">Current password</label>
                    <input id="password_current_password" name="current_password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-medic">
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-slate-300">New password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-medic">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-300">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-medic">
                </div>

                <button type="submit" class="rounded-xl bg-medic px-5 py-2.5 font-bold text-white hover:bg-medic-dark">
                    Update password
                </button>
            </form>
        </section>

        <section class="rounded-2xl border border-rescue/30 bg-rescue/5 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-white">Delete account</h2>
            <p class="mt-1 text-sm text-slate-400">
                Permanently delete your account and all associated quiz history, study progress, and section access. This cannot be undone.
            </p>

            <form method="POST" action="{{ route('settings.account.destroy') }}" class="mt-6 space-y-4"
                onsubmit="return confirm('Delete your SalusPrep account permanently? This cannot be undone.');">
                @csrf
                @method('DELETE')

                <div>
                    <label for="delete_current_password" class="mb-1 block text-sm font-medium text-slate-300">Current password</label>
                    <input id="delete_current_password" name="current_password" type="password" required
                        class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-white outline-none focus:ring-2 focus:ring-rescue">
                </div>

                <button type="submit" class="rounded-xl border border-rescue/40 bg-rescue/20 px-5 py-2.5 font-bold text-red-200 hover:bg-rescue/30">
                    Delete my account
                </button>
            </form>
        </section>
    </div>
@endsection
