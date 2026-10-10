<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Laporan & analisis penjualan.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['items']);

        // Rentang tanggal (format YYYY-MM-DD)
        $range = $request->input('range', '7');
        $now = now();

        $from = match ($range) {
            'today' => $now->copy()->startOfDay(),
            '7' => $now->copy()->subDays(6)->startOfDay(),
            '30' => $now->copy()->subDays(29)->startOfDay(),
            '90' => $now->copy()->subDays(89)->startOfDay(),
            'year' => $now->copy()->startOfYear(),
            'custom' => ($request->filled('from')
                ? \Carbon\Carbon::parse($request->from)->startOfDay()
                : $now->copy()->startOfDay()),
            default => $now->copy()->subDays(6)->startOfDay(),
        };

        if ($range === 'custom') {
            if ($request->filled('to')) {
                $to = \Carbon\Carbon::parse($request->to)->endOfDay();
            } elseif ($request->filled('from')) {
                $to = \Carbon\Carbon::parse($request->from)->endOfDay();
            } else {
                $to = $now->copy()->endOfDay();
            }
        } else {
            $to = $now->copy()->endOfDay();
        }

        $query->whereBetween('created_at', [$from, $to]);

        // Pencarian invoice
        if ($request->filled('q')) {
            $query->where('invoice', 'like', '%' . $request->q . '%');
        }

        $sales = $query->latest()->paginate(15)->withQueryString();

        // Ringkasan rentang
        $items = SaleItem::whereHas('sale', fn ($q) => $q->whereBetween('created_at', [$from, $to]));
        $summary = [
            'revenue' => (int) (clone $items)->sum('subtotal'),
            'cost' => (int) (clone $items)->sum('total_cost'),
            'units' => (int) (clone $items)->sum('quantity'),
            'sales_count' => Sale::whereBetween('created_at', [$from, $to])->count(),
        ];
        $summary['profit'] = $summary['revenue'] - $summary['cost'];
        $summary['margin'] = ($summary['revenue'] > 0
            ? round(($summary['profit'] / $summary['revenue']) * 100, 1)
            : 0.0);

        // Produk terlaris dalam rentang
        $topProducts = SaleItem::selectRaw('product_id, product_name, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->whereHas('sale', fn ($q) => $q->whereBetween('created_at', [$from, $to]))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('admin.reports', compact('sales', 'summary', 'range', 'from', 'to', 'topProducts'));
    }
}