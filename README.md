# Kala Senja

Situs kafe berbasis Laravel dengan menu yang dikelola dari database, formulir reservasi, dan area admin untuk mengelola menu serta konfirmasi reservasi.

## Menjalankan secara lokal

1. Pastikan PHP 8.1+, Composer, dan MySQL tersedia. Buka folder proyek melalui terminal Laragon.
2. Jika dependensi belum tersedia, jalankan `composer install`.
3. Pastikan `.env` sudah memiliki `APP_KEY` dan kredensial database yang benar. Buat database MySQL yang disebut pada `DB_DATABASE` jika belum ada.
4. Atur kredensial admin di `.env`:

   ```dotenv
   ADMIN_NAME="Admin Kala Senja"
   ADMIN_EMAIL=alamat-email-admin
   ADMIN_PASSWORD=kata-sandi-yang-kuat
   ```

5. Siapkan skema dan menu awal: `php artisan migrate --seed`.
6. Jalankan aplikasi: `php artisan serve`, lalu buka `http://127.0.0.1:8000`.
   Di Laragon, aplikasi juga dapat dibuka melalui virtual host proyek jika sudah diaktifkan.

Masuk ke `/admin/login` menggunakan email dan kata sandi yang disetel di `.env`. Admin dapat mengelola menu dan mengubah status reservasi. Jika kredensial admin diubah, jalankan kembali `php artisan db:seed` agar perubahan tersimpan.

Foto dan font halaman menggunakan URL Unsplash dan Google Fonts; koneksi internet diperlukan untuk memuatnya. Seluruh tampilan dan fungsi inti tetap berjalan jika aset eksternal tidak tersedia.

## Pengujian

Jalankan `php artisan test`. Pengujian menggunakan SQLite in-memory sehingga tidak mengubah database MySQL lokal.
