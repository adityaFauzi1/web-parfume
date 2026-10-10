<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout.
     */
    public function show()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang masih kosong. Silakan pilih produk terlebih dahulu.');
        }

        $products = Product::active()->whereIn('id', array_keys($cart))->get();
        $items = [];

        foreach ($products as $product) {
            $qty = $cart[$product->id];
            $items[] = [
                'product' => $product,
                'qty' => $qty,
                'stock' => $product->stock,
                'subtotal' => $product->price * $qty,
            ];
        }

        $total = collect($items)->sum('subtotal');

        return view('checkout.show', compact('items', 'total'));
    }

    /**
     * Proses transaksi checkout.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        $products = Product::active()->whereIn('id', array_keys($cart))->get();

        if ($products->isEmpty()) {
            return back()->with('error', 'Produk di keranjang tidak valid.');
        }

        // Validasi stok sebelum transaksi.
        $errors = [];
        foreach ($products as $product) {
            if ($cart[$product->id] > $product->stock) {
                $errors[] = "Stok \"{$product->name}\" hanya tersisa {$product->stock} botol.";
            }
        }

        if (! empty($errors)) {
            return back()->with('error', implode(' ', $errors));
        }

        $invoice = 'INV-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
        $grandTotal = 0;
        $sale = null;

        DB::transaction(function () use ($products, $cart, $data, $invoice, &$grandTotal, &$sale) {
            // Kunci baris produk untuk mencegah oversell ketika transaksi bersamaan.
            $locked = Product::whereIn('id', $products->pluck('id'))->lockForUpdate()->get();

            // Validasi stok ulang setelah dikunci.
            foreach ($locked as $product) {
                if ($cart[$product->id] > $product->stock) {
                    abort(422, "Stok \"{$product->name}\" tidak mencukupi.");
                }
            }

            $sale = Sale::create([
                'invoice' => $invoice,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'grand_total' => 0,
            ]);

            $items = [];

            foreach ($locked as $product) {
                $qty = $cart[$product->id];
                $subtotal = $product->price * $qty;
                $totalCost = $product->purchase_price * $qty;
                $grandTotal += $subtotal;

                $items[] = [
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'price' => $product->price,
                    'purchase_price' => $product->purchase_price,
                    'subtotal' => $subtotal,
                    'total_cost' => $totalCost,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Stok berkurang otomatis!
                $product->decrement('stock', $qty);
            }

            SaleItem::insert($items);

            // Simpan total omzet transaksi.
            $sale->update(['grand_total' => $grandTotal]);
        });

        // Bersihkan keranjang & simpan referensi transaksi terakhir.
        session()->forget('cart');
        session(['last_order_id' => $sale?->id]);

        return redirect()->route('checkout.success')
            ->with('success', 'Pembayaran berhasil. Stok telah diperbarui otomatis.');
    }

    /**
     * Halaman sukses setelah transaksi.
     */
    public function success()
    {
        $sale = Sale::with('items')->find(session('last_order_id'));

        if (! $sale) {
            return redirect()->route('home');
        }

        return view('checkout.success', compact('sale'));
    }
}