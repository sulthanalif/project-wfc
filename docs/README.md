# Dokumentasi Paket Smart WFC

Aplikasi web untuk mengelola penjualan paket barang yang dibayar dengan cicilan lewat jaringan agen: pesanan, cicilan pembayaran, distribusi barang, retur, keuangan, dan landing page. Dibangun dengan Laravel 10, berjalan di `paketsmartwfcjabar.id` (Hostinger).

## Urutan baca

| File | Isi | Baca saat |
|---|---|---|
| [01-setup.md](01-setup.md) | Instalasi lokal, `.env`, seeder, akun default | Pertama kali clone |
| [02-arsitektur.md](02-arsitektur.md) | Stack, struktur folder, alur request, helper, mail, queue | Sebelum mengubah apa pun |
| [03-database.md](03-database.md) | ERD dan semua tabel | Mengubah query, migration, model |
| [04-role-permission.md](04-role-permission.md) | Siapa boleh apa | Menambah halaman atau route |
| [05-routes.md](05-routes.md) | Semua route (dihasilkan otomatis) | Mencari controller dari URL |
| [06-modul/](06-modul/) | Alur bisnis per modul | Mengubah fitur tertentu |
| [07-frontend-design.md](07-frontend-design.md) | Layout Blade, template CSS, pola JS | Mengubah tampilan |
| [08-deployment.md](08-deployment.md) | Deploy ke Hostinger, cron, cache | Deploy |
| [09-known-issues.md](09-known-issues.md) | Bug yang diketahui dan utang teknis | Sebelum memperbaiki bug |

## Modul

- [Pesanan](06-modul/pesanan.md): pesanan biasa, Paket Full, Titik Aman, tambah produk, hitung ulang
- [Pembayaran](06-modul/pembayaran.md): cicilan, verifikasi, status lunas, invoice
- [Produk dan paket](06-modul/produk-paket.md): periode, katalog, paket, produk, sub produk, supplier
- [Distribusi](06-modul/distribusi.md): pengiriman barang dan surat jalan
- [Retur](06-modul/retur.md): pengembalian barang dengan bukti foto
- [Agen dan sub agen](06-modul/agen-subagen.md): registrasi, administrasi, aktivasi
- [Keuangan](06-modul/keuangan.md): pemasukan, pengeluaran, arus kas, pinjaman, pengadaan, komisi, reward
- [Laporan, export, import](06-modul/laporan-export.md)
- [Landing page](06-modul/landing-page.md)

## Glosarium

| Istilah | Arti di kode |
|---|---|
| Agen | User dengan role `agent`. Pemilik pesanan (`orders.agent_id`). |
| Sub agen | Anggota di bawah agen (`sub_agents`). Satu pesanan bisa berisi barang untuk beberapa sub agen (`order_details.sub_agent_id`). |
| Periode | Masa berlaku paket (`periods`). Hanya periode `is_active = 1` yang tampil di menu utama; sisanya masuk Arsip. |
| Paket | Kelompok produk dalam satu periode (`packages`). |
| Produk | Barang yang dipesan (`products`). Harga per hari × jumlah hari = `total_price`. |
| Sub produk | Komponen isi produk (`sub_products`), dipakai untuk kebutuhan pengadaan dan retur. |
| Titik Aman | Varian produk dengan `products.is_safe_point = 1`. Pesanan yang semua produknya Titik Aman tampil di menu Titik Aman. |
| Paket Full | Pesanan yang punya minimal satu produk biasa (`is_safe_point = 0`). |
| Mundur | Status pesanan `stop`: agen berhenti di tengah periode. |
| Distribusi | Pengiriman barang dari pesanan ke agen (`distributions`). |
| Surat jalan | PDF delivery order dari distribusi. |
