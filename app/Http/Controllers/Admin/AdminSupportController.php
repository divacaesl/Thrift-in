<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use Illuminate\Support\Facades\Auth;

class AdminSupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with('user', 'handler');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->paginate(20);
        return view('admin.support.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'handler', 'replies.sender'])->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        
        $request->validate([
            'pesan' => 'required|string',
            'status' => 'nullable|in:open,in_progress,resolved,closed'
        ]);

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'sender_id' => Auth::id(),
            'pesan' => $request->pesan
        ]);

        if ($request->filled('status') && $request->status != $ticket->status) {
            $ticket->status = $request->status;
        }

        if (!$ticket->handled_by) {
            $ticket->handled_by = Auth::id();
        }

        $ticket->save();

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}
