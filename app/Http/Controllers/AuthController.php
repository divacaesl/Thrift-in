<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Log IP and Time
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip()
            ]);
            
            if (in_array($user->status, ['nonaktif', 'suspended'])) {
                $reason = $user->suspend_reason ? " Alasan: " . $user->suspend_reason : "";
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error', 'Akun Anda dinonaktifkan atau disuspend.' . $reason);
            }

            // Role-based redirection
            if ($user->role === 'super_admin') {
                return redirect()->intended(route('superadmin.dashboard'));
            } elseif (in_array($user->role, ['admin', 'admin_produk', 'admin_keuangan', 'cs'])) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'penjual') {
                return redirect()->intended(route('seller.dashboard'));
            } else {
                return redirect()->intended(route('buyer.dashboard'));
            }
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
