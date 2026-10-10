@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')

<div class="flex items-center gap-4">
    <a href="{{ route('admin.products.index') }}" class="rounded-full border border-stone-300 px-4 py-2 text-sm text-stone-600 transition hover:text-amber-700">←</a>
    <div>
        <h2 class="font-display text-xl">Tambah Produk Baru</h2>
        <p class="mt-1 text-sm text-stone-500">Lengkapi informasi produk di bawah ini.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="mt-6">
    @csrf
    @include('admin.products._form')

    <div class="mt-6 flex items-center gap-3">
        <button type="submit" class="rounded-full bg-amber-500 px-8 py-3 text-sm font-semibold text-stone-950 transition hover:bg-amber-400">
            Simpan Produk
        </button>
        <a href="{{ route('admin.products.index') }}" class="rounded-full border border-stone-300 px-6 py-3 text-sm font-medium text-stone-600 hover:border-stone-400">Batal</a>
    </div>
</form>

@endsection