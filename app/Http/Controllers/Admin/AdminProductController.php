<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\FraudReport;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['penitip', 'kategori']);

        if ($request->filled('moderation_status')) {
            $query->where('moderation_status', $request->moderation_status);
        }

        $products = $query->latest()->paginate(20);
        
        $flaggedCount = Barang::where('moderation_status', 'flagged')->count();

        return view('admin.products.index', compact('products', 'flaggedCount'));
    }

    public function moderationAction(Request $request, $id)
    {
        $product = Barang::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string'
        ]);

        if ($request->action == 'approve') {
            $product->update([
                'moderation_status' => 'approved',
                'status' => 'ditampilkan',
                'moderation_notes' => null
            ]);
            
            // Resolve any pending fraud reports
            FraudReport::where('barang_id', $product->id)->update(['status' => 'dismissed']);
            
            $msg = 'Product approved and is now visible to buyers.';
        } else {
            $product->update([
                'moderation_status' => 'rejected',
                'status' => 'disembunyikan',
                'moderation_notes' => $request->notes ?? 'Melanggar aturan komunitas.'
            ]);
            
            FraudReport::where('barang_id', $product->id)->update(['status' => 'action_taken']);
            
            $msg = 'Product rejected and hidden from public.';
        }

        AdminActivityLog::create([
            'admin_id' => Auth::id(),
            'action_type' => 'moderate_product',
            'description' => "Moderated product ID {$product->id} to {$request->action}",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', $msg);
    }

    public function categories()
    {
        $categories = Kategori::all();
        return view('admin.products.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        Kategori::create($request->all());
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function destroyCategory($id)
    {
        Kategori::findOrFail($id)->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
