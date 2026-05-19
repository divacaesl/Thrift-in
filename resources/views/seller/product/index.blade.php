@extends('layouts.seller')

@section('title', 'Kelola Produk - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="fas fa-boxes-stacked text-primary me-2"></i>{{ $lang == 'en' ? 'My Store Catalog' : 'Katalog Produk Toko' }}</h5>
        <a href="{{ route('seller.product.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> {{ $lang == 'en' ? 'Add Preloved Item' : 'Tambah Barang Baru' }}
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>{{ $lang == 'en' ? 'Product Info' : 'Informasi Produk' }}</th>
                    <th>{{ $lang == 'en' ? 'Category' : 'Kategori' }}</th>
                    <th>{{ $lang == 'en' ? 'Condition' : 'Kondisi' }}</th>
                    <th>{{ $lang == 'en' ? 'Price' : 'Harga Jual' }}</th>
                    <th>{{ $lang == 'en' ? 'Stock' : 'Stok' }}</th>
                    <th>{{ $lang == 'en' ? 'Stats' : 'Statistik' }}</th>
                    <th>{{ $lang == 'en' ? 'Status' : 'Status' }}</th>
                    <th class="text-center">{{ $lang == 'en' ? 'Actions' : 'Aksi' }}</th>
                </tr>
            </thead>
            <tbody>
                @if($products->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open fs-2 mb-2 text-secondary"></i>
                            <p class="mb-0 small">{{ $lang == 'en' ? 'No items in catalog yet. Click Add Product to start.' : 'Belum ada produk yang dijual. Klik Tambah Barang Baru.' }}</p>
                        </td>
                    </tr>
                @else
                    @foreach($products as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('uploads/products/' . $p->foto) }}" onerror="this.src='https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=150&q=80'" alt="product" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid var(--border-color);">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $p->nama_barang }}</h6>
                                        <small class="text-primary fw-semibold">{{ $p->kode_barang }}</small>
                                        @if($p->brand)
                                            <span class="badge bg-secondary ms-1">{{ $p->brand }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                            <td>
                                @php
                                    $condClass = 'bg-secondary';
                                    if ($p->kondisi == 'baru') $condClass = 'bg-success';
                                    elseif ($p->kondisi == 'seperti_baru') $condClass = 'bg-info text-dark';
                                    elseif ($p->kondisi == 'bekas_layak') $condClass = 'bg-warning text-dark';
                                    elseif ($p->kondisi == 'bekas') $condClass = 'bg-danger';
                                @endphp
                                <span class="badge {{ $condClass }}">{{ strtoupper(str_replace('_', ' ', $p->kondisi)) }}</span>
                            </td>
                            <td>
                                @if($p->diskon_persen > 0)
                                    <small class="text-decoration-line-through text-muted small">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</small><br>
                                    <span class="fw-bold text-danger">Rp {{ number_format($p->harga_jual * (1 - $p->diskon_persen/100), 0, ',', '.') }}</span>
                                @else
                                    <span class="fw-bold text-dark">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($p->stok > 0)
                                    <span class="fw-semibold text-success">{{ $p->stok }} Pcs</span>
                                @else
                                    <span class="badge bg-danger">{{ $lang == 'en' ? 'Sold Out' : 'Habis' }}</span>
                                @endif
                            </td>
                            <td class="small text-muted" style="font-size: 0.8rem;">
                                <div><i class="fas fa-eye me-1 text-secondary"></i> {{ $p->viewer_count }} Views</div>
                                <div><i class="fas fa-heart me-1 text-danger"></i> {{ $p->favorite_count }} Likes</div>
                            </td>
                            <td>
                                @if($p->status == 'ditampilkan')
                                    <span class="badge bg-success bg-opacity-10 text-success">{{ $lang == 'en' ? 'Active' : 'Tampil' }}</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('seller.product.edit', $p->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('seller.product.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
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
