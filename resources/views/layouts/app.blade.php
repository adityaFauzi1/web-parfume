<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Maison Parfum') — Maison Parfum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .font-display { font-family: 'Playfair Display', Georgia, serif; }
        ::selection { background: #d97706; color: #fff; }
    </style>
</head>
<body class="bg-stone-50 text-stone-800 antialiased">

    <header class="sticky top-0 z-50 bg-stone-950/95 backdrop-blur border-b border-stone-800">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-2xl">✦</span>
                    <span class="font-display text-xl tracking-wide text-amber-200">Maison Parfum</span>
                </a>

                <div class="hidden md:flex items-center gap-8 text-sm">
                    <a href="{{ route('home') }}" class="text-stone-300 hover:text-amber-200 transition">Beranda</a>
                    <a href="{{ route('shop.index') }}" class="text-stone-300 hover:text-amber-200 transition">Produk</a>
                    <a href="{{ route('about') }}" class="text-stone-300 hover:text-amber-200 transition">Tentang</a>
                    <a href="{{ route('contact') }}" class="text-stone-300 hover:text-amber-200 transition">Kontak</a>
                </div>

                <div class="flex items-center gap-1 sm:gap-3">
                    {{-- Hamburger (mobile only) --}}
                    <button type="button" id="mobile-menu-btn"
                        class="rounded-full p-2 text-stone-300 hover:text-amber-200 hover:bg-stone-800 transition md:hidden"
                        aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu">
                        <svg id="icon-open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    @if(session('cart'))
                        <a href="{{ route('cart.index') }}" class="relative rounded-full p-2 text-stone-300 hover:text-amber-200 hover:bg-stone-800 transition" aria-label="Keranjang">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                            </svg>
                            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-[11px] font-bold text-stone-950">{{ array_sum(session('cart')) }}</span>
                        </a>
                    @else
                        <a href="{{ route('cart.index') }}" class="relative rounded-full p-2 text-stone-300 hover:text-amber-200 hover:bg-stone-800 transition" aria-label="Keranjang">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                            </svg>
                        </a>
                    @endif
                    <a href="{{ route('admin.login') }}" class="hidden sm:inline-flex items-center rounded-full border border-stone-700 px-4 py-2 text-xs font-medium text-stone-300 hover:border-amber-500 hover:text-amber-200 transition">
                        Admin
                    </a>
                </div>
            </div>
        </nav>
        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden border-t border-stone-800 px-4 pb-4 pt-2 md:hidden">
            <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2.5 text-sm text-stone-300 hover:bg-stone-800 hover:text-amber-200 transition">Beranda</a>
            <a href="{{ route('shop.index') }}" class="block rounded-lg px-3 py-2.5 text-sm text-stone-300 hover:bg-stone-800 hover:text-amber-200 transition">Produk</a>
            <a href="{{ route('about') }}" class="block rounded-lg px-3 py-2.5 text-sm text-stone-300 hover:bg-stone-800 hover:text-amber-200 transition">Tentang</a>
            <a href="{{ route('contact') }}" class="block rounded-lg px-3 py-2.5 text-sm text-stone-300 hover:bg-stone-800 hover:text-amber-200 transition">Kontak</a>
            <a href="{{ route('admin.login') }}" class="mt-1 block rounded-lg border border-stone-700 px-3 py-2.5 text-center text-xs font-medium text-stone-300 hover:border-amber-500 hover:text-amber-200 transition sm:hidden">Admin</a>
        </div>
    </header>

    @if(session('success'))
        <div class="mx-auto max-w-7xl px-4 pt-4">
            <div class="flex items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">✕</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-auto max-w-7xl px-4 pt-4">
            <div class="flex items-center justify-between rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <span>⚠ {{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-900">✕</button>
            </div>
        </div>
    @endif

    <main>
    @yield('content')
</main>

<footer class="mt-24 border-t border-stone-800 bg-stone-950 text-stone-400">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-4">
            <div class="md:col-span-2">
                <p class="font-display text-2xl text-amber-200">Maison Parfum</p>
                <p class="mt-3 max-w-md text-sm leading-relaxed">
                    Toko parfum terpercaya sejak 2015. Menghadirkan koleksi fragrance orisinal dari merek ternama dunia untuk menemani setiap momen berharga Anda.
                </p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-stone-200">Navigasi</p>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="{{ route('shop.index') }}" class="hover:text-amber-300 transition">Semua Produk</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-amber-300 transition">Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-amber-300 transition">Kontak</a></li>
                    <li><a href="{{ route('cart.index') }}" class="hover:text-amber-300 transition">Keranjang</a></li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-stone-200">Kontak</p>
                <ul class="mt-4 space-y-2 text-sm">
                    <li>Jl. Melati No. 12, Jakarta</li>
                    <li>+62 812-3456-7890</li>
                    <li>halo@maisonparfum.com</li>
                    <li>Senin – Sabtu, 09.00 – 21.00</li>
                </ul>
            </div>
        </div>
        <div class="mt-10 border-t border-stone-800 pt-6 text-center text-xs text-stone-600">
            © {{ date('Y') }} Maison Parfum. Dibuat dengan Laravel.
        </div>
    </div>
</footer>

<script>
    (function () {
        var btn = document.getElementById('mobile-menu-btn');
        var menu = document.getElementById('mobile-menu');
        if (!btn || !menu) return;
        var iconOpen = document.getElementById('icon-open');
        var iconClose = document.getElementById('icon-close');
        btn.addEventListener('click', function () {
            var isHidden = menu.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', String(!isHidden));
            if (iconOpen) iconOpen.classList.toggle('hidden', !isHidden);
            if (iconClose) iconClose.classList.toggle('hidden', isHidden);
        });
    })();
</script>

</body>
</html>