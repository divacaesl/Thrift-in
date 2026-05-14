<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::with(['penitip', 'kategori'])->latest()->get();
        $penitips = \App\Models\Penitip::where('status', 'aktif')->get();
        $kategoris = \App\Models\Kategori::all();
        
        return view('barang.index', compact('barangs', 'penitips', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'penitip_id' => 'required|exists:penitips,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'harga_jual' => 'required|numeric',
            'kondisi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Auto generate kode barang BRG-00x
        $last = Barang::latest()->first();
        $nextId = $last ? ($last->id + 1) : 1;
        $kode = 'BRG-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $data = $request->all();
        $data['kode_barang'] = $kode;
        $data['tgl_masuk'] = now()->toDateString();
        $data['status'] = 'diterima';

        if ($request->hasFile('foto')) {
            $fileName = $kode . '.' . $request->foto->extension();
            $request->foto->move(public_path('assets/uploads'), $fileName);
            $data['foto'] = $fileName;
        } else {
            $data['foto'] = 'default.jpg';
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }
}
