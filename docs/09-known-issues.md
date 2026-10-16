# Known Issues dan Utang Teknis

Daftar per 16 Oktober 2026, dari pembacaan kode dan log production. Hapus baris yang sudah diperbaiki.

## Keamanan

| # | Masalah | Lokasi | Dampak |
|---|---|---|---|
| S1 | Route ubah status bisa dipanggil role `agent`: `acc/{order}`, `changeOrderStatus/{order}`, `changePaymentStatus/{payment}`, `changePaymentVerify/{payment}`, `bulk-convert-to-safe-point`. Controller tidak mengecek role. | `routes/transaction/status.php` (grup role termasuk `agent` di `web.php`) | Agen bisa menyetujui pesanan atau menandai pembayarannya sendiri `accepted` lewat request manual |
| S2 | Tidak ada cek kepemilikan pada detail pesanan, ubah item, tambah item, pembayaran agen, dan retur. Hanya `index` yang difilter per agen. | `Transaction\*Controller` | Agen yang tahu UUID pesanan agen lain bisa melihat dan mengubahnya |
| S3 | `store` pesanan memakai `agent_id` dari input form | `Order*Controller@store` | Agen bisa membuat pesanan atas nama agen lain |
| S4 | Route debug publik: `GET /sendmail` mengirim email ke alamat tetap, `GET /testview` | `routes/web.php` | Siapa pun bisa memicu pengiriman email |
| S5 | `GET storage/images/{path}/{imageName}` tanpa login, termasuk folder `administration` (KTP dan surat perjanjian) | `GetImageController` | Dokumen pribadi agen bisa diunduh siapa saja yang tahu nama filenya |
| S6 | Middleware `active` tidak terpasang di grup admin karena ditulis sebagai elemen array biasa: `['middleware' => 'role:...', 'active']` | `routes/web.php` | Admin yang dinonaktifkan tetap bisa masuk |

## Bug

| # | Masalah | Lokasi |
|---|---|---|
| B1 | Route menunjuk ke method yang tidak ada (error 500 saat dibuka): `PaymentController@accPayment`, `@rejectPayment`, `LoanController@export`, `DistributionController@edit/@update`, `ProcurementController@edit/@update/@destroy`, `TutorialController@create/@show/@edit` | Cek ulang dengan skrip di bawah |
| B2 | Total harga untuk komisi dan dashboard agen dihitung `sub_price × qty`, padahal `sub_price` sudah termasuk qty. Bonus komisi persentase jadi terlalu besar. | `Admin\CommissionController` (show, archiveShow), `Agent\DashboardController` |
| B3 | `updatePayment` mengecek `hasFile('photo')` tapi membaca `file('image')`, sehingga ganti foto bukti dari sisi admin error | `PaymentController@updatePayment` |
| B4 | `where('status', ['accepted', 'stop'])` seharusnya `whereIn`; pesanan `stop` tidak ikut di data export | `PaymentController@index` baris 61, `@show` baris 104 |
| B5 | Validasi `changeStatus` retur menerima `canceled`, tetapi enum `product_returns.status` tidak punya nilai itu | `ProductReturnController@changeStatus` |
| B6 | `payment_status = paid` hanya kalau total bayar tepat sama dengan total harga; kelebihan bayar tetap `pending` | Semua perhitungan `payment_status` |
| B7 | Mengubah harga produk menghitung ulang semua pesanan lama yang memakai produk itu, tanpa menghitung ulang `payment_status` | `Admin\ProductController@update` |
| B8 | Beberapa periode bisa aktif bersamaan; kode memakai `Period::where('is_active', 1)->first()` | `PeriodController@activatePeriod` |
| B9 | Role `admin_finance` (salah ketik) di route `tutorial`, sehingga `finance_admin` tidak bisa membuka halaman tutorial | `routes/web.php` |
| B10 | Menghapus rekening (`bank_owners`) ikut menghapus pembayaran, pemasukan, pengeluaran, dan pinjaman yang terkait (FK `cascade`) | Migration `bank_owner_id` |
| B11 | `bulkConvertToSafePoint` mengakses `$product->name` saat `$product` null | `OrderController` |
| B12 | Halaman landing page error kalau tabel kontennya kosong (`::first()` lalu akses properti) | `LandingpageController` |

Skrip untuk mendeteksi B1 lagi (abaikan method `Auth\*` karena berasal dari trait `laravel/ui`):

```bash
php artisan route:list --except-vendor --json > /tmp/routes.json
python3 - /tmp/routes.json <<'EOF'
import json, sys, re, os
for r in json.load(open(sys.argv[1])):
    a = r['action']
    if '@' not in a or not a.startswith('App\\'): continue
    cls, m = a.split('@')
    path = 'app/' + cls[4:].replace('\\', '/') + '.php'
    if os.path.exists(path) and not re.search(r'function\s+' + m + r'\s*\(', open(path).read()):
        print(r['method'], r['uri'], a)
EOF
```

## Sudah diperbaiki (16 Oktober 2026)

| Masalah | Commit |
|---|---|
| Subtotal item tersimpan 100 kali lipat karena JS mem-parse teks `Rp 487.500,00`. Server sekarang menghitung `sub_price` sendiri. Data lama perlu diperbaiki manual (lihat [08-deployment.md](08-deployment.md)). | `9dca942` |
| `Attempt to read property "access_date" on null` di view pembayaran dan pesanan | `e39d07b` |
| Cron `schedule:run` gagal karena `proc_open` dimatikan hosting | Konfigurasi cron, lihat [08-deployment.md](08-deployment.md) |

## Utang teknis

- **Query di dalam view.** Banyak `@php` di Blade menjalankan query per baris tabel, misalnya `$order->payment()->where(...)->sum('pay')` di dalam `@foreach`. Pindahkan ke controller dengan eager load atau `withSum`.
- **Halaman tanpa pagination.** Sebagian index memakai `->get()` untuk semua data, terutama di cabang export dan `perPage=all`.
- **Tiga salinan view pesanan** (biasa, `full/`, `aman/`, masing-masing dengan `detail` dan `archive-detail`). Perubahan harus diterapkan di semua salinan.
- **Logika `payment_status` disalin** di `PaymentController` (4 method) dan `DetailOrderController`.
- **Foreign key tidak ada** untuk kolom `uuid(...)->constrained()` (lihat [03-database.md](03-database.md)).
- **Kode tidak terpakai:** `App\Helpers\ResponseFormatter`, paket `tymon/jwt-auth`, permission Spatie yang tidak pernah dicek, `resources/sass` dan `resources/js` (layout tidak memuatnya), banyak blok kode yang di-comment.
- **Test hampir tidak ada:** hanya `tests/Feature/OrderRouteTest.php`.
