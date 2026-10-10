<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Halaman beranda.
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featured = Product::active()->featured()->with('category')->take(8)->get();
        $latest = Product::active()->with('category')->latest()->take(4)->get();

        return view('home', compact('categories', 'featured', 'latest'));
    }

    /**
     * Halaman tentang kami.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Halaman kontak.
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Proses pesan dari form kontak.
     */
    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // Simulasi pengiriman pesan (bisa dihubungkan ke Mail).
        // Untuk saat ini, cukup tampilkan notifikasi sukses.

        return back()->with('success', 'Pesan Anda telah terkirim. Tim kami akan segera menghubungi Anda.');
    }
}