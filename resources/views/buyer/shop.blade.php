@extends('layouts.buyer')

@section('title', $penitip->nama . ' - ThriftIn Store')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="container py-4">
    <!-- Store Header Card -->
    <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 20px;">
        <!-- Banner -->
        <div style="height: 220px; background-color: #E2E8F0; position: relative;">
            <img src="{{ asset('uploads/shops/' . $penitip->banner_toko) }}" onerror="this.src='https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=1200&q=80'" alt="banner" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        
        <!-- Logo and Info row -->
        <div class="p-4 bg-white" style="position: relative;">
            <div class="d-flex flex-column flex-md-row align-items-center gap-4 text-center text-md-start" style="margin-top: -80px;">
                <div class="rounded-circle overflow-hidden border border-4 border-white shadow-sm bg-white" style="width: 120px; height: 120px; position: relative; z-index: 10;">
                    <img src="{{ asset('uploads/shops/' . $penitip->logo_toko) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/shop.svg'" alt="logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <div class="flex-grow-1 mt-md-5">
                    <h3 class="fw-bold mb-1 text-dark">{{ $penitip->nama }}</h3>
                    <p class="text-muted mb-2"><i class="fas fa-location-dot text-primary me-1"></i> {{ $penitip->alamat }}</p>
                    
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 align-items-center small">
                        <div>
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                            @endfor
                            <strong class="text-dark ms-1">{{ number_format($avgRating, 1) }}</strong>
                        </div>
                        <div class="text-muted">|</div>
                        <div><strong class="text-dark">{{ $followersCount }}</strong> {{ $lang == 'en' ? 'Followers' : 'Pengikut' }}</div>
                        <div class="text-muted">|</div>
                        <div><strong class="text-dark">{{ $totalProducts }}</strong> {{ $lang == 'en' ? 'Active Listings' : 'Barang Dijual' }}</div>
                    </div>
                </div>

                <div class="mt-3 mt-md-5">
                    <form action="{{ route('buyer.follow-seller', $penitip->id) }}" method="POST">
                        @csrf
                        @if($isFollowing)
                            <button type="submit" class="btn btn-outline-primary px-4 rounded-pill fw-semibold">
                                <i class="fas fa-user-check me-1"></i> {{ $lang == 'en' ? 'Following' : 'Mengikuti' }}
                            </button>
                        @else
                            <button type="submit" class="btn btn-primary px-4 rounded-pill fw-semibold">
                                <i class="fas fa-user-plus me-1"></i> {{ $lang == 'en' ? 'Follow Shop' : 'Ikuti Toko' }}
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-4 pt-3 border-top">
                <h6 class="fw-bold text-dark mb-2">{{ $lang == 'en' ? 'About Shop' : 'Deskripsi Toko' }}</h6>
                <p class="text-muted mb-0 small" style="line-height: 1.6;">
                    {{ $penitip->deskripsi_toko ?? ($lang == 'en' ? 'No store description available.' : 'Belum ada deskripsi toko.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Listings Grid -->
    <h4 class="fw-bold text-dark mb-4 mt-5"><i class="fas fa-boxes-stacked text-primary me-2"></i>{{ $lang == 'en' ? 'All Products Catalog' : 'Semua Katalog Produk' }}</h4>
    
    <div class="row g-4">
        @if($barangs->isEmpty())
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-box-open fs-2 mb-2"></i>
                <p class="mb-0 small">{{ $lang == 'en' ? 'This shop has no products for sale yet.' : 'Toko ini belum memiliki barang yang dijual.' }}</p>
            </div>
        @else
            @foreach($barangs as $b)
                <div class="col-6 col-md-3">
                    <div class="card border-0 shadow-sm h-100 overflow-hidden" style="border-radius: 16px;">
                        <a href="{{ route('buyer.detail', $b->id) }}">
                            <img src="{{ asset('uploads/products/' . $b->foto) }}" onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=300&q=80'" alt="product" class="card-img-top" style="height: 180px; object-fit: cover;">
                        </a>
                        <div class="p-3">
                            <span class="badge bg-secondary p-1 mb-2" style="font-size: 0.65rem;">{{ strtoupper($b->kondisi) }}</span>
                            <a href="{{ route('buyer.detail', $b->id) }}" class="text-decoration-none text-dark">
                                <h6 class="text-truncate fw-bold mb-1">{{ $b->nama_barang }}</h6>
                            </a>
                            <div class="text-primary fw-bold">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</div>
                            <div class="text-muted small mt-2" style="font-size: 0.75rem;"><i class="fas fa-location-dot me-1"></i>{{ $b->lokasi }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
