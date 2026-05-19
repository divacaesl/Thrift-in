<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penitip;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Pencairan;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // General Users Stats
        $totalUsers = User::count();
        $totalBuyers = User::where('role', 'pembeli')->count();
        $totalSellers = User::where('role', 'penjual')->count();
        $totalAdmins = User::whereIn('role', ['super_admin', 'admin', 'admin_produk', 'admin_keuangan', 'cs'])->count();

        // Product Stats
        $activeProducts = Barang::where('status', 'ditampilkan')->where('stok', '>', 0)->count();
        $soldProducts = Transaksi::whereIn('status_pesanan', ['dikirim', 'sampai'])->count(); // Assuming 1 qty per trans
        $flaggedProducts = Barang::where('moderation_status', 'flagged')->count();

        // Financial Stats
        $platformFeePercent = SystemSetting::where('key', 'platform_fee_percent')->value('value') ?? 5;
        
        // Income is roughly sum of (harga_jual * fee) for completed trans
        $completedTransactions = Transaksi::where('status_pesanan', 'sampai')->get();
        $platformRevenue = 0;
        foreach ($completedTransactions as $tx) {
            $feeAmount = ($tx->harga_jual * $platformFeePercent) / 100;
            $platformRevenue += $feeAmount;
        }

        $totalTransactions = Transaksi::count();
        $pendingPayouts = Pencairan::where('status', 'pending')->count();

        // Chart Data (Last 7 Days Sales Volume)
        $salesDates = [];
        $salesTotals = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $salesDates[] = Carbon::now()->subDays($i)->format('d M');
            $dailySales = Transaksi::whereDate('created_at', $date)
                ->whereIn('status_pesanan', ['diproses', 'dikemas', 'dikirim', 'sampai'])
                ->sum('harga_jual');
            $salesTotals[] = $dailySales;
        }

        $chartData = [
            'labels' => $salesDates,
            'values' => $salesTotals
        ];

        // Top Selling Categories
        $topCategories = Barang::join('kategoris', 'barangs.kategori_id', '=', 'kategoris.id')
            ->join('transaksis', 'barangs.id', '=', 'transaksis.barang_id')
            ->select('kategoris.nama_kategori', DB::raw('count(transaksis.id) as total_sold'))
            ->groupBy('kategoris.id', 'kategoris.nama_kategori')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalBuyers', 'totalSellers', 'totalAdmins',
            'activeProducts', 'soldProducts', 'flaggedProducts',
            'platformRevenue', 'totalTransactions', 'pendingPayouts',
            'chartData', 'topCategories'
        ));
    }
}
