<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penitip;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Follow;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;

class SellerDashboardController extends Controller
{
    private function getSellerPenitip()
    {
        $penitip = Auth::user()->penitip;
        if (!$penitip) {
            // Self-repair if penitip profile is missing
            $nextId = Penitip::max('id') + 1;
            $penitip = Penitip::create([
                'kode_penitip' => 'PNT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT),
                'nama' => Auth::user()->nama . ' Shop',
                'no_hp' => Auth::user()->no_hp ?? '0812',
                'email' => Auth::user()->email,
                'user_id' => Auth::id(),
                'logo_toko' => 'default_logo.png',
                'banner_toko' => 'default_banner.png',
                'is_verified' => true
            ]);
        }
        return $penitip;
    }

    public function index()
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');

        // Main Metrics
        $totalProduk = $penitip->barangs()->count();
        $ordersQuery = Transaksi::whereIn('barang_id', $barangIds);
        
        $totalPenjualanCount = (clone $ordersQuery)->where('status_pesanan', 'sampai')->count();
        $jumlahPesananCount = (clone $ordersQuery)->whereIn('status_pesanan', ['diproses', 'dikemas', 'dikirim', 'sampai'])->count();
        $totalPendapatan = (clone $ordersQuery)->where('status_pesanan', 'sampai')->sum('hasil_penitip');
        $produkTerjual = $penitip->barangs()->whereIn('status', ['terjual', 'dicairkan'])->count();

        // Followers & Wishlists
        $followersCount = Follow::where('penitip_id', $penitip->id)->count();
        $wishlistsCount = Wishlist::whereIn('barang_id', $barangIds)->count();

        // Recent Orders
        $recentOrders = Transaksi::whereIn('barang_id', $barangIds)
            ->latest()
            ->take(5)
            ->get();

        // Daily Sales Chart Data (Last 7 Days)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $salesVal = Transaksi::whereIn('barang_id', $barangIds)
                ->where('status_pesanan', 'sampai')
                ->whereDate('tgl_transaksi', $date)
                ->sum('hasil_penitip');
            
            $chartData['labels'][] = now()->subDays($i)->translatedFormat('d M');
            $chartData['values'][] = (float)$salesVal;
        }

        return view('seller.dashboard', compact(
            'penitip', 'totalProduk', 'totalPenjualanCount', 
            'jumlahPesananCount', 'totalPendapatan', 'produkTerjual',
            'followersCount', 'wishlistsCount', 'recentOrders', 'chartData'
        ));
    }

    public function profile()
    {
        $penitip = $this->getSellerPenitip();
        return view('seller.profile', compact('penitip'));
    }

    public function updateProfile(Request $request)
    {
        $penitip = $this->getSellerPenitip();

        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi_toko' => 'nullable|string',
            'alamat' => 'required|string',
            'nama_bank' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:30',
            'logo_file' => 'nullable|image|max:2048',
            'banner_file' => 'nullable|image|max:2048',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'deskripsi_toko' => $request->deskripsi_toko,
            'alamat' => $request->alamat,
            'nama_bank' => $request->nama_bank,
            'no_rekening' => $request->no_rekening,
        ];

        // Handle logo upload
        if ($request->hasFile('logo_file')) {
            $logoName = 'logo_' . $penitip->id . '_' . time() . '.' . $request->file('logo_file')->getClientOriginalExtension();
            $request->file('logo_file')->move(public_path('uploads/shops'), $logoName);
            $updateData['logo_toko'] = $logoName;
        }

        // Handle banner upload
        if ($request->hasFile('banner_file')) {
            $bannerName = 'banner_' . $penitip->id . '_' . time() . '.' . $request->file('banner_file')->getClientOriginalExtension();
            $request->file('banner_file')->move(public_path('uploads/shops'), $bannerName);
            $updateData['banner_toko'] = $bannerName;
        }

        $penitip->update($updateData);

        return back()->with('success', 'Profil toko Anda berhasil diperbarui!');
    }
}
