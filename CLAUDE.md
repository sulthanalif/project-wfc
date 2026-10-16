# CLAUDE.md

Paket Smart WFC: Laravel 10 untuk penjualan paket barang lewat agen (pesanan, cicilan, distribusi, retur, keuangan, landing page). Production di Hostinger shared hosting (`paketsmartwfcjabar.id`). UI dan pesan ke user berbahasa Indonesia.

Dokumentasi lengkap ada di `docs/`. Mulai dari `docs/README.md`, lalu buka file modul yang relevan sebelum mengubah fitur.

## Perintah

```bash
php artisan serve
php artisan test
php artisan route:list --except-vendor
# regenerate docs/05-routes.md setelah mengubah routes/
php artisan route:list --except-vendor --json > /tmp/routes.json && python3 docs/tools/gen_routes.py /tmp/routes.json > docs/05-routes.md
```

Tidak ada build frontend. CSS/JS CMS dan landing page adalah file jadi di `public/assets`.

## Aturan kode

- Harga dihitung di server: `order_details.sub_price = products.total_price × qty`, `orders.total_price = SUM(sub_price)`. Jangan memakai angka harga dari request.
- Setiap perubahan pada `payments` atau total pesanan harus diikuti perhitungan ulang `orders.payment_status`.
- Rantai `product->package->package->period` bisa null di setiap langkah. Pakai `?->`.
- Route baru wajib punya middleware `role:...`. `@hasrole` di view hanya menyembunyikan tombol.
- Data milik agen disaring dengan `ValidateRole::check('agent')` dan `Auth::id()`.
- Tulis data di dalam `DB::transaction`, balas dengan `back()->with('success'|'error', ...)`.
- View pesanan ada dalam tiga salinan (`transactions/`, `full/`, `aman/`). Ubah semuanya.
- Kolom baru lewat migration baru. Jangan mengedit migration lama.
- Hosting mematikan `proc_open`: jangan pakai `$schedule->command()`, `Process`, atau `exec`. Queue diproses cron `queue:work` (lihat `docs/08-deployment.md`).
- Sebelum memperbaiki bug, cek `docs/09-known-issues.md`. Setelah memperbaiki, pindahkan barisnya ke bagian "Sudah diperbaiki".

## Cara kerja

- Pakai skill `ponytail` (mode full): solusi paling sederhana yang benar, diff terpendek, tanpa abstraksi yang tidak diminta.
- Pakai skill `antislop` untuk pekerjaan UI dan teks (termasuk dokumentasi): tanpa em dash, tanpa klaim, angka, atau testimoni karangan, teks spesifik dan apa adanya. Untuk UI, muat juga `antislop-ui`; untuk teks, `antislop-copywriting`.
- Dokumentasi di `docs/` ditulis dari kode yang ada. Kalau mengubah perilaku yang tercatat di sana, perbarui file docs yang bersangkutan di commit yang sama.

## Git

- Format commit: `<add|update|fix> [Modul]: Pesan singkat`, contoh `<fix> [Transaction]: Error Add Product to Order Exist`.
- Branch utama `main`. Kerja optimalisasi di branch `optimalisasi`.
