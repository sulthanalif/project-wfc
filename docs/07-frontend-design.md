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

`app.blade.php` juga memasang filter tabel sederhana: input dengan `id="filter"` menyembunyikan baris `tbody tr` yang tidak cocok. Filter ini hanya bekerja pada baris di halaman yang sedang tampil.

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
| Editor teks | CKEditor (`ckeditor-classic.js`) |
| Gambar kecil | `w-10 h-10 image-fit zoom-in` |
| Pagination | `@include('cms.layouts.paginate')`, jumlah per halaman lewat `?perPage=` |
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
