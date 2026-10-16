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

## Pagination

Hampir semua halaman daftar memakai pola yang sama:

```php
$perPages = $request->get('perPage') ?? 5;
if ($perPages == 'all') { $data = Model::...->get(); }
else { $data = Model::...->paginate(intval($perPages)); }
```

```blade
<select id="records_per_page"> 10 / 25 / 50 / All </select>   {{-- JS mengganti ?perPage= lalu reload --}}
<input id="filter">                                           {{-- search di browser, layouts/app.blade.php --}}
@if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $data->links('cms.layouts.paginate') }}
@endif
```

### Backend

| # | Masalah | Halaman terdampak | Perbaikan |
|---|---|---|---|
| P1 | Opsi `perPage=all` memuat seluruh tabel tanpa batas | Semua halaman yang punya pilihan All (35 view dengan `records_per_page`) | Hapus opsi All atau batasi (misalnya maks 100) |
| P2 | Pembayaran admin memuat semua pesanan `accepted`/`stop`, `groupBy('agent_id')` di PHP, lalu memotong dengan `LengthAwarePaginator` manual | `transaction/payment` (`PaymentController@index`) | Paginate di database: `User::role('agent')->whereHas('order', ...)->withCount/withSum(...)->paginate()` |
| P3 | Dashboard admin memanggil `->paginate()->groupBy('agent_id')`. Hasilnya Collection biasa, jadi blok `@if (... instanceof LengthAwarePaginator)` di view tidak pernah benar: tombol halaman tidak tampil dan hanya 10 pesanan pertama yang dikelompokkan. Filter juga memakai `where('status', [...])` (lihat B4). | `admin` (`DashboardAdminController@index`) | Sama dengan P2, paginate per agen |
| P4 | Laporan menghitung semua data di PHP (`->get()` lalu `map`), pagination di view di-comment. Seluruh baris dirender sekaligus. | Semua `report/*` | Agregasi di SQL (`SUM`, `GROUP BY`) dan `paginate()`; export tetap ambil semua |
| P5 | Query per baris di view yang di-paginate (N+1), contohnya total bayar per pesanan dihitung di `@php` | `admin/payment/index` (6 query di view), `admin/index` (6), `distributions/index` (3), `payment/show`, `agen/payment/index`, `return/index` (2) | `with()`, `withSum('payment as paid', 'pay')`, `withCount()` di controller |
| P6 | Relasi yang dipakai view tidak di-eager load: produk (`detail`, `package.package`), user (`roles`, `agentProfile`), distribusi (`order`, `detail.orderDetail.order.agent.agentProfile`, `detail.orderDetail.subAgent`), paket (`period`) | `master/product`, `master/user`, `distribution`, `master/package` | Tambah `->with([...])` sebelum `paginate()` |
| P7 | Filter `whereHas('...period', is_active)` dengan rantai 4 relasi di setiap request daftar pesanan dan produk | `transaction/order*`, `master/product`, `master/package` | Index pada kolom pivot (`product_packages.product_id`, `package_id`, `order_details.order_id`, `product_id`) karena migration tidak membuat foreign key/index |

### Frontend

| # | Masalah | Halaman terdampak | Perbaikan |
|---|---|---|---|
| P8 | Link halaman tidak membawa query string. Pindah ke halaman 2 menghapus `perPage`, `status`, dan filter lain, sehingga kembali ke default. | Semua `->links('cms.layouts.paginate')` kecuali `finance/spending-type` dan `procurement/type` yang sudah `appends(['perPage' => ...])` | Satu baris di setiap controller: `->paginate($perPage)->withQueryString()` |
| P9 | Default controller `perPage = 5`, tetapi dropdown hanya punya 10/25/50/All. Saat halaman pertama dibuka, dropdown menampilkan 10 padahal yang tampil 5 baris. | 23 tempat di 16 controller (Product, Package, Order*, Distribution, User, SubProduct, Supplier, Spending, SpendingType, Income, Loan, Commission, Reward, SubAgent) | Samakan default ke 10, atau tambah opsi 5 |
| P10 | Search (`#filter`) hanya menyembunyikan baris di halaman yang sedang tampil. Data di halaman lain tidak ikut dicari, sehingga user mengira data tidak ada. | Semua halaman dengan input Search | Kirim `?search=` ke server dan filter dengan `where(... like ...)`, atau beri label "cari di halaman ini" |
| P11 | Mengganti `perPage` mempertahankan `?page=` lama. Dari halaman 5 dengan 10 baris, memilih 50 bisa membuka halaman kosong. | Script `records_per_page` di setiap view | Tambah `urlParams.delete('page')` sebelum reload |
| P12 | Script `records_per_page` dan markup dropdown disalin di setiap view (35 file) | Semua halaman daftar | Pindahkan ke satu partial `cms/layouts/per-page.blade.php` dan script ke `app.blade.php` |

Urutan yang disarankan: P8 dan P11 (satu baris per file, dampaknya langsung terasa), lalu P5 dan P6 di halaman pesanan dan pembayaran, lalu P2 dan P3.

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
