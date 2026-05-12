# ThriftIn 👕
> **Platform Manajemen Titip Jual Preloved**
> *Titip, Jual, Cuan — No Ribet.*

Sistem manajemen berbasis web untuk toko thrift lokal yang menerima barang titipan, mengelola katalog, memproses penjualan, dan mencairkan hasil ke penitip secara otomatis — lengkap dengan perhitungan komisi toko.

---

## ⚡ Quick Start

### Prasyarat
- XAMPP (Apache + MySQL aktif)
- PHP 7.4+
- Browser Chrome / Firefox / Edge

### Langkah Setup (5 menit)

**1. Letakkan project**
```
Salin folder thriftin ke:
C:\xampp\htdocs\thriftin
```

**2. Import database**
```
Buka → http://localhost/phpmyadmin
Klik → Import
Pilih → database/thriftin.sql
Klik → Go
```

> ⚠️ Database yang digunakan: **`thriftin_db`** (bukan `thriftin`)

**3. Cek konfigurasi** (`config/koneksi.php`)
```php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3307);       // sesuaikan jika port MySQL kamu berbeda
define('DB_NAME', 'thriftin_db');
define('DB_USER', 'root');
define('DB_PASS', '');         // isi jika punya password MySQL
```

**4. Buka di browser**
```
http://localhost/thriftin
```

### Akun Default

| Role  | Username | Password |
|-------|----------|----------|
| Admin | `admin`  | `password` |
| Kasir | `kasir1` | `password` |

---

## 📁 Struktur Folder

```
thriftin/
│
├── index.php                        ← Redirect otomatis ke login
├── login.php                        ← Halaman login + validasi
├── logout.php                       ← Hapus session & redirect
│
├── config/
│   └── koneksi.php                  ← Koneksi DB + helper functions
│                                       (rupiah, badgeKondisi, badgeStatus, generateKode)
│
├── includes/
│   ├── auth.php                     ← Guard session (include di semua halaman admin)
│   ├── header.php                   ← HTML head + topbar
│   ├── sidebar.php                  ← Navigasi sidebar + CSS global
│   └── footer.php                   ← Penutup HTML + Bootstrap JS
│
├── admin/
│   ├── dashboard.php                ← Statistik & ringkasan data
│   ├── logout.php                   ← Logout dari dalam admin
│   │
│   ├── penitip/
│   │   ├── index.php                ← Daftar penitip + search + hapus
│   │   ├── tambah.php               ← Form tambah penitip baru
│   │   └── edit.php                 ← Form edit data penitip
│   │
│   ├── barang/
│   │   ├── index.php                ← Katalog barang + filter status/kondisi
│   │   ├── tambah.php               ← Form input barang titipan + upload foto
│   │   ├── edit.php                 ← Form edit detail barang
│   │   ├── status.php               ← Update status barang (flow: diterima→terjual)
│   │   └── hapus.php                ← Hapus barang (dengan validasi)
│   │
│   ├── transaksi/
│   │   └── index.php                ← Form jual barang + riwayat transaksi
│   │                                   (auto-hitung komisi & hasil penitip)
│   │
│   ├── pencairan/
│   │   ├── index.php                ← Daftar pencairan dana penitip
│   │   └── tambah.php               ← Form cairkan dana ke penitip
│   │
│   └── laporan/
│       └── index.php                ← Laporan pendapatan + filter tanggal
│
├── database/
│   └── thriftin.sql                 ← Skema database + data awal (seed)
│
└── assets/
    └── uploads/                     ← Foto barang yang diupload
```

---

## 🗃️ Skema Database

| Tabel | Isi |
|-------|-----|
| `users` | Admin & kasir (role, status aktif/nonaktif) |
| `penitip` | Data orang yang menitipkan barang |
| `kategori` | Kategori barang (Atasan, Bawahan, Tas, dll) |
| `barang` | Katalog item titipan + kondisi + status |
| `transaksi` | Riwayat penjualan + komisi + hasil penitip |
| `pencairan` | Pencairan dana hasil jual ke penitip |

---

## 🧠 Business Logic Penting

### 1. Auto-Hitung Komisi Toko
```
Komisi default = 20% dari harga jual
→ diatur di config/koneksi.php: define('KOMISI_DEFAULT', 20)

Contoh:
  Harga jual     = Rp 150.000
  Komisi toko    = Rp  30.000  (20%)
  Hasil penitip  = Rp 120.000  (80%)
```

### 2. Flow Status Barang
```
DITERIMA → DIVERIFIKASI → DITAMPILKAN → TERJUAL → DICAIRKAN
                                    ↘ DITARIK  (penitip minta barang kembali)
```
> ⚠️ Status **tidak boleh mundur** — validasi ini krusial untuk test case pengujian!

### 3. Kondisi Barang
```
Baru          → item masih bersegel / belum pernah pakai
Seperti Baru  → dipakai 1-2x, mulus
Bekas Layak   → ada jejak pemakaian tapi masih oke
Bekas         → visible wear, harga lebih terjangkau
```

### 4. Generate Kode Otomatis
Format kode: `PREFIX-YYMMDD-NNN`
```
PNT-260510-001  → penitip ke-1 tanggal 10 Mei 2026
BRG-260510-003  → barang ke-3 hari itu
TRX-260510-007  → transaksi ke-7
```

---

## 🛠️ Helper Functions (`config/koneksi.php`)

```php
rupiah($angka)              // → "Rp 150.000"
badgeKondisi($kondisi)      // → <span class="badge ...">Seperti Baru</span>
badgeStatus($status)        // → <span class="badge ...">Terjual ✓</span>
generateKode($prefix, $table, $kolom, $conn)  // → "TRX-260510-001"
```

---

## 👥 Pembagian Tugas Tim

| Anggota | Modul yang Dikerjakan |
|---------|----------------------|
| Orang 1 | `login.php`, `includes/auth.php`, `includes/header.php`, setup TestLink & test case autentikasi |
| Orang 2 | `admin/penitip/` (index, tambah, edit), `admin/barang/` (index, tambah, edit, hapus, status) |
| Orang 3 | `admin/transaksi/index.php`, `admin/pencairan/` (index, tambah), Traceability Matrix |
| Orang 4 | `admin/laporan/index.php`, `admin/dashboard.php`, `includes/sidebar.php`, UI & SUS testing |

---

## 🎨 Design System

| Token | Nilai | Penggunaan |
|-------|-------|-----------|
| `--sage` | `#5C8A6B` | Warna utama (tombol, aktif, accent) |
| `--terracotta` | `#D4956A` | Highlight, brand logo |
| `--cream` | `#F5F0E8` | Background halaman login & elemen sekunder |

CSS Framework: **Bootstrap 5.3** (CDN) + Font Awesome 6.4

---

## 🐛 Troubleshooting

| Error | Kemungkinan Penyebab | Solusi |
|-------|---------------------|--------|
| `No connection could be made` | MySQL belum distart | Buka XAMPP → klik **Start** di baris MySQL |
| `Unknown database 'thriftin_db'` | Database belum diimport | Import `database/thriftin.sql` via phpMyAdmin |
| `Access denied for user 'root'` | Password MySQL salah | Sesuaikan `DB_PASS` di `config/koneksi.php` |
| Halaman putih / blank | Port MySQL bukan 3307 | Ubah `DB_PORT` sesuai port XAMPP kamu (default: 3306) |
| Foto barang tidak muncul | Folder uploads belum ada | Buat folder `assets/uploads/` secara manual |

---

## 📚 Referensi

- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3/)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [PHP MySQLi Docs](https://www.php.net/manual/en/book.mysqli.php)
- [TestLink Docs](https://testlink.org/doc/)
- [Katalon Recorder](https://docs.katalon.com/katalon-recorder/docs/overview.html)

---

> *Dibuat untuk keperluan Praktikum Pengujian Perangkat Lunak*
> *Program Studi Manajemen Informatika — Universitas Negeri Surabaya 2026*
