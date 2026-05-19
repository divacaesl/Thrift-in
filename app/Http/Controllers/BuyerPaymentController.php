<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\AlamatPengiriman;
use App\Models\Voucher;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class BuyerPaymentController extends Controller
{
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $cartItems = Cart::where('user_id', Auth::id())
            ->where('is_saved_for_later', false)
            ->with('barang')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $addresses = AlamatPengiriman::where('user_id', Auth::id())->get();
        $vouchers = Voucher::where('status', 'aktif')->get();

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->barang->harga_jual * $item->quantity;
        }

        return view('buyer.checkout', compact('cartItems', 'addresses', 'vouchers', 'subtotal'));
    }

    public function processCheckout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login')->with('error', 'Silakan login.');
        }

        $request->validate([
            'alamat_id' => 'required|exists:alamat_pengirimans,id',
            'ekspedisi' => 'required|string',
            'metode_bayar' => 'required|string',
            'catatan' => 'nullable|string',
            'voucher_code' => 'nullable|string'
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->where('is_saved_for_later', false)
            ->with('barang')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('buyer.cart')->with('error', 'Keranjang belanja kosong.');
        }

        $address = AlamatPengiriman::findOrFail($request->alamat_id);
        
        // Mock shipping cost based on courier and destination
        $ongkir = 12000;
        if ($request->ekspedisi === 'JNE') $ongkir = 15000;
        elseif ($request->ekspedisi === 'J&T') $ongkir = 12000;
        elseif ($request->ekspedisi === 'SiCepat') $ongkir = 11000;
        elseif ($request->ekspedisi === 'AnterAja') $ongkir = 10000;

        // Apply voucher discount if valid
        $diskon = 0;
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->barang->harga_jual * $item->quantity;
        }

        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('kode_voucher', $request->voucher_code)
                ->where('status', 'aktif')
                ->where('min_beli', '<=', $subtotal)
                ->first();
            if ($voucher) {
                $diskon = $voucher->diskon;
            }
        }

        // We process each item in the cart as a separate transaction/order
        // (Since preloved items are unique, they are created as individual sales)
        $lastTx = null;
        foreach ($cartItems as $item) {
            $barang = $item->barang;

            // Calculations
            $komisiPersen = 20.00;
            $komisiNominal = ($barang->harga_jual * $komisiPersen) / 100;
            $hasilPenitip = $barang->harga_jual - $komisiNominal;

            // Generate Kode Transaksi
            $last = Transaksi::latest()->first();
            $nextId = $last ? ($last->id + 1) : 1;
            $kode = 'TRX-' . date('Ymd') . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            // Create Transaction
            $lastTx = Transaksi::create([
                'kode_transaksi' => $kode,
                'barang_id' => $barang->id,
                'nama_pembeli' => $address->nama_penerima,
                'no_hp_pembeli' => $address->no_hp,
                'harga_jual' => $barang->harga_jual,
                'komisi_persen' => $komisiPersen,
                'komisi_nominal' => $komisiNominal,
                'hasil_penitip' => $hasilPenitip,
                'metode_bayar' => $request->metode_bayar,
                'tgl_transaksi' => now(),
                'kasir_id' => 1, // Default System/Admin cashier
                'user_id' => Auth::id(),
                'status_pesanan' => $request->metode_bayar === 'cod' ? 'diproses' : 'menunggu_pembayaran',
                'catatan' => $request->catatan,
                'ekspedisi' => $request->ekspedisi,
                'no_resi' => null,
                'ongkir' => $ongkir,
                'alamat_pengiriman_id' => $address->id
            ]);

            // Update item status to terjual
            $barang->update([
                'status' => 'terjual',
                'tgl_terjual' => now()
            ]);
        }

        // Clear active cart items
        Cart::where('user_id', Auth::id())->where('is_saved_for_later', false)->delete();

        // Create transaction notifications
        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Pesanan Berhasil Dibuat!',
            'pesan' => 'Pesanan Anda telah berhasil dibuat. Silakan selesaikan pembayaran untuk memproses barang.',
            'tipe' => 'transaksi',
            'is_read' => false
        ]);

        if ($request->metode_bayar === 'cod') {
            return redirect()->route('buyer.dashboard')->with('success', 'Pesanan COD berhasil dibuat! Penjual sedang memproses barang Anda.');
        }

        return redirect()->route('buyer.payment.confirm', $lastTx->id)->with('success', 'Silakan unggah bukti transfer pembayaran.');
    }

    public function showPaymentConfirm($id)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $transaksi = Transaksi::where('user_id', Auth::id())->with('barang')->findOrFail($id);

        return view('buyer.payment_confirm', compact('transaksi'));
    }

    public function uploadPaymentConfirm(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $transaksi = Transaksi::where('user_id', Auth::id())->findOrFail($id);

        // Save file
        $fileName = 'proof_' . $transaksi->kode_transaksi . '_' . time() . '.' . $request->bukti_transfer->extension();
        $request->bukti_transfer->move(public_path('uploads/proofs'), $fileName);

        // Update transaction status (Simulates Escrow / Rekening Bersama Verification)
        $transaksi->update([
            'bukti_transfer' => 'uploads/proofs/' . $fileName,
            'status_pesanan' => 'diproses' // Automatic status update (simulated instant approval)
        ]);

        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Pembayaran Terverifikasi!',
            'pesan' => 'Pembayaran Anda untuk transaksi ' . $transaksi->kode_transaksi . ' telah diverifikasi oleh sistem escrow. Dana aman di rekening bersama.',
            'tipe' => 'transaksi',
            'is_read' => false
        ]);

        return redirect()->route('buyer.dashboard')->with('success', 'Bukti transfer berhasil diunggah. Pembayaran Anda telah terverifikasi secara otomatis oleh sistem escrow!');
    }

    // Direct helper to simulate courier tracking updates for demonstration
    public function simulateDeliveryUpdate($id, $nextStatus)
    {
        $transaksi = Transaksi::findOrFail($id);
        
        $resi = $transaksi->no_resi;
        if (!$resi && $nextStatus === 'dikirim') {
            // Generate receipt number JNE-xxxxx
            $resi = strtoupper($transaksi->ekspedisi) . '-' . rand(100000000, 999999999);
        }

        $transaksi->update([
            'status_pesanan' => $nextStatus,
            'no_resi' => $resi
        ]);

        $msgMap = [
            'diproses' => 'Pesanan Anda sedang diproses oleh penjual.',
            'dikemas' => 'Pesanan Anda sedang dikemas oleh penjual.',
            'dikirim' => 'Pesanan Anda telah dikirim dengan nomor resi ' . $resi . '.',
            'sampai' => 'Pesanan Anda telah sampai di alamat tujuan. Silakan konfirmasi penerimaan barang.'
        ];

        Notifikasi::create([
            'user_id' => $transaksi->user_id,
            'judul' => 'Update Status Pengiriman: ' . ucfirst($nextStatus),
            'pesan' => $msgMap[$nextStatus] ?? 'Status pesanan Anda telah diperbarui.',
            'tipe' => 'transaksi',
            'is_read' => false
        ]);

        return back()->with('success', 'Simulasi status pengiriman berhasil diperbarui ke: ' . ucfirst($nextStatus));
    }
}
