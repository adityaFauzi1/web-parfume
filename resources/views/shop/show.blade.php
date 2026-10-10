@extends('layouts.app')

@section('title', $product->name)

@section('content')

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <nav class="mb-8 text-sm text-stone-500">
        <a href="{{ route('home') }}" class="hover:text-amber-700">Beranda</a>
        <span class="mx-2">/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-amber-700">Produk</a>
        <span class="mx-2">/</span>
        <a href="{{ route('shop.index', ['category' => $product->category?->slug]) }}" class="hover:text-amber-700">{{ $product->category?->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-stone-800">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-10 lg:grid-cols-2">
        {{-- Gambar --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
        </div>

        {{-- Info --}}
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-600">{{ $product->category?->name }}</span>
                <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-medium text-stone-600">{{ $product->gender }}</span>
                @if($product->is_featured)
                    <span class="rounded-full bg-amber-500 px-3 py-1 text-xs font-semibold text-stone-950">Unggulan</span>
                @endif
            </div>

            <h1 class="mt-4 font-display text-3xl sm:text-4xl">{{ $product->name }}</h1>
            <p class="mt-3 font-display text-2xl text-amber-700">{{ $product->formatted_price }}</p>

            <p class="mt-6 leading-relaxed text-stone-600">{{ $product->description }}</p>

            <dl class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
                <div class="rounded-xl bg-white p-4 ring-1 ring-stone-200">
                    <dt class="text-xs uppercase tracking-wider text-stone-500">Ukuran</dt>
                    <dd class="mt-1 font-semibold">{{ $product->size }}</dd>
                </div>
                <div class="rounded-xl bg-white p-4 ring-1 ring-stone-200">
                    <dt class="text-xs uppercase tracking-wider text-stone-500">Gender</dt>
                    <dd class="mt-1 font-semibold">{{ $product->gender }}</dd>
                </div>
                <div class="rounded-xl bg-white p-4 ring-1 ring-stone-200">
                    <dt class="text-xs uppercase tracking-wider text-stone-500">Stok</dt>
                    <dd class="mt-1 font-semibold {{ $product->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                    </dd>
                </div>
            </dl>

            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->slug) }}" method="POST" class="mt-8 flex flex-wrap items-center gap-4">
                    @csrf
                    <label class="flex items-center gap-2 rounded-full border border-stone-300 px-4 py-2.5">
                        <span class="text-sm text-stone-600">Qty</span>
                        <input type="number" name="qty" value="1" min="1" max="{{ min(10, $product->stock) }}"
                               class="w-16 bg-transparent text-center text-sm font-semibold focus:outline-none">
                    </label>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-stone-900 px-8 py-3 font-semibold text-white transition hover:bg-amber-600 hover:text-stone-950">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                        </svg>
                        Tambah ke Keranjang
                    </button>
                </form>
            @else
                <div class="mt-8 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                    Maaf, produk ini sedang habis. Silakan cek kembali nanti.
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Produk Serupa --}}
@if($related->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <h2 class="font-display text-2xl">Produk Serupa</h2>
    <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($related as $product)
            @include('partials.product-card', ['product' => $product])
        @endforeach
    </div>
</section>
@endif

@endsection