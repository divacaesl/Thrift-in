<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Penitip;

class SellerProductController extends Controller
{
    private function getSellerPenitip()
    {
        return Auth::user()->penitip;
    }

    public function index()
    {
        $penitip = $this->getSellerPenitip();
        $products = $penitip->barangs()->latest()->get();
        return view('seller.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Kategori::all();
        return view('seller.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $penitip = $this->getSellerPenitip();

        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'kondisi' => 'required|in:baru,seperti_baru,bekas_layak,bekas', // mapped to DB enums
            'harga_jual' => 'required|numeric|min:1000',
            'stok' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:100',
            'ukuran' => 'nullable|string|max:20',
            'warna' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:100',
            'berat' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:100',
            'tags' => 'nullable|string|max:255',
            'lama_penggunaan' => 'nullable|string|max:50',
            'frekuensi_penggunaan' => 'nullable|string|max:50',
            'defect_description' => 'nullable|string',
            'foto_file' => 'required|image|max:2048',
            'video_file' => 'nullable|mimes:mp4,mov,avi|max:10240',
            'bukti_keaslian_file' => 'nullable|image|max:2048',
            'invoice_keaslian_file' => 'nullable|image|max:2048',
            'sertifikat_keaslian_file' => 'nullable|image|max:2048',
            'multiple_fotos_files.*' => 'nullable|image|max:2048'
        ]);

        // Generate unique barang code
        $nextId = Barang::max('id') + 1;
        $kodeBarang = 'BRG-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Upload single photo
        $fotoName = 'default.jpg';
        if ($request->hasFile('foto_file')) {
            $fotoName = $kodeBarang . '_' . time() . '.' . $request->file('foto_file')->getClientOriginalExtension();
            $request->file('foto_file')->move(public_path('uploads/products'), $fotoName);
        }

        // Upload video
        $videoName = null;
        if ($request->hasFile('video_file')) {
            $videoName = $kodeBarang . '_video_' . time() . '.' . $request->file('video_file')->getClientOriginalExtension();
            $request->file('video_file')->move(public_path('uploads/products/videos'), $videoName);
        }

        // Upload authenticity files
        $buktiKeaslian = null;
        if ($request->hasFile('bukti_keaslian_file')) {
            $buktiKeaslian = $kodeBarang . '_bukti_' . time() . '.' . $request->file('bukti_keaslian_file')->getClientOriginalExtension();
            $request->file('bukti_keaslian_file')->move(public_path('uploads/authenticity'), $buktiKeaslian);
        }
        $invoiceKeaslian = null;
        if ($request->hasFile('invoice_keaslian_file')) {
            $invoiceKeaslian = $kodeBarang . '_invoice_' . time() . '.' . $request->file('invoice_keaslian_file')->getClientOriginalExtension();
            $request->file('invoice_keaslian_file')->move(public_path('uploads/authenticity'), $invoiceKeaslian);
        }
        $sertifikatKeaslian = null;
        if ($request->hasFile('sertifikat_keaslian_file')) {
            $sertifikatKeaslian = $kodeBarang . '_cert_' . time() . '.' . $request->file('sertifikat_keaslian_file')->getClientOriginalExtension();
            $request->file('sertifikat_keaslian_file')->move(public_path('uploads/authenticity'), $sertifikatKeaslian);
        }

        // Upload multiple photos
        $multiplePhotosArray = [$fotoName];
        if ($request->hasFile('multiple_fotos_files')) {
            foreach ($request->file('multiple_fotos_files') as $key => $file) {
                $mName = $kodeBarang . '_extra_' . $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $mName);
                $multiplePhotosArray[] = $mName;
            }
        }
        $multipleFotos = implode(',', $multiplePhotosArray);

        Barang::create([
            'kode_barang' => $kodeBarang,
            'penitip_id' => $penitip->id,
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'kondisi' => $request->kondisi,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'foto' => $fotoName,
            'video' => $videoName,
            'brand' => $request->brand,
            'ukuran' => $request->ukuran,
            'warna' => $request->warna,
            'material' => $request->material,
            'berat' => $request->berat,
            'lokasi' => $request->lokasi,
            'tags' => $request->tags,
            'lama_penggunaan' => $request->lama_penggunaan,
            'frekuensi_penggunaan' => $request->frekuensi_penggunaan,
            'defect_description' => $request->defect_description,
            'bukti_keaslian' => $buktiKeaslian,
            'invoice_keaslian' => $invoiceKeaslian,
            'sertifikat_keaslian' => $sertifikatKeaslian,
            'multiple_fotos' => $multipleFotos,
            'status' => 'ditampilkan', // Auto show for seller self-management
            'tgl_masuk' => now()->toDateString()
        ]);

        return redirect()->route('seller.product.index')->with('success', 'Produk preloved berhasil ditambahkan ke toko!');
    }

    public function edit($id)
    {
        $penitip = $this->getSellerPenitip();
        $product = Barang::where('penitip_id', $penitip->id)->findOrFail($id);
        $categories = Kategori::all();
        return view('seller.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $penitip = $this->getSellerPenitip();
        $product = Barang::where('penitip_id', $penitip->id)->findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'nullable|string',
            'kondisi' => 'required|in:baru,seperti_baru,bekas_layak,bekas',
            'harga_jual' => 'required|numeric|min:1000',
            'stok' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:100',
            'ukuran' => 'nullable|string|max:20',
            'warna' => 'nullable|string|max:50',
            'material' => 'nullable|string|max:100',
            'berat' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:100',
            'tags' => 'nullable|string|max:255',
            'lama_penggunaan' => 'nullable|string|max:50',
            'frekuensi_penggunaan' => 'nullable|string|max:50',
            'defect_description' => 'nullable|string',
            'foto_file' => 'nullable|image|max:2048',
            'video_file' => 'nullable|mimes:mp4,mov,avi|max:10240',
            'bukti_keaslian_file' => 'nullable|image|max:2048',
            'invoice_keaslian_file' => 'nullable|image|max:2048',
            'sertifikat_keaslian_file' => 'nullable|image|max:2048',
            'multiple_fotos_files.*' => 'nullable|image|max:2048'
        ]);

        $updateData = [
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'deskripsi' => $request->deskripsi,
            'kondisi' => $request->kondisi,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'brand' => $request->brand,
            'ukuran' => $request->ukuran,
            'warna' => $request->warna,
            'material' => $request->material,
            'berat' => $request->berat,
            'lokasi' => $request->lokasi,
            'tags' => $request->tags,
            'lama_penggunaan' => $request->lama_penggunaan,
            'frekuensi_penggunaan' => $request->frekuensi_penggunaan,
            'defect_description' => $request->defect_description,
        ];

        if ($request->hasFile('foto_file')) {
            $fotoName = $product->kode_barang . '_' . time() . '.' . $request->file('foto_file')->getClientOriginalExtension();
            $request->file('foto_file')->move(public_path('uploads/products'), $fotoName);
            $updateData['foto'] = $fotoName;
        }

        if ($request->hasFile('video_file')) {
            $videoName = $product->kode_barang . '_video_' . time() . '.' . $request->file('video_file')->getClientOriginalExtension();
            $request->file('video_file')->move(public_path('uploads/products/videos'), $videoName);
            $updateData['video'] = $videoName;
        }

        if ($request->hasFile('bukti_keaslian_file')) {
            $buktiKeaslian = $product->kode_barang . '_bukti_' . time() . '.' . $request->file('bukti_keaslian_file')->getClientOriginalExtension();
            $request->file('bukti_keaslian_file')->move(public_path('uploads/authenticity'), $buktiKeaslian);
            $updateData['bukti_keaslian'] = $buktiKeaslian;
        }
        if ($request->hasFile('invoice_keaslian_file')) {
            $invoiceKeaslian = $product->kode_barang . '_invoice_' . time() . '.' . $request->file('invoice_keaslian_file')->getClientOriginalExtension();
            $request->file('invoice_keaslian_file')->move(public_path('uploads/authenticity'), $invoiceKeaslian);
            $updateData['invoice_keaslian'] = $invoiceKeaslian;
        }
        if ($request->hasFile('sertifikat_keaslian_file')) {
            $sertifikatKeaslian = $product->kode_barang . '_cert_' . time() . '.' . $request->file('sertifikat_keaslian_file')->getClientOriginalExtension();
            $request->file('sertifikat_keaslian_file')->move(public_path('uploads/authenticity'), $sertifikatKeaslian);
            $updateData['sertifikat_keaslian'] = $sertifikatKeaslian;
        }

        if ($request->hasFile('multiple_fotos_files')) {
            $multiplePhotosArray = [ $updateData['foto'] ?? $product->foto ];
            foreach ($request->file('multiple_fotos_files') as $key => $file) {
                $mName = $product->kode_barang . '_extra_' . $key . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $mName);
                $multiplePhotosArray[] = $mName;
            }
            $updateData['multiple_fotos'] = implode(',', $multiplePhotosArray);
        }

        $product->update($updateData);

        return redirect()->route('seller.product.index')->with('success', 'Produk preloved berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $penitip = $this->getSellerPenitip();
        $product = Barang::where('penitip_id', $penitip->id)->findOrFail($id);
        $product->delete();

        return redirect()->route('seller.product.index')->with('success', 'Produk berhasil dihapus dari toko Anda.');
    }

    // AI Price Recommendation Endpoint
    public function getAiRecommendation(Request $request)
    {
        $kategoriId = $request->kategori_id;
        $brand = $request->brand;

        // Fetch prices of items in this category and brand
        $query = Barang::query();
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }
        if ($brand) {
            $query->where('brand', 'LIKE', '%' . $brand . '%');
        }

        $prices = $query->pluck('harga_jual');

        if ($prices->isEmpty()) {
            // Default recommended range based on category average
            $avg = 100000;
        } else {
            $avg = $prices->average();
        }

        // Recommend range
        $min = $avg * 0.75;
        $max = $avg * 1.25;

        return response()->json([
            'average' => round($avg),
            'min' => round($min),
            'max' => round($max),
            'message' => 'Rekomendasi didasarkan pada ' . $prices->count() . ' produk serupa di pasar.'
        ]);
    }
}
