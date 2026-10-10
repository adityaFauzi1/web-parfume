@extends('layouts.app')

@section('title', 'Kontak')

@section('content')

<section class="border-b border-stone-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-700">Hubungi Kami</p>
        <h1 class="mt-2 font-display text-4xl">Kontak</h1>
        <p class="mt-3 max-w-xl text-stone-500">Punya pertanyaan seputar produk, pemesanan, atau butuh rekomendasi wangi? Tim kami siap membantu.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-2">
        <div>
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700">📍</div>
                    <div>
                        <p class="font-semibold text-stone-900">Alamat</p>
                        <p class="mt-1 text-sm text-stone-600">Jl. Melati No. 12, Menteng, Jakarta Pusat 10310</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700">📞</div>
                    <div>
                        <p class="font-semibold text-stone-900">Telepon / WhatsApp</p>
                        <p class="mt-1 text-sm text-stone-600">+62 812-3456-7890 (Senin–Sabtu, 09.00–21.00 WIB)</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700">✉️</div>
                    <div>
                        <p class="font-semibold text-stone-900">Email</p>
                        <p class="mt-1 text-sm text-stone-600">halo@maisonparfum.com</p>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <form method="POST" action="{{ route('contact') }}" class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-stone-700">Nama</label>
                        <input type="text" id="name" name="name" required
                               class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-stone-700">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label for="subject" class="mb-1 block text-sm font-medium text-stone-700">Subjek</label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label for="message" class="mb-1 block text-sm font-medium text-stone-700">Pesan</label>
                    <textarea id="message" name="message" rows="5" required
                              class="w-full rounded-lg border border-stone-300 px-4 py-2.5 text-sm focus:border-amber-500 focus:outline-none"></textarea>
                </div>
                <button type="submit" class="w-full rounded-full bg-stone-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-amber-600 hover:text-stone-950">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</section>

@endsection