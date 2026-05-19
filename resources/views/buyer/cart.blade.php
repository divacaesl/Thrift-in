@extends('layouts.buyer')

@section('title', 'Keranjang Belanja - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<h3 class="fw-bold mb-4 text-dark"><i class="fas fa-shopping-cart text-primary me-2"></i>{{ $lang == 'en' ? 'Shopping Cart' : 'Keranjang Belanja' }}</h3>

<div class="row g-4">
    <!-- Active Cart Items list -->
    <div class="col-lg-8">
        <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-4">{{ $lang == 'en' ? 'Active Items' : 'Barang di Keranjang' }}</h5>
            
            @if($cartItems->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-cart-shopping fs-1 mb-3"></i>
                    <h6>{{ $lang == 'en' ? 'Your cart is empty' : 'Keranjang Anda kosong' }}</h6>
                    <p class="small mb-3">{{ $lang == 'en' ? 'Explore our vintage preloved collection to add items.' : 'Jelajahi barang vintage pilihan untuk ditambahkan di sini.' }}</p>
                    <a href="{{ url('/') }}" class="btn btn-primary btn-sm px-4" style="border-radius: 10px;">{{ $lang == 'en' ? 'Shop Now' : 'Belanja Sekarang' }}</a>
                </div>
            @else
                @php
                    $subtotal = 0;
                @endphp
                @foreach($cartItems as $item)
                    @php
                        // Check if negotiated price exists
                        $negotiatedPrice = session()->get('nego_price_' . $item->barang->id);
                        $itemPrice = $negotiatedPrice ?: $item->barang->harga_jual;
                        $subtotal += $itemPrice * $item->quantity;
                    @endphp
                    <div class="row align-items-center mb-3 pb-3 border-bottom g-3">
                        <div class="col-3 col-md-2">
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=150" class="img-fluid rounded" alt="{{ $item->barang->nama_barang }}" style="height: 70px; object-fit: cover;">
                        </div>
                        <div class="col-9 col-md-4">
                            <span class="text-primary small fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $item->barang->brand ?: 'Preloved' }}</span>
                            <h6 class="mb-1 text-dark text-truncate fw-bold" style="font-size: 0.95rem;">{{ $item->barang->nama_barang }}</h6>
                            <span class="text-muted small d-block"><i class="fas fa-location-dot me-1"></i>{{ $item->barang->lokasi }}</span>
                        </div>
                        <div class="col-6 col-md-3 text-md-center">
                            @if($negotiatedPrice)
                                <span class="fw-bold text-success d-block">Rp {{ number_format($negotiatedPrice, 0, ',', '.') }}</span>
                                <span class="text-muted small text-decoration-line-through">Rp {{ number_format($item->barang->harga_jual, 0, ',', '.') }}</span>
                            @else
                                <span class="fw-bold text-dark d-block">Rp {{ number_format($item->barang->harga_jual, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <!-- Quantity Modifier form -->
                                <form action="{{ route('buyer.cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-1">
                                    @csrf
                                    <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="btn btn-xs btn-outline-secondary p-1" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="px-2 fw-semibold">{{ $item->quantity }}</span>
                                    <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="btn btn-xs btn-outline-secondary p-1" {{ $item->quantity >= $item->barang->stok ? 'disabled' : '' }}>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </form>

                                <div class="d-flex gap-2">
                                    <!-- Save for later -->
                                    <form action="{{ route('buyer.cart.save', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light border" title="{{ $lang == 'en' ? 'Save for later' : 'Simpan untuk nanti' }}">
                                            <i class="far fa-bookmark text-primary"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Delete from cart -->
                                    <form action="{{ route('buyer.cart.delete', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" title="{{ $lang == 'en' ? 'Delete' : 'Hapus' }}">
                                            <i class="far fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Saved For Later items list -->
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-4"><i class="far fa-bookmark text-primary me-2"></i>{{ $lang == 'en' ? 'Saved for Later' : 'Simpan untuk Nanti' }}</h5>
            
            @if($savedItems->isEmpty())
                <p class="text-muted small text-center mb-0 py-3">{{ $lang == 'en' ? 'No items saved for later.' : 'Belum ada barang yang disimpan untuk nanti.' }}</p>
            @else
                @foreach($savedItems as $item)
                    <div class="row align-items-center mb-3 pb-3 border-bottom g-3">
                        <div class="col-3 col-md-2">
                            <img src="https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=150" class="img-fluid rounded" alt="{{ $item->barang->nama_barang }}" style="height: 60px; object-fit: cover; opacity: 0.7;">
                        </div>
                        <div class="col-9 col-md-5">
                            <span class="text-primary small fw-bold text-uppercase" style="font-size: 0.75rem;">{{ $item->barang->brand ?: 'Preloved' }}</span>
                            <h6 class="mb-1 text-muted text-truncate fw-bold" style="font-size: 0.95rem;">{{ $item->barang->nama_barang }}</h6>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="fw-bold text-muted">Rp {{ number_format($item->barang->harga_jual, 0, ',', '.') }}</span>
                        </div>
                        <div class="col-6 col-md-2 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <!-- Re-add to cart -->
                                <form action="{{ route('buyer.cart.save', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-cart-arrow-down me-1"></i>{{ $lang == 'en' ? 'Move to Cart' : 'Pindahkan' }}
                                    </button>
                                </form>
                                
                                <form action="{{ route('buyer.cart.delete', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger">
                                        <i class="far fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Cart Summary side Card -->
    <div class="col-lg-4">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px; position: sticky; top: 90px;">
            <h5 class="fw-bold text-dark mb-4">{{ $lang == 'en' ? 'Shopping Summary' : 'Ringkasan Belanja' }}</h5>
            
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted small">Subtotal</span>
                <strong class="text-dark">@if(isset($subtotal)) Rp {{ number_format($subtotal, 0, ',', '.') }} @else Rp 0 @endif</strong>
            </div>
            
            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                <span class="text-muted small">{{ $lang == 'en' ? 'Shipping' : 'Ongkir' }}</span>
                <span class="text-success small fw-semibold">{{ $lang == 'en' ? 'Calculated next' : 'Dihitung di checkout' }}</span>
            </div>

            <div class="d-flex justify-content-between mb-4">
                <span class="text-dark fw-bold">{{ $lang == 'en' ? 'Grand Total' : 'Total Harga' }}</span>
                <strong class="text-primary fs-5">@if(isset($subtotal)) Rp {{ number_format($subtotal, 0, ',', '.') }} @else Rp 0 @endif</strong>
            </div>

            @if(!$cartItems->isEmpty())
                <a href="{{ route('buyer.checkout') }}" class="btn btn-primary w-100 py-3 d-flex align-items-center justify-content-center gap-2" style="border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-lock me-1"></i>{{ $lang == 'en' ? 'Proceed to Checkout' : 'Lanjut ke Pembayaran' }}
                </a>
            @else
                <button class="btn btn-light w-100 py-3 border" disabled style="border-radius: 12px; font-weight: 600;">
                    {{ $lang == 'en' ? 'Proceed to Checkout' : 'Lanjut ke Pembayaran' }}
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
