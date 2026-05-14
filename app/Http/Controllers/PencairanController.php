<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pencairan;

class PencairanController extends Controller
{
    public function index()
    {
        $pencairans = Pencairan::with(['penitip', 'admin'])->latest()->get();
        $penitips = \App\Models\Penitip::where('status', 'aktif')->get();
        
        return view('pencairan.index', compact('pencairans', 'penitips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penitip_id' => 'required|exists:penitips,id',
            'jumlah' => 'required|numeric|min:1',
            'metode' => 'required'
        ]);

        // Auto generate kode pencairan WDR-xxx
        $last = Pencairan::latest()->first();
        $nextId = $last ? ($last->id + 1) : 1;
        $kode = 'WDR-' . date('Ymd') . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Pencairan::create([
            'kode_pencairan' => $kode,
            'penitip_id' => $request->penitip_id,
            'jumlah' => $request->jumlah,
            'tgl_pencairan' => now(),
            'metode' => $request->metode,
            'status' => 'selesai',
            'keterangan' => $request->keterangan,
            'admin_id' => auth()->id()
        ]);

        return redirect()->route('pencairan.index')->with('success', 'Data pencairan dana berhasil disimpan');
    }
}
