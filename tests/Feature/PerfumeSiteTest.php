<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerfumeSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_home_page_renders_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Maison Parfum')
            ->assertSee('Temukan Wangi');
    }

    public function test_shop_page_lists_products(): void
    {
        $this->get('/produk')
            ->assertOk()
            ->assertSee('Katalog Produk')
            ->assertSee('Bleu de Chanel EDP');
    }

    public function test_shop_filter_by_category(): void
    {
        $category = Category::where('name', 'Wanita')->first();

        $this->get('/produk?category=' . $category->slug)
            ->assertOk()
            ->assertSee('Chanel No. 5 EDP');

        // Produk pria tidak boleh tampil di filter wanita
        $this->assertTrue(
            ! str_contains($this->get('/produk?category=' . $category->slug)->getContent(), 'Versace Eros')
        );
    }

    public function test_shop_search(): void
    {
        $this->get('/produk?q=creed')
            ->assertOk()
            ->assertSee('Creed Aventus');
    }

    public function test_product_detail_page(): void
    {
        $product = Product::where('stock', '>', 0)->first();

        $this->assertNotNull($product, 'Tidak ada produk dengan stok tersedia.');

        $this->get('/produk/' . $product->slug)
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('Tambah ke Keranjang');
    }

    public function test_about_and_contact_pages(): void
    {
        $this->get('/tentang')->assertOk()->assertSee('Sejak 2015');
        $this->get('/kontak')->assertOk()->assertSee('Kirim Pesan');
    }

    public function test_cart_add_remove_and_clear(): void
    {
        $product = Product::active()->where('stock', '>', 0)->first();
        $this->assertNotNull($product, 'Tidak ada produk dengan stok tersedia.');

        // Tambah ke keranjang
        $this->post(route('cart.add', $product->slug), ['qty' => 2])
            ->assertRedirect();

        $this->assertEquals(2, session('cart')[$product->id] ?? 0);

        // Halaman keranjang menampilkan produk
        $this->get('/keranjang')
            ->assertOk()
            ->assertSee($product->name);

        // Hapus dari keranjang
        $this->delete(route('cart.remove', $product->slug))->assertRedirect();
        $this->assertArrayNotHasKey($product->id, session('cart') ?? []);
    }

    public function test_admin_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_admin_login_and_dashboard(): void
    {
        $user = User::where('email', 'admin@maisonparfum.com')->first();

        $this->post(route('admin.login.submit'), [
            'email' => $user->email,
            'password' => 'admin123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_non_admin_cannot_access_admin_area(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_crud_product(): void
    {
        $admin = User::where('email', 'admin@maisonparfum.com')->first();
        $category = Category::first();

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Parfum Test Baru',
                'category_id' => $category->id,
                'description' => 'Deskripsi produk test.',
                'price' => 500000,
                'purchase_price' => 320000,
                'size' => '100 ml',
                'gender' => 'Unisex',
                'stock' => 10,
                'min_stock' => 5,
                'is_featured' => false,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Parfum Test Baru', 'purchase_price' => 320000]);

        $product = Product::where('name', 'Parfum Test Baru')->first();
        $this->assertSame(36.0, $product->margin_percent);

        $this->actingAs($admin)
            ->put(route('admin.products.update', $product->id), [
                'name' => 'Parfum Test Diupdate',
                'category_id' => $category->id,
                'description' => 'Deskripsi produk test.',
                'price' => 550000,
                'purchase_price' => 330000,
                'size' => '100 ml',
                'gender' => 'Unisex',
                'stock' => 8,
                'min_stock' => 5,
                'is_featured' => true,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Parfum Test Diupdate', 'price' => 550000]);
    }

    public function test_checkout_reduces_stock_and_records_sale(): void
    {
        $product = Product::active()->orderByDesc('stock')->first();
        $stockBefore = $product->stock;

        session(['cart' => [$product->id => 2]]);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Pembeli Test',
            'customer_phone' => '081234567890',
        ])->assertRedirect(route('checkout.success'));

        // Stok berkurang otomatis.
        $this->assertSame($stockBefore - 2, $product->fresh()->stock);

        // Transaksi & item tercatat.
        $this->assertDatabaseHas('sales', ['customer_name' => 'Pembeli Test']);
        $sale = Sale::latest('id')->first();

        $this->assertDatabaseHas('sale_items', [
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
            'purchase_price' => $product->purchase_price,
        ]);

        // Perhitungan otomatis: omzet, modal, profit.
        $this->assertSame(2 * $product->price, $sale->grand_total);
        $this->assertSame(2 * $product->price, $sale->revenue);
        $this->assertSame(2 * $product->purchase_price, $sale->total_cost);
        $this->assertSame($sale->revenue - $sale->total_cost, $sale->profit);

        // Halaman sukses tampil.
        $this->get(route('checkout.success'))->assertOk()->assertSee($sale->invoice);
    }

    public function test_seller_dashboard_shows_financial_stats(): void
    {
        $admin = User::where('email', 'admin@maisonparfum.com')->first();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Omzet')
            ->assertSee('Total Modal')
            ->assertSee('Profit Bersih')
            ->assertSee('Margin Profit')
            ->assertSee('Grafik Laba Rugi');
    }

    public function test_report_page_shows_sales_summary(): void
    {
        $admin = User::where('email', 'admin@maisonparfum.com')->first();

        $response = $this->actingAs($admin)->get(route('admin.reports'));

        $response->assertOk()->assertSee('Laporan Penjualan');

        $content = $response->getContent();
        $this->assertStringContainsString('Omzet', $content);
        $this->assertStringContainsString('Modal (HPP)', $content);
        $this->assertStringContainsString('Profit', $content);
        $this->assertStringContainsString('Margin', $content);
    }

    public function test_low_stock_scope_and_notification(): void
    {
        $admin = User::where('email', 'admin@maisonparfum.com')->first();

        // Pastikan minimal satu produk yang stoknya <= min_stock hasil seeder.
        $this->assertGreaterThan(0, Product::lowStock()->count());

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertSee('Notifikasi Stok Menipis');
    }
}