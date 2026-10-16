# Modul Keuangan

Semua route di prefix `finance`, akses `finance_admin` dan `super_admin` (kecuali rekening bank, komisi, dan reward; lihat di bawah).

## Rekening bank (`bank_owners`)

`Admin\BankOwnerController`, `Route::resource('master/bank-owner')`, akses `super_admin`, `admin`, `finance_admin`. Setiap pembayaran, pemasukan, pengeluaran, dan pinjaman metode transfer menunjuk ke satu rekening lewat `bank_owner_id`.

Foreign key `bank_owner_id` di `payments`, `incomes`, `spendings`, `loans`, `loan_payments` memakai `onDelete('cascade')`. Menghapus satu rekening akan menghapus semua transaksi yang menunjuk ke rekening itu.

## Arus kas

`GET finance/cash-flow` (`Admin\CashFlowController@index`) menghitung saldo per rekening dan satu baris Tunai:

```
saldo = (pemasukan + pembayaran pesanan accepted) - (pengeluaran + pinjaman keluar)
```

Per rekening hanya transaksi `method = transfer` dengan `bank_owner_id` rekening itu; baris Tunai memakai `method = Tunai`. Pembayaran cicilan pinjaman (`loan_payments`) tidak dihitung (kodenya di-comment).

Pindah dana antar rekening: `POST finance/cash-flow/transfer` (`transferCash`). Dicatat sebagai satu pengeluaran (jenis `Transfer`, dibuat otomatis dengan `firstOrCreate`) di rekening asal dan satu pemasukan di rekening tujuan.

## Pemasukan dan pengeluaran

| Data | Controller | Route | Export |
|---|---|---|---|
| Pemasukan | `Admin\IncomeController` | `Route::resource('finance/income')` | `GET finance/export-income` |
| Pengeluaran | `Admin\SpendingController` | `Route::resource('finance/spending')` | `GET finance/export-spending` |
| Jenis pengeluaran | `Admin\SpendingTypeController` | `GET/POST finance/spending-type`, `DELETE finance/spending-type/{id}` | |

## Pinjaman

`Admin\LoanController`, `Route::resource('finance/loan')` tanpa `create`/`edit`:

| Aksi | Route |
|---|---|
| Catat cicilan pinjaman | `POST finance/loan/{loan}/payment` |
| Hapus cicilan | `DELETE finance/loan/{loan}/payment/{payment}` |
| Verifikasi cicilan | `PUT finance/changePaymentVerify/{payment}` (`verifyPayment`) |
| Export | `GET finance/export-loan` |

`loans.status_payment`: `unpaid`, `pending`, `paid`. Bukti cicilan di `images/loan_payments`.

## Pengadaan

`ProcurementController`, `Route::resource('finance/procurement')`. Constructor menghitung kebutuhan sub produk dari semua `order_details` pesanan `accepted`: untuk setiap produk, `qty × amount` setiap sub produknya. Halaman create menampilkan kebutuhan itu, dan `store` mencatatnya sebagai pengeluaran jenis `Pengadaan`.

## Komisi

`Admin\CommissionController`, prefix `master/commissions`, akses `admin` dan `super_admin`. Satu komisi terikat ke satu paket:

- `term`: target jumlah produk.
- `reward`: kalau berisi `%`, bonus = persen × total harga; selain itu bonus = nilai × jumlah produk.

Halaman detail menghitung pemenang: agen dengan total qty produk paket itu ≥ `term`, diurutkan dari qty terbesar. Lihat juga catatan perhitungan total harga di [09-known-issues.md](../09-known-issues.md).

## Reward

`RewardController`, prefix `master/rewards`, akses `admin` dan `super_admin`. Reward terikat ke periode, dengan `target_qty`. Dashboard agen menampilkan progres agen dan sub agen terhadap reward. Laporan: `GET report/agent-reward`.

## File terkait

- Controller: `app/Http/Controllers/Admin/{BankOwner,CashFlow,Income,Spending,SpendingType,Loan,Commission}Controller.php`, `ProcurementController.php`, `RewardController.php`
- Model: `BankOwner`, `Income`, `Spending`, `SpendingType`, `Loan`, `LoanPayment`, `Commission`, `Reward`
- Route: `routes/admin/finance.php`, `routes/admin/masterReward.php`
- View: `resources/views/cms/admin/{finance,banks,commissions,rewards}/`
