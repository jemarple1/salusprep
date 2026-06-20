@php
    $initials = collect(explode(' ', auth()->user()->name))
        ->filter()
        ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->join('');
@endphp

<div class="group relative" id="user-menu">
    <button
        type="button"
        id="user-menu-btn"
        aria-haspopup="true"
        aria-expanded="false"
        class="flex cursor-pointer items-center gap-2 rounded-lg px-2 py-1.5 text-sm hover:bg-white/5 sm:px-3 sm:py-2"
    >
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-medic/20 text-xs font-bold text-medic-light ring-1 ring-medic/30">
            {{ $initials }}
        </span>
        <span class="hidden max-w-[9rem] truncate font-medium text-slate-300 sm:inline">{{ auth()->user()->name }}</span>
        <svg class="hidden h-4 w-4 text-slate-400 sm:block" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
        </svg>
    </button>

    <div
        id="user-menu-dropdown"
        class="invisible absolute right-0 top-full z-[100] w-60 pt-2 opacity-0 transition-opacity duration-150 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100"
        role="menu"
    >
        <div class="overflow-hidden rounded-xl border border-slate-600 bg-[#1e293b] shadow-2xl">
            <div class="border-b border-slate-600 bg-[#0f172a] px-4 py-3">
                <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
            </div>

            <a href="{{ route('settings.edit') }}"
               role="menuitem"
               class="block px-4 py-3 text-sm text-slate-200 hover:bg-slate-700">
                Settings
            </a>

            <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-600">
                @csrf
                <button type="submit"
                        role="menuitem"
                        class="block w-full px-4 py-3 text-left text-sm text-slate-200 hover:bg-slate-700">
                    Log out
                </button>
            </form>
        </div>
    </div>
</div>
