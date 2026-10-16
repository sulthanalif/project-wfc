# Modul Retur

Agen mengembalikan barang (rusak, kedaluwarsa, kelebihan, lainnya) disertai foto bukti.

## Status

Nilai: `pending` (default), `processed`, `finished`, `rejected`. `changeStatus` tidak membatasi urutan; status apa pun bisa diganti ke status lain. Saat diganti ke `finished`, `date_out` diisi tanggal hari ini.

## Route

Semua di prefix `transaction`, akses `admin`, `super_admin`, `finance_admin`, `agent`.

| Aksi | Route | Method |
|---|---|---|
| CRUD retur | `Route::resource('return')` | `Transaction\ProductReturnController` |
| Ubah status | `PUT return/{return}/change-status` | `@changeStatus` |
| Tambah item | `POST return/{return}/add-item` | `@addItem` |
| Ubah item | `PUT return/{return}/{item}/edit-item` | `@editItem` |
| Hapus item | `DELETE return/{return}/{item}/delete` | `@destroyItem` |

## Detail

- Header: `product_returns` (`return_number`, agen, `status`, `date_in`, `date_out`, `notes`).
- Item: `product_return_details` menunjuk ke pesanan, produk, dan opsional sub produk, dengan `status_product` (`damaged`, `expired`, `overstock`, `other`), `qty`, dan `proof_image`.
- Pilihan pesanan di form hanya pesanan yang statusnya bukan `pending`, `reject`, atau `canceled`.
- Foto bukti bisa dari upload file (`saveUploadedProofImage`) atau data URL base64 dari kamera (`saveProofImage`). Disimpan di `images/proofs`.
- Validasi `changeStatus` menerima `canceled`, tetapi kolom enum tidak punya nilai itu (lihat [09-known-issues.md](../09-known-issues.md)).

## File terkait

- Controller: `app/Http/Controllers/Transaction/ProductReturnController.php`
- Model: `ProductReturn`, `ProductReturnDetail`
- View: `resources/views/cms/return/`
