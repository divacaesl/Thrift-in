<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Penitip;
use App\Models\Barang;
use App\Models\Voucher;
use App\Models\AlamatPengiriman;
use App\Models\Notifikasi;
use App\Models\Ulasan;
use App\Models\Pencairan;
use App\Models\Chat;
use App\Models\NegoHarga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Default Administrator & Cashier Users
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@thriftin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Kasir 1',
            'username' => 'kasir1',
            'email' => 'kasir1@thriftin.com',
            'password' => Hash::make('admin123'),
            'role' => 'kasir',
        ]);

        // Default Buyer User
        $buyer = User::create([
            'nama' => 'Budi Pembeli',
            'username' => 'budi',
            'email' => 'budi@email.com',
            'password' => Hash::make('budi123'),
            'role' => 'pembeli',
            'no_hp' => '081234567890',
            'foto_profil' => 'budi.jpg',
            'metode_bayar_favorit' => 'qris',
            'preferred_language' => 'id',
            'theme_mode' => 'light'
        ]);

        // Default Seller 1 (Sari Dewi)
        $seller1 = User::create([
            'nama' => 'Sari Dewi (Seller)',
            'username' => 'saridewi',
            'email' => 'sari@email.com',
            'password' => Hash::make('sari123'),
            'role' => 'penjual',
            'no_hp' => '081233334444',
            'foto_profil' => 'sari.jpg',
            'status' => 'aktif'
        ]);

        // Default Seller 2 (Toko Budi / Budi Santoso)
        $seller2 = User::create([
            'nama' => 'Budi Toko (Seller)',
            'username' => 'seller',
            'email' => 'seller@email.com',
            'password' => Hash::make('seller123'),
            'role' => 'penjual',
            'no_hp' => '082355556666',
            'foto_profil' => 'seller2.jpg',
            'status' => 'aktif'
        ]);

        // 2. Kategori default
        $kategori = [
            'Fashion Pria', 'Fashion Wanita', 'Sepatu', 'Tas',
            'Elektronik', 'Buku', 'Furniture', 'Kosmetik', 
            'Aksesoris', 'Hobi & Koleksi'
        ];
        $katModels = [];
        foreach ($kategori as $kat) {
            $katModels[] = \App\Models\Kategori::create(['nama_kategori' => $kat]);
        }

        // 3. Penitips (Linked to Sellers)
        $p1 = Penitip::create([
            'kode_penitip' => 'PNT-001',
            'nama' => 'Sari Dewi Boutique',
            'no_hp' => '081233334444',
            'email' => 'sari@email.com',
            'alamat' => 'Jl. Raya Surabaya No. 10',
            'nama_bank' => 'BCA',
            'no_rekening' => '1234567890',
            'user_id' => $seller1->id,
            'logo_toko' => 'logo_sari.jpg',
            'banner_toko' => 'banner_sari.jpg',
            'deskripsi_toko' => 'Koleksi pakaian preloved vintage berkualitas tinggi. Terkurasi dan wangi!',
            'is_verified' => true,
            'saldo' => 750000.00,
            'auto_reply_message' => 'Halo! Terima kasih telah menghubungi kami. Kami akan merespon pesan Anda segera.',
            'is_auto_reply_enabled' => true
        ]);

        $p2 = Penitip::create([
            'kode_penitip' => 'PNT-002',
            'nama' => 'Budi Sneakers & Bags',
            'no_hp' => '082355556666',
            'email' => 'seller@email.com',
            'alamat' => 'Jl. Pemuda No. 5, Surabaya',
            'nama_bank' => 'Mandiri',
            'no_rekening' => '0987654321',
            'user_id' => $seller2->id,
            'logo_toko' => 'logo_budi.jpg',
            'banner_toko' => 'banner_budi.jpg',
            'deskripsi_toko' => 'Pusat sneaker preloved original dan tas designer berkualitas. Garansi keaslian 100%!',
            'is_verified' => true,
            'saldo' => 2400000.00,
            'auto_reply_message' => 'Hai customer! Pesanan diproses setiap jam 4 sore. Silakan langsung checkout ya.',
            'is_auto_reply_enabled' => false
        ]);

        // 4. Barang / Products with comprehensive attributes
        $b1 = Barang::create([
            'kode_barang' => 'BRG-001',
            'penitip_id' => $p1->id,
            'kategori_id' => 1, // Fashion Pria
            'nama_barang' => 'Kemeja Flanel Vintage Uniqlo',
            'deskripsi' => 'Kemeja flanel vintage retro. Bahan adem, tebal, dan sangat nyaman digunakan.',
            'kondisi' => 'seperti_baru',
            'harga_jual' => 85000,
            'foto' => 'BRG-001.jpg',
            'status' => 'ditampilkan',
            'tgl_masuk' => now()->toDateString(),
            'brand' => 'Uniqlo',
            'ukuran' => 'L',
            'warna' => 'Merah Hitam',
            'stok' => 1,
            'lokasi' => 'Surabaya',
            'multiple_fotos' => 'BRG-001.jpg,BRG-001_side.jpg,BRG-001_tag.jpg',
            'is_flash_sale' => false,
            'diskon_persen' => 0,
            'material' => 'Flannel Cotton',
            'berat' => 350,
            'tags' => 'Vintage,Casual,Uniqlo',
            'lama_penggunaan' => '3 bulan',
            'frekuensi_penggunaan' => 'Jarang',
            'viewer_count' => 125,
            'favorite_count' => 14
        ]);

        $b2 = Barang::create([
            'kode_barang' => 'BRG-002',
            'penitip_id' => $p1->id,
            'kategori_id' => 1, // Fashion Pria
            'nama_barang' => 'Jaket Denim 90s Levi\'s',
            'deskripsi' => 'Jaket denim vintage original Levi Strauss Co. Denim tebal, warna fading natural keren abis.',
            'kondisi' => 'bekas_layak',
            'harga_jual' => 120000,
            'foto' => 'BRG-002.jpg',
            'status' => 'ditampilkan',
            'tgl_masuk' => now()->toDateString(),
            'brand' => 'Levi\'s',
            'ukuran' => 'XL',
            'warna' => 'Biru Denim',
            'stok' => 1,
            'lokasi' => 'Malang',
            'multiple_fotos' => 'BRG-002.jpg,BRG-002_back.jpg',
            'is_flash_sale' => true,
            'diskon_persen' => 15,
            'material' => 'Heavyweight Denim',
            'berat' => 950,
            'tags' => 'Vintage,Original,90s',
            'lama_penggunaan' => '2 tahun',
            'frekuensi_penggunaan' => 'Sering',
            'defect_description' => 'Ada noda pudar di bagian siku kiri bawah dan kerah sedikit berbulu.',
            'viewer_count' => 340,
            'favorite_count' => 52
        ]);

        $b3 = Barang::create([
            'kode_barang' => 'BRG-003',
            'penitip_id' => $p2->id,
            'kategori_id' => 4, // Tas
            'nama_barang' => 'Tas Kulit Mini Coklat Zara',
            'deskripsi' => 'Tas kulit asli mini dari Zara. Sangat manis, minus pemakaian normal di bagian strap.',
            'kondisi' => 'seperti_baru',
            'harga_jual' => 150000,
            'foto' => 'BRG-003.jpg',
            'status' => 'ditampilkan',
            'tgl_masuk' => now()->toDateString(),
            'brand' => 'Zara',
            'ukuran' => 'One Size',
            'warna' => 'Coklat',
            'stok' => 1,
            'lokasi' => 'Jakarta',
            'multiple_fotos' => 'BRG-003.jpg',
            'is_flash_sale' => false,
            'diskon_persen' => 0,
            'material' => 'Genuine Leather',
            'berat' => 450,
            'tags' => 'Original,Casual,Elegant',
            'lama_penggunaan' => '5 bulan',
            'frekuensi_penggunaan' => 'Jarang',
            'viewer_count' => 88,
            'favorite_count' => 9
        ]);

        $b4 = Barang::create([
            'kode_barang' => 'BRG-004',
            'penitip_id' => $p2->id,
            'kategori_id' => 3, // Sepatu
            'nama_barang' => 'Adidas Samba Classic White Black',
            'deskripsi' => 'Adidas Samba classic, original. Sol masih sangat tebal, box lengkap.',
            'kondisi' => 'seperti_baru',
            'harga_jual' => 450000,
            'foto' => 'BRG-004.jpg',
            'status' => 'ditampilkan',
            'tgl_masuk' => now()->toDateString(),
            'brand' => 'Adidas',
            'ukuran' => '42',
            'warna' => 'Putih',
            'stok' => 1,
            'lokasi' => 'Bandung',
            'multiple_fotos' => 'BRG-004.jpg,BRG-004_sole.jpg',
            'is_flash_sale' => true,
            'diskon_persen' => 10,
            'material' => 'Leather & Suede',
            'berat' => 700,
            'tags' => 'Classic,Original,Samba',
            'lama_penggunaan' => '2 bulan',
            'frekuensi_penggunaan' => 'Sangat Jarang',
            'bukti_keaslian' => 'invoice_adidas.jpg',
            'viewer_count' => 495,
            'favorite_count' => 88
        ]);

        // 5. Vouchers
        Voucher::create([
            'kode_voucher' => 'NEWUSER10',
            'diskon' => 10000,
            'min_beli' => 50000,
            'status' => 'aktif'
        ]);

        Voucher::create([
            'kode_voucher' => 'ONGKIRGRATIS',
            'diskon' => 15000,
            'min_beli' => 100000,
            'status' => 'aktif'
        ]);

        // 6. Alamat Pengiriman Contoh
        AlamatPengiriman::create([
            'user_id' => $buyer->id,
            'label' => 'Rumah Utama',
            'nama_penerima' => 'Budi Pembeli',
            'no_hp' => '081234567890',
            'alamat_lengkap' => 'Jl. Ketintang Baru No. 12, Kel. Ketintang, Kec. Gayungan',
            'kota' => 'Surabaya',
            'kode_pos' => '60231',
            'is_utama' => true
        ]);

        AlamatPengiriman::create([
            'user_id' => $buyer->id,
            'label' => 'Kantor',
            'nama_penerima' => 'Budi Kerja',
            'no_hp' => '081234567899',
            'alamat_lengkap' => 'Graha Pena lantai 5, Jl. Ahmad Yani No. 88',
            'kota' => 'Surabaya',
            'kode_pos' => '60234',
            'is_utama' => false
        ]);

        // 7. Notifikasi Awal
        Notifikasi::create([
            'user_id' => $buyer->id,
            'judul' => 'Selamat Datang!',
            'pesan' => 'Selamat datang di ThriftIn! Gunakan voucher NEWUSER10 untuk mendapatkan diskon Rp 10.000 pada pembelian pertamamu.',
            'tipe' => 'promo',
            'is_read' => false
        ]);
        
        // 8. Ulasan contoh
        Ulasan::create([
            'user_id' => $buyer->id,
            'barang_id' => $b3->id,
            'rating' => 5,
            'ulasan' => 'Barangnya beneran mulus banget kayak baru. Warnanya persis seperti di foto. Makasih seller!',
            'respon_rate' => 5,
            'kirim_rate' => 5,
            'sesuai_rate' => 5,
            'balasan_penjual' => 'Sama-sama kak! Senang bertransaksi dengan Anda. Semoga awet ya!'
        ]);

        // 9. Payouts (Pencairan)
        Pencairan::create([
            'kode_pencairan' => 'WD-001',
            'penitip_id' => $p2->id,
            'jumlah' => 1200000.00,
            'tgl_pencairan' => now()->subDays(5)->toDateString(),
            'metode' => 'transfer',
            'status' => 'selesai',
            'keterangan' => 'Penarikan dana saldo Toko Budi ke Rekening Mandiri 0987654321',
            'admin_id' => 1
        ]);

        Pencairan::create([
            'kode_pencairan' => 'WD-002',
            'penitip_id' => $p2->id,
            'jumlah' => 500000.00,
            'tgl_pencairan' => now()->toDateString(),
            'metode' => 'transfer',
            'status' => 'pending',
            'keterangan' => 'Pencairan saldo ke e-wallet GoPay 082355556666',
            'admin_id' => 1
        ]);

        // 10. Chat & Nego Seed
        Chat::create([
            'sender_id' => $buyer->id,
            'receiver_id' => $seller2->id,
            'barang_id' => $b4->id,
            'pesan' => 'Halo kak, apakah sepatu Adidas Samba ini masih ready stock? Boxnya aman?',
            'is_read' => false
        ]);

        NegoHarga::create([
            'user_id' => $buyer->id,
            'barang_id' => $b4->id,
            'harga_tawaran' => 400000.00,
            'status' => 'pending'
        ]);

        // 11. Admin Settings & Content Seed
        \App\Models\SystemSetting::insert([
            ['key' => 'site_name', 'value' => 'ThriftIn Preloved', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'support@thriftin.com', 'group' => 'general'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'group' => 'security'],
            ['key' => 'midtrans_sandbox', 'value' => 'true', 'group' => 'payment'],
            ['key' => 'platform_fee_percent', 'value' => '5', 'group' => 'finance'],
        ]);

        \App\Models\FraudReport::create([
            'barang_id' => $b1->id,
            'dilaporkan_oleh' => $buyer->id,
            'tipe_laporan' => 'barang_palsu',
            'deskripsi_laporan' => 'Kemeja Uniqlo ini sepertinya KW, tagnya berbeda dengan yang asli.',
            'ai_confidence_score' => 65.50,
            'status' => 'pending'
        ]);

        $ticket = \App\Models\SupportTicket::create([
            'kode_tiket' => 'TCK-1001',
            'user_id' => $buyer->id,
            'subjek' => 'Barang belum sampai tapi status selesai',
            'deskripsi' => 'Halo min, pesanan saya BRG-004 belum saya terima tapi statusnya berubah jadi selesai. Tolong dicek.',
            'prioritas' => 'tinggi',
            'status' => 'open'
        ]);

        \App\Models\TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'sender_id' => $buyer->id,
            'pesan' => 'Mohon segera dibantu ya, dananya lumayan besar.',
        ]);
    }
}
