@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')

<section class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="text-center">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-4xl text-emerald-600">✓</div>
        <h1 class="mt-6 font-display text-3xl sm:text-4xl">Pembayaran Berhasil!</h1>
        <p class="mt-3 text-stone-500">
            Terima kasih atas pembelian Anda. Stok produk telah diperbarui otomatis.
        </p>
        <p class="mt-1 inline-block rounded-full bg-stone-100 px-5 py-1.5 text-sm font-semibold text-stone-700">
            Invoice: {{ $sale->invoice }}
        </p>
    </div>

    <div class="mt-10 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="flex items-center justify-between border-b border-stone-100 px-6 py-4">
            <h2 class="font-display text-lg">Detail Pesanan</h2>
            <span class="text-sm text-stone-500">{{ $sale->created_at->translatedFormat('l, d F Y · H:i') }}</span>
        </div>

        <div class="divide-y divide-stone-100">
            @foreach($sale->items as $item)
                <div class="flex items-center gap-4 px-6 py-4">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-stone-900">{{ $item->product_name }}</p>
                        <p class="text-xs text-stone-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-sm font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between border-t border-stone-100 bg-stone-50 px-6 py-4">
            <span class="font-semibold text-stone-700">Total Dibayar</span>
            <span class="font-display text-2xl text-amber-700">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($sale->customer_name)
        <p class="mt-4 text-sm text-stone-500">Atas nama: <strong>{{ $sale->customer_name }}</strong></p>
    @endif

    <div class="mt-8 flex justify-center gap-4">
        <a href="{{ route('shop.index') }}" class="rounded-full bg-stone-900 px-8 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">
            Belanja Lagi
        </a>
        <a href="{{ route('home') }}" class="rounded-full border border-stone-300 px-8 py-3 text-sm font-medium text-stone-600 hover:border-amber-500 hover:text-amber-700">
            Ke Beranda
        </a>
    </div>
</section>

@endsection