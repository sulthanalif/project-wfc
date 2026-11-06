# Known Issues dan Utang Teknis

Daftar per 16 Oktober 2026, dari pembacaan kode dan log production. Hapus baris yang sudah diperbaiki.

## Keamanan

Tidak ada isu keamanan yang belum diperbaiki. Seluruh celah keamanan S1 s/d S6 telah selesai diperbaiki.

## Bug

| # | Masalah | Lokasi |
|---|---|---|
| B13 | Total pengeluaran dihitung `SUM(amount × qty)`, padahal pengadaan menyimpan `amount = harga × qty`. Baris pengadaan terhitung `harga × qty²`. Arus kas menjumlahkan `amount` saja, jadi kedua halaman tidak sama. Perlu dipastikan dulu arti `amount` di form pengeluaran manual. | `SpendingController@index`, `ProcurementController@store`, `CashFlowController@index` |
| B14 | Modal pinjaman di-include per baris dan masing-masing memuat `ckeditor-classic.js` serta script dengan `const` yang sama, sehingga muncul error `ckeditor-duplicated-modules` dan redeklarasi. | `cms/admin/finance/loan/modal/*.blade.php` |

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

Tidak ada masalah backend pagination yang belum diperbaiki.

### Frontend

Tidak ada masalah frontend pagination yang belum diperbaiki.

Seluruh issue pagination (P1 s/d P12) telah selesai diperbaiki.

## Sudah diperbaiki

| Masalah | Commit |
|---|---|
| Export pesanan & pembayaran hanya berisi baris halaman aktif karena `paginate()` mengubah query yang dipakai export | `optimalisasi` |
| Export arsip pesanan mengunduh data periode aktif (link mengarah ke export index) | `optimalisasi` |
| Tombol export retur tidak berfungsi (controller tidak menangani `export`) | `optimalisasi` |
| Search di tabel Dashboard admin diabaikan server | `optimalisasi` |
| Total pinjaman hanya menghitung baris di halaman aktif | `optimalisasi` |
| Form agen Full/Titik Aman memakai `form.submit()` sehingga lolos dari pengiriman fetch dan error "situs tidak dapat dijangkau" saat koneksi putus | `main` 8da4866 |
| S1: Pembatasan route dan controller status pesanan/pembayaran (`order.accOrder`, `order.changeOrderStatus`, `order.bulkConvertToSafePoint`, `changePaymentStatus`, `changePaymentVerify`, `return.changeStatus`) hanya untuk role admin/super_admin/finance_admin | `optimalisasi` |
| S2: Pengecekan otorisasi kepemilikan dan proteksi anti-IDOR di detail pesanan, arsip pesanan, ubah item, tambah item, pembayaran agen, retur produk, faktur invoice, dan sub agen | `optimalisasi` |
| S3: `Order*Controller@store` memaksa `agent_id` sesuai user login untuk agen, mencegah manipulasi pesanan atas nama agen lain | `optimalisasi` |
| S4: Penghapusan route debug publik `GET /sendmail` dan `GET /testview` di `routes/web.php` | `optimalisasi` |
| S5: Proteksi path traversal dan pembatasan akses dokumen sensitif (KTP dan Surat Perjanjian) pada `GetImageController` khusus untuk pemilik dokumen atau admin | `optimalisasi` |
| S6: Perbaikan penulisan middleware array `['role:super_admin|admin|finance_admin', 'active']` di grup route admin sehingga admin nonaktif terblokir | `optimalisasi` |
| B1: Perbaikan route action hilang: hapus route mati accPayment/rejectPayment, batasi resource distribution/tutorial/procurement, dan buat class LoanExport untuk export pinjaman | `optimalisasi` |
| B2: Total harga komisi dan dashboard agen dihitung langsung dari `sub_price` tanpa dikali `qty` ganda | `optimalisasi` |
| B3: `updatePayment` admin membaca `$request->file('photo')` sesuai validasi dan nama form input | `optimalisasi` |
| B4: Export pembayaran per agen memakai `whereIn('status', ['accepted', 'stop'])` agar pesanan stop ikut terunduh | `optimalisasi` |
| B5: Validasi `changeStatus` retur disesuaikan dengan nilai enum database ('pending,processed,finished,rejected') | `optimalisasi` |
| B6 & B7: Perhitungan `payment_status` menandai `paid` jika total bayar `>= total_price` (termasuk kelebihan bayar) dan kalkulasi otomatis saat harga produk berubah | `optimalisasi` |
| B8: Aktivasi periode menonaktifkan periode aktif lain dalam transaksi database sehingga hanya 1 periode aktif bersamaan | `optimalisasi` |
| B9: Koreksi typo role `admin_finance` menjadi `finance_admin` pada route page tutorial | `optimalisasi` |
| B10: Proteksi penghapusan rekening bank di controller jika masih terkait transaksi dan migration restrict foreign key di database | `optimalisasi` |
| B11: Pengecekan keamanan null product pada `bulkConvertToSafePoint` sebelum mengakses `$product->name` | `optimalisasi` |
| B12: Fallback instance model baru pada `LandingpageController` sehingga halaman profil dan landing page tidak crash saat tabel kosong | `optimalisasi` |
| P10: Klarifikasi scope pencarian dengan label/placeholder 'Cari di halaman ini...' di seluruh 43 view dan pesan feedback saat filter kosong di `app.blade.php` | `2db3bd3` |
| P4: Agregasi SQL (subquery, join, group by) dan LengthAwarePaginator di semua modul laporan admin (`report/*`), mengaktifkan pagination di view dan export dataset lengkap | `1b0f2ff` |
| P12: Partial dropdown bersama `cms/layouts/per-page.blade.php` dan skrip event listener terpusat di `app.blade.php` (menghapus 35 duplikasi view) | `4abbc4a` |
| P1: Opsi `perPage=all` dan input `perPage` dibatasi maksimal 100 di controller | `c939467` |
| P7: Index database pada kolom pivot `product_packages` (`product_id`, `package_id`), `order_details` (`order_id`, `product_id`), dan `orders` (`agent_id`, `status`) | `c939467` |
| P9: Default `perPage = 10` disamakan di seluruh 16 controller agar konsisten dengan pilihan pertama dropdown UI | `c939467` |
| P2: Paginate pembayaran admin per agen langsung di database (`User::role('agent')->whereHas('order', ...)->paginate()`) | `f62129d` |
| P3: Paginate pesanan dashboard admin per agen langsung di database, memperbaiki pagination yang hilang akibat `groupBy` di PHP | `f62129d` |
| P5: Eliminasi query N+1 per baris view dengan `withSum('payment as paid_total', 'pay')` dan `withCount()` di controller serta optimasi view | `2b1911d` |
| P6: Eager loading relasi yang dipakai view: produk (`detail`, `package.package.period`), user (`roles`, `agentProfile`, `adminProfile`), distribusi (`detail.orderDetail...`), paket (`period`), dan pesanan (`detail...`, `distributions.detail`) | `2b1911d` |
| P8: Link pagination tidak membawa query string (`->withQueryString()` di controller) | `b424a88` |
| P11: Ganti `perPage` dan filter status mempertahankan `?page=` lama (`urlParams.delete('page')` di view) | `b424a88` |
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
