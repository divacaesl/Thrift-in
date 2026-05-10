-- ============================================================
-- ThriftIn - Platform Manajemen Titip Jual Preloved
-- Database: thriftin
-- ============================================================

CREATE DATABASE IF NOT EXISTS thriftin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE thriftin;

-- ------------------------------------------------------------
-- Tabel: users (Admin & Kasir)
-- ------------------------------------------------------------
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir',
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Tabel: penitip (orang yang nitip barang)
-- ------------------------------------------------------------
CREATE TABLE penitip (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_penitip VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    alamat TEXT DEFAULT NULL,
    nama_bank VARCHAR(50) DEFAULT NULL,
    no_rekening VARCHAR(30) DEFAULT NULL,
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Tabel: kategori barang
-- ------------------------------------------------------------
CREATE TABLE kategori (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(50) NOT NULL
);

-- ------------------------------------------------------------
-- Tabel: barang titipan
-- ------------------------------------------------------------
CREATE TABLE barang (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_barang VARCHAR(20) NOT NULL UNIQUE,
    penitip_id INT NOT NULL,
    kategori_id INT NOT NULL,
    nama_barang VARCHAR(150) NOT NULL,
    deskripsi TEXT DEFAULT NULL,
    kondisi ENUM('baru', 'seperti_baru', 'bekas_layak', 'bekas') NOT NULL,
    harga_jual DECIMAL(12,2) NOT NULL,
    foto VARCHAR(255) DEFAULT 'default.jpg',
    status ENUM('diterima','diverifikasi','ditampilkan','terjual','dicairkan','ditarik') NOT NULL DEFAULT 'diterima',
    tgl_masuk DATE NOT NULL,
    tgl_terjual DATE DEFAULT NULL,
    catatan TEXT DEFAULT NULL,
    FOREIGN KEY (penitip_id) REFERENCES penitip(id) ON DELETE CASCADE,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
);

-- ------------------------------------------------------------
-- Tabel: transaksi penjualan
-- ------------------------------------------------------------
CREATE TABLE transaksi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_transaksi VARCHAR(20) NOT NULL UNIQUE,
    barang_id INT NOT NULL,
    nama_pembeli VARCHAR(100) NOT NULL,
    no_hp_pembeli VARCHAR(20) NOT NULL,
    harga_jual DECIMAL(12,2) NOT NULL,
    komisi_persen DECIMAL(5,2) NOT NULL DEFAULT 20.00,
    komisi_nominal DECIMAL(12,2) NOT NULL,
    hasil_penitip DECIMAL(12,2) NOT NULL,
    metode_bayar ENUM('tunai','transfer') NOT NULL DEFAULT 'tunai',
    tgl_transaksi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    kasir_id INT NOT NULL,
    FOREIGN KEY (barang_id) REFERENCES barang(id),
    FOREIGN KEY (kasir_id) REFERENCES users(id)
);

-- ------------------------------------------------------------
-- Tabel: pencairan dana ke penitip
-- ------------------------------------------------------------
CREATE TABLE pencairan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode_pencairan VARCHAR(20) NOT NULL UNIQUE,
    penitip_id INT NOT NULL,
    jumlah DECIMAL(12,2) NOT NULL,
    tgl_pencairan DATE NOT NULL,
    metode ENUM('transfer','tunai') NOT NULL DEFAULT 'transfer',
    status ENUM('pending','diproses','selesai') NOT NULL DEFAULT 'pending',
    keterangan TEXT DEFAULT NULL,
    admin_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penitip_id) REFERENCES penitip(id),
    FOREIGN KEY (admin_id) REFERENCES users(id)
);

-- ============================================================
-- DATA AWAL / SEED
-- ============================================================

-- Users default (password: Admin123!)
INSERT INTO users (nama, username, email, password, role) VALUES
('Administrator', 'admin', 'admin@thriftin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Kasir 1', 'kasir1', 'kasir1@thriftin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kasir');

-- Kategori default
INSERT INTO kategori (nama_kategori) VALUES
('Atasan'), ('Bawahan'), ('Dress & Rok'), ('Outer & Jaket'),
('Sepatu'), ('Tas'), ('Aksesoris'), ('Lainnya');

-- Penitip contoh
INSERT INTO penitip (kode_penitip, nama, no_hp, email, alamat, nama_bank, no_rekening) VALUES
('PNT-001', 'Sari Dewi', '081234567890', 'sari@email.com', 'Jl. Raya Surabaya No. 10', 'BCA', '1234567890'),
('PNT-002', 'Budi Santoso', '082345678901', 'budi@email.com', 'Jl. Pemuda No. 5', 'Mandiri', '0987654321');

-- Barang contoh
INSERT INTO barang (kode_barang, penitip_id, kategori_id, nama_barang, kondisi, harga_jual, status, tgl_masuk) VALUES
('BRG-001', 1, 1, 'Kemeja Flanel Vintage', 'seperti_baru', 85000, 'ditampilkan', CURDATE()),
('BRG-002', 1, 4, 'Jaket Denim 90s', 'bekas_layak', 120000, 'ditampilkan', CURDATE()),
('BRG-003', 2, 6, 'Tas Kulit Mini Coklat', 'seperti_baru', 150000, 'terjual', CURDATE());
