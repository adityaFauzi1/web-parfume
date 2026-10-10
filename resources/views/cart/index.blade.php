@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')

<section class="border-b border-stone-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Belanja Anda</p>
        <h1 class="mt-2 font-display text-4xl">Keranjang Belanja</h1>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    @if(empty($items))
        <div class="rounded-2xl border border-dashed border-stone-300 py-20 text-center">
            <p class="text-4xl">🛒</p>
            <p class="mt-3 font-semibold text-stone-700">Keranjang Anda kosong</p>
            <p class="mt-1 text-sm text-stone-500">Temukan fragrance favorit Anda di katalog kami.</p>
            <a href="{{ route('shop.index') }}" class="mt-5 inline-block rounded-full bg-amber-500 px-8 py-3 font-semibold text-stone-950 transition hover:bg-amber-400">
                Jelajahi Produk
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left text-xs uppercase tracking-wider text-stone-500">
                    <tr>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4 text-right">Subtotal</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @foreach($items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <a href="{{ route('shop.show', $item['product']->slug) }}" class="flex items-center gap-4">
                                    <img src="{{ asset($item['product']->image) }}" alt="{{ $item['product']->name }}" class="h-16 w-16 rounded-lg object-cover">
                                    <span>
                                        <span class="block font-medium text-stone-900">{{ $item['product']->name }}</span>
                                        <span class="block text-xs text-stone-500">{{ $item['product']->size }} · {{ $item['product']->gender }}</span>
                                    </span>
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $item['product']->formatted_price }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('cart.update', $item['product']->slug) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="0" max="99"
                                           class="w-20 rounded-lg border border-stone-300 px-3 py-1.5 text-center text-sm focus:border-amber-500 focus:outline-none">
                                    <button type="submit" class="rounded-lg border border-stone-300 px-3 py-1.5 text-xs font-medium hover:border-amber-500 hover:text-amber-700">Update</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-stone-900">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('cart.remove', $item['product']->slug) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-stone-400 hover:text-red-600" aria-label="Hapus">✕</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-medium text-red-600 hover:underline">Kosongkan Keranjang</button>
            </form>

            <div class="w-full rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200 sm:w-80">
                <div class="flex items-center justify-between">
                    <span class="text-stone-600">Total</span>
                    <span class="font-display text-2xl text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <p class="mt-2 text-xs text-stone-500">Ongkos kirim dihitung saat checkout.</p>
                <a href="{{ route('checkout.show') }}" class="mt-4 block rounded-full bg-amber-500 px-6 py-3 text-center text-sm font-semibold text-stone-950 transition hover:bg-amber-400">
                    Lanjut ke Checkout →
                </a>
                <a href="{{ route('shop.index') }}" class="mt-2 block rounded-full border border-stone-300 px-6 py-2.5 text-center text-sm font-medium text-stone-600 transition hover:border-amber-500 hover:text-amber-700">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    @endif
</section>

@endsection