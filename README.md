# Sistem Laporan Kerusakan Fasilitas Sekolah (Laravel 13)

Paket ini **melengkapi/menggantikan** file pada project Laravel 13 Anda yang sudah ada (bukan project baru).
Stack: Laravel 13, PHP, MySQL (Laragon), Blade, Tailwind CSS (CDN), Eloquent, Laravel Storage.

## 1. Struktur folder

```
app/
  Http/
    Controllers/   AuthController, DashboardController, ReportController, CategoryController,
                   UrgencyController, WorkerController, StudentController, ReportHistoryController
    Middleware/    RoleMiddleware
  Models/          User, Report, Category, Urgency, Worker, ReportHistory
database/
  migrations/      users (+ password_reset_tokens, sessions), categories, urgencies, workers,
                   reports, report_histories  (urutan sudah benar untuk foreign key)
  seeders/         DatabaseSeeder, UserSeeder, CategorySeeder, UrgencySeeder, WorkerSeeder, ReportSeeder
resources/views/
  layouts/app.blade.php        layout + navigasi per role
  auth/login.blade.php
  dashboard/{admin,siswa}.blade.php
  reports/{index,create,show,print}.blade.php
  master/{index,form}.blade.php   dipakai bersama oleh Kategori, Urgensi, Petugas
  students/{index,form}.blade.php
  histories/index.blade.php
  partials/status-badge.blade.php
routes/web.php
```

## 2. Memasang ke project Laravel yang sudah ada

1. **Backup** project Anda terlebih dahulu.
2. Salin isi ZIP ke root project (gabungkan folder, jawab "replace" bila diminta).
3. **Hapus migration lama milik Anda** di `database/migrations/` yang bernama:
   `*_create_users_table.php`, `*_create_categories_table.php`, `*_create_urgencies_table.php`,
   `*_create_workers_table.php`, `*_create_reports_table.php`, `*_create_report_histories_table.php`
   (kecuali file yang baru saja disalin dari paket ini). Jika tidak dihapus, migration akan bentrok (tabel ganda).
   Migration `cache`/`jobs` bawaan Laravel **tidak** disentuh.
4. Model, controller, middleware, view, dan `routes/web.php` dengan nama sama akan tergantikan.
   Jika `routes/web.php` Anda punya route lain, gabungkan manual.
5. Pastikan `app/Http/Controllers/Controller.php` (bawaan Laravel) tetap ada.
6. Middleware dipakai lewat nama kelas di route (`RoleMiddleware::class . ':admin'`), sehingga
   **tidak perlu mengubah `bootstrap/app.php`**. (Jika Anda ingin alias `role`, tambahkan
   `$middleware->alias(['role' => \App\Http\Middleware\RoleMiddleware::class]);` di `bootstrap/app.php`.)

## 3. Konfigurasi database Laragon

1. Jalankan Laragon, klik **Start All** (Apache/Nginx + MySQL).
2. Buat database: HeidiSQL/phpMyAdmin → database baru `laporan_sekolah` (collation `utf8mb4_unicode_ci`).
3. Edit `.env`:
   ```
   APP_URL=http://laporan-sekolah.test
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laporan_sekolah
   DB_USERNAME=root
   DB_PASSWORD=
   FILESYSTEM_DISK=public
   ```
   (Laragon default: user `root`, password kosong.)

## 4. Migration, seeder, storage

Di terminal Laragon pada folder project:

```bash
php artisan migrate:fresh --seed   # pertama kali (menghapus semua tabel lalu membuat ulang)
php artisan storage:link           # agar foto laporan bisa ditampilkan
```

Seeder aman dijalankan ulang tanpa menggandakan data:
```bash
php artisan db:seed
```
(Gunakan `php artisan migrate` biasa jika database masih kosong.)

> Tailwind dimuat lewat CDN (`cdn.tailwindcss.com`) sehingga **tidak perlu `npm install`** dan butuh koneksi internet.
> Untuk produksi, ganti dengan build Tailwind/Vite.

## 5. Akun login pengujian

| Role  | Username | Password     | Kelas     |
|-------|----------|--------------|-----------|
| Admin | admin    | admin12345   | -         |
| Siswa | siswa1   | siswa12345   | X IPA 1   |
| Siswa | siswa2   | siswa12345   | X IPS 2   |
| Siswa | siswa3   | siswa12345   | XI IPA 2  |
| Siswa | siswa4   | siswa12345   | XI IPS 1  |
| Siswa | siswa5   | siswa12345   | XII IPA 3 |

Ini **akun pengujian**. Ganti password sebelum dipakai sungguhan (menu Siswa untuk siswa; untuk admin
gunakan `php artisan tinker` → `User::where('username','admin')->first()->update(['password'=>'PasswordBaru'])`).

Petugas contoh bernama `... (DATA UJI)` memakai nomor dummy `628000000001`–`628000000003`
(**bukan** nomor WhatsApp sungguhan). Ganti lewat menu Petugas.

## 6. Menjalankan aplikasi

- Laragon (Auto Virtual Hosts): buka `http://nama-folder-project.test`
- Atau: `php artisan serve` lalu buka `http://127.0.0.1:8000`

## 7. Cara menguji tiap fitur

**Login/logout** — login sebagai `admin` dan `siswa1`; password salah menampilkan pesan error; Keluar mengakhiri sesi.

**Pembatasan akses (role)**
- Login `siswa1`, buka `/admin/students` → harus **403**.
- Buka `/laporan/{id}` milik siswa lain (mis. laporan `LK-SEED-0002` milik siswa2) → **403**.
- Daftar "Laporan Saya" siswa hanya menampilkan laporan miliknya.
- Tanpa login, semua halaman mengarahkan ke `/login`.

**Siswa – buat laporan** — menu Buat Laporan: pilih kategori & urgensi, isi lokasi manual, deskripsi, unggah foto
(JPG/PNG/WEBP ≤ 2 MB) → tiket `LK-YYYYMMDD-XXXXX` dibuat, status *Menunggu Verifikasi*, riwayat awal tercatat.
Detail laporan menampilkan foto, status, dan riwayat.

**Admin – semua laporan** — menu Laporan: pencarian, filter status/kategori/urgensi/petugas/tanggal, pagination.

**Admin – alur status** — buka detail laporan:
- *Menunggu Verifikasi* → **Verifikasi/Terima** atau **Tolak** (alasan wajib; kosong → ditolak validasi).
- *Diterima* → **Mulai Perbaikan** → *Sedang Diperbaiki* → **Tandai Selesai** (catatan perbaikan opsional).
- *Ditolak* dan *Selesai* bersifat final. Loncat status ditolak oleh server.
- Setiap perubahan menambah baris di Riwayat (juga terlihat di menu Riwayat).

**Admin – petugas & WhatsApp** — di detail laporan pilih petugas → Simpan → tombol **Bagikan via WhatsApp**
membuka `wa.me` dengan pesan tugas terisi (nomor dummy tidak akan terkirim; ganti nomor petugas untuk uji nyata).

**Admin – catatan perbaikan** — form Catatan Perbaikan di detail laporan; tercatat di riwayat.

**Admin – kelola siswa** — menu Siswa: tambah/ubah/hapus, cari nama/username/kelas, filter kelas.
Password minimal 8 karakter dan di-hash otomatis; kosongkan saat ubah agar tidak berubah.
Siswa yang sudah punya laporan tidak bisa dihapus.

**Admin – kategori, urgensi, petugas** — CRUD + pencarian + pagination. Kategori/urgensi yang sudah dipakai laporan
tidak bisa dihapus; menghapus petugas mengosongkan petugas pada laporan terkait.

**Statistik & cetak** — Dashboard Admin: jumlah per status, per kategori, per urgensi. Tombol **Cetak / PDF**
(di dashboard dan daftar laporan, mengikuti filter aktif) membuka halaman cetak; pilih *Save as PDF* di dialog cetak browser.

**Seeder idempoten** — jalankan `php artisan db:seed` dua kali; jumlah data tetap (1 admin, 5 siswa, 10 kategori,
4 urgensi, 3 petugas, 6 laporan contoh).

## 8. Catatan teknis

- Foreign key: `reports` → users/categories/urgencies (`restrictOnDelete`), workers (`nullOnDelete`);
  `report_histories` → reports (`cascadeOnDelete`), users sebagai admin (`nullOnDelete`).
- `report_histories.admin_id` bernilai `NULL` untuk riwayat awal yang dibuat siswa.
- Foto disimpan di `storage/app/public/reports` (disk `public`).
- Tidak ada `vendor/`, `node_modules/`, atau data pribadi di paket ini.
