<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Complaint;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class BuyerSupportController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'q' => 'Bagaimana cara membeli barang di ThriftIn?',
                'a' => 'Cari barang preloved impian Anda, tambahkan ke keranjang, dan lakukan checkout. Lakukan pembayaran ke rekening bersama (escrow) dan unggah bukti transfer. Barang Anda segera diproses oleh penjual.'
            ],
            [
                'q' => 'Apakah barang di ThriftIn bisa ditawar?',
                'a' => 'Ya! Anda bisa menggunakan sistem negosiasi harga di dalam chat dengan penjual. Jika penjual menerima tawaran Anda, Anda bisa membeli barang tersebut dengan harga kesepakatan.'
            ],
            [
                'q' => 'Apa itu sistem Escrow / Rekening Bersama?',
                'a' => 'Sistem Escrow adalah sistem penampungan dana sementara demi keamanan transaksi. Dana yang Anda bayar akan disimpan dengan aman oleh ThriftIn, dan baru diteruskan ke penjual setelah Anda mengonfirmasi penerimaan barang secara utuh.'
            ],
            [
                'q' => 'Bagaimana jika barang yang diterima rusak atau tidak sesuai?',
                'a' => 'Anda bisa mengajukan komplain atau retur barang melalui formulir komplain di halaman Bantuan. Pastikan menyertakan bukti foto agar admin kami segera memproses dana pengembalian (refund).'
            ]
        ];

        $orders = [];
        if (Auth::check()) {
            $orders = Transaksi::where('user_id', Auth::id())->with('barang')->latest()->get();
        }

        return view('buyer.support', compact('faqs', 'orders'));
    }

    public function submitComplaint(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'alasan' => 'required|string|min:10',
            'foto' => 'nullable|image|max:2048'
        ]);

        $transaksi = Transaksi::where('user_id', Auth::id())->findOrFail($request->transaksi_id);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fileName = 'complaint_' . $transaksi->id . '_' . time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads/complaints'), $fileName);
            $fotoPath = 'uploads/complaints/' . $fileName;
        }

        $complaint = Complaint::create([
            'transaksi_id' => $transaksi->id,
            'user_id' => Auth::id(),
            'alasan' => $request->alasan,
            'foto' => $fotoPath,
            'status' => 'pending'
        ]);

        // Update transaction status
        $transaksi->update(['status_pesanan' => 'refund']);

        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Komplain Diajukan',
            'pesan' => 'Formulir komplain Anda untuk transaksi ' . $transaksi->kode_transaksi . ' telah diterima. Admin kami akan melakukan investigasi dalam 1x24 jam.',
            'tipe' => 'transaksi',
            'is_read' => false
        ]);

        return back()->with('success', 'Formulir komplain berhasil diajukan. Dana transaksi ditahan oleh sistem escrow demi keamanan Anda.');
    }

    // Live chat simulation endpoint
    public function simulateSupportChat(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $responses = [
            'halo' => 'Halo! Terima kasih telah menghubungi Customer Service ThriftIn. Ada yang bisa kami bantu hari ini?',
            'refund' => 'Untuk pengajuan refund atau retur, silakan isi formulir komplain di sebelah kiri dengan menyertakan bukti foto keadaan barang ya.',
            'bayar' => 'Pembayaran via Bank Transfer diverifikasi instan oleh sistem setelah bukti transfer diunggah. Jika ada kendala, hubungi kami.',
            'resi' => 'Nomor resi pengiriman otomatis akan terupdate di dashboard pembeli Anda setelah penjual mengirimkan paket ke kurir.',
            'default' => 'Terima kasih atas laporan Anda. Pertanyaan Anda telah dicatat, tim CS kami akan segera menghubungi Anda kembali.'
        ];

        $lowerMsg = strtolower($request->message);
        $reply = $responses['default'];

        if (str_contains($lowerMsg, 'halo') || str_contains($lowerMsg, 'hi') || str_contains($lowerMsg, 'siang')) {
            $reply = $responses['halo'];
        } elseif (str_contains($lowerMsg, 'refund') || str_contains($lowerMsg, 'retur') || str_contains($lowerMsg, 'kembali')) {
            $reply = $responses['refund'];
        } elseif (str_contains($lowerMsg, 'bayar') || str_contains($lowerMsg, 'transfer') || str_contains($lowerMsg, 'konfirmasi')) {
            $reply = $responses['bayar'];
        } elseif (str_contains($lowerMsg, 'resi') || str_contains($lowerMsg, 'kirim') || str_contains($lowerMsg, 'ongkir')) {
            $reply = $responses['resi'];
        }

        return response()->json(['reply' => '[Admin CS] ' . $reply]);
    }
}
