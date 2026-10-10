# 🫙 Maison Parfum — Website Toko Parfum (Laravel 12)

Website toko parfum berbasis Laravel 12 dengan fitur katalog produk, keranjang belanja,
dan panel admin untuk mengelola produk serta kategori.

## ✨ Fitur

**Frontend (Pengunjung)**
- Beranda dengan hero, kategori, produk unggulan, dan produk terbaru
- Katalog produk: pencarian, filter kategori & gender, sorting, pagination
- Halaman detail produk (deskripsi, ukuran, gender, stok, produk serupa)
- Keranjang belanja berbasis session (tambah, ubah jumlah, hapus, kosongkan)
- Halaman Tentang & Kontak (form kirim pesan)

**Admin**
- Login/logout admin sederhana (middleware `admin`)
- Dashboard statistik + peringatan stok menipis
- CRUD Produk (dengan upload gambar)
- CRUD Kategori

## 🚀 Cara Menjalankan

```bash
# 1. Install dependensi PHP (jika belum)
composer install

# 2. Salin .env & generate key
copy .env.example .env        # (Windows)
php artisan key:generate

# 3. Migrasi + seed database SQLite
php artisan migrate:fresh --seed

# 4. Install dependensi frontend & build aset (Tailwind CSS)
npm install
npm run build

# 5. Storage link (untuk gambar upload)
php artisan storage:link

# 6. Jalankan server
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser.

## 🔑 Akun Admin (hasil seeder)

| Email                  | Password  |
|------------------------|-----------|
| admin@maisonparfum.com | admin123  |

Setelah login, kelola toko di **http://127.0.0.1:8000/admin**.

## 🗄️ Database

- SQLite (`database/database.sqlite`) — tanpa konfigurasi tambahan.
- Tabel: `users`, `categories`, `products`.
- Seeder mengisi 1 akun admin, 4 kategori (Pria, Wanita, Unisex, Niche),
  dan 12 produk parfum ikonik dengan gambar placeholder SVG.

## 🧪 Testing

```bash
php artisan test
```

Terdapat 13 test fungsional (halaman publik, keranjang, autentikasi admin, CRUD).

## 📁 Struktur Penting

```
app/Http/Controllers/          # Home, Shop, Cart, Auth, Admin\{Dashboard, Product, Category}
app/Http/Middleware/Admin.php  # Proteksi area admin
app/Models/                    # Product, Category (+ User dengan is_admin)
database/migrations/           # users, categories, products
database/seeders/              # DatabaseSeeder, CategorySeeder, ProductSeeder
resources/views/               # Layouts, frontend, dan admin (Blade + Tailwind)
routes/web.php                 # Semua route
```
