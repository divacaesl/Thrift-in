<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Notifikasi;

class SellerOrderController extends Controller
{
    private function getSellerPenitip()
    {
        return Auth::user()->penitip;
    }

    public function index(Request $request)
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');

        $status = $request->status;
        $query = Transaksi::whereIn('barang_id', $barangIds)->latest();

        if ($status) {
            $query->where('status_pesanan', $status);
        }

        $orders = $query->get();

        return view('seller.order.index', compact('orders', 'status'));
    }

    public function confirm($id)
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');
        $order = Transaksi::whereIn('barang_id', $barangIds)->findOrFail($id);

        $order->update(['status_pesanan' => 'diproses']);

        // Send notification to buyer
        if ($order->user_id) {
            Notifikasi::create([
                'user_id' => $order->user_id,
                'judul' => 'Pesanan Diproses',
                'pesan' => 'Pesananmu ' . $order->kode_transaksi . ' sedang diproses oleh toko ' . $penitip->nama . '.',
                'tipe' => 'transaksi',
                'is_read' => false
            ]);
        }

        return back()->with('success', 'Pesanan berhasil dikonfirmasi dan sedang diproses.');
    }

    public function ship(Request $request, $id)
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');
        $order = Transaksi::whereIn('barang_id', $barangIds)->findOrFail($id);

        $request->validate([
            'ekspedisi' => 'required|in:JNE,J&T,SiCepat,AnterAja',
            'no_resi' => 'required|string|max:50'
        ]);

        $order->update([
            'status_pesanan' => 'dikirim',
            'ekspedisi' => $request->ekspedisi,
            'no_resi' => $request->no_resi
        ]);

        // Notify buyer
        if ($order->user_id) {
            Notifikasi::create([
                'user_id' => $order->user_id,
                'judul' => 'Pesanan Dikirim!',
                'pesan' => 'Pesananmu ' . $order->kode_transaksi . ' telah dikirim menggunakan ' . $request->ekspedisi . ' dengan No. Resi: ' . $request->no_resi . '.',
                'tipe' => 'transaksi',
                'is_read' => false
            ]);
        }

        return back()->with('success', 'Status pesanan diubah ke Dikirim. Resi berhasil dimasukkan.');
    }

    public function cancel($id)
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');
        $order = Transaksi::whereIn('barang_id', $barangIds)->findOrFail($id);

        $order->update(['status_pesanan' => 'refund']);

        // Restore product stock and status
        $barang = $order->barang;
        if ($barang) {
            $barang->update([
                'stok' => $barang->stok + 1,
                'status' => 'ditampilkan'
            ]);
        }

        // Notify buyer
        if ($order->user_id) {
            Notifikasi::create([
                'user_id' => $order->user_id,
                'judul' => 'Pesanan Dibatalkan',
                'pesan' => 'Pesananmu ' . $order->kode_transaksi . ' dibatalkan oleh penjual. Silakan periksa saldo/rekening Anda untuk pengembalian.',
                'tipe' => 'transaksi',
                'is_read' => false
            ]);
        }

        return back()->with('success', 'Pesanan dibatalkan. Stok barang telah dikembalikan.');
    }

    public function printLabel($id)
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');
        $order = Transaksi::whereIn('barang_id', $barangIds)->findOrFail($id);

        return view('seller.order.label', compact('order', 'penitip'));
    }
}
