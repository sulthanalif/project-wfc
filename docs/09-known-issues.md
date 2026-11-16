# Known Issues dan Utang Teknis

Daftar per 16 Oktober 2026, dari pembacaan kode dan log production. Hapus baris yang sudah diperbaiki.

## Keamanan

Tidak ada isu keamanan yang belum diperbaiki. Seluruh celah keamanan S1 s/d S6 telah selesai diperbaiki.

## Bug

Tidak ada bug yang belum diperbaiki.

## Pagination

Hampir semua halaman daftar memakai pola yang sama:

```php
$perPage = min(max((int) ($request->get('perPage') === 'all' ? 50 : $request->get('perPage', 10)), 1), 50);
$data = Model::...->paginate($perPage)->withQueryString();
```

```blade
<select id="records_per_page"> 10 / 25 / 50 </select>   {{-- JS mengganti ?perPage= lalu reload --}}
<input id="filter">                                     {{-- search di browser, layouts/app.blade.php --}}
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
| Upload berkas agen (KTP, Surat Perjanjian) bisa tampil "Data Sukses Diunggah!" padahal file gagal ditulis ke disk, karena disk `public` memakai `throw => false` dan hasil `put()` tidak dicek. Sekarang `AdministrationController::saveFile` melempar error dan transaksi dibatalkan | `main` |
| Admin mengunggah berkas untuk agen yang belum punya baris `administrations`: file tersimpan di disk, tapi `$user->administration()->update()` mengubah 0 baris, jadi toast "Data Sukses Diunggah!" muncul dan halaman tetap "Belum Ada Berkas". Upload satu file saja malah error karena `$user->administration` null. Sekarang memakai `updateOrCreate`, dan agen tanpa berkas wajib diunggah kedua file sekaligus | `main` |
| Agen bisa mengganti berkas agen lain lewat `PUT master/administration/{user}` karena `update()` tidak mengecek pemilik. Sekarang agen hanya boleh untuk `{user}` miliknya sendiri (403 untuk yang lain) | `main` |
| Upload berkas agen yang gagal validasi (file lebih dari 10 MB, bukan JPG/PNG/GIF, atau ditolak batas upload PHP) hanya menampilkan JSON error berbahasa Inggris lalu form kosong lagi, jadi agen mengira sudah terkirim. Sekarang pesannya berbahasa Indonesia dan input punya `required` dan `accept` | `main` |
| B13: Total pengeluaran menghitung `amount × qty`, padahal pengadaan menyimpan `amount` sebagai total (kartu total sekitar 147 kali lipat). Sekarang semua total memakai `total_amount`, baris pengadaan lama di-backfill | `optimalisasi` |
| B15: Laporan per barang (Perpaket, total produk Pesanan Agen, Sub Produk, Stock Sub Product) dengan filter periode menghitung semua produk dari pesanan yang punya minimal satu produk periode itu, jadi produk periode lain di pesanan yang sama ikut terhitung. Sekarang disaring per produk (`packages.period_id`) | `main` |
| B14: `ckeditor-classic.js` dimuat sekali per modal (error `ckeditor-duplicated-modules` di halaman pinjaman). Sekarang `@pushOnce('custom-scripts', 'ckeditor-classic')`, dan halaman tanpa `.editor` tidak memuatnya | `optimalisasi` |
| Detail pesanan (6 salinan) membawa sisa script form pembayaran yang error karena `#method` tidak ada | `optimalisasi` |
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
| P13: Hapus opsi `all` pada dropdown `per-page` (pilihan dibatasi 10, 25, 50) dan standardisasi paginasi ke batas aman maksimum 50 baris di 22 controller CMS | `optimalisasi` |
| P1: Opsi `perPage=all` dan input `perPage` dibatasi maksimal 100 di controller | `c939467` |
| P7: Index database pada kolom pivot `product_packages` (`product_id`, `package_id`), `order_details` (`order_id`, `product_id`), dan `orders` (`agent_id`, `status`) | `c939467` |
| P9: Default `perPage = 10` disamakan di seluruh 16 controller agar konsisten dengan pilihan pertama dropdown UI | `c939467` |
| P2: Paginate pembayaran admin per agen langsung di database (`User::role('agent')->whereHas('order', ...)->paginate()`) | `f62129d` |
| P3: Paginate pesanan dashboard admin per agen langsung di database, memperbaiki pagination yang hilang akibat `groupBy` di PHP | `f62129d` |
| Sisa query di view detail pembayaran (admin/agen, termasuk modal bayar) dan detail pinjaman: `payment()->...->sum()` dan `loanPayments()->sum()` diganti penjumlahan dari relasi yang sudah dimuat. `cms/transactions/table/table.blade.php` dihapus karena tidak di-include di mana pun | `main` |
| Logika `payment_status` disalin: `DetailOrderController@editDetail` mengisi `'unpaid'` sendiri di dua blok duplikat saat item terakhir dihapus. Sekarang satu blok, lalu lewat `Order::recalculatePaymentStatus()` seperti semua jalur lain | `main` |
| Kode tidak terpakai: `App\Helpers\ResponseFormatter`, paket `tymon/jwt-auth` (beserta `lcobucci/jwt`, `lcobucci/clock`, `stella-maris/clock`), `resources/js`, `resources/sass`, `resources/css`, `vite.config.js`, `package.json`, dan blok kode yang di-comment (5 baris atau lebih) di controller dan `routes/web.php` dihapus | `main` |
| `App\Http\Controllers\TestController` (route-nya sudah di-comment) dihapus. Catatan utang teknis "Halaman tanpa pagination" dan "Test hampir tidak ada" dihapus karena sudah tertangani P4, P13, dan test yang ada sekarang | `main` |
| P5: Eliminasi query N+1 per baris view dengan `withSum('payment as paid_total', 'pay')` dan `withCount()` di controller serta optimasi view | `2b1911d` |
| P6: Eager loading relasi yang dipakai view: produk (`detail`, `package.package.period`), user (`roles`, `agentProfile`, `adminProfile`), distribusi (`detail.orderDetail...`), paket (`period`), dan pesanan (`detail...`, `distributions.detail`) | `2b1911d` |
| P8: Link pagination tidak membawa query string (`->withQueryString()` di controller) | `b424a88` |
| P11: Ganti `perPage` dan filter status mempertahankan `?page=` lama (`urlParams.delete('page')` di view) | `b424a88` |
| Subtotal item tersimpan 100 kali lipat karena JS mem-parse teks `Rp 487.500,00`. Server sekarang menghitung `sub_price` sendiri. Data lama perlu diperbaiki manual (lihat [08-deployment.md](08-deployment.md)). | `9dca942` |
| `Attempt to read property "access_date" on null` di view pembayaran dan pesanan | `e39d07b` |
| Cron `schedule:run` gagal karena `proc_open` dimatikan hosting | Konfigurasi cron, lihat [08-deployment.md](08-deployment.md) |

## Utang teknis

- **Tiga salinan view pesanan** (biasa, `full/`, `aman/`, masing-masing dengan `detail` dan `archive-detail`). Perubahan harus diterapkan di semua salinan.
- **Foreign key tidak ada** untuk 31 kolom `uuid(...)->constrained()` (lihat [03-database.md](03-database.md)). Diputuskan tidak dipasang (15 November 2026). Alasannya: beberapa tombol hapus akan mulai gagal, misalnya hapus pesanan tidak menghapus `distributions`, hapus agen tidak menghapus `orders`, hapus produk tidak mengecek `order_details`. Selain itu ada data yatim yang harus dibersihkan dulu. Hitungan di database lokal: `distribution_details.order_detail_id` 99, `agent_profiles.user_id` 11, `orders.agent_id` 4, `product_details.product_id` 3, `sub_agents.agent_id` 3, `administrations.user_id` 1, `product_returns.user_id` 1. Kalau mau dipasang, tentukan aturan hapus per relasi dan backup production dulu.
- **Permission Spatie tidak pernah dicek.** `RoleAndPermissionSeeder` membuat `manage_users`, `add_order`, `approve_order`, tapi kode hanya memeriksa role.
- **Test baru mencakup sebagian modul.** Ada 17 file di `tests/Feature`, kebanyakan dari perbaikan bug, keamanan, dan pagination. Belum ada test untuk alur pesanan, pembayaran, dan distribusi dari awal sampai akhir. Sebagian test memakai data database lokal (`firstOrFail()`), jadi butuh database berisi data.
