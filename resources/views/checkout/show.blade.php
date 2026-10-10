@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<section class="border-b border-stone-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Pembayaran</p>
        <h1 class="mt-2 font-display text-4xl">Checkout</h1>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-5">
        {{-- Form data pembeli --}}
        <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form" class="space-y-4 lg:col-span-3">
            @csrf

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
                <h2 class="font-display text-lg">Data Pembeli</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="customer_name" class="mb-1 block text-sm font-medium text-stone-700">Nama</label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                               class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                        @error('customer_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="customer_phone" class="mb-1 block text-sm font-medium text-stone-700">No. WhatsApp</label>
                        <input type="text" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                        @error('customer_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Ringkasan pesanan --}}
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
                <h2 class="border-b border-stone-100 px-6 py-4 font-display text-lg">Ringkasan Pesanan ({{ count($items) }} item)</h2>

                <div class="divide-y divide-stone-100">
                    @foreach($items as $item)
                        <div class="flex items-center gap-4 px-6 py-4">
                            <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="h-14 w-14 rounded-lg object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-stone-900">{{ $item['product']->name }}</p>
                                <p class="text-xs text-stone-500">{{ $item['product']->size }} · {{ $item['qty'] }} × {{ $item['product']->formatted_price }}</p>
                            </div>
                            <p class="text-sm font-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between border-t border-stone-100 px-6 py-4">
                    <span class="font-medium text-stone-700">Total Pembayaran</span>
                    <span class="font-display text-2xl text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </form>

        {{-- Ringkasan + tombol bayar --}}
        <div class="lg:col-span-2">
            <div class="sticky top-24 space-y-4">
                <div class="rounded-2xl bg-stone-950 p-6 text-white">
                    <h3 class="font-display text-lg text-amber-200">Rincian</h3>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-stone-400">Subtotal</dt>
                            <dd>Rp {{ number_format($total, 0, ',', '.') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-stone-400">Ongkos Kirim</dt>
                            <dd class="text-emerald-400">Gratis</dd>
                        </div>
                        <div class="flex justify-between border-t border-stone-800 pt-3 font-semibold">
                            <dt>Total</dt>
                            <dd class="font-display text-xl text-amber-200">Rp {{ number_format($total, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                    <p class="mt-4 rounded-lg bg-stone-900 px-4 py-3 text-xs leading-relaxed text-stone-400">
                        Dengan menekan tombol bayar, stok produk akan otomatis berkurang sesuai jumlah yang dibeli.
                    </p>
                </div>

                <button type="submit" form="checkout-form" class="w-full rounded-full bg-amber-500 px-8 py-4 text-sm font-bold text-stone-950 transition hover:bg-amber-400">
                    Bayar Sekarang — Rp {{ number_format($total, 0, ',', '.') }}
                </button>
                <a href="{{ route('cart.index') }}" class="block text-center text-sm text-stone-500 hover:text-amber-700">← Kembali ke Keranjang</a>
            </div>
        </div>
    </div>
</section>

@endsection