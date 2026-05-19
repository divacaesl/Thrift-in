<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Wishlist;
use App\Models\Voucher;
use App\Models\Notifikasi;
use App\Models\AlamatPengiriman;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BuyerAccountController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $userId = Auth::id();

        $purchases = Transaksi::where('user_id', $userId)
            ->with(['barang.penitip', 'alamatPengiriman'])
            ->latest()
            ->get();

        $wishlist = Wishlist::where('user_id', $userId)
            ->with('barang')
            ->get();

        $vouchers = Voucher::where('status', 'aktif')->get();
        $notifications = Notifikasi::where('user_id', $userId)->latest()->get();

        return view('buyer.dashboard', compact('purchases', 'wishlist', 'vouchers', 'notifications'));
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $user = Auth::user();
        $addresses = AlamatPengiriman::where('user_id', $user->id)->get();

        return view('buyer.profile', compact('user', 'addresses'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'foto_profil' => 'nullable|image|max:1024',
            'metode_bayar_favorit' => 'nullable|string'
        ]);

        $data = [
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'metode_bayar_favorit' => $request->metode_bayar_favorit
        ];

        if ($request->hasFile('foto_profil')) {
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $request->foto_profil->extension();
            $request->foto_profil->move(public_path('uploads/profiles'), $fileName);
            $data['foto_profil'] = 'uploads/profiles/' . $fileName;
        }

        $user->update($data);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function addAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'kota' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'is_utama' => 'nullable|boolean'
        ]);

        $isUtama = $request->has('is_utama') ? true : false;
        $userId = Auth::id();

        if ($isUtama) {
            // Set other addresses to false
            AlamatPengiriman::where('user_id', $userId)->update(['is_utama' => false]);
        }

        AlamatPengiriman::create([
            'user_id' => $userId,
            'label' => $request->label,
            'nama_penerima' => $request->nama_penerima,
            'no_hp' => $request->no_hp,
            'alamat_lengkap' => $request->alamat_lengkap,
            'kota' => $request->kota,
            'kode_pos' => $request->kode_pos,
            'is_utama' => $isUtama
        ]);

        return back()->with('success', 'Alamat pengiriman berhasil ditambahkan.');
    }

    public function deleteAddress($id)
    {
        $address = AlamatPengiriman::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return back()->with('success', 'Alamat pengiriman berhasil dihapus.');
    }

    public function setUtamaAddress($id)
    {
        $userId = Auth::id();
        AlamatPengiriman::where('user_id', $userId)->update(['is_utama' => false]);

        $address = AlamatPengiriman::where('user_id', $userId)->findOrFail($id);
        $address->update(['is_utama' => true]);

        return back()->with('success', 'Alamat utama berhasil diperbarui.');
    }

    public function readNotification($id)
    {
        $notif = Notifikasi::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function readAllNotifications()
    {
        Notifikasi::where('user_id', Auth::id())->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    public function confirmReceipt($id)
    {
        $transaksi = Transaksi::where('user_id', Auth::id())->findOrFail($id);
        
        $transaksi->update([
            'status_pesanan' => 'sampai'
        ]);

        // Simulates Escrow System: release funds to seller
        Notifikasi::create([
            'user_id' => Auth::id(),
            'judul' => 'Transaksi Selesai!',
            'pesan' => 'Terima kasih telah berbelanja di ThriftIn! Dana sebesar Rp ' . number_format($transaksi->harga_jual, 0, ',', '.') . ' telah diteruskan ke seller ' . $transaksi->barang->penitip->nama . ' secara aman.',
            'tipe' => 'transaksi',
            'is_read' => false
        ]);

        return back()->with('success', 'Penerimaan dikonfirmasi! Sistem escrow telah meneruskan dana penjualan ke seller secara aman.');
    }
}
