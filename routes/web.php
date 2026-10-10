<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// ─── Halaman Publik ─────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'about'])->name('about');
Route::get('/kontak', [HomeController::class, 'contact'])->name('contact');
Route::post('/kontak', [HomeController::class, 'storeContact'])->name('contact.submit');

Route::get('/produk', [ShopController::class, 'index'])->name('shop.index');
Route::get('/produk/{product:slug}', [ShopController::class, 'show'])->name('shop.show');

// ─── Keranjang ──────────────────────────────────────────────────
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/update/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');

// ─── Checkout ────────────────────────────────────────────────────
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/selesai', [CheckoutController::class, 'success'])->name('checkout.success');

// ─── Autentikasi Admin ──────────────────────────────────────────
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// ─── Area Admin (dilindungi) ────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports');

    Route::resource('products', AdminProductController::class)
        ->except('show')
        ->names('products');

    Route::resource('categories', CategoryController::class)
        ->except('show')
        ->names('categories');
});
