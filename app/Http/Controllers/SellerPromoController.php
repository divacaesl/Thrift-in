<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;

class SellerPromoController extends Controller
{
    private function getSellerPenitip()
    {
        return Auth::user()->penitip;
    }

    public function index()
    {
        $penitip = $this->getSellerPenitip();
        $products = $penitip->barangs()->latest()->get();
        return view('seller.promo.index', compact('products'));
    }

    public function updatePromo(Request $request, $id)
    {
        $penitip = $this->getSellerPenitip();
        $product = Barang::where('penitip_id', $penitip->id)->findOrFail($id);

        $request->validate([
            'diskon_persen' => 'required|integer|min:0|max:100',
        ]);

        $product->update([
            'diskon_persen' => $request->diskon_persen,
            'is_flash_sale' => $request->has('is_flash_sale')
        ]);

        return back()->with('success', 'Promo produk ' . $product->nama_barang . ' berhasil diperbarui!');
    }

    public function boostProduct($id)
    {
        $penitip = $this->getSellerPenitip();
        $product = Barang::where('penitip_id', $penitip->id)->findOrFail($id);

        // Boost simulated by multiplying viewer count and adding a Rare tag
        $currentTags = $product->tags ? explode(',', $product->tags) : [];
        if (!in_array('Featured', $currentTags)) {
            $currentTags[] = 'Featured';
        }

        $product->update([
            'viewer_count' => $product->viewer_count + rand(100, 300),
            'tags' => implode(',', $currentTags)
        ]);

        return back()->with('success', 'Produk ' . $product->nama_barang . ' berhasil di-Boost! Diprioritaskan tampil di halaman utama.');
    }
}
