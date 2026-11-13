# Frontend dan Design

## Dua tampilan terpisah

| | CMS (setelah login) | Landing page (publik) |
|---|---|---|
| Layout | `cms/layouts/app.blade.php` | `landing/layout.blade.php` |
| CSS | `public/assets/cms/css/app.css` (Tailwind CSS 3.0.24, sudah di-compile) | Bootstrap 5 + `public/assets/landing/css/style.css` |
| JS | `public/assets/cms/js/app.js` (bundle template: Tom Select, CKEditor, Toastify, ikon Lucide) | `bootstrap.bundle.min.js`, tiny-slider, swiper |
| Ikon | Lucide (`<i data-lucide="...">`) | Material Design Icons, Unicons (CDN) |
| Font | Roboto | Poppins dan Roboto |
| Warna utama | `--color-primary: 182 149 116` (`#B69574`, coklat) di `:root` | `#bc8f6e` di `colors/default.css` |

Kedua tampilan memakai file CSS/JS jadi dari `public/assets`. `vite.config.js` dan `resources/sass`, `resources/js` adalah sisa scaffolding `laravel/ui` dan tidak dimuat oleh layout mana pun. Tidak ada `tailwind.config.js` di repo, jadi kelas Tailwind yang belum ada di `app.css` tidak akan berfungsi. Pakai kelas yang sudah dipakai di view lain.

## Struktur layout CMS

```
app.blade.php
├── mobile-menu.blade.php   # Menu versi HP
├── topbar.blade.php        # Breadcrumb, profil, notifikasi session('success'/'error')
├── sidebar.blade.php       # Menu desktop, dibungkus @hasrole per role
└── <div class="content"> @yield('content')
```

Slot tambahan: `@push('custom-styles')` dan `@push('custom-scripts')`. Menu baru harus ditambahkan di `sidebar.blade.php` dan `mobile-menu.blade.php`.

`app.blade.php` memasang pencarian server pada input `id="filter"`: setelah 500 ms (atau Enter) parameter `?search=` dikirim, lalu tabel, ringkasan jumlah data, dan pagination diganti dari respons. Parameter lain di URL (filter, `perPage`) ikut terbawa.

## Filter tabel

Controller mendaftarkan filter lewat `App\Helpers\TableFilter::apply($query, $request, $defs)`, lalu mengirim hasilnya ke view sebagai `$filters`. View cukup memanggil `<x-table.filter :filters="$filters" />` (biasanya tepat sebelum `<!-- BEGIN: Data List -->`).

```php
$filters = TableFilter::apply($query, $request, [
    'period' => ['label' => 'Periode', 'type' => 'period', 'apply' => TableFilter::byOrderPeriod()],
    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Pending', 'accepted' => 'Diterima']],
    'order_date' => ['label' => 'Tanggal Pesanan', 'type' => 'date_range'],
]);
```

| Tipe | Parameter URL | Perilaku |
|---|---|---|
| `select` | `?status=accepted` | `where(column, value)`, atau closure `apply`. Nilai di luar `options` diabaikan. |
| `date_range` | `?order_date_from=&order_date_to=` | `whereDate` dengan format `YYYY-MM-DD` |
| `period` | `?period=<id>` atau `all` | Default periode aktif. Perlu `apply` atau `column`. |

Aturan periode:

- Tabel transaksi (pesanan, pembayaran, distribusi, retur, komisi, laporan, keuangan) memakai filter periode dengan default periode aktif.
- Halaman arsip hanya menawarkan periode tidak aktif, default `Semua`.
- Arus kas dan reward memakai default `Semua`. Data master lain tidak punya filter periode.
- Pesanan terhubung ke periode lewat paket produknya: `TableFilter::byOrderPeriod($kolomIdPesanan, $relasi)` memakai subquery join `order_details → product_packages → packages`. Jangan memakai `whereHas('detail.product.package.package...')` di tabel besar: MySQL mengevaluasinya per baris dan bisa butuh lebih dari 1 detik.
- Data tanpa paket (keuangan) memakai `TableFilter::byPeriodDates('date')`, yaitu rentang `start_date` sampai `end_date` periode.

`'apply' => null` berarti helper hanya membaca nilai; controller menerapkannya sendiri lewat `$filters['key']['value']`. Contohnya daftar agen di Pembayaran, yang memakai periode untuk membatasi agen sekaligus pesanan yang dijumlahkan (`PaymentController::agentPaymentQuery`), dan laporan (`ReportController::reportFilters`).

Export harus mengikuti filter: link export memakai `request()->fullUrlWithQuery(['export' => 'true'])`, dan pagination dijalankan pada `(clone $query)`. `paginate()` menambahkan LIMIT ke query aslinya, sehingga export yang memakai query yang sama setelah paginate hanya berisi baris halaman aktif.

CSS CMS sudah jadi (tidak ada build Tailwind). Class yang belum pernah dipakai, misalnya `sm:w-44` atau `w-36`, tidak ada di `app.css`. Cek dulu sebelum memakainya.

## Standar halaman tabel (Index CMS)

Setiap pembuatan halaman daftar data baru di CMS wajib mengikuti struktur berikut agar perilaku filter, pencarian server-side, pemilihan perPage, penomoran urut, dan paginasi seragam di seluruh aplikasi.

### 1. Pola Controller

```php
public function index(Request $request)
{
    $query = ModelName::latest('id');

    // 1. Pencarian server-side (terhubung otomatis dengan input id="filter" di layout app.blade.php)
    if ($request->filled('search')) {
        $search = trim($request->get('search'));
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        });
    }

    // 2. Filter terstruktur via helper TableFilter
    $filters = TableFilter::apply($query, $request, [
        'status' => [
            'label' => 'Status',
            'type' => 'select',
            'options' => ['active' => 'Aktif', 'inactive' => 'Tidak Aktif'],
        ],
        'date' => [
            'label' => 'Tanggal',
            'type' => 'date_range',
            'column' => 'created_at',
        ],
    ]);

    // 3. Paginasi aman dengan batas maksimum 50 baris
    $perPage = min(max((int) ($request->get('perPage') === 'all' ? 50 : $request->get('perPage', 10)), 1), 50);
    $items = $query->paginate($perPage)->withQueryString();

    return view('cms.admin.modul.index', compact('items', 'filters'));
}
```

### 2. Pola View Blade (`index.blade.php`)

```blade
@extends('cms.layouts.app', [
    'title' => 'Nama Halaman',
])

@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        Nama Halaman
    </h2>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Toolbar atas: Tombol aksi, Per-Page, info jumlah data, dan input pencarian -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{ route('modul.create') }}" class="btn btn-primary shadow-md mr-2">Tambah Data</a>
            @include('cms.layouts.per-page')

            @if ($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="hidden md:block mx-auto text-slate-500">
                    Menampilkan {{ $items->firstItem() }} hingga {{ $items->lastItem() }} dari {{ $items->total() }} data
                </div>
            @else
                <div class="hidden md:block mx-auto text-slate-500">
                    Menampilkan semua {{ $items->count() }} data
                </div>
            @endif

            <div class="w-full xl:w-auto flex items-center mt-3 xl:mt-0">
                <div class="w-56 relative text-slate-500">
                    <input type="text" class="form-control w-56 box pr-10" placeholder="Cari di halaman ini..."
                        title="Hanya mencari data pada halaman yang sedang aktif" id="filter">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-3 right-0" data-lucide="search"></i>
                </div>
            </div>
        </div>

        <!-- Filter tabel dinamis -->
        <x-table.filter :filters="$filters" />

        <!-- Tabel data -->
        <x-table.wrapper>
            <x-slot:header>
                <tr>
                    <th class="text-center whitespace-nowrap">#</th>
                    <th class="whitespace-nowrap">NAMA</th>
                    <th class="text-center whitespace-nowrap">STATUS</th>
                    <th class="text-center whitespace-nowrap">AKSI</th>
                </tr>
            </x-slot:header>

            @if ($items->isEmpty())
                <x-table.empty colspan="4" message="Belum Ada Data" />
            @else
                @foreach ($items as $item)
                    <tr class="intro-x">
                        <!-- Nomor urut berlanjut sesuai halaman paginasi -->
                        <td class="text-center font-medium">
                            {{ $items instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($items->firstItem() + $loop->index) : $loop->iteration }}
                        </td>
                        <td class="whitespace-nowrap font-medium">{{ $item->name }}</td>
                        <td class="text-center whitespace-nowrap">
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-success/20 text-success">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="table-report__action text-center whitespace-nowrap">
                            <a href="{{ route('modul.show', $item) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </x-table.wrapper>
        <!-- END: Tabel data -->

        <!-- Paginasi seragam -->
        <x-table.pagination :collection="$items" />
    </div>
@endsection
```

## Pola komponen CMS

Ambil contoh dari view yang sudah ada, misalnya `cms/admin/products/index.blade.php`.

| Komponen | Kelas / pola |
|---|---|
| Tombol utama | `btn btn-primary` |
| Tombol sekunder | `btn btn-outline-secondary` |
| Hapus | `btn btn-danger` di dalam modal konfirmasi |
| Tabel | `table table-report`, baris `intro-x` |
| Kartu | `box p-5` |
| Input | `form-control`, label `form-label` |
| Select dengan pencarian | `<select class="tom-select">` |
| Editor teks | `<textarea class="editor">` + `@pushOnce('custom-scripts', 'ckeditor-classic')` memuat `ckeditor-classic.js` (sekitar 740 KB) sekali per halaman. Jangan memuatnya di halaman tanpa `.editor`. |
| Gambar kecil | `w-10 h-10 image-fit zoom-in` |
| Pagination | `<x-table.pagination :collection="$data" />`, jumlah per halaman lewat `?perPage=` (maks. 100) |
| Filter tabel | `<x-table.filter :filters="$filters" />`, lihat bagian Filter tabel |
| Pesan sukses/error | `back()->with('success', ...)` dari controller, tampil otomatis di topbar |

## JavaScript di view

JS ditulis inline di `@push('custom-scripts')` per halaman. Form buat pesanan dan modal tambah produk (`cms/transactions/**/create*.blade.php`, `cms/transactions/modal/add-product*.blade.php`) memuat jQuery sendiri dari `code.jquery.com/jquery-3.7.1.min.js`; halaman lain memakai DOM API biasa. Form pesanan dan distribusi mengumpulkan baris tabel menjadi JSON, menaruhnya di input hidden, lalu submit form biasa (bukan AJAX).

Format Rupiah:

- Tampilan: `new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(n)` menghasilkan `Rp 487.500,00`.
- Simpan nilai angka di atribut (`data-price`) atau variabel. Jangan mengambil angka kembali dari teks hasil format; menghapus semua karakter non-digit dari `Rp 487.500,00` menghasilkan `48750000` (100 kali lipat).

## Landing page

Template Bootstrap dengan section yang diisi dari database (lihat [06-modul/landing-page.md](06-modul/landing-page.md)). Gambar konten diambil lewat route `getImage`, aset statis (logo, foto pemilik, syarat dan kebijakan) ada di `public/assets/*.jpg|png`.

## Responsif

Template CMS sudah punya `mobile-menu`. Tabel lebar dibungkus `overflow-auto` sehingga bisa digeser di HP. Tabel detail pesanan sudah lebar; saat menambah kolom, cek di layar HP.
