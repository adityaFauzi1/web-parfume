<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where('is_active', true)->get();

        if ($products->isEmpty()) {
            return;
        }

        // Lacak jumlah terjual per produk agar stok tetap konsisten.
        $sold = $products->pluck('stock', 'id')->map(fn () => 0)->all();

        // Data deterministik agar hasil seeder selalu konsisten.
        mt_srand(20261010);

        // Generate 90 hari data penjualan.
        $day = now()->subDays(89)->startOfDay();

        for ($i = 0; $i < 90; $i++) {
            $salesCount = mt_rand(0, 2);

            for ($s = 0; $s < $salesCount; $s++) {
                $this->createRandomSale($day->copy(), $products, $sold);
            }

            $day->addDay();
        }

        // Pastikan minimal 3 produk masuk kategori stok menipis (<= min_stock)
        // untuk memperlihatkan notifikasi restock.
        $lowCount = $products->filter(
            fn ($p) => $p->stock - $sold[$p->id] <= $p->min_stock
        )->count();

        $need = 3 - $lowCount;

        if ($need > 0) {
            $candidates = $products->sortBy('price')->values();

            foreach ($candidates as $product) {
                if ($need <= 0) {
                    break;
                }

                $remaining = $product->stock - $sold[$product->id];

                if ($remaining > $product->min_stock) {
                    $qty = min(2, $remaining - $product->min_stock);
                    $this->pushSale(now()->subDays(mt_rand(0, 6))->copy(), $product, $qty, $sold);
                    $need--;
                }
            }
        }

        // Tulis stok akhir.
        foreach ($products as $product) {
            $product->update([
                'stock' => max(0, $product->stock - $sold[$product->id]),
            ]);
        }
    }

    /**
     * Buat satu transaksi acak berisi 1–2 item.
     */
    private function createRandomSale($date, $products, &$sold): void
    {
        $pool = $products->filter(
            fn ($p) => $p->stock - $sold[$p->id] > 1
        )->values();

        if ($pool->isEmpty()) {
            return;
        }

        $count = min(mt_rand(1, 2), $pool->count());
        $picked = $pool->random($count);

        $payloads = [];

        foreach ($picked as $product) {
            $qty = mt_rand(1, 2);
            $payloads[] = [$product, $qty];
        }

        $this->createSale($date, $payloads);

        foreach ($payloads as [$product, $qty]) {
            $sold[$product->id] += $qty;
        }
    }

    /**
     * Buat transaksi & item penjualan, lalu catat jumlah terjual.
     */
    private function createSale($date, array $payloads): void
    {
        $items = [];

        foreach ($payloads as [$product, $qty]) {
            $items[] = [
                'product' => $product,
                'qty' => $qty,
                'subtotal' => $product->price * $qty,
                'cost' => $product->purchase_price * $qty,
            ];
        }

        $sale = Sale::create([
            'invoice' => 'INV-' . strtoupper(substr(uniqid(), -8)) . Str::upper(Str::random(3)),
            'customer_name' => mt_rand(0, 3) === 0 ? 'Pelanggan ' . mt_rand(1, 99) : null,
            'grand_total' => collect($items)->sum('subtotal'),
            'created_at' => $date->copy()->addHours(mt_rand(9, 21))->addMinutes(mt_rand(0, 59)),
            'updated_at' => $date->copy(),
        ]);

        foreach ($items as $item) {
            $product = $item['product'];

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $item['qty'],
                'price' => $product->price,
                'purchase_price' => $product->purchase_price,
                'subtotal' => $item['subtotal'],
                'total_cost' => $item['cost'],
                'created_at' => $sale->created_at,
                'updated_at' => $sale->created_at,
            ]);
        }
    }

    /**
     * Transaksi target untuk memastikan stok sebuah produk menipis.
     */
    private function pushSale($date, Product $product, int $qty, array &$sold): void
    {
        $this->createSale($date, [[$product, $qty]]);
        $sold[$product->id] += $qty;
    }
}