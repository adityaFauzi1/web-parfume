@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

<section class="relative overflow-hidden bg-stone-950 text-white">
    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 via-stone-900 to-stone-950"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 sm:py-32 lg:px-8 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-300">Sejak 2015</p>
        <h1 class="mx-auto mt-4 max-w-3xl font-display text-4xl leading-tight sm:text-5xl">Sepuluh Tahun Menghadirkan Aroma yang Berkesan</h1>
    </div>
</section>

<section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="prose-sm max-w-none space-y-6 leading-relaxed text-stone-600">
        <p>
            <strong class="text-stone-900">Maison Parfum</strong> lahir dari kecintaan terhadap seni parfum. Dimulai dari sebuah butik kecil di Jakarta pada tahun 2015,
            kami berkomitmen untuk membawa fragrance orisinal dari maison-maison ternama dunia ke tangan para pecinta wangi Indonesia.
        </p>
        <p>
            Setiap produk yang kami kurasi melewati proses seleksi ketat untuk memastikan keaslian, kualitas, dan daya tahannya.
            Kami percaya bahwa parfum bukan sekadar wewangian — ia adalah identitas, kenangan, dan pernyataan diri yang tak terucapkan.
        </p>
    </div>

    <div class="mt-14 grid gap-6 sm:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <p class="font-display text-4xl text-amber-700">10+</p>
            <p class="mt-2 text-sm font-medium text-stone-900">Tahun Pengalaman</p>
            <p class="mt-1 text-sm text-stone-500">Melayani ribuan pelanggan setia.</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <p class="font-display text-4xl text-amber-700">250+</p>
            <p class="mt-2 text-sm font-medium text-stone-900">Koleksi Produk</p>
            <p class="mt-1 text-sm text-stone-500">Dari brand lokal hingga niche internasional.</p>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            <p class="font-display text-4xl text-amber-700">100%</p>
            <p class="mt-2 text-sm font-medium text-stone-900">Produk Orisinal</p>
            <p class="mt-1 text-sm text-stone-500">Dijamin keasliannya, tanpa terkecuali.</p>
        </div>
    </div>

    <div class="mt-16 flex justify-center">
        <a href="{{ route('shop.index') }}" class="inline-flex items-center rounded-full bg-amber-500 px-8 py-3 font-semibold text-stone-950 transition hover:bg-amber-400">
            Mulai Berbelanja
            <span class="ml-2">→</span>
        </a>
    </div>
</section>

@endsection