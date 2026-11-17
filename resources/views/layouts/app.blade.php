<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laboratorium Bioakustik - SK03')</title>
    <!-- Menggunakan CDN Tailwind CSS untuk kemudahan styling kustom cepat -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @media (min-width: 768px) {
            #app-sidebar[data-collapsed="true"] {
                width: 5rem;
            }

            #app-sidebar[data-collapsed="true"] [data-sidebar-label] {
                display: none;
            }

            #app-sidebar[data-collapsed="true"] .sidebar-link {
                justify-content: center;
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            #app-sidebar[data-collapsed="true"] #desktop-sidebar-toggle svg {
                transform: rotate(180deg);
            }
        }

        #app-sidebar {
            transition: width 180ms ease, transform 180ms ease;
        }

        @media (prefers-reduced-motion: reduce) {
            #app-sidebar {
                transition: none;
            }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <header class="sticky top-0 z-20 border-b border-emerald-950 bg-emerald-950 text-white shadow-sm">
        <div class="flex h-16 items-center gap-3 px-4 sm:px-6">
            <button id="mobile-sidebar-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-emerald-100 transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white md:hidden" aria-label="Buka menu navigasi" aria-controls="app-sidebar" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-700 text-sm font-bold tracking-wide text-white">AB</div>
            <div>
                <h1 class="text-sm font-bold tracking-wide sm:text-base">Laboratorium Bioakustik</h1>
                <p class="text-xs text-emerald-300">Pengelolaan Data Lapangan</p>
            </div>
        </div>
    </header>

    <div class="flex min-h-[calc(100vh-4rem)] flex-col md:flex-row">
        <button id="sidebar-backdrop" type="button" class="fixed inset-x-0 bottom-0 top-16 z-20 hidden bg-slate-950/40 md:hidden" aria-label="Tutup menu navigasi"></button>
        <aside id="app-sidebar" data-collapsed="false" class="fixed bottom-0 left-0 top-16 z-30 hidden w-72 overflow-y-auto border-r border-slate-200 bg-white shadow-xl md:sticky md:top-16 md:z-auto md:block md:h-[calc(100vh-4rem)] md:w-64 md:shrink-0 md:shadow-none">
            <div class="flex items-center justify-between px-4 pb-2 pt-6">
                <span data-sidebar-label class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menu utama</span>
                <button id="desktop-sidebar-toggle" type="button" class="hidden h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition hover:bg-slate-100 hover:text-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 md:inline-flex" aria-label="Ciutkan menu samping" aria-controls="app-sidebar" aria-expanded="true" title="Ciutkan menu samping">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            <nav aria-label="Navigasi utama" class="flex flex-col gap-1 px-3 pb-5">
                <a href="{{ route('dashboard') }}" title="Dashboard" class="sidebar-link {{ request()->routeIs('dashboard') ? 'bg-emerald-800 text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-900' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 3.75h6.5v6.5h-6.5zM13.75 3.75h6.5v6.5h-6.5zM3.75 13.75h6.5v6.5h-6.5zM13.75 13.75h6.5v6.5h-6.5z" />
                    </svg>
                    <span data-sidebar-label>Dashboard</span>
                </a>
                <a href="{{ route('rekaman.index') }}" title="Data Rekaman" class="sidebar-link {{ request()->routeIs('rekaman.*') ? 'bg-emerald-800 text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-900' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 9v6m4-10v14m4-10v6m4-10v14m4-10v6" />
                    </svg>
                    <span data-sidebar-label>Data Rekaman</span>
                </a>
                <a href="{{ route('habitats.index') }}" title="Habitat & Ekosistem" class="sidebar-link {{ request()->routeIs('habitats.*') ? 'bg-emerald-800 text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-900' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 20v-8m0 4c-4.5 0-7-2.5-7-6.5C9 9.5 12 11 12 16zm0-2c0-4.5 2.5-7 7-7 0 4.5-2.5 7-7 7z" />
                    </svg>
                    <span data-sidebar-label>Habitat &amp; Ekosistem</span>
                </a>
                <a href="{{ route('titik-perekaman.index') }}" title="Titik Perekaman" class="sidebar-link {{ request()->routeIs('titik-perekaman.*') ? 'bg-emerald-800 text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-900' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 10.25c0 5.25-7.5 10-7.5 10s-7.5-4.75-7.5-10a7.5 7.5 0 1115 0z" />
                        <circle cx="12" cy="10" r="2.25" stroke-width="1.8" />
                    </svg>
                    <span data-sidebar-label>Titik Perekaman</span>
                </a>
                <a href="{{ route('anotasi.index') }}" title="Anotasi Suara" class="sidebar-link {{ request()->routeIs('anotasi.*') ? 'bg-emerald-800 text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-900' }} flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5.5v13l-4-3H4.5v-7H7l4-3zm4 4a3.5 3.5 0 010 5m2.5-7.5a7 7 0 010 10" />
                    </svg>
                    <span data-sidebar-label>Anotasi Suara</span>
                </a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                @if (session('success'))
                    <div role="status" class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div role="alert" class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="border-t border-slate-200 bg-white px-4 py-4 text-center text-xs text-slate-500 sm:text-sm">
                &copy; 2026 STTI NIIT I-Tech | Laboratorium Bioakustik
            </footer>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('app-sidebar');
        const mobileToggle = document.getElementById('mobile-sidebar-toggle');
        const desktopToggle = document.getElementById('desktop-sidebar-toggle');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');

        function setMobileSidebarOpen(isOpen) {
            sidebar.classList.toggle('hidden', !isOpen);
            sidebarBackdrop.classList.toggle('hidden', !isOpen);
            mobileToggle.setAttribute('aria-expanded', String(isOpen));
            mobileToggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
            document.body.classList.toggle('overflow-hidden', isOpen);
        }

        mobileToggle.addEventListener('click', function () {
            setMobileSidebarOpen(mobileToggle.getAttribute('aria-expanded') !== 'true');
        });

        sidebarBackdrop.addEventListener('click', function () {
            setMobileSidebarOpen(false);
        });

        desktopToggle.addEventListener('click', function () {
            const isCollapsed = sidebar.dataset.collapsed !== 'true';
            sidebar.dataset.collapsed = String(isCollapsed);
            desktopToggle.setAttribute('aria-expanded', String(!isCollapsed));
            desktopToggle.setAttribute('aria-label', isCollapsed ? 'Perluas menu samping' : 'Ciutkan menu samping');
            desktopToggle.setAttribute('title', isCollapsed ? 'Perluas menu samping' : 'Ciutkan menu samping');
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && mobileToggle.getAttribute('aria-expanded') === 'true') {
                setMobileSidebarOpen(false);
                mobileToggle.focus();
            }
        });
    </script>
</body>
</html>
