<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penitip;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function suspend(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'suspend_reason' => 'required|string|max:255'
        ]);

        $user->update([
            'status' => 'suspended',
            'suspend_reason' => $request->suspend_reason
        ]);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'action_type' => 'suspend_user',
            'description' => "Suspended user {$user->email} due to: {$request->suspend_reason}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'User account has been suspended.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => 'aktif',
            'suspend_reason' => null
        ]);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'action_type' => 'activate_user',
            'description' => "Re-activated user {$user->email}",
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'User account re-activated.');
    }

    public function sellerVerificationQueue()
    {
        // Get penitip where KTP is uploaded but not verified yet
        $sellers = Penitip::where('is_verified', false)
            ->whereNotNull('ktp')
            ->whereNotNull('selfie')
            ->with('user')
            ->get();
            
        return view('admin.users.seller_kyc', compact('sellers'));
    }

    public function verifySeller($id)
    {
        $penitip = Penitip::findOrFail($id);
        $penitip->update(['is_verified' => true]);

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'action_type' => 'verify_seller',
            'description' => "Verified identity for seller: {$penitip->nama}",
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', 'Seller identity verified successfully.');
    }

    public function rejectSeller($id)
    {
        $penitip = Penitip::findOrFail($id);
        // Clear documents
        $penitip->update([
            'ktp' => null,
            'selfie' => null,
            'is_verified' => false
        ]);

        return back()->with('success', 'Seller verification rejected. Documents cleared.');
    }
}
