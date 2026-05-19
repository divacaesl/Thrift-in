<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommunicationController extends Controller
{
    public function broadcast()
    {
        return view('super_admin.broadcast');
    }

    public function sendBroadcast(\Illuminate\Http\Request $request)
    {
        return redirect()->back()->with('success', 'Pesan broadcast berhasil dikirim ke antrean (queue).');
    }
}
