# Modul Laporan, Export, Import

## Laporan

Semua di `Admin\ReportController`, prefix `report`, akses `super_admin`, `admin`, `finance_admin`. Tambahkan `?export=1` ke URL untuk mengunduh Excel. Rincian agen per produk tidak punya export.

| Menu | Route | Method | Class export |
|---|---|---|---|
| Harian | `report/daily` | `daily` | Pilih bagian dengan `?feature=orders` (default), `package`, atau `instalment`; masing-masing memakai `ReportAgentOrderExport`, `ReportProductDetailExport`, `ReportInstalmentExport` |
| Total Setoran | `report/total-deposit` | `totalDeposit` | `ReportTotalDepositExport` |
| Perpaket | `report/product-detail` | `productDetail` | `ReportProductDetailExport` |
| Rincian agen per produk | `report/agent-product-detail/{productId}` | `agentProductDetail` | |
| Reward Agen | `report/agent-reward` | `agentReward` | `ReportAgentOrderExport` |
| Pesanan Agen | `report/agent-order` | `agentOrder` | `ReportAgentOrderExport` |
| Cicilan | `report/instalment` | `instalment` | `ReportInstalmentExport` |
| Sub Produk (kebutuhan) | `report/requirement` | `requirement` | `ReportRequirementExport` |
| Stock Sub Product | `report/stock-sub-product` | `stockSubProduct` | `ReportStockSubExport` |

Laporan untuk agen sendiri: `report/agent-instalment` (`Agent\AgentReportController@agentInstallment`).

View laporan: `resources/views/cms/admin/reports/`.

## Export master data

| Route | Controller | Class |
|---|---|---|
| `GET export-agent` | `Admin\UserController@export` | `AgentExport` |
| `GET export-subagent` | `SubAgentController@export` | `SubAgentExport` |
| `GET export-package` | `Admin\PackageController@export` | `PackageExport` |
| `GET export-product` | `Admin\ProductController@export` | `ProductExport` |
| `GET export-sub-product` | `Admin\SubProductController@export` | `SubProductExport` |
| `GET finance/export-income` | `Admin\IncomeController@export` | `IncomeExport` |
| `GET finance/export-spending` | `Admin\SpendingController@export` | `SpendingExport` |
| `GET finance/export-loan` | `Admin\LoanController@export` | Method belum ada, route ini error |
| `?export=true` di detail pesanan | `Order*Controller@show` | `DetailOrderExport` |
| Tombol export di halaman pembayaran dan dashboard admin | `PaymentController`, `DashboardAdminController` | `ExportDatas` (generik: data + judul + header) |

## Import

| Route | Class |
|---|---|
| `POST import-agent` | `App\Imports\AgentImport` |
| `POST import-package` | `App\Imports\PackageImport` |
| `POST import-product` | `App\Imports\ProductImport` |
| `POST import-sub-product` | `App\Imports\SubProductImport` |

Format kolom yang diharapkan ada di masing-masing class import. Template file contoh disimpan di `public/assets/doc/` dan diunduh lewat `GET download/{file}`.

## PDF

| Dokumen | Route | Controller |
|---|---|---|
| Faktur cicilan | `GET transaction/invoice/{order}/{payment}` | `Transaction\ExportInvoiceController@getInvoice` |
| Invoice pesanan | `GET transaction/invoice/{order}` | `@getInvoiceOrder` |
| Surat jalan | `GET distribution/export/{distribution}` | `Admin\ExportDeliveryOrderController@getDeliveryOrder` |

Template PDF dirender dengan dompdf dari `cms/transactions/export/invoice.blade.php`, `cms/transactions/export/invoice-order.blade.php`, dan `cms/admin/distributions/export/distribution-order.blade.php`.

## Menambah laporan baru

1. Tambah method di `Admin\ReportController` dan route di `routes/admin/report.php`.
2. Kalau perlu Excel, buat class di `app/Exports` (contoh paling sederhana: `ReportInstalmentExport`) dan pakai pola `if ($request->get('export') == 1) return Excel::download(...)`.
3. Tambah menu di blok Laporan pada `cms/layouts/sidebar.blade.php` dan `mobile-menu.blade.php`.
