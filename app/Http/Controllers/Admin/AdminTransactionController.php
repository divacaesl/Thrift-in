<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['barang.penitip']);

        if ($request->filled('status')) {
            $query->where('status_pesanan', $request->status);
        }
        
        if ($request->filled('dispute')) {
            $query->whereNotNull('dispute_status');
        }

        $transactions = $query->latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function resolveDispute(Request $request, $id)
    {
        $transaction = Transaksi::findOrFail($id);
        
        $request->validate([
            'resolution' => 'required|in:buyer_win,seller_win',
            'notes' => 'required|string'
        ]);

        if ($request->resolution == 'buyer_win') {
            $transaction->update([
                'status_pesanan' => 'refund',
                'dispute_status' => 'resolved_buyer',
                'dispute_notes' => $request->notes
            ]);
            // Logic for refunding buyer balance goes here in real env
            $msg = 'Dispute resolved in favor of Buyer. Refund initiated.';
        } else {
            $transaction->update([
                'status_pesanan' => 'sampai',
                'dispute_status' => 'resolved_seller',
                'dispute_notes' => $request->notes
            ]);
            // Logic for releasing escrow to seller
            $seller = $transaction->barang->penitip;
            $seller->increment('saldo', $transaction->hasil_penitip);
            
            $msg = 'Dispute resolved in favor of Seller. Funds released.';
        }

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'action_type' => 'resolve_dispute',
            'description' => "Resolved dispute for order {$transaction->kode_transaksi}: {$request->resolution}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', $msg);
    }
}
