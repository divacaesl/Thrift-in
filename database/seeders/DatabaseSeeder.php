<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users default
        // Users default
        \App\Models\User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@thriftin.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin',
        ]);

        \App\Models\User::create([
            'nama' => 'Kasir 1',
            'username' => 'kasir1',
            'email' => 'kasir1@thriftin.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'kasir',
        ]);

        // Kategori default
        $kategori = [
            'Atasan', 'Bawahan', 'Dress & Rok', 'Outer & Jaket',
            'Sepatu', 'Tas', 'Aksesoris', 'Lainnya'
        ];
        foreach ($kategori as $kat) {
            \App\Models\Kategori::create(['nama_kategori' => $kat]);
        }

        // Penitip contoh
        \App\Models\Penitip::insert([
            [
                'kode_penitip' => 'PNT-001',
                'nama' => 'Sari Dewi',
                'no_hp' => '081234567890',
                'email' => 'sari@email.com',
                'alamat' => 'Jl. Raya Surabaya No. 10',
                'nama_bank' => 'BCA',
                'no_rekening' => '1234567890'
            ],
            [
                'kode_penitip' => 'PNT-002',
                'nama' => 'Budi Santoso',
                'no_hp' => '082345678901',
                'email' => 'budi@email.com',
                'alamat' => 'Jl. Pemuda No. 5',
                'nama_bank' => 'Mandiri',
                'no_rekening' => '0987654321'
            ]
        ]);

        // Barang contoh
        \App\Models\Barang::create([
            'kode_barang' => 'BRG-001',
            'penitip_id' => 1,
            'kategori_id' => 1,
            'nama_barang' => 'Kemeja Flanel Vintage',
            'kondisi' => 'seperti_baru',
            'harga_jual' => 85000,
            'foto' => 'BRG-001.jpg',
            'status' => 'ditampilkan',
            'tgl_masuk' => now()->toDateString()
        ]);

        \App\Models\Barang::create([
            'kode_barang' => 'BRG-002',
            'penitip_id' => 1,
            'kategori_id' => 4,
            'nama_barang' => 'Jaket Denim 90s',
            'kondisi' => 'bekas_layak',
            'harga_jual' => 120000,
            'foto' => 'BRG-002.jpg',
            'status' => 'ditampilkan',
            'tgl_masuk' => now()->toDateString()
        ]);

        \App\Models\Barang::create([
            'kode_barang' => 'BRG-003',
            'penitip_id' => 2,
            'kategori_id' => 6,
            'nama_barang' => 'Tas Kulit Mini Coklat',
            'kondisi' => 'seperti_baru',
            'harga_jual' => 150000,
            'foto' => 'BRG-003.jpg',
            'status' => 'terjual',
            'tgl_masuk' => now()->toDateString()
        ]);
    }
}
