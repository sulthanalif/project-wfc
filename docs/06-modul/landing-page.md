# Modul Landing Page

Halaman publik, isinya dikelola admin dari CMS. Setiap bagian adalah satu baris di tabelnya (`::first()`), jadi CMS hanya mengubah, tidak menambah.

## Halaman publik

| URL | Method | View |
|---|---|---|
| `/` | `LandingpageController@index` | `landing/welcome.blade.php` |
| `/company-profile` | `@profile` | `landing/pages/profile.blade.php` |
| `/catalogs-product` | `@catalogs` | `landing/pages/catalogs.blade.php` (semua produk) |
| `/download/{file}` | `DownloadFileController@download` | File dari `public/assets/doc/` |

Data di halaman utama: `Header`, `Profile`, `Benefit`, `Gallery` (dengan gambar), `Contact` (dengan nomor), `ReviewPage` + `Review` yang `publish = 1`, `Reward` di periode aktif, dan produk (5 per halaman).

Route publik ada di `routes/landingpage/page.php`, layout di `landing/layout.blade.php`.

## CMS (master/landing-page)

Akses `admin` dan `super_admin`. Controller di `app/Http/Controllers/Admin/Landingpage/`.

| Bagian | Controller | Aksi tambahan |
|---|---|---|
| Header | `HeaderController` | |
| Profil | `ProfileController` | |
| Detail profil (sejarah, visi, misi) | `DetailProfileController` | Tambah/hapus misi |
| Benefit (agen dan mitra) | `BenefitController` | |
| Galeri | `GalleryController` | Tambah/hapus gambar |
| Ulasan | `ReviewPageController` | Tambah, ubah, hapus, publish ulasan |
| Kontak | `ContactController` | Tambah/ubah/hapus nomor |

Gambar landing page disimpan di `images/landingpage`.

Karena halaman membaca `::first()`, tabel harus punya minimal satu baris. Di instalasi baru jalankan seeder landing page (lihat [01-setup.md](../01-setup.md)), atau halaman akan error saat mengakses properti dari `null`.
