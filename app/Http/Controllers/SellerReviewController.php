<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ulasan;
use App\Models\Barang;

class SellerReviewController extends Controller
{
    private function getSellerPenitip()
    {
        return Auth::user()->penitip;
    }

    public function index()
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');

        $reviews = Ulasan::whereIn('barang_id', $barangIds)
            ->with(['user', 'barang'])
            ->latest()
            ->get();

        // Calculate average store ratings
        $averageRating = $reviews->avg('rating') ?? 0;
        $averageRespon = $reviews->avg('respon_rate') ?? 0;
        $averageKirim = $reviews->avg('kirim_rate') ?? 0;
        $averageSesuai = $reviews->avg('sesuai_rate') ?? 0;

        return view('seller.review.index', compact(
            'reviews', 'averageRating', 'averageRespon', 'averageKirim', 'averageSesuai'
        ));
    }

    public function reply(Request $request, $id)
    {
        $penitip = $this->getSellerPenitip();
        $barangIds = $penitip->barangs()->pluck('id');
        
        $review = Ulasan::whereIn('barang_id', $barangIds)->findOrFail($id);

        $request->validate([
            'balasan_penjual' => 'required|string',
        ]);

        $review->update([
            'balasan_penjual' => $request->balasan_penjual
        ]);

        return back()->with('success', 'Balasan ulasan berhasil dikirim!');
    }
}
