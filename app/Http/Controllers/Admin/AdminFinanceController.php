<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pencairan;
use App\Models\SystemSetting;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $withdrawals = Pencairan::with('penitip')->latest()->paginate(20);
        $pendingCount = Pencairan::where('status', 'pending')->count();
        
        $platformFee = SystemSetting::where('key', 'platform_fee_percent')->value('value') ?? 5;

        return view('admin.finance.index', compact('withdrawals', 'pendingCount', 'platformFee'));
    }

    public function processWithdrawal(Request $request, $id)
    {
        $wd = Pencairan::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string'
        ]);

        if ($request->action == 'approve') {
            $wd->update([
                'status' => 'selesai',
                'admin_id' => Auth::id(),
                'keterangan' => $request->notes ?? $wd->keterangan . ' (Approved)'
            ]);
            
            // Deduct balance from seller
            $wd->penitip->decrement('saldo', $wd->jumlah);
            $msg = 'Withdrawal request approved and processed.';
        } else {
            $wd->update([
                'status' => 'ditolak',
                'admin_id' => Auth::id(),
                'keterangan' => $request->notes ?? 'Penarikan ditolak oleh sistem.'
            ]);
            $msg = 'Withdrawal request rejected. Funds remain in seller wallet.';
        }

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'action_type' => 'process_withdrawal',
            'description' => "Processed WD {$wd->kode_pencairan} - Action: {$request->action}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', $msg);
    }
}
