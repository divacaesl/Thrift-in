<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\NegoHarga;
use App\Models\Barang;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class BuyerChatController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login');
        }

        $userId = Auth::id();

        // Find all users this buyer has chatted with
        $chats = Chat::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->latest()
            ->get();

        $conversationUserIds = [];
        foreach ($chats as $chat) {
            if ($chat->sender_id !== $userId) {
                $conversationUserIds[] = $chat->sender_id;
            }
            if ($chat->receiver_id !== $userId) {
                $conversationUserIds[] = $chat->receiver_id;
            }
        }
        $conversationUserIds = array_unique($conversationUserIds);
        
        // Fetch conversational partners. If none exists, seed a default conversation with the first seller (consignor: Sari Dewi / user ID 4).
        if (empty($conversationUserIds)) {
            // Find a seller user
            $sellerUser = User::where('role', 'kasir')->first();
            if ($sellerUser) {
                $conversationUserIds[] = $sellerUser->id;
                // Add a welcome chat message from the seller
                Chat::create([
                    'sender_id' => $sellerUser->id,
                    'receiver_id' => $userId,
                    'pesan' => 'Halo! Terima kasih telah berkunjung ke toko kami. Ada yang bisa kami bantu? Anda juga bisa menawar harga barang di sini, lho!',
                    'is_read' => false
                ]);
            }
        }

        $contacts = User::whereIn('id', $conversationUserIds)->get();

        // Get active chat partner
        $activeContactId = $request->input('contact_id');
        if (!$activeContactId && !$contacts->isEmpty()) {
            $activeContactId = $contacts->first()->id;
        }

        $activeContact = $activeContactId ? User::find($activeContactId) : null;
        $messages = collect();
        if ($activeContact) {
            $messages = Chat::where(function($q) use ($userId, $activeContactId) {
                $q->where('sender_id', $userId)->where('receiver_id', $activeContactId);
            })->orWhere(function($q) use ($userId, $activeContactId) {
                $q->where('sender_id', $activeContactId)->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')->get();

            // Mark as read
            Chat::where('sender_id', $activeContactId)->where('receiver_id', $userId)->update(['is_read' => true]);
        }

        // Product context if redirecting from details page "Tanya Penjual"
        $contextProduct = null;
        if ($request->filled('barang_id')) {
            $contextProduct = Barang::find($request->barang_id);
        }

        return view('buyer.chat', compact('contacts', 'activeContact', 'messages', 'contextProduct'));
    }

    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'pesan' => 'required|string',
            'barang_id' => 'nullable|exists:barangs,id',
            'gambar' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('gambar')) {
            $fileName = 'chat_' . time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('uploads/chats'), $fileName);
            $imagePath = 'uploads/chats/' . $fileName;
        }

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'barang_id' => $request->barang_id,
            'pesan' => $request->pesan,
            'gambar' => $imagePath,
            'is_read' => false
        ]);

        // Auto-reply Simulation to showcase real-time interactions!
        $autoReplies = [
            'kondisi' => 'Kondisi barangnya masih bagus kak, mulus 95% minim defect. Silakan ditawar!',
            'nego' => 'Boleh ditawar kak, ajukan saja harga penawaran kakak menggunakan tombol "Nego Harga" di bawah deskripsi chat ya!',
            'ori' => 'Dijamin 100% original kak, silakan cek detail foto tag dan kelengkapannya.',
            'default' => 'Halo kak! Barang ini ready dan siap dikirim. Silakan hubungi kami jika ada pertanyaan lain atau tawar langsung harganya ya!'
        ];

        $lowerPesan = strtolower($request->pesan);
        $replyText = $autoReplies['default'];
        if (str_contains($lowerPesan, 'kondisi') || str_contains($lowerPesan, 'minus') || str_contains($lowerPesan, 'defect')) {
            $replyText = $autoReplies['kondisi'];
        } elseif (str_contains($lowerPesan, 'nego') || str_contains($lowerPesan, 'kurang') || str_contains($lowerPesan, 'diskon')) {
            $replyText = $autoReplies['nego'];
        } elseif (str_contains($lowerPesan, 'ori') || str_contains($lowerPesan, 'asli')) {
            $replyText = $autoReplies['ori'];
        }

        // Save a mock reply after 1 second
        Chat::create([
            'sender_id' => $request->receiver_id,
            'receiver_id' => Auth::id(),
            'barang_id' => $request->barang_id,
            'pesan' => '[Auto-Reply] ' . $replyText,
            'is_read' => false
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }

    public function submitOffer(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Silakan login.');
        }

        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'harga_tawaran' => 'required|numeric|min:1'
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        $originalPrice = $barang->harga_jual;
        $offeredPrice = $request->harga_tawaran;

        // Auto accept if offer is >= 85% of original price, otherwise reject
        $isAccepted = ($offeredPrice >= ($originalPrice * 0.85));

        $status = $isAccepted ? 'diterima' : 'ditolak';

        $nego = NegoHarga::create([
            'user_id' => Auth::id(),
            'barang_id' => $barang->id,
            'harga_tawaran' => $offeredPrice,
            'status' => $status
        ]);

        // Send chat bubbles to represent negotiation
        $sellerUser = User::where('role', 'kasir')->first();
        $sellerId = $sellerUser ? $sellerUser->id : 1;

        // User offer message bubble
        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $sellerId,
            'barang_id' => $barang->id,
            'pesan' => 'Saya mengajukan penawaran harga sebesar Rp ' . number_format($offeredPrice, 0, ',', '.') . ' untuk ' . $barang->nama_barang . '.'
        ]);

        // Seller reply bubble
        if ($isAccepted) {
            $reply = 'Penawaran Anda sebesar Rp ' . number_format($offeredPrice, 0, ',', '.') . ' DITERIMA! Silakan langsung tambahkan barang ke keranjang untuk checkout dengan harga kesepakatan.';
            
            // Adjust the actual price in the cart or session for this user!
            // To simulate buying at the negotiated price, we can save the negotiated price in the cart or session.
            session()->put('nego_price_' . $barang->id, $offeredPrice);

            Notifikasi::create([
                'user_id' => Auth::id(),
                'judul' => 'Nego Harga Diterima!',
                'pesan' => 'Tawaran Anda untuk ' . $barang->nama_barang . ' sebesar Rp ' . number_format($offeredPrice, 0, ',', '.') . ' telah disetujui penjual. Checkout sekarang!',
                'tipe' => 'chat',
                'is_read' => false
            ]);
        } else {
            $reply = 'Maaf, penawaran Anda sebesar Rp ' . number_format($offeredPrice, 0, ',', '.') . ' DITOLAK. Tawaran terlalu rendah. Silakan ajukan harga yang lebih tinggi.';
            
            Notifikasi::create([
                'user_id' => Auth::id(),
                'judul' => 'Nego Harga Ditolak',
                'pesan' => 'Tawaran Anda untuk ' . $barang->nama_barang . ' sebesar Rp ' . number_format($offeredPrice, 0, ',', '.') . ' ditolak penjual. Coba nego lagi.',
                'tipe' => 'chat',
                'is_read' => false
            ]);
        }

        Chat::create([
            'sender_id' => $sellerId,
            'receiver_id' => Auth::id(),
            'barang_id' => $barang->id,
            'pesan' => '[Nego Status] ' . $reply
        ]);

        return redirect()->route('buyer.chat', ['contact_id' => $sellerId])->with('success', 'Penawaran harga berhasil diajukan.');
    }
}
