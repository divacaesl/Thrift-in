<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['barang', 'kasir'])->latest()->get();
        // Hanya barang yang statusnya 'ditampilkan' yang bisa dijual
        $barangs = \App\Models\Barang::where('status', 'ditampilkan')->get();
        
        return view('transaksi.index', compact('transaksis', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_pembeli' => 'required|string|max:100',
            'metode_bayar' => 'required'
        ]);

        $barang = \App\Models\Barang::find($request->barang_id);
        
        // Perhitungan komisi (Default: 20% untuk toko)
        $komisiPersen = 20;
        $komisiNominal = ($barang->harga_jual * $komisiPersen) / 100;
        $hasilPenitip = $barang->harga_jual - $komisiNominal;

        // Auto generate kode transaksi TRX-xxx
        $last = Transaksi::latest()->first();
        $nextId = $last ? ($last->id + 1) : 1;
        $kode = 'TRX-' . date('Ymd') . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Transaksi::create([
            'kode_transaksi' => $kode,
            'barang_id' => $barang->id,
            'nama_pembeli' => $request->nama_pembeli,
            'no_hp_pembeli' => $request->no_hp_pembeli,
            'harga_jual' => $barang->harga_jual,
            'komisi_persen' => $komisiPersen,
            'komisi_nominal' => $komisiNominal,
            'hasil_penitip' => $hasilPenitip,
            'metode_bayar' => $request->metode_bayar,
            'tgl_transaksi' => now(),
            'kasir_id' => auth()->id()
        ]);

        // Update status barang jadi terjual
        $barang->update([
            'status' => 'terjual',
            'tgl_terjual' => now()
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan');
    }
}
