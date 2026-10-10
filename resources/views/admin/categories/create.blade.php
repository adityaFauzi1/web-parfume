@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')

<div class="flex items-center gap-4">
    <a href="{{ route('admin.categories.index') }}" class="rounded-full border border-stone-300 px-4 py-2 text-sm text-stone-600 transition hover:text-amber-700">←</a>
    <div>
        <h2 class="font-display text-xl">Tambah Kategori Baru</h2>
        <p class="mt-1 text-sm text-stone-500">Lengkapi informasi kategori di bawah ini.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="mt-6 max-w-2xl space-y-4">
    @csrf

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-stone-700">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label for="description" class="mb-1 block text-sm font-medium text-stone-700">Deskripsi</label>
            <textarea id="description" name="description" rows="4"
                      class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4">
            <label class="mb-1 block text-sm font-medium text-stone-700">Gambar</label>
            <input type="file" id="image" name="image" accept="image/*"
                   class="w-full text-sm text-stone-500 file:mr-3 file:rounded-lg file:border-0 file:bg-stone-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-stone-700">
            <p class="mt-2 text-xs text-stone-400">JPG, PNG, WEBP atau SVG. Maks 2MB.</p>
            @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="rounded-full bg-amber-500 px-8 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-400">
            Simpan Kategori
        </button>
        <a href="{{ route('admin.categories.index') }}" class="rounded-full border border-stone-300 px-6 py-3 text-sm font-medium text-stone-600 hover:border-stone-400">Batal</a>
    </div>
</form>

@endsection