# PresentPro

Sistem backend API berbasis Laravel untuk manajemen absensi karyawan dan admin.  
Memiliki dua tipe pengguna: **Admin** dan **Karyawan**, dengan hak akses berbeda.  

---

## 🎯 Fitur Utama

- Autentikasi **Admin** dan **Karyawan**  
- Role-based access control (Admin vs Karyawan)  
- CRUD data karyawan (Admin)  
- Karyawan bisa melihat & mengubah profil sendiri  
- Endpoint API RESTful  
- Respon JSON  
- Manajemen absensi (absensi masuk / keluar) — *(asumsi, kalau ada fitur absensi, kalau tidak bisa dihapus bagian ini)*  
- Pencarian / filter data karyawan (misal berdasarkan departemen, status)  
- Migrasi dan seeder database untuk setup awal  
- Dokumentasi SQL (file `.sql` untuk backup / import database)

---

## 🔧 Teknologi yang Digunakan

- **Framework**: Laravel (versi sesuai proyek)  
- **Database**: MySQL (atau sesuaikan dengan yang kamu pakai)  
- **Autentikasi**: Laravel Sanctum (atau metode autentikasi lain, sesuaikan)  
- **API Style**: RESTful  
- **Response**: JSON  

---

## 🚀 Panduan Instalasi & Setup

Berikut langkah-langkah untuk menjalankan `PresentPro` secara lokal:

1. **Clone repository**  
   ```bash
   git clone https://github.com/TheFrans1/PresentPro.git
   cd PresentPro
