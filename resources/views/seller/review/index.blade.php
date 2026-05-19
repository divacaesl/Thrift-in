@extends('layouts.seller')

@section('title', 'Ulasan Pembeli - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<!-- Ratings Statistics Banner (Section 10) -->
<div class="row g-4 mb-4">
    <!-- Star Rating -->
    <div class="col-md-3">
        <div class="card p-4 text-center h-100 justify-content-center">
            <h6 class="text-muted mb-2 small">{{ $lang == 'en' ? 'Average Rating' : 'Rating Rata-rata Toko' }}</h6>
            <h1 class="fw-bold text-warning mb-2">{{ number_format($averageRating, 1) }}<span class="fs-6 text-muted">/5</span></h1>
            <div>
                @for($i=1; $i<=5; $i++)
                    <i class="fas fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-secondary opacity-25' }}"></i>
                @endfor
            </div>
        </div>
    </div>
    
    <!-- Responsibility Rate -->
    <div class="col-md-3">
        <div class="card p-4 text-center h-100 justify-content-center">
            <h6 class="text-muted mb-2 small">{{ $lang == 'en' ? 'Response Speed' : 'Kecepatan Respon Chat' }}</h6>
            <h2 class="fw-bold text-success">{{ number_format($averageRespon, 1) }}<span class="fs-6 text-muted">/5</span></h2>
            <small class="text-muted">Berdasarkan ulasan pembeli</small>
        </div>
    </div>

    <!-- Shipping Rate -->
    <div class="col-md-3">
        <div class="card p-4 text-center h-100 justify-content-center">
            <h6 class="text-muted mb-2 small">{{ $lang == 'en' ? 'Delivery Speed' : 'Kecepatan Pengemasan' }}</h6>
            <h2 class="fw-bold text-primary">{{ number_format($averageKirim, 1) }}<span class="fs-6 text-muted">/5</span></h2>
            <small class="text-muted">Berdasarkan ulasan pembeli</small>
        </div>
    </div>

    <!-- Item Correctness Rate -->
    <div class="col-md-3">
        <div class="card p-4 text-center h-100 justify-content-center">
            <h6 class="text-muted mb-2 small">{{ $lang == 'en' ? 'Item Correctness' : 'Kesesuaian Deskripsi' }}</h6>
            <h2 class="fw-bold text-info">{{ number_format($averageSesuai, 1) }}<span class="fs-6 text-muted">/5</span></h2>
            <small class="text-muted">Kesesuaian foto dan minus barang</small>
        </div>
    </div>
</div>

<!-- Reviews list -->
<div class="card p-4">
    <h5 class="fw-bold mb-4"><i class="fas fa-star text-warning me-2"></i>{{ $lang == 'en' ? 'Customer Feedback' : 'Daftar Ulasan & Penilaian' }}</h5>

    <div class="list-group list-group-flush">
        @if($reviews->isEmpty())
            <div class="text-center py-5 text-muted small">Belum ada ulasan untuk barang Anda.</div>
        @else
            @foreach($reviews as $rev)
                <div class="list-group-item py-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ asset('uploads/profiles/' . $rev->user->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" alt="profile" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $rev->user->nama }}</h6>
                                <small class="text-muted">Membeli: <strong>{{ $rev->barang->nama_barang }}</strong></small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div>
                                @for($i=1; $i<=5; $i++)
                                    <i class="fas fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-secondary opacity-25' }}" style="font-size: 0.8rem;"></i>
                                @endfor
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $rev->created_at->format('d M Y') }}</small>
                        </div>
                    </div>

                    <p class="mb-3 text-dark small" style="line-height: 1.5;">{!! nl2br(e($rev->ulasan)) !!}</p>

                    <!-- Breakdown criteria ratings -->
                    <div class="d-flex gap-4 mb-3 small text-secondary bg-light p-2 rounded-3" style="font-size: 0.75rem;">
                        <div>Chat Respon: <strong class="text-dark">{{ $rev->respon_rate }}/5</strong></div>
                        <div>Kemasan/Kirim: <strong class="text-dark">{{ $rev->kirim_rate }}/5</strong></div>
                        <div>Kesesuaian: <strong class="text-dark">{{ $rev->sesuai_rate }}/5</strong></div>
                    </div>

                    <!-- Seller Reply block -->
                    @if($rev->balasan_penjual)
                        <div class="p-3 bg-light border-start border-primary border-3 rounded-3 mb-2" style="font-size: 0.85rem;">
                            <span class="fw-bold text-primary d-block mb-1"><i class="fas fa-reply me-1"></i> Balasan Anda (Toko):</span>
                            <span class="text-dark">{{ $rev->balasan_penjual }}</span>
                        </div>
                    @else
                        <!-- Reply trigger Form -->
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-primary py-1 px-3 small" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm{{ $rev->id }}">
                                <i class="fas fa-comment me-1"></i> Balas Ulasan
                            </button>
                            
                            <div class="collapse mt-2" id="replyForm{{ $rev->id }}">
                                <form action="{{ route('seller.review.reply', $rev->id) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="text" name="balasan_penjual" class="form-control form-control-sm" placeholder="Ketik balasan Anda ke pembeli..." required>
                                    <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
