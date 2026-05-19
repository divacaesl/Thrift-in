@extends('layouts.buyer')

@section('title', $barang->nama_barang . ' - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
    $pics = explode(',', $barang->multiple_fotos ?: $barang->foto);
@endphp

<!-- Back link -->
<div class="mb-4">
    <a href="{{ url('/') }}" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i>{{ $lang == 'en' ? 'Back to products' : 'Kembali ke produk' }}</a>
</div>

<div class="row g-5 mb-5">
    <!-- Image Gallery Section -->
    <div class="col-md-6">
        <div id="productGallery" class="carousel slide mb-3 shadow-sm rounded-4 overflow-hidden border" data-bs-ride="false">
            <div class="carousel-inner">
                @foreach($pics as $index => $pic)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=600" class="d-block w-100 img-fluid" alt="Product Image {{ $index + 1 }}" style="height: 400px; object-fit: cover;">
                    </div>
                @endforeach
            </div>
            @if(count($pics) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#productGallery" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productGallery" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            @endif
        </div>
        
        <!-- Thumbnails -->
        @if(count($pics) > 1)
            <div class="d-flex gap-2 justify-content-center">
                @foreach($pics as $index => $pic)
                    <button type="button" data-bs-target="#productGallery" data-bs-slide-to="{{ $index }}" class="btn p-0 border rounded overflow-hidden {{ $index == 0 ? 'border-primary' : '' }}" style="width: 60px; height: 60px;">
                        <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=150" class="img-fluid" alt="thumb" style="width: 100%; height: 100%; object-fit: cover;">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Product Info Section -->
    <div class="col-md-6">
        <span class="badge @if($barang->kondisi == 'baru') bg-success @elseif($barang->kondisi == 'seperti_baru') bg-info @elseif($barang->kondisi == 'bekas_layak') bg-warning text-dark @else bg-danger @endif mb-2" style="font-size: 0.85rem;">
            @if($barang->kondisi == 'baru') Baru / Like New @elseif($barang->kondisi == 'seperti_baru') Mulus / Good @elseif($barang->kondisi == 'bekas_layak') Layak / Fair @else Defect @endif
        </span>
        
        <h2 class="fw-bold text-dark mb-1">{{ $barang->nama_barang }}</h2>
        <p class="text-primary fw-bold text-uppercase mb-3">{{ $barang->brand ?: 'Preloved Brand' }}</p>

        <!-- Price Panel -->
        <div class="p-3 mb-4 rounded-4 bg-light border d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small d-block">{{ $lang == 'en' ? 'Price' : 'Harga Penawaran' }}</span>
                @php
                    // Check if there is an accepted negotiated price in the session
                    $negotiatedPrice = session()->get('nego_price_' . $barang->id);
                @endphp
                @if($negotiatedPrice)
                    <span class="fs-3 fw-bold text-success">Rp {{ number_format($negotiatedPrice, 0, ',', '.') }}</span>
                    <span class="text-muted small text-decoration-line-through d-block">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</span>
                    <span class="badge bg-success-subtle text-success border border-success small ms-2">{{ $lang == 'en' ? 'Negotiation Deal' : 'Harga Kesepakatan' }}</span>
                @else
                    <span class="fs-3 fw-bold text-dark">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</span>
                @endif
            </div>
            
            @if(!$negotiatedPrice)
                <!-- Offer triggering modal -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#offerModal">
                    <i class="fas fa-comments-dollar me-2"></i>{{ $lang == 'en' ? 'Negotiate' : 'Tawar Harga' }}
                </button>
            @endif
        </div>

        <!-- Specifications Grid -->
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="p-2 border rounded-3 text-center bg-white">
                    <span class="text-muted small d-block">{{ $lang == 'en' ? 'Size' : 'Ukuran' }}</span>
                    <strong class="text-dark">{{ $barang->ukuran ?: 'One Size' }}</strong>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 border rounded-3 text-center bg-white">
                    <span class="text-muted small d-block">{{ $lang == 'en' ? 'Color' : 'Warna' }}</span>
                    <strong class="text-dark">{{ $barang->warna ?: '-' }}</strong>
                </div>
            </div>
            <div class="col-4">
                <div class="p-2 border rounded-3 text-center bg-white">
                    <span class="text-muted small d-block">{{ $lang == 'en' ? 'Location' : 'Lokasi' }}</span>
                    <strong class="text-dark">{{ $barang->lokasi ?: 'Surabaya' }}</strong>
                </div>
            </div>
        </div>

        <!-- Consignor / Seller Info -->
        <div class="card p-3 border-0 bg-white mb-4 shadow-sm" style="border-radius: 12px;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary border" style="width: 48px; height: 48px;">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $barang->penitip->nama }}</h6>
                        <span class="text-muted small"><i class="fas fa-location-dot me-1"></i>{{ $barang->penitip->alamat }}</span>
                    </div>
                </div>
                <!-- Follow button -->
                <form action="{{ route('buyer.seller.follow', $barang->penitip->id) }}" method="POST">
                    @csrf
                    @php
                        $isFollowing = auth()->check() && \App\Models\Follow::where('follower_id', auth()->id())->where('penitip_id', $barang->penitip->id)->exists();
                    @endphp
                    <button type="submit" class="btn btn-sm {{ $isFollowing ? 'btn-light' : 'btn-outline-primary' }}">
                        {{ $isFollowing ? ($lang == 'en' ? 'Following' : 'Diikuti') : ($lang == 'en' ? 'Follow Store' : 'Ikuti Toko') }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Call to Actions -->
        <div class="d-flex gap-3 mb-4">
            <!-- Tanya Penjual -->
            <a href="{{ route('buyer.chat', ['barang_id' => $barang->id]) }}" class="btn btn-outline-primary flex-grow-1 py-2">
                <i class="fas fa-comment-dots me-2"></i>{{ $lang == 'en' ? 'Ask Seller' : 'Tanya Penjual' }}
            </a>
            
            <!-- Add to Cart Form -->
            <form action="{{ route('buyer.cart.add') }}" method="POST" class="flex-grow-2">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-shopping-cart me-2"></i>{{ $lang == 'en' ? 'Add to Cart' : 'Beli / Keranjang' }}
                </button>
            </form>
        </div>

        <!-- Shipping Calculator Widget -->
        <div class="card p-3 border border-dashed mb-4">
            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-truck-fast text-primary me-2"></i>{{ $lang == 'en' ? 'Shipping Estimation' : 'Estimasi Ongkos Kirim' }}</h6>
            <div class="row g-2 mb-2">
                <div class="col-8">
                    <input type="text" class="form-control form-control-sm" id="shipToCity" placeholder="{{ $lang == 'en' ? 'Enter city (e.g. Jakarta)' : 'Kota tujuan (misal: Jakarta)' }}">
                </div>
                <div class="col-4">
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="calculateShipping()">Hitung</button>
                </div>
            </div>
            <div id="shippingResult" class="small text-muted mt-2" style="display: none;"></div>
        </div>
    </div>
</div>

<!-- Bottom Tabs (Description & Reviews) -->
<ul class="nav nav-tabs mb-4" id="detailTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold text-dark" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-pane" type="button" role="tab">{{ $lang == 'en' ? 'Description' : 'Deskripsi Barang' }}</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-dark" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab">{{ $lang == 'en' ? 'Reviews' : 'Ulasan' }} ({{ $totalReviews }})</button>
    </li>
</ul>

<div class="tab-content" id="detailTabsContent">
    <!-- Description Pane -->
    <div class="tab-pane fade show active" id="description-pane" role="tabpanel">
        <div class="bg-white p-4 border rounded-4 shadow-sm mb-5">
            <h5 class="fw-bold mb-3">{{ $lang == 'en' ? 'Product Description' : 'Keterangan Barang' }}</h5>
            <p class="text-dark mb-4">{{ $barang->deskripsi ?: ($lang == 'en' ? 'No description available.' : 'Tidak ada deskripsi barang.') }}</p>
            
            <h6 class="fw-bold text-dark mb-2">{{ $lang == 'en' ? 'Important Notes' : 'Catatan Khusus Penitip' }}</h6>
            <div class="p-3 bg-light rounded border-start border-warning border-4 text-muted small">
                {{ $barang->catatan ?: ($lang == 'en' ? 'Preloved items are unique, sold as is. Inspect condition details carefully.' : 'Barang preloved bersifat unik, dijual apa adanya. Mohon teliti ulasan dan kondisi foto sebelum membeli.') }}
            </div>
        </div>
    </div>

    <!-- Reviews Pane -->
    <div class="tab-pane fade" id="reviews-pane" role="tabpanel">
        <div class="bg-white p-4 border rounded-4 shadow-sm mb-5">
            <!-- Review Summary Stats -->
            <div class="row align-items-center g-4 mb-4 border-bottom pb-4">
                <div class="col-md-4 text-center border-end">
                    <h1 class="fw-extrabold text-warning mb-1" style="font-size: 3.5rem;">{{ number_format($avgRating, 1) }}</h1>
                    <div class="text-warning mb-2 fs-5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="text-muted small">{{ $totalReviews }} {{ $lang == 'en' ? 'verified ratings' : 'ulasan pembeli' }}</span>
                </div>
                
                <!-- Detailed metrics -->
                <div class="col-md-8 px-lg-4">
                    <h6 class="fw-bold text-dark mb-3">{{ $lang == 'en' ? 'Seller Ratings Breakdown' : 'Rincian Penilaian Seller' }}</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <span class="small text-muted d-block">{{ $lang == 'en' ? 'Response Speed' : 'Kecepatan Respon' }}</span>
                            <div class="text-warning small">
                                <i class="fas fa-star"></i> <strong class="text-dark">4.9 / 5</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <span class="small text-muted d-block">{{ $lang == 'en' ? 'Shipment speed' : 'Kecepatan Pengiriman' }}</span>
                            <div class="text-warning small">
                                <i class="fas fa-star"></i> <strong class="text-dark">4.8 / 5</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <span class="small text-muted d-block">{{ $lang == 'en' ? 'Conformity of items' : 'Kesesuaian Deskripsi' }}</span>
                            <div class="text-warning small">
                                <i class="fas fa-star"></i> <strong class="text-dark">4.9 / 5</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Comments list -->
            @if($barang->ulasans->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="far fa-star fs-2 mb-2"></i>
                    <p class="small mb-0">{{ $lang == 'en' ? 'No reviews yet. Be the first to buy and review!' : 'Belum ada ulasan pembeli. Jadilah yang pertama membeli!' }}</p>
                </div>
            @else
                @foreach($barang->ulasans as $ul)
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $ul->user->nama }}</span>
                                <span class="badge bg-light text-muted border small">Verified Buyer</span>
                            </div>
                            <span class="text-muted small" style="font-size: 0.75rem;">{{ $ul->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="text-warning small mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $ul->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-dark small mb-2">{{ $ul->ulasan }}</p>
                        @if($ul->foto)
                            <img src="{{ asset($ul->foto) }}" class="rounded img-fluid" alt="Ulasan Foto" style="max-height: 120px;">
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Nego / Offer Modal -->
<div class="modal fade" id="offerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">{{ $lang == 'en' ? 'Offer Price Negotiation' : 'Nego Penawaran Harga' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('buyer.chat.offer') }}" method="POST">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                <div class="modal-body py-4">
                    <p class="text-muted small mb-4">{{ $lang == 'en' ? 'Submit your target price. Our simulated seller system will evaluate your offer immediately!' : 'Masukkan target harga penawaran Anda. Sistem penjual simulasi kami akan langsung menentukan persetujuan!' }}</p>
                    
                    <div class="mb-3">
                        <span class="small text-muted d-block mb-1">{{ $lang == 'en' ? 'Original Price' : 'Harga Asli' }}</span>
                        <strong class="fs-5 text-dark">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</strong>
                    </div>

                    <div class="mb-3">
                        <label for="harga_tawaran" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Your Offer Price' : 'Harga Penawaran Anda' }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="number" name="harga_tawaran" class="form-control" id="harga_tawaran" min="1" required placeholder="{{ $barang->harga_jual - 10000 }}" style="border-radius: 0 10px 10px 0;">
                        </div>
                        <span class="text-muted small mt-1 d-block" style="font-size: 0.75rem;"><i class="fas fa-circle-info me-1"></i>{{ $lang == 'en' ? 'Offers above 85% of original price are usually accepted.' : 'Penawaran di atas 85% dari harga asli biasanya langsung disetujui.' }}</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">{{ $lang == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">{{ $lang == 'en' ? 'Submit Offer' : 'Ajukan Tawaran' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function calculateShipping() {
        const cityInput = document.getElementById('shipToCity').value.trim();
        const resDiv = document.getElementById('shippingResult');
        
        if (!cityInput) {
            alert('{{ $lang == "en" ? "Please enter a destination city" : "Mohon masukkan kota tujuan" }}');
            return;
        }

        resDiv.style.display = 'block';
        resDiv.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Calculating...';

        setTimeout(() => {
            let cost = 12000;
            if (cityInput.toLowerCase().includes('jakarta') || cityInput.toLowerCase().includes('bandung')) {
                cost = 19000;
            } else if (cityInput.toLowerCase().includes('surabaya')) {
                cost = 9000;
            } else {
                cost = 25000;
            }

            resDiv.innerHTML = `
                <div class="border rounded p-2 bg-light">
                    <div class="d-flex justify-content-between mb-1">
                        <strong>JNE Express:</strong> <span>Rp ${cost.toLocaleString('id-ID')} (2-3 hari)</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <strong>J&T Regular:</strong> <span>Rp ${(cost - 2000).toLocaleString('id-ID')} (2-4 hari)</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>AnterAja Eco:</strong> <span>Rp ${(cost - 3000).toLocaleString('id-ID')} (3-5 hari)</span>
                    </div>
                </div>
            `;
        }, 800);
    }
</script>
@endsection
