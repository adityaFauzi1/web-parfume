@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden bg-stone-950 text-white">
    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 via-stone-900 to-stone-950"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-28 sm:px-6 sm:py-36 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-300">Essence Collection 2026</p>
            <h1 class="mt-4 font-display text-4xl leading-tight sm:text-6xl">
                Temukan Wangi yang <span class="italic text-amber-300">Menceritakan</span> Dirimu
            </h1>
            <p class="mt-6 text-lg leading-relaxed text-stone-300">
                Maison Parfum menghadirkan koleksi fragrance orisinal dari merek ikonik dunia.
                Dari parfum pria maskulin hingga aroma feminin elegan, semua tersedia untuk Anda.
            </p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('shop.index') }}" class="inline-flex items-center rounded-full bg-amber-500 px-8 py-3 font-semibold text-stone-950 transition hover:bg-amber-400">
                    Jelajahi Koleksi
                    <span class="ml-2">→</span>
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center rounded-full border border-stone-600 px-8 py-3 font-medium text-stone-200 transition hover:border-amber-400 hover:text-amber-300">
                    Tentang Kami
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Kategori --}}
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Kategori</p>
            <h2 class="mt-2 font-display text-3xl">Jelajahi Berdasarkan Kategori</h2>
        </div>
        <a href="{{ route('shop.index') }}" class="hidden text-sm font-medium text-amber-700 hover:text-amber-900 sm:block">Lihat Semua →</a>
    </div>

    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($categories as $category)
            <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="group relative overflow-hidden rounded-2xl shadow-sm ring-1 ring-stone-200">
                <div class="aspect-[4/5] overflow-hidden">
                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" loading="lazy"
                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                </div>
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-stone-950/90 to-transparent p-5">
                    <p class="font-display text-xl text-white">{{ $category->name }}</p>
                    <p class="mt-1 text-xs text-stone-300">{{ $category->products_count }} produk</p>
                </div>
            </a>
        @empty
            <p class="text-stone-500">Belum ada kategori.</p>
        @endforelse
    </div>
</section>
{{-- Produk Unggulan --}}
<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Kurasi Kami</p>
                <h2 class="mt-2 font-display text-3xl">Produk Unggulan</h2>
            </div>
            <a href="{{ route('shop.index') }}" class="hidden text-sm font-medium text-amber-700 hover:text-amber-900 sm:block">Lihat Semua →</a>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($featured as $product)
                @include('partials.product-card', ['product' => $product])
            @empty
                <p class="text-stone-500">Belum ada produk unggulan.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Keunggulan --}}
<section class="bg-stone-950 py-20 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-3">
            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-500/20 text-2xl">✓</div>
                <h3 class="mt-4 font-display text-lg">100% Orisinal</h3>
                <p class="mt-2 text-sm leading-relaxed text-stone-400">Seluruh produk kami dijamin keasliannya, diimpor langsung dari distributor resmi.</p>
            </div>
            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-500/20 text-2xl">⚡</div>
                <h3 class="mt-4 font-display text-lg">Pengiriman Cepat</h3>
                <p class="mt-2 text-sm leading-relaxed text-stone-400">Pesanan diproses maksimal 1x24 jam dengan kemasan premium yang aman.</p>
            </div>
            <div class="text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-500/20 text-2xl">↺</div>
                <h3 class="mt-4 font-display text-lg">Garansi 3 Hari</h3>
                <p class="mt-2 text-sm leading-relaxed text-stone-400">Tukar atau kembalikan produk dalam 3 hari jika ada masalah yang kami tanggung.</p>
            </div>
        </div>
    </div>
</section>

{{-- Produk Terbaru --}}
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Baru Masuk</p>
            <h2 class="mt-2 font-display text-3xl">Produk Terbaru</h2>
        </div>
        <a href="{{ route('shop.index') }}" class="hidden text-sm font-medium text-amber-700 hover:text-amber-900 sm:block">Lihat Semua →</a>
    </div>

    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($latest as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <p class="text-stone-500">Belum ada produk.</p>
        @endforelse
    </div>
</section>

@endsection