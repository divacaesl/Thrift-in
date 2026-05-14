<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penitip;

class PenitipController extends Controller
{
    public function index()
    {
        $penitips = Penitip::latest()->get();
        return view('penitip.index', compact('penitips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'nama_bank' => 'required|string|max:50',
            'no_rekening' => 'required|string|max:30',
        ]);

        // Generate kode penitip otomatis PNT-00x
        $last = Penitip::latest()->first();
        $nextId = $last ? ($last->id + 1) : 1;
        $kode = 'PNT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        Penitip::create(array_merge($request->all(), ['kode_penitip' => $kode]));

        return redirect()->route('penitip.index')->with('success', 'Data penitip berhasil ditambahkan');
    }

    public function destroy(Penitip $penitip)
    {
        $penitip->delete();
        return redirect()->route('penitip.index')->with('success', 'Data penitip berhasil dihapus');
    }
}
