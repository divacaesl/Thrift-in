<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class BuyerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('buyer.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => 'required', // Can be email or username
            'password' => 'required',
        ]);

        $loginField = filter_var($credentials['login_id'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $authData = [
            $loginField => $credentials['login_id'],
            'password' => $credentials['password']
        ];

        if (Auth::attempt($authData)) {
            $request->session()->regenerate();

            if (Auth::user()->status === 'nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Akun Anda dinonaktifkan.');
            }

            // Redirect based on role
            if (Auth::user()->role === 'pembeli') {
                return redirect()->intended('/');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Email/Username atau password salah.');
    }

    public function showRegisterForm()
    {
        return view('buyer.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'role' => 'pembeli',
            'status' => 'aktif'
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // Create welcome notification
        \App\Models\Notifikasi::create([
            'user_id' => $user->id,
            'judul' => 'Registrasi Berhasil!',
            'pesan' => 'Selamat datang di ThriftIn, ' . $user->nama . '! Temukan barang-barang preloved impianmu di sini.',
            'tipe' => 'promo',
            'is_read' => false
        ]);

        return redirect('/')->with('success', 'Registrasi berhasil dan Anda telah masuk otomatis.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil keluar.');
    }

    // Simulation for Google Login
    public function loginGoogle()
    {
        // We find or create a mock Google user
        $user = User::firstOrCreate(
            ['email' => 'googleuser@gmail.com'],
            [
                'nama' => 'Google User Demo',
                'username' => 'google_user',
                'password' => Hash::make('google123'),
                'role' => 'pembeli',
                'no_hp' => '08999888777',
                'foto_profil' => 'default_profile.jpg',
                'status' => 'aktif'
            ]
        );

        Auth::login($user);
        session()->regenerate();

        return redirect('/')->with('success', 'Berhasil masuk menggunakan akun Google.');
    }

    // Simulation for Password Recovery / Forgot Password
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // Simulate sending a reset link
        return back()->with('success', 'Link reset password telah dikirim ke email Anda (Simulasi). Silakan periksa kotak masuk.');
    }

    // Simulation for OTP Verification
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required']);
        // Simulate OTP success
        return back()->with('success', 'Verifikasi OTP Berhasil (Simulasi). Akun Anda telah terverifikasi.');
    }
}
