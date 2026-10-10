<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang.
     */
    public function index()
    {
        $cart = session('cart', []);
        $items = [];

        if (! empty($cart)) {
            $products = Product::active()->whereIn('id', array_keys($cart))->get();
            foreach ($products as $product) {
                $items[] = [
                    'product' => $product,
                    'qty' => $cart[$product->id],
                    'subtotal' => $product->price * $cart[$product->id],
                ];
            }
        }

        $total = collect($items)->sum('subtotal');

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * Tambahkan produk ke keranjang.
     */
    public function add(Request $request, Product $product)
    {
        abort_if(! $product->is_available, 422, 'Produk tidak tersedia.');

        $qty = max(1, min(99, (int) $request->input('qty', 1)));

        $cart = session('cart', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $qty;
        session(['cart' => $cart]);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Perbarui jumlah item pada keranjang.
     */
    public function update(Request $request, Product $product)
    {
        $cart = session('cart', []);

        if ($request->input('qty') > 0) {
            $cart[$product->id] = min(99, (int) $request->input('qty'));
        } else {
            unset($cart[$product->id]);
        }

        session(['cart' => $cart]);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function remove(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Kosongkan seluruh keranjang.
     */
    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}