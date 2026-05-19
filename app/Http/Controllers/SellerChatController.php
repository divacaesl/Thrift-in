<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\User;
use App\Models\Barang;
use App\Models\NegoHarga;

class SellerChatController extends Controller
{
    private function getSellerPenitip()
    {
        return Auth::user()->penitip;
    }

    public function index(Request $request)
    {
        $userId = Auth::id();
        $penitip = $this->getSellerPenitip();

        // Retrieve all users who have sent messages to or received messages from this seller
        $chatPartnersIds = Chat::where(function($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->select('sender_id', 'receiver_id')
            ->get()
            ->flatMap(function($chat) use ($userId) {
                return [$chat->sender_id, $chat->receiver_id];
            })
            ->unique()
            ->filter(function($id) use ($userId) {
                return $id != $userId;
            });

        $contacts = User::whereIn('id', $chatPartnersIds)->get();

        $activeContactId = $request->contact_id ?? $chatPartnersIds->first();
        $activeContact = null;
        $messages = collect();
        $activeNego = null;

        if ($activeContactId) {
            $activeContact = User::find($activeContactId);
            
            // Mark incoming messages as read
            Chat::where('sender_id', $activeContactId)
                ->where('receiver_id', $userId)
                ->update(['is_read' => true]);

            // Get conversational messages
            $messages = Chat::where(function($q) use ($userId, $activeContactId) {
                    $q->where('sender_id', $userId)
                      ->where('receiver_id', $activeContactId);
                })
                ->orWhere(function($q) use ($userId, $activeContactId) {
                    $q->where('sender_id', $activeContactId)
                      ->where('receiver_id', $userId);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Get any pending nego request from this buyer for this seller's products
            $sellerBarangIds = $penitip->barangs()->pluck('id');
            $activeNego = NegoHarga::where('user_id', $activeContactId)
                ->whereIn('barang_id', $sellerBarangIds)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        return view('seller.chat.index', compact('contacts', 'activeContact', 'messages', 'activeNego', 'penitip'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'pesan' => 'required|string',
            'barang_id' => 'nullable|exists:barangs,id',
            'gambar_file' => 'nullable|image|max:2048'
        ]);

        $gambarName = null;
        if ($request->hasFile('gambar_file')) {
            $gambarName = 'chat_' . time() . '.' . $request->file('gambar_file')->getClientOriginalExtension();
            $request->file('gambar_file')->move(public_path('uploads/chats'), $gambarName);
        }

        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'pesan' => $request->pesan,
            'barang_id' => $request->barang_id,
            'gambar' => $gambarName,
            'is_read' => false
        ]);

        return redirect()->route('seller.chat', ['contact_id' => $request->receiver_id])->with('success', 'Pesan terkirim.');
    }

    public function acceptOffer($nego_id)
    {
        $nego = NegoHarga::findOrFail($nego_id);
        $nego->update(['status' => 'diterima']);

        // Send confirmation chat message to buyer
        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $nego->user_id,
            'barang_id' => $nego->barang_id,
            'pesan' => '[Tawaran Diterima] Penawaran Anda sebesar Rp ' . number_format($nego->harga_tawaran, 0, ',', '.') . ' untuk ' . $nego->barang->nama_barang . ' telah disetujui! Silakan langsung beli dari detail barang.',
            'is_read' => false
        ]);

        return back()->with('success', 'Penawaran harga disetujui! Pembeli diinfokan via chat.');
    }

    public function declineOffer($nego_id)
    {
        $nego = NegoHarga::findOrFail($nego_id);
        $nego->update(['status' => 'ditolak']);

        // Send rejection chat message
        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $nego->user_id,
            'barang_id' => $nego->barang_id,
            'pesan' => '[Tawaran Ditolak] Maaf, penawaran Anda sebesar Rp ' . number_format($nego->harga_tawaran, 0, ',', '.') . ' untuk ' . $nego->barang->nama_barang . ' ditolak.',
            'is_read' => false
        ]);

        return back()->with('success', 'Penawaran harga ditolak.');
    }

    public function counterOffer(Request $request, $nego_id)
    {
        $nego = NegoHarga::findOrFail($nego_id);
        $request->validate(['harga_counter' => 'required|numeric|min:1000']);

        $nego->update([
            'status' => 'ditolak' // close active offer
        ]);

        // Submit counter nego record as pending or just send offer in chat
        NegoHarga::create([
            'user_id' => $nego->user_id,
            'barang_id' => $nego->barang_id,
            'harga_tawaran' => $request->harga_counter,
            'status' => 'pending'
        ]);

        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $nego->user_id,
            'barang_id' => $nego->barang_id,
            'pesan' => '[Counter Offer] Kami menawarkan harga baru: Rp ' . number_format($request->harga_counter, 0, ',', '.') . '. Apakah Anda setuju?',
            'is_read' => false
        ]);

        return back()->with('success', 'Counter-offer berhasil dikirim.');
    }

    public function updateAutoReply(Request $request)
    {
        $penitip = $this->getSellerPenitip();
        
        $request->validate([
            'auto_reply_message' => 'required|string',
        ]);

        $penitip->update([
            'is_auto_reply_enabled' => $request->has('is_auto_reply_enabled'),
            'auto_reply_message' => $request->auto_reply_message
        ]);

        return back()->with('success', 'Pengaturan Auto Reply Chat berhasil diperbarui.');
    }
}
