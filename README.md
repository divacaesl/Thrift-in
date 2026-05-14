# ThriftIn - Manajemen Titip Jual Preloved

**ThriftIn** adalah sistem manajemen inventory dan penjualan khusus untuk bisnis titip jual (consignment) barang preloved/thrift. Proyek ini telah dimigrasi secara penuh dari arsitektur PHP Native ke framework **Laravel 11** dengan desain yang modern dan responsif.

## 🚀 Fitur Utama yang Sudah Siap

- **Dashboard Real-Time**: Pantau statistik barang, total penjualan, komisi toko, dan pencairan dana.
- **Manajemen Kategori**: CRUD kategori barang menggunakan Modal Bootstrap.
- **Data Penitip (Vendor)**: Pengelolaan data penitip lengkap dengan kode unik (PNT-xxx), informasi bank, dan status akun.
- **Katalog Barang Titipan**: 
  - Penambahan barang baru dengan sistem upload foto.
  - Relasi otomatis ke data penitip dan kategori.
  - Pelacakan status barang (Diterima, Ditampilkan, Terjual).
- **Sistem Transaksi Penjualan**:
  - Pencatatan penjualan barang yang sedang "Ditampilkan".
  - **Perhitungan Otomatis**: Komisi toko (20%) dan bagi hasil penitip dihitung secara instan.
  - Update otomatis status barang menjadi "Terjual" saat transaksi disimpan.
- **Pencairan Dana (Withdrawal)**: Pencatatan pembayaran hasil penjualan kepada penitip.
- **Laporan Penjualan**: Menu laporan khusus admin untuk rekap data.
- **Sidebar Responsif**: Desain sidebar terbaru dengan fitur *internal scrolling* dan proteksi *taskbar clipping* agar menu tetap terlihat di berbagai resolusi layar.

## 🛠️ Teknologi

- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL (Koneksi .env port 3306/3307)
- **Frontend**: Bootstrap 5, FontAwesome 6, Custom Sage & Terracotta Theme.
- **Auth**: Custom Laravel Session Authentication.

## 🔐 Kredensial Akses

| Akun | Username | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` |
| **Kasir** | `kasir1` | `admin123` |

## 📦 Cara Menjalankan Proyek

1. **Persiapan**: Pastikan Composer dan PHP 8.2+ sudah terinstal.
2. **Environment**: Salin `.env.example` ke `.env` (sudah dikonfigurasi otomatis ke `thriftin_db`).
3. **Migrasi Database**:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Perintah ini akan membuat ulang tabel dan mengisi data contoh beserta akun login)*.
4. **Jalankan Aplikasi**:
   ```bash
   php artisan serve
   ```
   Buka `http://localhost:8000` di browser Anda.

---
&copy; 2026 **ThriftIn Team**.
