# Modul Agen dan Sub Agen

## Pendaftaran agen

1. Calon agen daftar di `/register` (`Auth\RegisterController`). User dibuat dengan `active = '0'` dan role `agent`. Form memakai reCAPTCHA.
2. Verifikasi email (middleware `verified`).
3. Upload KTP dan surat perjanjian di `/new-agent` (`AdministrationController@store`). File disimpan di `images/administration`, nama file di tabel `administrations`.
4. Halaman `/waiting` sampai admin mengaktifkan.
5. Admin membuka `master/user`, klik ubah status (`Admin\UserController@changeStatus`). `active` dibalik; saat menjadi `1`, email `NotificationApproveUser` dikirim.

Admin juga bisa membuat akun langsung (`UserController@store`, akun dibuat `active = 0`) atau import Excel (`POST import-agent`).

## Kelola user (master/user)

`Admin\UserController`, akses `admin` dan `super_admin`:

| Aksi | Keterangan |
|---|---|
| `changeStatus` | Aktif/nonaktif akun |
| `changeStatusAccess` | Membalik `is_open_access` (izin ubah pesanan di luar periode) |
| `changeRole` | Ganti role |
| `getAdmin`, `getAgent` | Daftar per jenis user |
| `export` | `GET export-agent` |

Berkas administrasi agen dilihat dan diganti lewat `GET/PUT master/administration/{user}`.

## Profil agen

`Agent\AgentProfileController`, route `{id}/profile` dan `{id}/profile/edit` (dari `routes/agent/profile.php`). Data di `agent_profiles` (alamat lengkap sampai RT/RW, foto). Ganti email dan password: `Auth\UpdateEmailPasswordController`.

## Dashboard agen

`Agent\DashboardController@index` (`GET agent`): ringkasan pesanan, pembayaran, dan progres reward/komisi agen. Laporan cicilan agen: `GET report/agent-instalment` (`Agent\AgentReportController`).

## Sub agen

`SubAgentController`, `Route::resource('master/sub-agent')`, akses `admin`, `super_admin`, `agent`. Agen mengelola sub agennya sendiri (`sub_agents.agent_id`). Saat membuat pesanan, setiap item bisa ditujukan ke sub agen; `order_details.sub_agent_id = null` berarti untuk agen itu sendiri. Export: `GET export-subagent`.

## Ulasan agen

`ReviewAgentController` (`Route::resource('reviews')`, role `agent`): agen menulis ulasan, admin memilih yang tampil di landing page (lihat [landing-page.md](landing-page.md)).

## File terkait

- Controller: `Auth/RegisterController.php`, `AdministrationController.php`, `Admin/UserController.php`, `Agent/*`, `SubAgentController.php`, `ReviewAgentController.php`
- Model: `User`, `AgentProfile`, `AdminProfile`, `Administration`, `SubAgent`, `Review`
- Route: `routes/admin/masterUser.php`, `routes/agent/*.php`
- View: `resources/views/cms/admin/users/`, `resources/views/cms/agen/`, `resources/views/cms/sub-agent/`
