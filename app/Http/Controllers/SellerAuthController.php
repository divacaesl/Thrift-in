<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Penitip;

class SellerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('seller.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'penjual') {
                Auth::logout();
                return back()->with('error', 'Akun Anda bukan akun Penjual.');
            }

            if ($user->status === 'nonaktif') {
                Auth::logout();
                return back()->with('error', 'Akun Penjual Anda dinonaktifkan.');
            }

            $request->session()->regenerate();
            return redirect()->route('seller.dashboard')->with('success', 'Selamat datang kembali di Dasbor Seller!');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function showRegisterForm()
    {
        return view('seller.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'nama_toko' => 'required|string|max:100',
            'alamat_toko' => 'required|string',
            'ktp_file' => 'nullable|image|max:2048',
            'selfie_file' => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'penjual',
            'status' => 'aktif'
        ]);

        // Generate next kode_penitip
        $nextId = Penitip::max('id') + 1;
        $kodePenitip = 'PNT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Set default dummy files
        $ktpName = 'ktp_dummy.jpg';
        if ($request->hasFile('ktp_file')) {
            $ktpName = 'ktp_' . time() . '.' . $request->file('ktp_file')->getClientOriginalExtension();
            $request->file('ktp_file')->move(public_path('uploads/verifications'), $ktpName);
        }

        $selfieName = 'selfie_dummy.jpg';
        if ($request->hasFile('selfie_file')) {
            $selfieName = 'selfie_' . time() . '.' . $request->file('selfie_file')->getClientOriginalExtension();
            $request->file('selfie_file')->move(public_path('uploads/verifications'), $selfieName);
        }

        Penitip::create([
            'kode_penitip' => $kodePenitip,
            'nama' => $request->nama_toko,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'alamat' => $request->alamat_toko,
            'user_id' => $user->id,
            'ktp' => $ktpName,
            'selfie' => $selfieName,
            'is_verified' => true, // Auto verify in simulation
            'logo_toko' => 'default_logo.png',
            'banner_toko' => 'default_banner.png',
            'saldo' => 0.00
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('seller.dashboard')->with('success', 'Registrasi Toko berhasil! Akun Anda aktif.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('seller.login')->with('success', 'Anda telah berhasil keluar.');
    }
}
