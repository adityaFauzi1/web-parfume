@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="font-display text-xl">Daftar Produk</h2>
        <p class="mt-1 text-sm text-stone-500">Total {{ $products->total() }} produk</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-full bg-amber-500 px-6 py-2.5 text-sm font-semibold text-stone-950 transition hover:bg-amber-400">
        + Tambah Produk
    </a>
</div>

<div class="mt-6 rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col gap-3 border-b border-stone-100 p-4 sm:flex-row">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
               class="flex-1 rounded-lg border border-stone-300 px-4 py-2 text-sm focus:border-amber-500 focus:outline-none">
        <select name="category" class="rounded-lg border border-stone-300 px-4 py-2 text-sm focus:border-amber-500 focus:outline-none">
            <option value="">Semua Kategori</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-stone-900 px-6 py-2 text-sm font-medium text-white transition hover:bg-stone-700">Filter</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-left text-xs uppercase tracking-wider text-stone-500">
                <tr>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Harga Jual</th>
                    <th class="px-6 py-4">Harga Beli</th>
                    <th class="px-6 py-4">Margin</th>
                    <th class="px-6 py-4">Stok</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover">
                                <div>
                                    <p class="font-medium text-stone-900">{{ $product->name }}</p>
                                    <p class="text-xs text-stone-500">{{ $product->size }} · {{ $product->gender }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-stone-600">{{ $product->category?->name }}</td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-stone-900">{{ $product->formatted_price }}</p>
                            <p class="text-xs text-stone-400">{{ $product->price }} per {{ $product->size }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->purchase_price > 0)
                                <span class="text-rose-600">{{ $product->formatted_purchase_price }}</span>
                            @else
                                <span class="text-stone-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ $product->margin_percent }}%
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="{{ $product->stock <= $product->min_stock ? 'font-semibold text-red-600' : 'text-stone-600' }}">
                                {{ $product->stock }}
                            </span>
                            @if($product->is_low_stock)
                                <span class="block text-[11px] font-medium text-red-500">⚠ di bawah min</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($product->is_active)
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">Aktif</span>
                            @else
                                <span class="rounded-full bg-stone-200 px-3 py-1 text-xs font-medium text-stone-600">Nonaktif</span>
                            @endif
                            @if($product->is_featured)
                                <span class="ml-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">Unggulan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="rounded-lg border border-stone-300 px-3 py-1.5 text-xs font-medium text-stone-700 transition hover:border-amber-500 hover:text-amber-700">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-stone-500">Tidak ada produk ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-stone-100 p-4">
        {{ $products->links() }}
    </div>
</div>

@endsection