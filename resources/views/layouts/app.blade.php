<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo-meta
        :page-meta-title="$pageMetaTitle ?? null"
        :page-meta-description="$pageMetaDescription ?? null"
    />

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-18250454039"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'AW-18250454039');
    </script>

    <script>
        (function () {
            var fullres = document.createElement('script');
            fullres.async = true;
            fullres.src = 'https://t.fullres.net/salusprep.js?' + (new Date() - new Date() % 43200000);
            document.head.appendChild(fullres);
        })();
    </script>

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
                            pharma: { DEFAULT: '#8b5cf6', dark: '#7c3aed', light: '#c4b5fd' },
                            rescue: { DEFAULT: '#dc2626', dark: '#991b1b' },
                            navy: { DEFAULT: '#0f172a', light: '#1e293b' },
                        }
                    }
                }
            }
        </script>
    @endif
</head>
<body class="flex min-h-screen flex-col bg-navy text-slate-100 antialiased">
    <div class="relative flex flex-1 flex-col">
        <div class="theme-page-glow-medic pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(22,163,74,0.08),_transparent_50%)]"></div>
        <div class="theme-page-glow-ems pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,_rgba(0,107,182,0.12),_transparent_45%)]"></div>

        <header class="theme-header relative z-50 border-b border-white/10 bg-[#1e293b]">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6">
                <div class="group relative flex items-center gap-3" id="platform-switcher">
                    @isset($platformSections)
                        <button
                            type="button"
                            id="platform-switcher-btn"
                            aria-haspopup="true"
                            aria-expanded="false"
                            class="flex h-11 w-11 cursor-pointer items-center justify-center rounded-lg bg-medic text-3xl font-bold text-white shadow-lg shadow-medic/25 ring-2 ring-medic-light/30 hover:bg-medic-dark"
                            title="Switch certification level"
                        >⛨</button>
                        <div>
                            <a href="{{ isset($sectionSlug) ? route('platform.home', $sectionSlug) : route('platform.home', 'emt-basic') }}" class="block">
                                <p class="text-sm font-bold tracking-wide text-white">SalusPrep</p>
                                <p class="text-xs font-medium text-medic-light">
                                    @isset($sectionLabel)
                                        {{ $sectionLabel }} {{ $sectionHeaderTag ?? \App\Support\CertificationLevel::NREMT_MARK }}
                                    @else
                                        Adaptive Practice
                                    @endisset
                                </p>
                            </a>
                        </div>
                        <div
                            id="platform-switcher-menu"
                            class="invisible absolute left-0 top-full z-[100] w-64 pt-2 opacity-0 transition-opacity duration-150 group-hover:visible group-hover:opacity-100 group-focus-within:visible group-focus-within:opacity-100"
                            role="menu"
                        >
                            <div class="overflow-hidden rounded-xl border border-slate-600 bg-[#1e293b] shadow-2xl">
                            <p class="border-b border-slate-600 bg-[#0f172a] px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-400">Switch platform</p>
                            @foreach ($platformSwitcherGroups ?? [] as $group)
                                <p class="border-b border-slate-600/60 bg-[#0f172a]/80 px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-500">{{ $group['title'] }}</p>
                                @foreach ($group['items'] as $platform)
                                    <a href="{{ route('platform.home', $platform['slug']) }}"
                                       role="menuitem"
                                       class="block px-4 py-3 text-sm hover:bg-slate-700 {{ $platform['active'] ? 'bg-medic/20 font-bold text-medic-light' : 'text-slate-200' }}">
                                        {{ $platform['label'] }}
                                        @if ($platform['active'])
                                            <span class="ml-1 text-xs text-medic-light">●</span>
                                        @endif
                                    </a>
                                @endforeach
                            @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route('platform.home', 'emt-basic') }}" class="flex h-11 w-11 items-center justify-center rounded-lg bg-medic text-3xl font-bold text-white shadow-lg shadow-medic/25">⛨</a>
                        <div>
                            <a href="{{ route('platform.home', 'emt-basic') }}" class="block">
                                <p class="text-sm font-bold tracking-wide text-white">SalusPrep</p>
                                <p class="text-xs font-medium text-medic-light">Adaptive Practice</p>
                            </a>
                        </div>
                    @endisset
                </div>

                <nav class="hidden items-center gap-2 text-sm md:flex">
                    @isset($sectionSlug)
                        <x-exam-countdown />
                        <x-welcome-nav-link :link="$welcomeNavLink ?? null" />
                        <a href="{{ route('platform.dashboard', $sectionSlug) }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-medic-light">Test Center</a>
                        <a href="{{ route('skills.index', $sectionSlug) }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-safety-light">Skills</a>
                        <a href="{{ route('study.index', $sectionSlug) }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-ems-light">Flashcards</a>
                        <a href="{{ route('review.index', $sectionSlug) }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-pharma-light">Review</a>
                    @endisset
                    @auth
                        <x-user-menu />
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-white">Log in</a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-medic px-4 py-2 font-bold text-white hover:bg-medic-dark">Sign up</a>
                    @endauth
                </nav>

                <x-mobile-nav />
            </div>
        </header>

        <main class="relative z-0 mx-auto w-full max-w-5xl flex-1 px-4 py-8 sm:px-6 sm:py-12">
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
            @stack('page-footer')
        </main>

        <footer class="theme-footer relative z-10 border-t border-white/10 bg-[#1e293b]">
            <div class="mx-auto max-w-5xl px-4 py-6 sm:px-6">
                <div class="flex flex-col items-center justify-between gap-3 text-sm text-slate-500 sm:flex-row">
                    <p>&copy; {{ date('Y') }} SalusPrep. All rights reserved.</p>
                    <nav class="flex items-center gap-4">
                        <a href="{{ route('legal.about') }}" class="hover:text-medic-light">About &amp; Contact</a>
                        <a href="{{ route('legal.terms') }}" class="hover:text-medic-light">Terms of Service</a>
                        <a href="{{ route('legal.privacy') }}" class="hover:text-medic-light">Privacy Policy</a>
                        @isset($previewTimer)
                            <x-preview-timer :timer="$previewTimer" />
                        @endisset
                    </nav>
                </div>
                <div class="mt-3 text-center sm:text-left">
                    <x-footer-affiliation-notice />
                </div>
            </div>
        </footer>
    </div>

    <x-cookie-consent />

    @isset($platformSections)
    <script>
        (function () {
            var switcher = document.getElementById('platform-switcher');
            var btn = document.getElementById('platform-switcher-btn');
            var menu = document.getElementById('platform-switcher-menu');
            if (!switcher || !btn || !menu) return;

            function setExpanded(isOpen) {
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            switcher.addEventListener('mouseenter', function () {
                setExpanded(true);
            });

            switcher.addEventListener('mouseleave', function () {
                setExpanded(false);
            });

            switcher.addEventListener('focusin', function () {
                setExpanded(true);
            });

            switcher.addEventListener('focusout', function () {
                if (!switcher.contains(document.activeElement)) {
                    setExpanded(false);
                }
            });
        })();
    </script>
    @endisset

    @auth
    <script>
        (function () {
            var menu = document.getElementById('user-menu');
            var btn = document.getElementById('user-menu-btn');
            if (!menu || !btn) return;

            function setExpanded(isOpen) {
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            }

            menu.addEventListener('mouseenter', function () {
                setExpanded(true);
            });

            menu.addEventListener('mouseleave', function () {
                setExpanded(false);
            });

            menu.addEventListener('focusin', function () {
                setExpanded(true);
            });

            menu.addEventListener('focusout', function () {
                if (!menu.contains(document.activeElement)) {
                    setExpanded(false);
                }
            });
        })();
    </script>
    @endauth

    @unless (file_exists(public_path('build/manifest.json')))
    <style>
        .cookie-consent {
            border-color: rgba(255, 255, 255, 0.12);
            background-color: rgba(30, 41, 59, 0.98);
            backdrop-filter: blur(8px);
        }
    </style>
    <script>
        (function () {
            var consentKey = 'salusprep-cookie-consent';

            function hideBanner() {
                var banner = document.getElementById('cookie-consent');
                if (banner) {
                    banner.classList.add('hidden');
                }
            }

            function acceptCookies() {
                try {
                    localStorage.setItem(consentKey, 'accepted');
                } catch (e) {}

                hideBanner();
            }

            try {
                if (localStorage.getItem(consentKey) === 'accepted') {
                    hideBanner();
                    return;
                }
            } catch (e) {}

            var banner = document.getElementById('cookie-consent');
            if (banner) {
                banner.classList.remove('hidden');
            }

            document.querySelectorAll('[data-cookie-accept]').forEach(function (button) {
                button.addEventListener('click', acceptCookies);
            });
        })();
    </script>
    @endunless
</body>
</html>
