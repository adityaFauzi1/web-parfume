<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
    /**
     * Daftar produk katalog dengan filter & pencarian.
     */
    public function index()
    {
        $query = Product::active()->with('category');

        // Filter kategori
        if (request('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', request('category')));
        }

        // Filter gender
        if (request('gender') && in_array(request('gender'), ['Pria', 'Wanita', 'Unisex'])) {
            $query->where('gender', request('gender'));
        }

        // Pencarian
        if (request('q')) {
            $query->where('name', 'like', '%' . request('q') . '%');
        }

        // Sorting
        $sort = request('sort', 'latest');
        $query = match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->latest(),
        };

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('products')->get();

        return view('shop.index', compact('products', 'categories'));
    }

    /**
     * Detail satu produk.
     */
    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }
}