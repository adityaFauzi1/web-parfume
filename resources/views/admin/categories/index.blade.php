@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="font-display text-xl">Daftar Kategori</h2>
        <p class="mt-1 text-sm text-stone-500">Total {{ $categories->count() }} kategori</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center rounded-full bg-amber-500 px-6 py-2.5 text-sm font-semibold text-stone-950 transition hover:bg-amber-400">
        + Tambah Kategori
    </a>
</div>

<div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
    @forelse($categories as $category)
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
            <div class="aspect-[4/3] overflow-hidden">
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover">
            </div>
            <div class="p-5">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-display text-lg">{{ $category->name }}</h3>
                        <p class="text-xs text-stone-500">{{ $category->products_count }} produk</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="rounded-lg border border-stone-300 px-3 py-1.5 text-xs font-medium text-stone-700 transition hover:border-amber-500 hover:text-amber-700">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50">Hapus</button>
                        </form>
                    </div>
                </div>
                <p class="mt-2 line-clamp-2 text-sm text-stone-500">{{ $category->description }}</p>
            </div>
        </div>
    @empty
        <div class="col-span-full rounded-2xl border border-dashed border-stone-300 py-16 text-center text-stone-500">
            Belum ada kategori.
        </div>
    @endforelse
</div>

@endsection