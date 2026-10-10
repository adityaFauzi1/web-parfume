<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin Maison Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-display { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>
<body class="bg-stone-100 text-stone-800 antialiased">

<div class="flex min-h-screen">
    {{-- Overlay (mobile) --}}
    <div id="admin-sidebar-overlay" class="fixed inset-0 z-40 hidden bg-stone-950/60 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 shrink-0 -translate-x-full flex-col border-r border-stone-800 bg-stone-950 text-stone-300 transition-transform duration-300 lg:static lg:translate-x-0">
        <div class="flex h-16 items-center gap-2 border-b border-stone-800 px-6">
            <span class="text-xl">✦</span>
            <span class="font-display tracking-wide text-amber-200">Maison Parfum</span>
        </div>

        <nav class="space-y-1 px-3 py-4 text-sm">
            <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-stone-500">Menu</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500/15 text-amber-300' : 'hover:bg-stone-800 hover:text-white' }}">
                <span>📊</span> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition {{ request()->routeIs('admin.products.*') ? 'bg-amber-500/15 text-amber-300' : 'hover:bg-stone-800 hover:text-white' }}">
                <span>🫙</span> Produk
                @if(! empty($lowStockCount = \App\Models\Product::lowStock()->count()))
                    <span class="ml-auto rounded-full bg-red-500/20 px-2 py-0.5 text-[11px] font-semibold text-red-300">{{ $lowStockCount }}⚠</span>
                @endif
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500/15 text-amber-300' : 'hover:bg-stone-800 hover:text-white' }}">
                <span>🏷️</span> Kategori
            </a>
            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition {{ request()->routeIs('admin.reports') ? 'bg-amber-500/15 text-amber-300' : 'hover:bg-stone-800 hover:text-white' }}">
                <span>📈</span> Laporan Penjualan
            </a>
        </nav>

        <div class="mt-auto w-64 border-t border-stone-800 px-6 py-4">
            <p class="text-sm font-medium text-stone-200">{{ auth()->user()->name }}</p>
            <p class="text-xs text-stone-500">{{ auth()->user()->email }}</p>
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-stone-700 px-3 py-2 text-xs font-medium text-stone-300 transition hover:border-red-500 hover:text-red-400">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Konten --}}
    <div class="min-w-0 flex-1">
        <header class="sticky top-0 z-40 flex h-16 items-center justify-between gap-3 border-b border-stone-200 bg-white/90 px-4 backdrop-blur sm:px-8">
            <div class="flex min-w-0 items-center gap-2">
                <button type="button" id="admin-menu-btn" class="rounded-full p-2 text-stone-600 hover:bg-stone-100 hover:text-stone-900 lg:hidden" aria-label="Buka menu admin">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <h1 class="truncate font-display text-base sm:text-lg">@yield('title', 'Dashboard')</h1>
            </div>
            <a href="{{ route('home') }}" class="shrink-0 rounded-full border border-stone-300 px-3 py-2 text-xs font-medium text-stone-600 transition hover:border-amber-500 hover:text-amber-700 sm:px-4">
                <span class="hidden sm:inline">Lihat Website ↗</span><span class="sm:hidden">Website ↗</span>
            </a>
        </header>

        @if(session('success'))
            <div class="mx-4 mt-6 flex items-center justify-between gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 sm:mx-8">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-4 mt-6 flex items-center justify-between gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 sm:mx-8">
                <span>⚠ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">✕</button>
            </div>
        @endif

        <main class="overflow-x-auto p-4 pt-6 sm:p-8 sm:pt-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    (function () {
        var btn = document.getElementById('admin-menu-btn');
        var sidebar = document.getElementById('admin-sidebar');
        var overlay = document.getElementById('admin-sidebar-overlay');
        if (!btn || !sidebar) return;
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
        }
        function closeSidebar() {
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
            }
        }
        btn.addEventListener('click', function () {
            sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
        });
        if (overlay) overlay.addEventListener('click', closeSidebar);
        sidebar.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', closeSidebar);
        });
    })();
</script>

@yield('scripts')

</body>
</html>