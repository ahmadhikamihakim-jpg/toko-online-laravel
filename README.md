# Toko Online — E-Commerce Laravel 12 + Midtrans

Aplikasi toko online berbasis Laravel: katalog produk dengan pencarian dan filter kategori, keranjang belanja, dan checkout dengan pilihan pembayaran transfer bank, COD, atau Midtrans. Panel admin dipakai untuk mengelola produk, kategori, dan pesanan yang masuk.

## Fitur utama

- Katalog produk dengan foto, pencarian, dan filter kategori.
- Keranjang belanja dan checkout yang mewajibkan login.
- Pilihan pembayaran: transfer bank, COD, dan Midtrans Snap.
- Webhook Midtrans dengan pemeriksaan tanda tangan (signature) untuk memperbarui status pesanan.
- Halaman pesanan pelanggan dan pelacakan status pesanan.
- Panel admin: kelola produk (termasuk unggah foto), kategori, pesanan, dan pengguna.

## Teknologi

- PHP 8.2, Laravel 12, Blade
- SQLite (bawaan) / MySQL
- Midtrans Snap untuk pembayaran online

## Cara menjalankan

1. `composer install`
2. Salin `.env.example` menjadi `.env`.
3. `php artisan key:generate`
4. `php artisan migrate --seed`
5. `php artisan serve`, lalu buka `http://localhost:8000`

Akun demo hasil seed:

- Admin: `admin@toko.test` / `password`
- Pengguna: `budi@toko.test` / `password`

Catatan: pembayaran Midtrans memerlukan kunci dari Dashboard Midtrans (Settings → Access Keys) yang diisi sendiri di `.env` (`MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`). Gunakan kunci mode Sandbox untuk pengujian.

---
Proyek belajar dan portofolio oleh Andy Hakim.
