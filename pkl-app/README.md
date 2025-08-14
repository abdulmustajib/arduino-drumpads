# Sistem Manajemen PKL (PHP + MySQL)

Fitur:
- Autentikasi (Admin, Siswa, Guru Pembimbing, Guru Industri)
- Monitoring PKL (catatan harian siswa, review/approve/reject oleh pembimbing/industri)
- Laporan siswa (unggah berkas, review/approve/reject)
- Admin: kelola pengguna, perusahaan, penugasan siswa-mentor-perusahaan

## Persiapan
1. Siapkan server web (Apache/Nginx) dan PHP 8.1+ dengan ekstensi `pdo_mysql`, `fileinfo`.
2. Buat database MySQL, contoh: `pkl_app`.
3. Salin kode ke direktori web, contoh `/var/www/html/pkl-app`.
4. Konfigurasi environment (opsional):
   - `APP_BASE_URL` (default `/`)
   - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
5. Pastikan direktori `storage/uploads` writeable oleh web server.

## Instalasi Awal
- Akses `http://host/app/public/index.php?route=install` atau rute `install` via rewrite.
- Isi form admin pertama.
- Login melalui `route=login`.

## Routing
- Tanpa rewrite: akses via `public/index.php?route=monitoring`.
- Dengan rewrite (disarankan), arahkan semua request ke `public/index.php`.

## Catatan Keamanan
- Ubah `CSRF_KEY` pada konfigurasi.
- Gunakan HTTPS di server produksi.