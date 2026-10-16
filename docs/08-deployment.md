# Deployment

Production: `paketsmartwfcjabar.id` di shared hosting Hostinger, folder `/home/u882277989/domains/paketsmartwfcjabar.id/public_html`.

## Batasan hosting

- `proc_open` dimatikan lewat `disable_functions` dan tidak bisa diaktifkan di shared hosting. Akibatnya `php artisan schedule:run` gagal untuk setiap `$schedule->command(...)`, karena Laravel menjalankan command terjadwal sebagai proses terpisah.
- Lihat isi `disable_functions` dengan `php -i | grep disable_functions`.

## Cron

Hanya satu cron, setiap menit:

```
/usr/bin/php /home/u882277989/domains/paketsmartwfcjabar.id/public_html/artisan queue:work --stop-when-empty --max-time=55 >> /dev/null 2>&1
```

Cron ini memproses antrean email langsung. Jangan menambahkan cron `schedule:run`, karena isi `app/Console/Kernel.php` (`queue:work` tiap menit) sudah digantikan cron di atas.

Kalau suatu saat butuh jadwal lain, tulis dengan `$schedule->call(fn () => ...)` (berjalan di proses yang sama, tidak butuh `proc_open`), lalu pasang lagi cron `schedule:run`.

## `.env` production

| Key | Nilai |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `QUEUE_CONNECTION` | `database` |
| `MAIL_MAILER` | `smtp`. Sementara `log` selama layanan email belum diperpanjang. |
| `MAIL_FROM_ADDRESS` | Harus mailbox yang ada dan dimiliki akun `MAIL_USERNAME`. Kalau tidak, SMTP menolak dengan `553 5.7.1 Sender address rejected`. |

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
