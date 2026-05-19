<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penitip;
use App\Models\Transaksi;
use App\Models\Pencairan;

class SellerFinanceController extends Controller
{
    private function getSellerPenitip()
    {
        return Auth::user()->penitip;
    }

    public function index()
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');

        // Cash calculations
        $totalKeuntungan = Transaksi::whereIn('barang_id', $barangIds)
            ->where('status_pesanan', 'sampai')
            ->sum('hasil_penitip');

        $danaTertunda = Transaksi::whereIn('barang_id', $barangIds)
            ->whereIn('status_pesanan', ['menunggu_pembayaran', 'diproses', 'dikemas', 'dikirim'])
            ->sum('hasil_penitip');

        $totalRefund = Transaksi::whereIn('barang_id', $barangIds)
            ->where('status_pesanan', 'refund')
            ->sum('hasil_penitip');

        // Financial logs
        $transactions = Transaksi::whereIn('barang_id', $barangIds)
            ->latest()
            ->get();

        $withdrawals = Pencairan::where('penitip_id', $penitip->id)
            ->latest()
            ->get();

        return view('seller.finance.index', compact(
            'penitip', 'totalKeuntungan', 'danaTertunda', 
            'totalRefund', 'transactions', 'withdrawals'
        ));
    }

    public function withdraw(Request $request)
    {
        $penitip = $this->getSellerPenitip();

        $request->validate([
            'jumlah' => 'required|numeric|min:10000|max:' . $penitip->saldo,
            'metode' => 'required|in:transfer,tunai',
            'nama_bank' => 'required|string|max:50',
            'no_rekening' => 'required|string|max:30',
            'keterangan' => 'nullable|string'
        ]);

        // Generate withdrawal code
        $nextId = Pencairan::max('id') + 1;
        $kodePencairan = 'WD-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Deduct balance
        $penitip->update([
            'saldo' => $penitip->saldo - $request->jumlah
        ]);

        // Create withdrawal record
        Pencairan::create([
            'kode_pencairan' => $kodePencairan,
            'penitip_id' => $penitip->id,
            'jumlah' => $request->jumlah,
            'tgl_pencairan' => now()->toDateString(),
            'metode' => $request->metode,
            'status' => 'pending',
            'keterangan' => 'Penarikan ke ' . $request->nama_bank . ' - Rek: ' . $request->no_rekening . '. ' . ($request->keterangan ?? ''),
            'admin_id' => 1 // default Admin
        ]);

        return redirect()->route('seller.finance.index')->with('success', 'Permintaan pencairan dana berhasil dikirim! Silakan tunggu verifikasi admin.');
    }
}
