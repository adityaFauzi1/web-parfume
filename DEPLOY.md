# ─── Deploy Maison Parfum (Laravel 12) ─────────────────────────────

> TL;DR — Mau yang **gratis & semirip Vercel** (push → online)?
> Pakai **Pilihan A — Render**. Vercel TIDAK disarankan untuk Laravel ini
> (pecah di: session database, SQLite, upload gambar, queue). File
> `vercel.json`/`api/index.php` tetap disediakan, tapi baca bagian Vercel
> dulu sebelum memilihnya.

## Pilihan A — ✅ GRATIS & termudah: Render (pengganti Vercel untuk Laravel)

Alur sama enaknya seperti Vercel: push ke GitHub → otomatis build →
online di `https://maison-parfum.onrender.com` (HTTPS + domain gratis).
Sudah disiapkan: `Dockerfile` (FrankenPHP + PHP 8.3 + pdo_pgsql),
`render.yaml` (web + Postgres), `AppServiceProvider` force-https.

### 1. Upload proyek ke GitHub
```powershell
cd 'c:\ADIT\rm\BUAT GAWE\web parfum'
# install git dari https://git-scm.com/download/win bila belum ada
git init
git add .
git commit -m "Maison Parfum siap deploy"
# buat repo baru di github.com lalu:
git remote add origin https://github.com/USERNAME/maison-parfum.git
git branch -M main
git push -u origin main
```

### 2. Buat APP_KEY produksi (di laptop, sekali saja)
```powershell
cd 'c:\ADIT\rm\BUAT GAWE\web parfum'
php artisan key:generate --show
# contoh output: base64:xxxx... → COPY nilainya untuk langkah 4
```

### 3. Deploy di Render (https://render.com)
1. Sign up (GitHub) → Dashboard → **New + → Blueprint** →
   pilih repo `maison-parfum` → **Apply**.
   Render membaca `render.yaml`: membuat 1 Web Service (Docker, free)
   + 1 Postgres (free).
2. Saat diminta isi `sync: false` values:
   - `APP_URL` → isi **setelah** service jadi, mis.
     `https://maison-parfum.onrender.com` (lalu redeploy / Save).
   - `APP_KEY` → paste output langkah 2 (wajib diawali `base64:`).
3. Tunggu build ±5–10 mnt (build pertama lama: install PHP+Node).
   Script start otomatis: `storage:link` → `migrate --force` →
   `db:seed` (hanya bila tabel users kosong) → cache → serve.
4. Buka URL → login `/admin`:
   `admin@maisonparfum.com` / `admin123` → **langsung ganti password!**

### 4. Batasan paket gratis Render (jujur!)
- Web **sleep setelah ±15 mnt idle** → request pertama lambat
  ~30–60 detik (cold start). Atasi: buka berkala / pakai UptimeRobot
  ping tiap 5 mnt (gratis) agar tetap hangat.
- **File lokal hilang tiap redeploy** (tanpa persistent disk di free).
  Gambar placeholder ikut di-seed ulang aman; tapi **foto produk yang
  di-upload admin akan hilang saat redeploy**. Solusi: upgrade termurah
  (+disk) atau pindah upload ke S3/Cloudinary bila serius jualan.
- **Postgres gratis 1 GB & kedaluwarsa 30 hari** (+masa tenggang 14 hari).
  Cukup untuk demo/toko kecil; backup via Render Dashboard → Backups
  (manual) sebelum expired, atau upgrade.
- 750 jam/bulan + bandwidth/build minutes terbatas — cukup untuk 1 web kecil.

## Pilihan Vercel — ⚠️ TIDAK disarankan untuk aplikasi ini

Bisa jalan (file `vercel.json` + `api/index.php` sudah dibuat mengikuti
pola `vercel-php`), tetapi:
- Filesystem **read-only** → SQLite MATI (butuh DB eksternal seperti
  Neon/Supabase Postgres + `DATABASE_URL`).
- `SESSION_DRIVER=database` MATI (DB SQLite ikut mati) → wajib `cookie`.
- Upload gambar admin HILANG antar request (tanpa disk persisten).
- Tanpa queue worker/scheduler; limit eksekusi ±30 dtk; checkout dengan
  `lockForUpdate()` rawan timeout di serverless.
- `vercel-php` adalah runtime komunitas (bukan resmi Vercel).

Hanya pilih Vercel bila: paham trade-off di atas + sudah siapkan Neon/
Supabase + storage S3. Untuk toko sungguhan → tetap pilih Render/Pil. B/C.

## Pilihan B — Shared hosting cPanel (Niagahoster/Hostinger ~Rp 20–40rb/bln)

### 1. Siapkan file
- File `.env.production.example` sudah dibuat — salin jadi `.env`
  di hosting dan sesuaikan `APP_URL`, `DB_*`, `MAIL_*`.
- File `public/artisan-helper.php.example` → rename jadi
  `artisan-helper.php` + ganti token, upload hanya saat butuh
  migrate, **hapus setelah dipakai**.

### 2. Upload
1. Zip SEMUA file proyek **kecuali** `node_modules`, `.git`, `vendor`
   besar? (vendor tetap perlu — atau jalankan `composer install`
   via SSH/Terminal cPanel bila tersedia).
2. Di cPanel: domain diarahkan ke folder `public/` (Document Root =
   `/home/username/maisonparfum/public`). Bukan ke root proyek!
3. Buat database MySQL di **MySQL Databases** → catat nama, user,
   password → isi ke `.env`.

### 3. Perintah via Terminal cPanel / artisan-helper
```
composer install --no-dev --optimize-autoloader
cp .env.production.example .env   # lalu edit
php artisan key:generate --force
php artisan migrate --force --seed
php artisan storage:link
php artisan optimize
```
Jika symlink `public/storage` dilarang hosting, hapus link lalu
copy folder `storage/app/public` → `public/storage` manual.

### 4. PHP version: pilih **8.2+** di cPanel → Select PHP Version.

## Pilihan C — VPS Ubuntu (paling kencang, ~Rp 60rb/bln ke atas)

Singkatnya (Ubuntu 24.04 + Nginx + PHP 8.2 + MySQL):
```bash
# di VPS
apt update && apt install -y nginx mysql-server php8.2-fpm php8.2-mysql \
  php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-sqlite3 \
  composer nodejs npm git
git clone <repo-kamu> /var/www/maisonparfum
cd /var/www/maisonparfum
composer install --no-dev -o && npm ci && npm run build
cp .env.production.example .env && php artisan key:generate
php artisan migrate --force --seed && php artisan storage:link
php artisan optimize
chown -R www-data:www-data storage bootstrap/cache
# Nginx server block root → /var/www/maisonparfum/public
# + certbot --nginx untuk HTTPS gratis
```

## Setelah deploy (semua pilihan)
- [ ] `APP_DEBUG=false`, `APP_ENV=production`
- [ ] `APP_URL` = domain final (https)
- [ ] Ganti password admin + ganti `APP_KEY` baru
      (`php artisan key:generate --show`, lalu update Variables/`.env`)
- [ ] `php artisan optimize` (config+route+view cache)
- [ ] Cek: home, katalog, checkout, upload gambar admin, email
