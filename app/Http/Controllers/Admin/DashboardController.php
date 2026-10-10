<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_stock' => Product::sum('stock'),
            'users' => User::count(),
        ];

        // ─── Statistik penjualan keseluruhan ───────────────────────────
        $totals = [
            'revenue' => (int) SaleItem::sum('subtotal'),
            'cost' => (int) SaleItem::sum('total_cost'),
            'profit' => (int) SaleItem::sum('subtotal') - (int) SaleItem::sum('total_cost'),
            'sales_count' => Sale::count(),
            'units_sold' => (int) SaleItem::sum('quantity'),
        ];
        $totals['margin'] = $totals['revenue'] > 0
            ? round(($totals['profit'] / $totals['revenue']) * 100, 1)
            : 0.0;

        // ─── Statistik hari ini ─────────────────────────────────────────
        $today = now()->startOfDay();
        $todayRevenue = (int) SaleItem::whereHas('sale', fn ($q) => $q->where('created_at', '>=', $today))->sum('subtotal');
        $todayCost = (int) SaleItem::whereHas('sale', fn ($q) => $q->where('created_at', '>=', $today))->sum('total_cost');
        $todayProfit = $todayRevenue - $todayCost;
        $todayCount = Sale::where('created_at', '>=', $today)->count();

        // ─── Data grafik laba rugi (harian / mingguan / bulanan) ──────────
        $period = request('periode', 'harian');
        if (! in_array($period, ['harian', 'mingguan', 'bulanan'], true)) {
            $period = 'harian';
        }
        $chart = $this->chartData($period);

        $lowStock = Product::lowStock()->orderBy('stock', 'asc')->take(8)->get();
        $lowStockCount = Product::lowStock()->count();

        $latestSales = Sale::with('items')->latest()->take(6)->get();
        $latestProducts = Product::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'totals',
            'todayRevenue',
            'todayCost',
            'todayProfit',
            'todayCount',
            'chart',
            'lowStock',
            'lowStockCount',
            'latestSales',
            'latestProducts'
        ));
    }

    /**
     * Siapkan data deret waktu omzet vs modal untuk grafik.
     */
    private function chartData(string $period): array
    {
        $from = match ($period) {
            'mingguan' => now()->subWeeks(7)->startOfWeek(),
            'bulanan' => now()->subMonths(11)->startOfMonth(),
            default => now()->subDays(29)->startOfDay(),
        };

        $labels = [];
        $cursor = $from->copy();

        while ($cursor->lte(now())) {
            $key = $this->periodKey($cursor, $period);
            $labels[$key] = $this->periodLabel($cursor, $period);
            $cursor = $this->periodAdvance($cursor, $period);
        }

        $revenue = array_fill_keys(array_keys($labels), 0);
        $cost = array_fill_keys(array_keys($labels), 0);

        // Ambil transaksi dalam rentang lalu agregasi di PHP agar tetap
        // portabel antar database (SQLite/MySQL).
        SaleItem::with('sale')
            ->whereHas('sale', fn ($q) => $q->where('created_at', '>=', $from))
            ->get()
            ->each(function (SaleItem $item) use (&$revenue, &$cost, $period) {
                $key = $this->periodKey($item->sale->created_at, $period);

                if (array_key_exists($key, $revenue)) {
                    $revenue[$key] += $item->subtotal;
                    $cost[$key] += $item->total_cost;
                }
            });

        return [
            'labels' => array_values($labels),
            'revenue' => array_values($revenue),
            'cost' => array_values($cost),
            'period' => $period,
        ];
    }

    private function periodKey(\DateTimeInterface $date, string $period): string
    {
        return match ($period) {
            'mingguan' => $date->format('o') . '-W' . $date->format('W'),
            'bulanan' => $date->format('Y-m'),
            default => $date->format('Y-m-d'),
        };
    }

    private function periodLabel(\DateTimeInterface $date, string $period): string
    {
        return match ($period) {
            'mingguan' => 'W' . $date->format('W'),
            'bulanan' => $date->format('M'),
            default => $date->format('d M'),
        };
    }

    private function periodAdvance(\Carbon\Carbon $date, string $period): \Carbon\Carbon
    {
        return match ($period) {
            'mingguan' => $date->addWeek(),
            'bulanan' => $date->addMonth(),
            default => $date->addDay(),
        };
    }
}