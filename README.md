# WebPresent Pro

WebPresent Pro adalah sebuah **website absensi karyawan** berbasis Laravel yang menyediakan sistem manajemen kehadiran modern dengan dua jenis pengguna, yaitu **Admin** dan **Karyawan**. Sistem ini dirancang untuk mempermudah proses pencatatan kehadiran, pengelolaan karyawan, serta pengajuan izin/sakit.

---

## 🎯 Fitur Utama

### 👨‍💼 Admin
Admin memiliki akses penuh untuk mengelola seluruh aktivitas absensi dan data karyawan. Fitur yang tersedia antara lain:

- **CRUD Jenis Absensi** (Hadir, Sakit, Izin, dan lainnya)
- **CRUD Akun Karyawan** (membuat, mengedit, menonaktifkan akun karyawan)
- **Melihat seluruh data absensi karyawan**
- **Menyetujui atau menolak surat izin/sakit yang diajukan karyawan**
- **Mengekspor data absensi ke file** (Excel / CSV)
- **Mengatur data sistem terkait absensi**

### 👨‍🏭 Karyawan
Karyawan memiliki fitur yang fokus pada kehadiran dan laporan pribadi, antara lain:

- **Melakukan absensi masuk dan absensi pulang by foto**
- **Melihat riwayat pengajuan surat pribadi**
- **Mengajukan surat izin atau surat sakit kepada admin**
- **Melihat status pengajuan (disetujui / ditolak)**

---

## 🧰 Teknologi yang Digunakan

- **Laravel Framewor : v12 **
- **MySQL : v8.0 **
- **Blade Template Engine**
- **Bootstrap CSS**
- **Php : 8.3 **

---

## 🚀 Cara Instalasi dan Menjalankan Proyek

1. **clone repository**
   ```bash
   git clone https://github.com/TheFrans1/PresentPro.git
   cd PresentPro

2. **clone repository**
   ```bash
   composer install

3. **setup enviroment**
   ```bash
   cp .env.example .env
   php artisan key:generate

4. **konfigurasi database edit file .env
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_smartpresencepro
    DB_USERNAME=root
    DB_PASSWORD=your_password
5. **buat database**
   ```bash
   CREATE DATABASE db_smartpresencepro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

6. **jalankan migration & seeder
    ```bash
    php artisan migrate
    php artisan db:seed --class=AdminUserSeeder // untuk membuat akun admin
    php artisan db:seed --class=JadwalKerjaSeeder // membuat jadwal kerja default

7. **jalankan web**
   ```bash
   npm run dev

8. ** jalankan auto absensi pulang & cari karyawan alpa by sistem**
    ```bash
    php artisan absensi:tandai-alpha
    php artisan absensi:auto-pulang

### 👨‍💼 info akun admin
```bash
user : admin
password : password
