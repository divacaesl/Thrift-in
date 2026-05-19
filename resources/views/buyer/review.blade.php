@extends('layouts.buyer')

@section('title', 'Tulis Ulasan Pembeli - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<div class="row justify-content-center py-5">
    <div class="col-md-8 col-lg-6">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body">
                <h4 class="fw-bold text-dark mb-1"><i class="far fa-star text-warning me-2"></i>{{ $lang == 'en' ? 'Write Product Review' : 'Berikan Ulasan Barang' }}</h4>
                <p class="text-muted small mb-4">{{ $lang == 'en' ? 'Your feedback helps other preloved buyers make safe choices!' : 'Feedback Anda sangat membantu pembeli lain dalam memilih barang preloved!' }}</p>

                <!-- Product Summary Card -->
                <div class="p-3 mb-4 rounded-4 bg-light border d-flex align-items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=100" class="rounded border" alt="Product thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                    <div>
                        <strong class="text-dark small d-block">{{ $transaksi->barang->nama_barang }}</strong>
                        <span class="text-muted small">{{ $lang == 'en' ? 'Seller:' : 'Penjual:' }} {{ $transaksi->barang->penitip->nama }}</span>
                    </div>
                </div>

                <form action="{{ route('buyer.review.store', $transaksi->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- 1. General Star Rating -->
                    <div class="mb-4 text-center">
                        <label class="form-label fw-bold text-dark small d-block mb-2">{{ $lang == 'en' ? 'Overall Satisfaction' : 'Kepuasan Keseluruhan' }}</label>
                        <div class="d-flex justify-content-center gap-2 fs-3 text-secondary" id="star-rating-selector">
                            <i class="far fa-star rating-star pointer" data-value="1" onclick="setRatingValue(1)"></i>
                            <i class="far fa-star rating-star pointer" data-value="2" onclick="setRatingValue(2)"></i>
                            <i class="far fa-star rating-star pointer" data-value="3" onclick="setRatingValue(3)"></i>
                            <i class="far fa-star rating-star pointer" data-value="4" onclick="setRatingValue(4)"></i>
                            <i class="far fa-star rating-star pointer" data-value="5" onclick="setRatingValue(5)"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" required value="">
                    </div>

                    <!-- 2. Breakdown Ratings -->
                    <div class="p-3 border rounded-4 mb-4 bg-light">
                        <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">{{ $lang == 'en' ? 'Specific Seller Ratings' : 'Penilaian Khusus Seller' }}</h6>
                        
                        <div class="row align-items-center mb-3">
                            <div class="col-6">
                                <span class="small text-muted">{{ $lang == 'en' ? 'Response Speed' : 'Kecepatan Respon' }}</span>
                            </div>
                            <div class="col-6">
                                <div class="d-flex gap-1 fs-5 text-secondary" id="respon-rating-selector">
                                    <i class="far fa-star star-respon pointer" data-value="1" onclick="setSubRating('respon', 1)"></i>
                                    <i class="far fa-star star-respon pointer" data-value="2" onclick="setSubRating('respon', 2)"></i>
                                    <i class="far fa-star star-respon pointer" data-value="3" onclick="setSubRating('respon', 3)"></i>
                                    <i class="far fa-star star-respon pointer" data-value="4" onclick="setSubRating('respon', 4)"></i>
                                    <i class="far fa-star star-respon pointer" data-value="5" onclick="setSubRating('respon', 5)"></i>
                                </div>
                                <input type="hidden" name="respon_rate" id="responValue" required value="">
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-6">
                                <span class="small text-muted">{{ $lang == 'en' ? 'Shipment Speed' : 'Kecepatan Kirim' }}</span>
                            </div>
                            <div class="col-6">
                                <div class="d-flex gap-1 fs-5 text-secondary" id="kirim-rating-selector">
                                    <i class="far fa-star star-kirim pointer" data-value="1" onclick="setSubRating('kirim', 1)"></i>
                                    <i class="far fa-star star-kirim pointer" data-value="2" onclick="setSubRating('kirim', 2)"></i>
                                    <i class="far fa-star star-kirim pointer" data-value="3" onclick="setSubRating('kirim', 3)"></i>
                                    <i class="far fa-star star-kirim pointer" data-value="4" onclick="setSubRating('kirim', 4)"></i>
                                    <i class="far fa-star star-kirim pointer" data-value="5" onclick="setSubRating('kirim', 5)"></i>
                                </div>
                                <input type="hidden" name="kirim_rate" id="kirimValue" required value="">
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-6">
                                <span class="small text-muted">{{ $lang == 'en' ? 'Conformity of items' : 'Kesesuaian Barang' }}</span>
                            </div>
                            <div class="col-6">
                                <div class="d-flex gap-1 fs-5 text-secondary" id="sesuai-rating-selector">
                                    <i class="far fa-star star-sesuai pointer" data-value="1" onclick="setSubRating('sesuai', 1)"></i>
                                    <i class="far fa-star star-sesuai pointer" data-value="2" onclick="setSubRating('sesuai', 2)"></i>
                                    <i class="far fa-star star-sesuai pointer" data-value="3" onclick="setSubRating('sesuai', 3)"></i>
                                    <i class="far fa-star star-sesuai pointer" data-value="4" onclick="setSubRating('sesuai', 4)"></i>
                                    <i class="far fa-star star-sesuai pointer" data-value="5" onclick="setSubRating('sesuai', 5)"></i>
                                </div>
                                <input type="hidden" name="sesuai_rate" id="sesuaiValue" required value="">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Written Review Comment -->
                    <div class="mb-3">
                        <label for="ulasan" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Write Comments' : 'Tulis Ulasan / Komentar' }}</label>
                        <textarea name="ulasan" id="ulasan" class="form-control" rows="3" placeholder="{{ $lang == 'en' ? 'Explain what you like about the product condition...' : 'Jelaskan mengapa Anda menyukai kondisi barang ini...' }}" style="border-radius: 10px;"></textarea>
                    </div>

                    <!-- 4. Photo Uploader -->
                    <div class="mb-4">
                        <label for="foto" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Add Photo (Optional)' : 'Tambahkan Foto Barang (Opsional)' }}</label>
                        <input class="form-control form-control-sm" type="file" id="foto" name="foto" accept="image/*" style="border-radius: 8px;">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">
                        {{ $lang == 'en' ? 'Submit Review' : 'Kirim Penilaian Ulasan' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .pointer {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .pointer:hover {
        transform: scale(1.2);
    }
</style>
@endpush

@push('scripts')
<script>
    function setRatingValue(val) {
        document.getElementById('ratingValue').value = val;
        
        // Update general stars classes
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(star => {
            const starVal = parseInt(star.getAttribute('data-value'));
            if (starVal <= val) {
                star.className = 'fas fa-star rating-star pointer text-warning';
            } else {
                star.className = 'far fa-star rating-star pointer';
            }
        });
    }

    function setSubRating(type, val) {
        document.getElementById(type + 'Value').value = val;
        
        // Update sub stars classes
        const stars = document.querySelectorAll('.star-' + type);
        stars.forEach(star => {
            const starVal = parseInt(star.getAttribute('data-value'));
            if (starVal <= val) {
                star.className = 'fas fa-star star-' + type + ' pointer text-warning';
            } else {
                star.className = 'far fa-star star-' + type + ' pointer';
            }
        });
    }
</script>
@endpush
@endsection
