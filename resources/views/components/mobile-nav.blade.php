<div class="relative md:hidden" id="mobile-nav">
    <button
        type="button"
        id="mobile-nav-btn"
        aria-haspopup="true"
        aria-expanded="false"
        aria-controls="mobile-nav-menu"
        class="flex items-center gap-2 rounded-xl border border-white/10 bg-navy-light/80 px-3.5 py-2 text-sm font-semibold text-slate-200 shadow-sm transition hover:border-medic/30 hover:bg-navy-light hover:text-white"
    >
        <span>Navigate</span>
        <svg id="mobile-nav-chevron" class="h-4 w-4 text-slate-400 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
        </svg>
    </button>

    <div
        id="mobile-nav-menu"
        class="invisible absolute right-0 top-full z-[100] mt-2 w-56 origin-top-right scale-95 opacity-0 transition-all duration-150"
        role="menu"
    >
        <div class="overflow-hidden rounded-xl border border-slate-600 bg-[#1e293b] shadow-2xl">
            @isset($sectionSlug)
                <p class="border-b border-slate-600 bg-[#0f172a] px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-400">Study tools</p>
                @if ($examCountdown ?? null)
                    <div class="border-b border-slate-600 px-4 py-3">
                        <x-exam-countdown :compact="true" />
                    </div>
                @endif
                <a href="{{ route('skills.index', $sectionSlug) }}" role="menuitem" class="block px-4 py-3 text-sm font-medium text-slate-200 hover:bg-slate-700">Skills</a>
                <a href="{{ route('platform.dashboard', $sectionSlug) }}" role="menuitem" class="block px-4 py-3 text-sm font-medium text-slate-200 hover:bg-slate-700">Test Center</a>
                <a href="{{ route('study.index', $sectionSlug) }}" role="menuitem" class="block px-4 py-3 text-sm font-medium text-slate-200 hover:bg-slate-700">Flashcards</a>
            @endisset

            @auth
                <div class="border-t border-slate-600 bg-[#0f172a] px-4 py-3">
                    <p class="truncate text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                </div>
                <a href="{{ route('settings.edit') }}" role="menuitem" class="block px-4 py-3 text-sm text-slate-200 hover:bg-slate-700">Settings</a>
                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-600">
                    @csrf
                    <button type="submit" role="menuitem" class="block w-full px-4 py-3 text-left text-sm text-slate-200 hover:bg-slate-700">Log out</button>
                </form>
            @else
                <div class="border-t border-slate-600 p-3">
                    <a href="{{ route('login') }}" role="menuitem" class="block rounded-lg px-3 py-2.5 text-center text-sm font-medium text-slate-200 hover:bg-slate-700">Log in</a>
                    <a href="{{ route('register') }}" role="menuitem" class="mt-2 block rounded-lg bg-medic px-3 py-2.5 text-center text-sm font-bold text-white hover:bg-medic-dark">Sign up</a>
                </div>
            @endauth
        </div>
    </div>
</div>

<script>
    (function () {
        var root = document.getElementById('mobile-nav');
        var btn = document.getElementById('mobile-nav-btn');
        var menu = document.getElementById('mobile-nav-menu');
        var chevron = document.getElementById('mobile-nav-chevron');
        if (!root || !btn || !menu) return;

        function setOpen(isOpen) {
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            menu.classList.toggle('invisible', !isOpen);
            menu.classList.toggle('opacity-0', !isOpen);
            menu.classList.toggle('scale-95', !isOpen);
            btn.classList.toggle('border-medic/40', isOpen);
            btn.classList.toggle('ring-1', isOpen);
            btn.classList.toggle('ring-medic/20', isOpen);
            if (chevron) {
                chevron.classList.toggle('rotate-180', isOpen);
                chevron.classList.toggle('text-medic-light', isOpen);
            }
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            setOpen(btn.getAttribute('aria-expanded') !== 'true');
        });

        document.addEventListener('click', function (e) {
            if (!root.contains(e.target)) {
                setOpen(false);
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                setOpen(false);
            }
        });

        menu.querySelectorAll('a, button').forEach(function (item) {
            item.addEventListener('click', function () {
                setOpen(false);
            });
        });
    })();
</script>
