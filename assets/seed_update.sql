-- ============================================================
-- Update foto barang seed data agar sesuai dengan file aset
-- Jalankan SETELAH import thriftin.sql
-- ============================================================
USE thriftin_db;

UPDATE barang SET foto = 'BRG-001.jpg' WHERE kode_barang = 'BRG-001';
UPDATE barang SET foto = 'BRG-002.jpg' WHERE kode_barang = 'BRG-002';
UPDATE barang SET foto = 'BRG-003.jpg' WHERE kode_barang = 'BRG-003';

-- Tambah 3 barang contoh baru
INSERT INTO barang (kode_barang, penitip_id, kategori_id, nama_barang, deskripsi, kondisi, harga_jual, foto, status, tgl_masuk) VALUES
('BRG-004', 2, 2, 'Celana Cargo Army',
 'Celana cargo warna army green, banyak kantong, cocok buat street style.',
 'bekas_layak', 95000, 'BRG-004.jpg', 'ditampilkan', CURDATE()),

('BRG-005', 1, 3, 'Dress Floral Vintage',
 'Dress midi motif bunga-bunga retro. Kondisi masih sangat bagus, beli 2x pakai.',
 'seperti_baru', 130000, 'BRG-005.jpg', 'ditampilkan', CURDATE()),

('BRG-006', 2, 5, 'Boots Kulit Coklat',
 'Boots kulit asli, sol masih tebal. Timeless banget cocok semua outfit.',
 'bekas_layak', 175000, 'BRG-006.jpg', 'ditampilkan', CURDATE());
