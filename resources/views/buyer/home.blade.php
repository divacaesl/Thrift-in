@extends('layouts.buyer')

@section('title', 'ThriftIn - Preloved & Thrift E-Commerce')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<!-- Hero / Promo Carousel -->
<div id="promoCarousel" class="carousel slide mb-5" data-bs-ride="carousel" style="border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
    <div class="carousel-inner">
        <div class="carousel-item active" style="height: 320px; background: linear-gradient(135deg, #5C8A6B, #3E5E49);">
            <div class="d-flex align-items-center justify-content-between px-5 h-100 text-white">
                <div class="col-lg-6">
                    <span class="badge bg-warning text-dark mb-3 fw-bold">{{ $lang == 'en' ? 'LIMITED PROMO' : 'PROMO TERBATAS' }}</span>
                    <h1 class="fw-bold mb-3" style="font-size: 2.5rem;">{{ $lang == 'en' ? 'New User Discount!' : 'Diskon Pengguna Baru!' }}</h1>
                    <p class="mb-4">{{ $lang == 'en' ? 'Get an immediate Rp 10,000 discount on your first checkout using code:' : 'Dapatkan diskon instan Rp 10.000 untuk transaksi pertamamu dengan kode:' }} <strong class="bg-dark px-2 py-1 rounded text-warning">NEWUSER10</strong></p>
                    <a href="{{ url('/?brand=uniqlo') }}" class="btn btn-warning text-dark fw-bold px-4 py-2" style="border-radius: 10px;">{{ $lang == 'en' ? 'Shop Now' : 'Belanja Sekarang' }}</a>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <i class="fas fa-ticket-alt text-warning" style="font-size: 10rem; opacity: 0.25;"></i>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="height: 320px; background: linear-gradient(135deg, #D4956A, #B3764D);">
            <div class="d-flex align-items-center justify-content-between px-5 h-100 text-white">
                <div class="col-lg-6">
                    <span class="badge bg-dark text-white mb-3 fw-bold">{{ $lang == 'en' ? 'FLASH SALE' : 'CUCI GUDANG' }}</span>
                    <h1 class="fw-bold mb-3" style="font-size: 2.5rem;">{{ $lang == 'en' ? 'Up to 50% Off Preloved' : 'Hemat s/d 50% Koleksi Preloved' }}</h1>
                    <p class="mb-4">{{ $lang == 'en' ? 'Vintage shirts, hoodies, sneakers and branded bags are on flash sale for limited hours.' : 'Kaos vintage, hoodie, sneakers, dan tas branded diskon kilat dalam jam terbatas.' }}</p>
                    <a href="#flash-sale-section" class="btn btn-dark text-white fw-bold px-4 py-2" style="border-radius: 10px;">{{ $lang == 'en' ? 'Hunt Now' : 'Berburu Sekarang' }}</a>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <i class="fas fa-bolt text-white" style="font-size: 10rem; opacity: 0.25;"></i>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
</div>

<!-- Categories Circle Navigation -->
<h4 class="fw-bold mb-3">{{ $lang == 'en' ? 'Explore Categories' : 'Telusuri Kategori' }}</h4>
<div class="row g-3 mb-5 overflow-auto flex-nowrap pb-2">
    @foreach($categories as $cat)
        <div class="col-6 col-md-3 col-lg-2">
            <a href="{{ url('/?kategori=' . $cat->id) }}" class="card text-center p-3 text-decoration-none border-0 h-100 shadow-sm" style="border-radius: 16px;">
                <div class="p-3 mb-2 rounded-circle mx-auto d-flex align-items-center justify-content-center text-primary" style="background-color: var(--border-color); width: 60px; height: 60px;">
                    @if($cat->nama_kategori == 'Fashion Pria')
                        <i class="fas fa-mars fs-3"></i>
                    @elseif($cat->nama_kategori == 'Fashion Wanita')
                        <i class="fas fa-venus fs-3"></i>
                    @elseif($cat->nama_kategori == 'Sepatu')
                        <i class="fas fa-shoe-prints fs-3"></i>
                    @elseif($cat->nama_kategori == 'Tas')
                        <i class="fas fa-briefcase fs-3"></i>
                    @elseif($cat->nama_kategori == 'Elektronik')
                        <i class="fas fa-laptop fs-3"></i>
                    @elseif($cat->nama_kategori == 'Buku')
                        <i class="fas fa-book fs-3"></i>
                    @elseif($cat->nama_kategori == 'Aksesoris')
                        <i class="fas fa-glasses fs-3"></i>
                    @else
                        <i class="fas fa-box-open fs-3"></i>
                    @endif
                </div>
                <span class="small fw-bold text-dark">{{ $cat->nama_kategori }}</span>
            </a>
        </div>
    @endforeach
</div>

<!-- Flash Sales Countdown Section -->
@if(!$flashSales->isEmpty())
<div id="flash-sale-section" class="p-4 mb-5 text-white" style="background: linear-gradient(135deg, #1f1f1f, #2B2A27); border-radius: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.15);">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <h3 class="fw-bold mb-0 text-warning"><i class="fas fa-bolt me-2"></i>Flash Sale</h3>
            <!-- Countdown timer in JS -->
            <div class="bg-dark text-warning px-3 py-1 rounded fw-bold border border-warning" style="font-size: 0.9rem;" id="countdown-timer">
                02:45:12
            </div>
        </div>
        <span class="small text-muted">{{ $lang == 'en' ? 'Discounts applied instantly' : 'Potongan harga langsung otomatis' }}</span>
    </div>
    
    <div class="row g-4">
        @foreach($flashSales as $fs)
            @php
                $slashPrice = $fs->harga_jual;
                $disc = $fs->diskon_persen ?: 10;
                $finalPrice = $slashPrice - ($slashPrice * $disc / 100);
            @endphp
            <div class="col-md-3 col-6">
                <div class="card bg-dark border-secondary h-100 text-white p-2">
                    <span class="position-absolute badge bg-danger m-2" style="z-index: 2;">-{{ $disc }}%</span>
                    <img src="https://images.unsplash.com/photo-1578932750294-f5075e85f44a?q=80&w=300" class="card-img-top rounded img-fluid" alt="{{ $fs->nama_barang }}" style="height: 140px; object-fit: cover;">
                    <div class="card-body px-1 py-2">
                        <span class="text-warning small fw-bold">{{ $fs->brand ?: 'Unbranded' }}</span>
                        <h6 class="card-title text-white small text-truncate mb-1">{{ $fs->nama_barang }}</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-danger" style="font-size: 0.95rem;">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                            <span class="text-muted small text-decoration-line-through" style="font-size: 0.8rem;">Rp {{ number_format($slashPrice, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('buyer.detail', $fs->id) }}" class="btn btn-warning text-dark btn-sm w-100 mt-2 fw-bold" style="border-radius: 8px;">
                            {{ $lang == 'en' ? 'Buy Now' : 'Beli Sekarang' }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- AI Recommendations Section -->
@if(auth()->check() && !$aiRecommendation->isEmpty())
<div class="mb-5">
    <div class="d-flex align-items-center gap-2 mb-3">
        <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-brain text-primary me-2 animate-pulse"></i>{{ $lang == 'en' ? 'AI Recommendation for You' : 'Rekomendasi AI Untuk Anda' }}</h4>
        <span class="badge bg-primary text-white" style="font-size: 0.75rem;">AI</span>
    </div>
    <div class="row g-4">
        @foreach($aiRecommendation as $rec)
            <div class="col-md-3 col-6">
                <div class="card h-100">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=300" class="card-img-top img-fluid" alt="{{ $rec->nama_barang }}" style="height: 150px; object-fit: cover;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-primary small fw-bold">{{ $rec->brand ?: 'Preloved' }}</span>
                            <span class="badge bg-light text-success border border-success">{{ ucfirst(str_replace('_', ' ', $rec->kondisi)) }}</span>
                        </div>
                        <h6 class="card-title text-dark small text-truncate">{{ $rec->nama_barang }}</h6>
                        <span class="fw-bold text-dark">Rp {{ number_format($rec->harga_jual, 0, ',', '.') }}</span>
                        <a href="{{ route('buyer.detail', $rec->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                            {{ $lang == 'en' ? 'View Details' : 'Lihat Detail' }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Main Store Area -->
<div class="row">
    <!-- Sidebar Filters (Collapsible) -->
    <div class="col-lg-3 mb-4">
        <div class="card p-3 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-sliders me-2"></i>Filters</h5>
                <a href="{{ url('/') }}" class="text-decoration-none small text-muted">{{ $lang == 'en' ? 'Reset' : 'Reset Filter' }}</a>
            </div>
            
            <form action="{{ url('/') }}" method="GET">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <!-- Category filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">{{ $lang == 'en' ? 'Category' : 'Kategori' }}</label>
                    <select name="kategori" class="form-select form-select-sm" style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="">{{ $lang == 'en' ? 'All Categories' : 'Semua Kategori' }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('kategori') == $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">{{ $lang == 'en' ? 'Price Range' : 'Rentang Harga' }}</label>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text bg-light text-muted">Rp</span>
                        <input type="number" name="min_harga" class="form-control" placeholder="Min" value="{{ request('min_harga') }}" style="border-radius: 0 8px 8px 0;">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <span class="input-group-text bg-light text-muted">Rp</span>
                        <input type="number" name="max_harga" class="form-control" placeholder="Max" value="{{ request('max_harga') }}" style="border-radius: 0 8px 8px 0;">
                    </div>
                </div>

                <!-- Condition Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">{{ $lang == 'en' ? 'Condition' : 'Kondisi Barang' }}</label>
                    <select name="kondisi" class="form-select form-select-sm" style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="">{{ $lang == 'en' ? 'All Conditions' : 'Semua Kondisi' }}</option>
                        <option value="baru" {{ request('kondisi') == 'baru' ? 'selected' : '' }}>Baru / Tag (Like New)</option>
                        <option value="seperti_baru" {{ request('kondisi') == 'seperti_baru' ? 'selected' : '' }}>Mulus (Good)</option>
                        <option value="bekas_layak" {{ request('kondisi') == 'bekas_layak' ? 'selected' : '' }}>Layak Pakai (Fair)</option>
                        <option value="bekas" {{ request('kondisi') == 'bekas' ? 'selected' : '' }}>Kekurangan Minor (Defect)</option>
                    </select>
                </div>

                <!-- Brand Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">Brand</label>
                    <input type="text" name="brand" class="form-control form-control-sm" placeholder="e.g. Zara, Nike" value="{{ request('brand') }}" style="border-radius: 8px;">
                </div>

                <!-- Location Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">{{ $lang == 'en' ? 'Seller Location' : 'Lokasi Penjual' }}</label>
                    <input type="text" name="lokasi" class="form-control form-control-sm" placeholder="{{ $lang == 'en' ? 'e.g. Surabaya' : 'misal: Surabaya' }}" value="{{ request('lokasi') }}" style="border-radius: 8px;">
                </div>

                <!-- Color Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">{{ $lang == 'en' ? 'Color' : 'Warna' }}</label>
                    <input type="text" name="warna" class="form-control form-control-sm" placeholder="{{ $lang == 'en' ? 'e.g. Black' : 'misal: Hitam' }}" value="{{ request('warna') }}" style="border-radius: 8px;">
                </div>

                <!-- Size Filter -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">{{ $lang == 'en' ? 'Size' : 'Ukuran' }}</label>
                    <select name="ukuran" class="form-select form-select-sm" style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="">{{ $lang == 'en' ? 'All Sizes' : 'Semua Ukuran' }}</option>
                        <option value="S" {{ request('ukuran') == 'S' ? 'selected' : '' }}>S</option>
                        <option value="M" {{ request('ukuran') == 'M' ? 'selected' : '' }}>M</option>
                        <option value="L" {{ request('ukuran') == 'L' ? 'selected' : '' }}>L</option>
                        <option value="XL" {{ request('ukuran') == 'XL' ? 'selected' : '' }}>XL</option>
                        <option value="One Size" {{ request('ukuran') == 'One Size' ? 'selected' : '' }}>One Size</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm w-100 py-2">{{ $lang == 'en' ? 'Apply Filters' : 'Terapkan Filter' }}</button>
            </form>
        </div>
    </div>

    <!-- Product Grid & Sorter -->
    <div class="col-lg-9">
        <!-- Top Toolbar -->
        <div class="card p-3 mb-4 border-0 shadow-sm d-flex flex-row justify-content-between align-items-center flex-wrap gap-2" style="border-radius: 16px;">
            <div class="text-muted small">
                {{ $lang == 'en' ? 'Showing' : 'Menampilkan' }} <strong>{{ $barangs->count() }}</strong> {{ $lang == 'en' ? 'results' : 'produk preloved' }}
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted">{{ $lang == 'en' ? 'Sort By:' : 'Urutkan:' }}</span>
                <form action="{{ url('/') }}" method="GET" id="sortForm">
                    <!-- Carry filters -->
                    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                    @if(request('kategori')) <input type="hidden" name="kategori" value="{{ request('kategori') }}"> @endif
                    @if(request('kondisi')) <input type="hidden" name="kondisi" value="{{ request('kondisi') }}"> @endif
                    @if(request('min_harga')) <input type="hidden" name="min_harga" value="{{ request('min_harga') }}"> @endif
                    @if(request('max_harga')) <input type="hidden" name="max_harga" value="{{ request('max_harga') }}"> @endif
                    @if(request('brand')) <input type="hidden" name="brand" value="{{ request('brand') }}"> @endif
                    @if(request('lokasi')) <input type="hidden" name="lokasi" value="{{ request('lokasi') }}"> @endif
                    @if(request('warna')) <input type="hidden" name="warna" value="{{ request('warna') }}"> @endif
                    @if(request('ukuran')) <input type="hidden" name="ukuran" value="{{ request('ukuran') }}"> @endif

                    <select name="sort" class="form-select form-select-sm" style="border-radius: 8px;" onchange="this.form.submit()">
                        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>{{ $lang == 'en' ? 'Newest Arrivals' : 'Produk Terbaru' }}</option>
                        <option value="harga_termurah" {{ request('sort') == 'harga_termurah' ? 'selected' : '' }}>{{ $lang == 'en' ? 'Price: Low to High' : 'Harga Termurah' }}</option>
                        <option value="harga_termahal" {{ request('sort') == 'harga_termahal' ? 'selected' : '' }}>{{ $lang == 'en' ? 'Price: High to Low' : 'Harga Termahal' }}</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Main Product Grid -->
        @if($barangs->isEmpty())
            <div class="card border-0 shadow-sm p-5 text-center" style="border-radius: 16px;">
                <i class="fas fa-box-open fs-1 text-muted mb-3"></i>
                <h5 class="fw-bold mb-1">{{ $lang == 'en' ? 'No items found' : 'Barang tidak ditemukan' }}</h5>
                <p class="text-muted small mb-0">{{ $lang == 'en' ? 'Try adjusting your search filters or browse other categories.' : 'Coba ubah kata kunci pencarian Anda atau telusuri kategori lainnya.' }}</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($barangs as $b)
                    <div class="col-md-4 col-6">
                        <div class="card h-100 border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; position: relative;">
                            <!-- Condition badge -->
                            <span class="position-absolute m-2 badge @if($b->kondisi == 'baru') bg-success @elseif($b->kondisi == 'seperti_baru') bg-info @elseif($b->kondisi == 'bekas_layak') bg-warning text-dark @else bg-danger @endif" style="top: 0; left: 0; z-index: 10;">
                                @if($b->kondisi == 'baru') Like New @elseif($b->kondisi == 'seperti_baru') Good @elseif($b->kondisi == 'bekas_layak') Fair @else Defect @endif
                            </span>

                            <!-- Wishlist Toggle (like icon) -->
                            <form action="{{ route('buyer.wishlist.toggle', $b->id) }}" method="POST" class="position-absolute" style="top: 8px; right: 8px; z-index: 10;">
                                @csrf
                                <button type="submit" class="btn btn-light rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center border-0" style="width: 32px; height: 32px; color: var(--accent);">
                                    @if(auth()->check() && \App\Models\Wishlist::where('user_id', auth()->id())->where('barang_id', $b->id)->exists())
                                        <i class="fas fa-heart text-danger"></i>
                                    @else
                                        <i class="far fa-heart"></i>
                                    @endif
                                </button>
                            </form>

                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=300" class="card-img-top img-fluid" alt="{{ $b->nama_barang }}" style="height: 180px; object-fit: cover;">
                            <div class="card-body p-3 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-primary small fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $b->brand ?: 'Preloved' }}</span>
                                    <span class="text-muted small" style="font-size: 0.75rem;"><i class="fas fa-location-dot me-1"></i>{{ $b->lokasi ?: 'Surabaya' }}</span>
                                </div>
                                <h6 class="card-title text-dark fw-bold mb-2 text-truncate" style="font-size: 0.95rem;">{{ $b->nama_barang }}</h6>
                                <div class="mt-auto pt-2 border-top">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span class="fw-bold text-dark" style="font-size: 1.05rem;">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</span>
                                        <a href="{{ route('buyer.detail', $b->id) }}" class="btn btn-sm btn-primary py-1 px-3" style="border-radius: 8px;">
                                            <i class="fas fa-eye me-1"></i>{{ $lang == 'en' ? 'Details' : 'Detail' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Recently Viewed Section -->
@if(auth()->check() && !$recentlyViewed->isEmpty())
<div class="mt-5 pt-4 border-top">
    <h4 class="fw-bold mb-4 text-dark"><i class="fas fa-clock-rotate-left text-muted me-2"></i>{{ $lang == 'en' ? 'Recently Viewed Products' : 'Produk Terakhir Dilihat' }}</h4>
    <div class="row g-4">
        @foreach($recentlyViewed as $rv)
            <div class="col-6 col-md-3 col-lg-2">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                    <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=150" class="card-img-top img-fluid" alt="{{ $rv->nama_barang }}" style="height: 100px; object-fit: cover;">
                    <div class="card-body p-2 text-center">
                        <h6 class="text-dark small text-truncate mb-1" style="font-size: 0.8rem;">{{ $rv->nama_barang }}</h6>
                        <span class="fw-bold text-primary small d-block" style="font-size: 0.85rem;">Rp {{ number_format($rv->harga_jual, 0, ',', '.') }}</span>
                        <a href="{{ route('buyer.detail', $rv->id) }}" class="btn btn-outline-primary btn-xs w-100 mt-2 py-0" style="font-size: 0.75rem;">{{ $lang == 'en' ? 'View' : 'Lihat' }}</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<script>
    // Countdown Timer logic
    const timerElement = document.getElementById('countdown-timer');
    if (timerElement) {
        let hours = 2, minutes = 45, seconds = 12;
        setInterval(() => {
            seconds--;
            if (seconds < 0) {
                seconds = 59;
                minutes--;
                if (minutes < 0) {
                    minutes = 59;
                    hours--;
                    if (hours < 0) {
                        hours = 2; // Reset cycle
                    }
                }
            }
            const hStr = String(hours).padStart(2, '0');
            const mStr = String(minutes).padStart(2, '0');
            const sStr = String(seconds).padStart(2, '0');
            timerElement.textContent = `${hStr}:${mStr}:${sStr}`;
        }, 1000);
    }
</script>
@endsection
