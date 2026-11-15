# Deployment

Production: `paketsmartwfcjabar.id` di shared hosting Hostinger, folder `/home/u882277989/domains/paketsmartwfcjabar.id/public_html`.

## Batasan hosting

- `proc_open` dimatikan lewat `disable_functions` dan tidak bisa diaktifkan di shared hosting. Akibatnya `php artisan schedule:run` gagal untuk setiap `$schedule->command(...)`, karena Laravel menjalankan command terjadwal sebagai proses terpisah.
- Lihat isi `disable_functions` dengan `php -i | grep disable_functions`.

## Cron

Hanya satu cron, setiap menit:

```
/usr/bin/php /home/u882277989/domains/paketsmartwfcjabar.id/public_html/artisan queue:work --stop-when-empty --rest=15 --max-time=40 --tries=3 --backoff=60 >> /dev/null 2>&1
```

Cron ini memproses antrean email langsung. Semua email masuk antrean: empat mailable di `app/Mail` dan notifikasi verifikasi email serta reset password (`app/Notifications/Queued*`, dipasang di model `User`). Jadi request tidak menunggu SMTP.

- `--rest=15` memberi jeda 15 detik antar email, karena SMTP Gmail membatasi pengiriman beruntun. Hasilnya maksimal 3 email per menit.
- `--max-time=40`, bukan 55: worker baru mengecek batas waktu setelah `--rest`. Kalau 55, worker lama masih jalan saat cron menit berikutnya mulai, dan jedanya tidak berlaku.
- `--tries=3 --backoff=60`: email yang gagal dicoba lagi 2 kali dengan jarak 60 detik sebelum masuk `failed_jobs`. Jangan menambahkan cron `schedule:run`, karena isi `app/Console/Kernel.php` (`queue:work` tiap menit) sudah digantikan cron di atas.

Kalau suatu saat butuh jadwal lain, tulis dengan `$schedule->call(fn () => ...)` (berjalan di proses yang sama, tidak butuh `proc_open`), lalu pasang lagi cron `schedule:run`.

## `.env` production

| Key | Nilai |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `QUEUE_CONNECTION` | `database` |
| `MAIL_MAILER` | `smtp`. Sementara `log` selama layanan email belum diperpanjang. |
| `MAIL_HOST` / `MAIL_PORT` / `MAIL_ENCRYPTION` | `smtp.gmail.com` / `587` / `tls` |
| `MAIL_USERNAME` / `MAIL_PASSWORD` | Alamat Gmail / App Password Google (bukan password akun) |
| `MAIL_FROM_ADDRESS` | Sama dengan alamat Gmail di `MAIL_USERNAME`. Kalau beda, SMTP menolak dengan `553 5.7.1 Sender address rejected`. |
| `APP_URL` | `https://paketsmartwfcjabar.id`. Link verifikasi dan reset password dibuat di dalam job, tanpa request, jadi memakai nilai ini. |

## Langkah deploy

```bash
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Setelah mengubah `.env`, jalankan `php artisan config:clear` atau `config:cache` lagi; nilai lama tetap terpakai selama cache config masih ada.

Tidak perlu `npm run build` (lihat [07-frontend-design.md](07-frontend-design.md)).

## Setelah deploy

- Cek log: `storage/logs/laravel-YYYY-MM-DD.log`.
- Job email yang gagal tersimpan di tabel `failed_jobs`. Kirim ulang: `php artisan queue:retry all`. Buang: `php artisan queue:flush`.

## Memperbaiki data harga pesanan

Kalau `order_details.sub_price` tidak sesuai `products.total_price × qty`:

- Per pesanan: buka `master/countPrice/{order}`.
- Lewat SQL, hanya baris yang terkena bug ×100:

```sql
UPDATE order_details od
JOIN products p ON p.id = od.product_id
SET od.sub_price = p.total_price * od.qty
WHERE od.sub_price = p.total_price * od.qty * 100;
```

Setelah itu samakan `orders.total_price` dengan jumlah `sub_price`, lalu cek `payment_status`. Backup tabel dulu.
