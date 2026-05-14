<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Penitip;
use App\Models\Pencairan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $barangTerjual = Barang::where('status', 'terjual')->count();
        $totalPenitip = Penitip::count();
        
        $totalPenjualan = Transaksi::sum('harga_jual');
        $totalKomisi = Transaksi::sum('komisi_nominal');
        $totalPencairan = Pencairan::where('status', 'selesai')->sum('jumlah');

        $barangBaru = Barang::with(['penitip', 'kategori'])->latest()->take(5)->get();
        $transaksiTerbaru = Transaksi::with(['barang', 'kasir'])->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalBarang', 'barangTerjual', 'totalPenitip', 
            'totalPenjualan', 'totalKomisi', 'totalPencairan',
            'barangBaru', 'transaksiTerbaru'
        ));
    }
}
