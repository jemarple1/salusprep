@props([
    'icon' => 'clipboard',
])

@php
    $class = 'h-5 w-5';
@endphp

@switch($icon)
    @case('clipboard')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path stroke-linecap="round" d="M9 12h6M9 16h4"/></svg>
        @break
    @case('triage')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 12h.01M7 17h.01M12 7h5M12 12h5M12 17h5"/><rect x="3" y="3" width="6" height="18" rx="1.5"/></svg>
        @break
    @case('pediatric')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="7" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 21v-1a6 6 0 0112 0v1"/><path stroke-linecap="round" d="M4 10h3M17 10h3"/></svg>
        @break
    @case('salt')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/><path stroke-linecap="round" d="M12 12v9M4 7.5l8 4.5 8-4.5"/></svg>
        @break
    @case('patients')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="9" cy="7" r="3"/><circle cx="17" cy="9" r="2.5"/><path stroke-linecap="round" d="M3 21v-1a4 4 0 014-4h4a4 4 0 014 4v1M14 21v-1a3 3 0 013-3h1"/></svg>
        @break
    @case('brain')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5a3 3 0 00-3 3v1a2 2 0 000 4v1a3 3 0 003 3"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 5a3 3 0 013 3v1a2 2 0 010 4v1a3 3 0 01-3 3"/><path stroke-linecap="round" d="M9 8h6M9 12h6M9 16h6"/></svg>
        @break
    @case('burn')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2 3 5 4.5 5 8a5 5 0 11-10 0c0-3.5 3-5 5-8z"/><path stroke-linecap="round" d="M12 14v4"/></svg>
        @break
    @case('stroke')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2"/><circle cx="12" cy="12" r="9"/></svg>
        @break
    @case('vitals')
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l2-5 4 10 2-5h6"/></svg>
        @break
    @default
        <svg class="{{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="2"/><path stroke-linecap="round" d="M8 12h8"/></svg>
@endswitch
