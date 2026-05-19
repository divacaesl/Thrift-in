<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Penitip;
use App\Models\RecentlyViewed;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class BuyerHomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::where('status', 'ditampilkan');

        // Search name/brand
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_barang', 'like', "%{$q}%")
                  ->orWhere('brand', 'like', "%{$q}%")
                  ->orWhere('deskripsi', 'like', "%{$q}%");
            });
            
            // Record search activity in session for AI recommendation
            $searches = session()->get('search_history', []);
            $searches[] = $q;
            session()->put('search_history', array_unique($searches));
        }

        // Advanced Filters
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('min_harga')) {
            $query->where('harga_jual', '>=', $request->min_harga);
        }
        if ($request->filled('max_harga')) {
            $query->where('harga_jual', '<=', $request->max_harga);
        }
        if ($request->filled('brand')) {
            $query->where('brand', 'like', "%{$request->brand}%");
        }
        if ($request->filled('ukuran')) {
            $query->where('ukuran', $request->ukuran);
        }
        if ($request->filled('warna')) {
            $query->where('warna', $request->warna);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', "%{$request->lokasi}%");
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'harga_termurah':
                    $query->orderBy('harga_jual', 'asc');
                    break;
                case 'harga_termahal':
                    $query->orderBy('harga_jual', 'desc');
                    break;
                case 'produk_terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $barangs = $query->get();
        $categories = Kategori::all();
        $flashSales = Barang::where('status', 'ditampilkan')->where('is_flash_sale', true)->get();
        
        // AI Recommendations simulation (based on search history or category of viewed items)
        $aiRecommendation = collect();
        if (Auth::check()) {
            $history = RecentlyViewed::where('user_id', Auth::id())->pluck('barang_id')->toArray();
            if (!empty($history)) {
                $categoriesInHistory = Barang::whereIn('id', $history)->pluck('kategori_id')->toArray();
                $aiRecommendation = Barang::where('status', 'ditampilkan')
                    ->whereIn('kategori_id', $categoriesInHistory)
                    ->whereNotIn('id', $history)
                    ->limit(4)->get();
            }
        }
        if ($aiRecommendation->isEmpty()) {
            $aiRecommendation = Barang::where('status', 'ditampilkan')->inRandomOrder()->limit(4)->get();
        }

        // Recently Viewed items
        $recentlyViewed = collect();
        if (Auth::check()) {
            $rvIds = RecentlyViewed::where('user_id', Auth::id())
                ->latest('viewed_at')
                ->limit(6)
                ->pluck('barang_id')
                ->toArray();
            $recentlyViewed = Barang::whereIn('id', $rvIds)->get();
        }

        return view('buyer.home', compact('barangs', 'categories', 'flashSales', 'aiRecommendation', 'recentlyViewed'));
    }

    public function detail($id)
    {
        $barang = Barang::with(['penitip', 'kategori', 'ulasans.user'])->findOrFail($id);

        // Record Recently Viewed
        if (Auth::check()) {
            RecentlyViewed::updateOrCreate(
                ['user_id' => Auth::id(), 'barang_id' => $barang->id],
                ['viewed_at' => now()]
            );
        }

        // Calculate average ratings
        $avgRating = $barang->ulasans->avg('rating') ?: 5;
        $totalReviews = $barang->ulasans->count();

        // Similar products
        $similar = Barang::where('status', 'ditampilkan')
            ->where('kategori_id', $barang->kategori_id)
            ->where('id', '!=', $barang->id)
            ->limit(4)->get();

        return view('buyer.detail', compact('barang', 'avgRating', 'totalReviews', 'similar'));
    }

    public function followSeller($id)
    {
        if (!Auth::check()) {
            return redirect()->route('buyer.login')->with('error', 'Silakan login terlebih dahulu untuk mengikuti toko.');
        }

        $penitip = Penitip::findOrFail($id);
        
        $exists = Follow::where('follower_id', Auth::id())->where('penitip_id', $penitip->id)->first();
        if ($exists) {
            $exists->delete();
            return back()->with('success', 'Anda berhenti mengikuti ' . $penitip->nama);
        }

        Follow::create([
            'follower_id' => Auth::id(),
            'penitip_id' => $penitip->id
        ]);

        return back()->with('success', 'Anda sekarang mengikuti ' . $penitip->nama);
    }

    public function shop($id)
    {
        $penitip = Penitip::with(['barangs' => function($q) {
            $q->where('status', 'ditampilkan');
        }])->findOrFail($id);

        $barangs = $penitip->barangs;
        $totalProducts = $barangs->count();

        // Calculate average ratings from reviews
        $barangIds = $penitip->barangs()->pluck('id');
        $reviews = \App\Models\Ulasan::whereIn('barang_id', $barangIds)->get();
        $avgRating = $reviews->avg('rating') ?: 5;

        // Follow stats
        $followersCount = Follow::where('penitip_id', $penitip->id)->count();
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Follow::where('follower_id', Auth::id())->where('penitip_id', $penitip->id)->exists();
        }

        return view('buyer.shop', compact('penitip', 'barangs', 'totalProducts', 'avgRating', 'followersCount', 'isFollowing'));
    }

    public function toggleLanguage($lang)
    {
        if (in_array($lang, ['id', 'en'])) {
            session()->put('preferred_language', $lang);
            if (Auth::check()) {
                Auth::user()->update(['preferred_language' => $lang]);
            }
        }
        return back();
    }

    public function toggleTheme($theme)
    {
        if (in_array($theme, ['light', 'dark'])) {
            session()->put('theme_mode', $theme);
            if (Auth::check()) {
                Auth::user()->update(['theme_mode' => $theme]);
            }
        }
        return response()->json(['success' => true]);
    }
}
