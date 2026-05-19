<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminManagementController extends Controller
{
    public function index()
    {
        return view('super_admin.admins_index');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        // Logic to create admin would go here
        return redirect()->back()->with('success', 'Admin berhasil ditambahkan.');
    }

    public function suspend($id)
    {
        // Logic to suspend admin
        return redirect()->back()->with('success', 'Admin berhasil disuspend.');
    }
}
