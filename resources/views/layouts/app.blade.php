<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — SalusPrep{{ isset($sectionLabel) ? ' '.$sectionLabel : '' }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            safety: { DEFAULT: '#f59e0b', dark: '#d97706', light: '#fbbf24' },
                            ems: { DEFAULT: '#006bb6', dark: '#004d84', light: '#3399cc' },
                            medic: { DEFAULT: '#16a34a', dark: '#15803d', light: '#4ade80' },
                            rescue: { DEFAULT: '#dc2626', dark: '#991b1b' },
                            navy: { DEFAULT: '#0f172a', light: '#1e293b' },
                        }
                    }
                }
            }
        </script>
    @endif
    <style>
        #platform-switcher-menu { background-color: #1e293b; }
    </style>
</head>
<body class="min-h-screen bg-navy text-slate-100 antialiased">
    <div class="relative min-h-screen">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(22,163,74,0.08),_transparent_50%)]"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,_rgba(0,107,182,0.12),_transparent_45%)]"></div>

        <header class="relative z-50 border-b border-white/10 bg-[#1e293b]">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6">
                <div class="flex items-center gap-3">
                    @isset($platformSections)
                        <div class="relative" id="platform-switcher">
                            <button
                                type="button"
                                id="platform-switcher-btn"
                                aria-haspopup="true"
                                aria-expanded="false"
                                class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-lg bg-medic text-xl font-bold text-white shadow-lg shadow-medic/25 ring-2 ring-medic-light/30 hover:bg-medic-dark"
                                title="Switch certification level"
                            >✚</button>
                            <div
                                id="platform-switcher-menu"
                                class="hidden absolute left-0 top-full z-[100] mt-2 w-56 overflow-hidden rounded-xl border border-slate-600 shadow-2xl"
                                role="menu"
                            >
                                <p class="border-b border-slate-600 bg-[#0f172a] px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-400">Switch platform</p>
                                @foreach ($platformSections as $platform)
                                    <a href="{{ route('platform.home', $platform['slug']) }}"
                                       role="menuitem"
                                       class="block px-4 py-3 text-sm hover:bg-slate-700 {{ $platform['active'] ? 'bg-medic/20 font-bold text-medic-light' : 'text-slate-200' }}">
                                        {{ $platform['label'] }}
                                        @if ($platform['active'])
                                            <span class="ml-1 text-xs text-medic-light">●</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route('platform.home', 'emt-basic') }}" class="flex h-11 w-11 items-center justify-center rounded-lg bg-medic text-xl font-bold text-white shadow-lg shadow-medic/25">✚</a>
                    @endisset

                    <div>
                        <a href="{{ isset($sectionSlug) ? route('platform.home', $sectionSlug) : route('platform.home', 'emt-basic') }}" class="block">
                            <p class="text-sm font-bold tracking-wide text-white">SalusPrep</p>
                            <p class="text-xs font-medium text-medic-light">
                                @isset($sectionLabel)
                                    {{ $sectionLabel }} NREMT
                                @else
                                    NREMT Adaptive Practice
                                @endisset
                            </p>
                        </a>
                    </div>
                </div>

                <nav class="flex items-center gap-2 text-sm">
                    @auth
                        @isset($sectionSlug)
                            <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-medic-light">Dashboard</a>
                        @endisset
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-white">Log out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-white">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-medic px-4 py-2 font-bold text-white hover:bg-medic-dark">Sign up</a>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="relative z-0 mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-12">
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-medic/40 bg-medic/10 px-4 py-3 text-sm font-medium text-medic-light">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rescue/40 bg-rescue/10 px-4 py-3 text-sm text-red-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @isset($platformSections)
    <script>
        (function () {
            var switcher = document.getElementById('platform-switcher');
            var btn = document.getElementById('platform-switcher-btn');
            var menu = document.getElementById('platform-switcher-menu');
            if (!switcher || !btn || !menu) return;

            function openMenu() {
                menu.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            }

            function closeMenu() {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }

            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (menu.classList.contains('hidden')) {
                    openMenu();
                } else {
                    closeMenu();
                }
            });

            document.addEventListener('click', function () {
                closeMenu();
            });

            switcher.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        })();
    </script>
    @endisset
</body>
</html>
