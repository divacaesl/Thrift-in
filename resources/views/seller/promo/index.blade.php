@extends('layouts.seller')

@section('title', 'Promosi Produk - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="card p-4">
    <h5 class="fw-bold mb-2"><i class="fas fa-rectangle-ad text-primary me-2"></i>{{ $lang == 'en' ? 'Promotional & Product Boosters' : 'Pengaturan Diskon & Boost Produk' }}</h5>
    <p class="text-muted small mb-4">{{ $lang == 'en' ? 'Increase store exposure by setting discount percentages, opt-in for Flash Sales, or boost search priorities.' : 'Tingkatkan kunjungan toko Anda dengan mengatur diskon harga coret, flash sale, atau menyundul produk.' }}</p>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>{{ $lang == 'en' ? 'Product Info' : 'Informasi Barang' }}</th>
                    <th>{{ $lang == 'en' ? 'Original Price' : 'Harga Asli' }}</th>
                    <th>{{ $lang == 'en' ? 'Promo Discount' : 'Atur Diskon (%)' }}</th>
                    <th>{{ $lang == 'en' ? 'Flash Sale' : 'Ikut Flash Sale' }}</th>
                    <th>{{ $lang == 'en' ? 'Stats' : 'Statistik' }}</th>
                    <th class="text-center">{{ $lang == 'en' ? 'Action Promos' : 'Aksi Promosi' }}</th>
                </tr>
            </thead>
            <tbody>
                @if($products->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada barang di katalog Anda.</td>
                    </tr>
                @else
                    @foreach($products as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('uploads/products/' . $p->foto) }}" onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=150&q=80'" alt="product" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;">{{ $p->nama_barang }}</span>
                                        @if($p->tags)
                                            @foreach(explode(',', $p->tags) as $t)
                                                <span class="badge bg-secondary p-1" style="font-size: 0.65rem;">{{ $t }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('seller.promo.update', $p->id) }}" method="POST" id="formPromo{{ $p->id }}">
                                    @csrf
                                    <div class="input-group input-group-sm" style="max-width: 120px;">
                                        <input type="number" name="diskon_persen" class="form-control" min="0" max="100" value="{{ $p->diskon_persen }}">
                                        <span class="input-group-text">%</span>
                                    </div>
                            </td>
                            <td>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input" type="checkbox" name="is_flash_sale" value="1" {{ $p->is_flash_sale ? 'checked' : '' }} onchange="document.getElementById('formPromo{{ $p->id }}').submit()">
                                    </div>
                            </td>
                            <td class="small text-muted" style="font-size: 0.8rem;">
                                <div>Views: {{ $p->viewer_count }}</div>
                                <div>Likes: {{ $p->favorite_count }}</div>
                            </td>
                            <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="submit" class="btn btn-sm btn-primary">{{ $lang == 'en' ? 'Save' : 'Simpan' }}</button>
                                </form>
                                        <form action="{{ route('seller.promo.boost', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-rocket text-dark"></i> Boost</button>
                                        </form>
                                    </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
