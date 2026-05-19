<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        return view('super_admin.api_index');
    }

    public function generate(\Illuminate\Http\Request $request)
    {
        return redirect()->back()->with('success', 'API Key baru berhasil di-generate.');
    }
}
