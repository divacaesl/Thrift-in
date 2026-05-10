========================================
  ThriftIn - Panduan Setup
========================================

LANGKAH SETUP:
1. Copy folder 'thriftin' ke C:\xampp\htdocs\
2. Buka XAMPP, aktifkan Apache + MySQL
3. Buka browser ke http://localhost/phpmyadmin
4. Klik 'Import' -> pilih file database/thriftin.sql -> klik 'Go'
5. Buka http://localhost/thriftin

LOGIN DEFAULT:
  Username : admin
  Password : password

STRUKTUR FOLDER:
  /config         -> koneksi database
  /includes       -> header & footer
  /admin/         -> semua halaman dashboard
    /penitip      -> kelola penitip (CRUD)
    /barang       -> katalog barang (CRUD + status)
    /transaksi    -> proses jual + hitung komisi
    /laporan      -> laporan pendapatan
    /pencairan    -> pencairan dana penitip
  /database       -> file SQL

MODUL YANG SUDAH ADA:
  [✓] Login / Logout
  [✓] Dashboard dengan statistik
  [✓] Manajemen Penitip (list + tambah)
  [✓] Transaksi Penjualan + auto-hitung komisi
  [✓] Laporan Pendapatan + filter tanggal

MODUL YANG PERLU DILANJUTKAN (bagi ke anggota):
  [ ] Penitip: edit.php (Orang 1)
  [ ] Barang: index.php, tambah.php, edit.php, update-status.php (Orang 2)
  [ ] Pencairan: index.php, proses.php (Orang 3)
  [ ] UI polish + responsif (Orang 4)

KOMISI DEFAULT: 20% dari harga jual
(bisa diubah di config/koneksi.php -> KOMISI_DEFAULT)
========================================
