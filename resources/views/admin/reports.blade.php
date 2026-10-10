@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')

<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="font-display text-xl">Laporan & Analisis Penjualan</h2>
        <p class="mt-1 text-sm text-stone-500">
            Periode: <strong>{{ $from->translatedFormat('d M Y') }}</strong> — <strong>{{ $to->translatedFormat('d M Y') }}</strong>
        </p>
    </div>
</div>

{{-- Ringkasan rentang --}}
<div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl bg-stone-950 p-5 text-white">
        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Omzet</p>
        <p class="mt-1 font-display text-2xl text-emerald-300">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl bg-stone-950 p-5 text-white">
        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Modal (HPP)</p>
        <p class="mt-1 font-display text-2xl text-rose-300">Rp {{ number_format($summary['cost'], 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl bg-stone-950 p-5 text-white">
        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Profit</p>
        <p class="mt-1 font-display text-2xl text-amber-300">Rp {{ number_format($summary['profit'], 0, ',', '.') }}</p>
    </div>
    <div class="rounded-2xl bg-stone-950 p-5 text-white">
        <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Margin</p>
        <p class="mt-1 font-display text-2xl text-sky-300">{{ $summary['margin'] }}%</p>
        <p class="mt-1 text-xs text-stone-400">{{ $summary['sales_count'] }} transaksi · {{ $summary['units'] }} item terjual</p>
    </div>
</div>

{{-- Filter periode --}}
<form method="GET" action="{{ route('admin.reports') }}" class="mt-6 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-stone-200">
    @csrf
    <div>
        <label class="mb-1 block text-xs font-medium text-stone-500">Periode Cepat</label>
        <select name="range" onchange="this.form.submit()" class="rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm focus:border-amber-500 focus:outline-none">
            @foreach(['7' => '7 Hari Terakhir', '30' => '30 Hari Terakhir', '90' => '90 Hari Terakhir', 'year' => 'Tahun Ini', 'custom' => 'Kustom'] as $key => $label)
                <option value="{{ $key }}" @selected($range === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-stone-500">Dari Tanggal</label>
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-lg border border-stone-300 px-4 py-2 text-sm focus:border-amber-500 focus:outline-none">
    </div>
    <div>
        <label class="mb-1 block text-xs font-medium text-stone-500">Sampai Tanggal</label>
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-lg border border-stone-300 px-4 py-2 text-sm focus:border-amber-500 focus:outline-none">
    </div>
    <div class="flex-1">
        <label class="mb-1 block text-xs font-medium text-stone-500">Cari Invoice</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="cth: INV-..." class="w-full rounded-lg border border-stone-300 px-4 py-2 text-sm focus:border-amber-500 focus:outline-none">
    </div>
    <button type="submit" class="rounded-lg bg-stone-900 px-6 py-2 text-sm font-medium text-white transition hover:bg-stone-700">Terapkan</button>
</form>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    {{-- Tabel transaksi --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-200 lg:col-span-2">
        <div class="border-b border-stone-100 px-6 py-4">
            <h3 class="font-display text-lg">Daftar Transaksi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-stone-50 text-left text-xs uppercase tracking-wider text-stone-500">
                    <tr>
                        <th class="px-6 py-3">Invoice</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-right">Omzet</th>
                        <th class="px-6 py-3 text-right">Modal</th>
                        <th class="px-6 py-3 text-right">Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($sales as $sale)
                        <tr>
                            <td class="px-6 py-3">
                                <p class="font-semibold text-stone-900">{{ $sale->invoice }}</p>
                                <p class="text-xs text-stone-500">{{ $sale->items->sum('quantity') }} item @if($sale->customer_name) · {{ $sale->customer_name }} @endif</p>
                            </td>
                            <td class="px-6 py-3 text-stone-600">{{ $sale->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-6 py-3 text-right font-medium text-stone-900">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right text-rose-600">Rp {{ number_format($sale->total_cost, 0, ',', '.') }}</td>
                            <td class="px-6 py-3 text-right font-semibold text-emerald-600">Rp {{ number_format($sale->profit, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-stone-500">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($sales->hasPages())
            <div class="border-t border-stone-100 p-4">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

    {{-- Produk terlaris --}}
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-stone-200">
        <div class="border-b border-stone-100 px-6 py-4">
            <h3 class="font-display text-lg">Produk Terlaris</h3>
        </div>
        <div class="divide-y divide-stone-100">
            @forelse($topProducts as $top)
                <div class="flex items-center gap-3 px-6 py-4">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 font-bold text-amber-700">{{ $loop->iteration }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-stone-900">{{ $top->product_name }}</p>
                        <p class="text-xs text-stone-500">{{ $top->total_qty }} unit terjual</p>
                    </div>
                    <p class="text-sm font-semibold text-stone-900">Rp {{ number_format($top->total_revenue, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="px-6 py-12 text-center text-sm text-stone-500">Belum ada data.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection