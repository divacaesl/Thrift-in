<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class BuyerReviewController extends Controller
{
    public function create($id)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $transaksi = Transaksi::where('user_id', Auth::id())->with('barang')->findOrFail($id);

        // Check if already reviewed
        $existing = Ulasan::where('user_id', Auth::id())
            ->where('barang_id', $transaksi->barang_id)
            ->first();

        if ($existing) {
            return redirect()->route('buyer.dashboard')->with('info', 'Anda sudah memberikan ulasan untuk barang ini.');
        }

        return view('buyer.review', compact('transaksi'));
    }

    public function store(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
            'respon_rate' => 'required|integer|min:1|max:5',
            'kirim_rate' => 'required|integer|min:1|max:5',
            'sesuai_rate' => 'required|integer|min:1|max:5'
        ]);

        $transaksi = Transaksi::where('user_id', Auth::id())->findOrFail($id);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fileName = 'review_' . $transaksi->id . '_' . time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('uploads/reviews'), $fileName);
            $fotoPath = 'uploads/reviews/' . $fileName;
        }

        Ulasan::create([
            'user_id' => Auth::id(),
            'barang_id' => $transaksi->barang_id,
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
            'foto' => $fotoPath,
            'respon_rate' => $request->respon_rate,
            'kirim_rate' => $request->kirim_rate,
            'sesuai_rate' => $request->sesuai_rate
        ]);

        return redirect()->route('buyer.dashboard')->with('success', 'Terima kasih atas ulasan Anda! Penilaian Anda sangat membantu seller.');
    }
}
