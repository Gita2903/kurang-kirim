<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Surat Jalan - Kurang Kirim">
    <title>@yield('title', 'Kurang Kirim') — Surat Jalan</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tom Select for Searchable Dropdowns --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        /* TomSelect Dark Theme Overrides */
        .ts-control {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #f1f5f9 !important;
            border-radius: 0.75rem !important;
            padding: 0.65rem 1rem !important;
            min-height: 46px;
            box-shadow: none !important;
            transition: all 0.3s ease;
        }
        .ts-control.focus {
            border-color: rgba(139, 92, 246, 0.5) !important;
            background-color: rgba(255, 255, 255, 0.07) !important;
            box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2) !important;
        }
        .ts-control > input { color: #f1f5f9 !important; }
        .ts-dropdown {
            background-color: #0f172a !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #f1f5f9 !important;
            border-radius: 0.75rem !important;
            overflow: hidden;
            margin-top: 4px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
        }
        .ts-dropdown .option { padding: 10px 16px; cursor: pointer; }
        .ts-dropdown .option:hover, .ts-dropdown .option.active {
            background-color: rgba(139, 92, 246, 0.2) !important;
            color: white !important;
        }
        .ts-control .item { color: #f1f5f9 !important; }
    </style>

</head>
<body class="min-h-screen bg-slate-950 text-slate-100 font-sans antialiased">

    {{-- Animated Background Gradient --}}
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 -right-40 h-[500px] w-[500px] rounded-full bg-violet-600/10 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 h-[500px] w-[500px] rounded-full bg-cyan-600/10 blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-indigo-600/5 blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>

    {{-- Navigation --}}
    <nav class="sticky top-0 z-50 border-b border-white/5 bg-slate-950/80 backdrop-blur-xl">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-cyan-500 shadow-lg shadow-violet-500/20 group-hover:shadow-violet-500/40 transition-shadow duration-300">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold bg-gradient-to-r from-violet-400 to-cyan-400 bg-clip-text text-transparent">
                        Kurang Kirim
                    </span>
                </a>

                {{-- Nav Links --}}
                <div class="flex items-center gap-1 sm:gap-2">
                    <a href="{{ route('dashboard') }}"
                       class="relative px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('dashboard') ? 'text-white bg-white/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">Input Surat Jalan</span>
                        </span>
                        @if(request()->routeIs('dashboard'))
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 w-8 bg-gradient-to-r from-violet-500 to-cyan-500 rounded-full"></span>
                        @endif
                    </a>
                    <a href="{{ route('history') }}"
                       class="relative px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('history') ? 'text-white bg-white/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="hidden sm:inline">Riwayat</span>
                        </span>
                        @if(request()->routeIs('history'))
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 w-8 bg-gradient-to-r from-violet-500 to-cyan-500 rounded-full"></span>
                        @endif
                    </a>
                    <a href="{{ route('toko.index') }}"
                       class="relative px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('toko.*') ? 'text-white bg-white/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="hidden sm:inline">Master Toko</span>
                        </span>
                        @if(request()->routeIs('toko.*'))
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 w-8 bg-gradient-to-r from-violet-500 to-cyan-500 rounded-full"></span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div id="flash-success" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-emerald-300 backdrop-blur-sm animate-slide-down">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
                <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-200 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-auto border-t border-white/5 py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="text-center text-xs text-slate-600">
                &copy; {{ date('Y') }} Kurang Kirim — Sistem Manajemen Surat Jalan
            </p>
        </div>
    </footer>

    {{-- Auto-dismiss flash message --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const flash = document.getElementById('flash-success');
            if (flash) {
                setTimeout(() => {
                    flash.style.transition = 'opacity 500ms, transform 500ms';
                    flash.style.opacity = '0';
                    flash.style.transform = 'translateY(-10px)';
                    setTimeout(() => flash.remove(), 500);
                }, 5000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
