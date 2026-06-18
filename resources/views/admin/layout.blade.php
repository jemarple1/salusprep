<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — SalusPrep Admin</title>
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
        <style>{!! file_get_contents(resource_path('css/theme.css')) !!}</style>
    @endif
</head>
<body class="min-h-screen bg-navy text-slate-100 antialiased">
    <header class="border-b border-white/10 bg-[#1e293b]">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
            <div>
                <p class="text-sm font-bold uppercase tracking-wider text-safety-light">SalusPrep Admin</p>
                <p class="text-xs text-slate-400">Platform growth dashboard</p>
            </div>
            @auth('admin')
                <div class="flex items-center gap-4 text-sm">
                    <span class="text-slate-400">{{ auth('admin')->user()->username }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg px-3 py-2 font-medium text-slate-300 hover:bg-white/5 hover:text-white">Log out</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-10">
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
    @stack('scripts')
</body>
</html>
