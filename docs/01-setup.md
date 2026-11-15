# Setup Lokal

## Kebutuhan

- PHP 8.1 atau lebih baru (`composer.json`: `"php": "^8.1"`), dengan ekstensi `gd` untuk Intervention Image dan dompdf
- Composer
- MySQL atau MariaDB
- Node.js tidak diperlukan. Tampilan CMS dan landing page memakai file CSS/JS jadi di `public/assets`, jadi tidak perlu build (lihat [07-frontend-design.md](07-frontend-design.md)).

## Langkah

```bash
git clone https://github.com/sulthanalif/project-wfc.git
cd project-wfc
composer install
cp .env.example .env
php artisan key:generate
```

Isi `.env`:

| Key | Isi |
|---|---|
| `DB_*` | Koneksi database lokal |
| `QUEUE_CONNECTION` | `sync` untuk lokal (email langsung dikirim), `database` di server |
| `MAIL_MAILER` | `log` untuk lokal, email ditulis ke `storage/logs` |
| `NOCAPTCHA_SECRET`, `NOCAPTCHA_SITEKEY` | Google reCAPTCHA untuk form register. Kosongkan di lokal kalau tidak menguji register. |

Migrasi dan data awal:

```bash
php artisan migrate
php artisan db:seed --class=RoleAndPermissionSeeder
php artisan db:seed --class=AdminSeeder
php artisan db:seed --class=SpendingTypeSeeder
php artisan db:seed --class=BankOwnerSeeder
```

`DatabaseSeeder` hanya menjalankan `BenefitSeeder`; seeder lain di-comment, jadi panggil satu per satu seperti di atas. Seeder landing page (`HeaderSeeder`, `ProfileSeeder`, `DetailProfileSeeder`, `GallerySeeder`, `ContactSeeder`, `ReviewPageSeeder`) perlu dijalankan juga kalau ingin halaman depan terisi.

Jalankan:

```bash
php artisan serve
```

## Akun dari AdminSeeder

Semua password: `password`. Semua akun langsung aktif (`active = 1`).

| Email | Role |
|---|---|
| superadmin@gmail.com | super_admin |
| financeadmin@gmail.com | finance_admin |
| admin@gmail.com | admin |
| agent@gmail.com | agent |

Jangan jalankan `AdminSeeder` di production.

## Gambar upload

File upload disimpan di `storage/app/public/images/<folder>` dan disajikan lewat route `GET storage/images/{path}/{imageName}` (`GetImageController@displayImage`), bukan lewat symlink `storage:link`. Folder yang dipakai: `administration`, `landingpage`, `loan_payments`, `package`, `photos`, `product`, `proofs`, `reward`.

## Test

```bash
php artisan test
```

Ada 17 file di `tests/Feature`, kebanyakan dari perbaikan bug, keamanan, dan pagination. Sebagian test membaca data yang sudah ada (`firstOrFail()`) dan memakai `DatabaseTransactions`, jadi butuh database lokal berisi data. Alur pesanan, pembayaran, dan distribusi dari awal sampai akhir belum punya test, jadi cek manual di browser tetap perlu.
