# ThriftIn 👕
> Platform Manajemen Titip Jual Preloved — Titip, Jual, Cuan. No Ribet.

---

## 📁 Struktur Folder

```
thriftin/
│
├── index.php                    ← Redirect otomatis
├── login.php                    ← Halaman login
├── thriftin.sql                 ← File database (import ke phpMyAdmin)
│
├── config/
│   └── koneksi.php              ← Koneksi database + helper functions
│
├── includes/
│   ├── auth.php                 ← Guard login (include di semua halaman admin)
│   └── sidebar.php              ← Sidebar + CSS global
│
├── admin/
│   ├── logout.php               ✅ Selesai
│   ├── dashboard.php            ✅ Selesai
│   │
│   ├── penitip.php              ← [ORANG 2] CRUD data penitip
│   ├── penitip_tambah.php       ← [ORANG 2]
│   ├── penitip_edit.php         ← [ORANG 2]
│   ├── penitip_hapus.php        ← [ORANG 2]
│   │
│   ├── barang.php               ← [ORANG 2] Katalog barang titipan
│   ├── barang_tambah.php        ← [ORANG 2]
│   ├── barang_edit.php          ← [ORANG 2]
│   ├── barang_hapus.php         ← [ORANG 2]
│   ├── barang_status.php        ← [ORANG 2] Update status barang
│   │
│   ├── transaksi.php            ← [ORANG 3] Daftar transaksi jual
│   ├── transaksi_tambah.php     ← [ORANG 3] Proses jual barang
│   │
│   ├── pencairan.php            ← [ORANG 3] Daftar pencairan dana
│   ├── pencairan_tambah.php     ← [ORANG 3] Cairkan dana ke penitip
│   │
│   ├── laporan.php              ← [ORANG 4] Laporan & rekap
│   ├── pengaturan.php           ← [ORANG 4] Pengaturan toko (admin only)
│   └── users.php                ← [ORANG 1] Kelola user (admin only)
│
└── assets/
    ├── uploads/                 ← Foto barang diupload di sini
    └── img/
```

---

## 🚀 Cara Setup

### 1. Import Database
1. Buka `http://localhost/phpmyadmin`
2. Buat database baru → nama: `thriftin_db`
3. Klik tab **Import** → pilih file `thriftin.sql` → klik Go

### 2. Copy ke htdocs
```
Salin seluruh folder ke:
C:\xampp\htdocs\thriftin\
```

### 3. Akses di Browser
```
http://localhost/thriftin
```

### 4. Login Default
| Username | Password | Role  |
|----------|----------|-------|
| admin    | admin123 | Admin |
| kasir1   | admin123 | Kasir |

---

## 👥 Pembagian Tugas

| Anggota  | File yang Dikerjakan                              |
|----------|---------------------------------------------------|
| Orang 1  | `login.php`, `users.php`, setup TestLink          |
| Orang 2  | `penitip.php` + CRUD, `barang.php` + CRUD         |
| Orang 3  | `transaksi_tambah.php`, `pencairan.php`           |
| Orang 4  | `laporan.php`, `pengaturan.php`, UI polish        |

---

## 🧠 Business Logic Penting

### Hitung Komisi
```php
// Di config/koneksi.php sudah ada fungsi ini:
$result = hitungKomisi($harga_jual, $komisi_persen);
// $result['komisi_nominal'] → bagian toko
// $result['hasil_penitip']  → bagian penitip
```

### Flow Status Barang
```
MENUNGGU → DITAMPILKAN → TERJUAL → DICAIRKAN
                    ↘ DITARIK (kalau penitip minta balik)
```
> ⚠️ Status tidak boleh mundur! Validasi ini penting untuk test case.

### Harga Minimum per Kondisi (dari tabel pengaturan)
```
Baru           → min Rp 10.000
Seperti Baru   → min Rp  5.000
Bekas Layak    → min Rp  3.000
Bekas          → min Rp  1.000
```

---