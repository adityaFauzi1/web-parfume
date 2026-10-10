@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')

<section class="border-b border-stone-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Koleksi</p>
        <h1 class="mt-2 font-display text-4xl">Katalog Produk</h1>
        <p class="mt-3 max-w-xl text-stone-500">Jelajahi koleksi fragrance orisinal kami. Gunakan filter untuk menemukan wangi yang paling sesuai dengan kepribadian Anda.</p>

        <form method="GET" action="{{ route('shop.index') }}" class="mt-8 flex flex-col gap-3 lg:flex-row lg:items-center">
            <div class="flex-1">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama parfum..."
                       class="w-full rounded-full border border-stone-300 bg-stone-50 px-5 py-2.5 text-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200">
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                <select name="category" class="rounded-full border border-stone-300 bg-white px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="gender" class="rounded-full border border-stone-300 bg-white px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    <option value="">Semua Gender</option>
                    @foreach(['Pria', 'Wanita', 'Unisex'] as $gender)
                        <option value="{{ $gender }}" @selected(request('gender') === $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
                <select name="sort" class="rounded-full border border-stone-300 bg-white px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    <option value="latest" @selected(request('sort') === 'latest')>Terbaru</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga Terendah</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga Tertinggi</option>
                    <option value="name" @selected(request('sort') === 'name')>Nama A–Z</option>
                </select>
                <button type="submit" class="rounded-full bg-stone-900 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-stone-700">
                    Filter
                </button>
            </div>
        </form>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <p class="mb-6 text-sm text-stone-500">
        Menampilkan <strong>{{ $products->total() }}</strong> produk
        @if(request('q')) untuk pencarian "<strong>{{ request('q') }}</strong>"@endif
    </p>

    @if($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-stone-300 py-20 text-center">
            <p class="text-4xl">🫙</p>
            <p class="mt-3 font-semibold text-stone-700">Produk tidak ditemukan</p>
            <p class="mt-1 text-sm text-stone-500">Coba ubah kata kunci atau filter pencarian Anda.</p>
            <a href="{{ route('shop.index') }}" class="mt-5 inline-block text-sm font-medium text-amber-700 hover:underline">Reset Filter</a>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    @endif
</section>

@endsection