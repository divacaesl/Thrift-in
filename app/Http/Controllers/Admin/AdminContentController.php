<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Voucher;

class AdminContentController extends Controller
{
    public function banners()
    {
        $banners = Banner::orderBy('urutan')->get();
        return view('admin.content.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'gambar_file' => 'required|image|max:2048'
        ]);

        // Upload simulasi
        $fileName = 'banner_' . time() . '.jpg';
        
        Banner::create([
            'judul' => $request->judul,
            'gambar' => $fileName,
            'link_url' => $request->link_url,
            'is_active' => true,
            'urutan' => Banner::max('urutan') + 1
        ]);

        return back()->with('success', 'Banner ditambahkan.');
    }

    public function destroyBanner($id)
    {
        Banner::findOrFail($id)->delete();
        return back()->with('success', 'Banner dihapus.');
    }

    public function vouchers()
    {
        $vouchers = Voucher::latest()->paginate(20);
        return view('admin.content.vouchers', compact('vouchers'));
    }

    public function storeVoucher(Request $request)
    {
        $request->validate([
            'kode_voucher' => 'required|string|unique:vouchers',
            'diskon' => 'required|numeric',
            'min_beli' => 'required|numeric'
        ]);

        Voucher::create($request->all());

        return back()->with('success', 'Voucher berhasil dibuat.');
    }
}
