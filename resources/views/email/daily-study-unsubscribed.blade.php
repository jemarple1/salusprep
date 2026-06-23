@extends('layouts.app')

@section('title', 'Unsubscribed')

@section('content')
    <div class="mx-auto max-w-lg rounded-2xl border border-white/10 bg-navy-light/80 p-8 text-center">
        <p class="text-xs font-bold uppercase tracking-wider text-medic-light">Daily study emails</p>
        <h1 class="mt-3 text-2xl font-bold text-white">You're unsubscribed</h1>
        <p class="mt-3 text-sm leading-relaxed text-slate-400">
            We won't send any more morning checklist emails{{ $user->name ? ', '.$user->name : '' }}.
            You can turn them back on anytime in account settings.
        </p>
        <a href="{{ route('settings.edit') }}" class="mt-6 inline-flex rounded-xl bg-medic px-5 py-2.5 text-sm font-bold text-white hover:bg-medic-dark">
            Account settings
        </a>
    </div>
@endsection
