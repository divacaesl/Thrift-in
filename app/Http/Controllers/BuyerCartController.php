<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;

class BuyerCartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $cartItems = Cart::where('user_id', Auth::id())
            ->where('is_saved_for_later', false)
            ->with('barang')
            ->get();

        $savedItems = Cart::where('user_id', Auth::id())
            ->where('is_saved_for_later', true)
            ->with('barang')
            ->get();

        return view('buyer.cart', compact('cartItems', 'savedItems'));
    }

    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate(['barang_id' => 'required|exists:barangs,id']);
        $barang = Barang::findOrFail($request->barang_id);

        if ($barang->status !== 'ditampilkan') {
            return back()->with('error', 'Barang ini sudah tidak tersedia.');
        }

        // Preloved items usually have stock = 1. Let's enforce that if already in cart, do not add more.
        $existing = Cart::where('user_id', Auth::id())->where('barang_id', $barang->id)->first();
        if ($existing) {
            if ($existing->is_saved_for_later) {
                $existing->update(['is_saved_for_later' => false, 'quantity' => 1]);
                return redirect()->route('buyer.cart')->with('success', 'Barang dipindahkan kembali ke keranjang belanja.');
            }
            return redirect()->route('buyer.cart')->with('info', 'Barang sudah ada di keranjang belanja Anda.');
        }

        Cart::create([
            'user_id' => Auth::id(),
            'barang_id' => $barang->id,
            'quantity' => 1,
            'is_saved_for_later' => false
        ]);

        return redirect()->route('buyer.cart')->with('success', 'Barang berhasil ditambahkan ke keranjang.');
    }

    public function updateQty(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        // Ensure quantity doesn't exceed stock (preloved stock is usually 1, but we support general)
        $maxStock = $cartItem->barang->stok;
        $qty = min($request->quantity, $maxStock);

        $cartItem->update(['quantity' => $qty]);

        return back()->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Barang dihapus dari keranjang.');
    }

    public function saveForLater($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->update(['is_saved_for_later' => !$cartItem->is_saved_for_later]);

        $msg = $cartItem->is_saved_for_later ? 'Barang disimpan untuk nanti.' : 'Barang dipindahkan ke keranjang belanja utama.';
        return back()->with('success', $msg);
    }

    public function toggleWishlist($id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $barang = Barang::findOrFail($id);
        $exists = Wishlist::where('user_id', Auth::id())->where('barang_id', $barang->id)->first();

        if ($exists) {
            $exists->delete();
            return back()->with('success', 'Barang dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'barang_id' => $barang->id
        ]);

        return back()->with('success', 'Barang ditambahkan ke wishlist.');
    }
}
