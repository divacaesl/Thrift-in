@extends('layouts.admin')

@section('title', 'Moderasi Produk - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-box-open text-info me-2"></i> Moderasi Produk Platform</h5>
            <p class="text-muted small mb-0">Periksa produk yang diupload oleh seller. Sistem AI akan otomatis memberikan label <span class="badge bg-danger">Flagged</span> pada barang yang terindikasi palsu atau memiliki harga tidak wajar.</p>
        </div>
        
        <div class="d-flex gap-2">
            <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2">
                <select name="moderation_status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('moderation_status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="flagged" {{ request('moderation_status') == 'flagged' ? 'selected' : '' }}>Terindikasi Fraud / Flagged</option>
                    <option value="approved" {{ request('moderation_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('moderation_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </form>
        </div>
    </div>

    @if($flaggedCount > 0)
    <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 d-flex align-items-center mb-4">
        <i class="fas fa-triangle-exclamation fs-3 me-3"></i>
        <div>
            <strong>Perhatian:</strong> Terdapat {{ $flaggedCount }} produk yang membutuhkan pengecekan segera karena ditandai oleh sistem AI (indikasi penipuan/produk palsu).
        </div>
    </div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light text-muted small">
                <tr>
                    <th style="width: 35%;">PRODUK INFO</th>
                    <th>SELLER</th>
                    <th>HARGA & STOK</th>
                    <th>STATUS MODERASI</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $item)
                    <tr>
                        <td>
                            <div class="d-flex gap-3">
                                <img src="{{ asset('uploads/barangs/' . $item->foto) }}" onerror="this.src='https://via.placeholder.com/60?text=No+Image'" alt="product" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark">{{ $item->nama_barang }}</div>
                                    <div class="text-muted small">ID: {{ $item->kode_barang }} • {{ $item->kategori->nama_kategori ?? 'Uncategorized' }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;"><i class="fas fa-tag me-1"></i>{{ $item->brand ?? '-' }} | {{ str_replace('_', ' ', $item->kondisi) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $item->penitip->nama ?? 'Unknown' }}</div>
                            <span class="badge bg-light text-dark border"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $item->lokasi }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-success">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</div>
                            <div class="small text-muted">Stok: {{ $item->stok }}</div>
                        </td>
                        <td>
                            @if($item->moderation_status == 'approved')
                                <span class="badge badge-soft-success"><i class="fas fa-check-circle me-1"></i> Approved</span>
                            @elseif($item->moderation_status == 'flagged')
                                <span class="badge badge-soft-danger"><i class="fas fa-flag me-1"></i> AI Flagged</span>
                            @elseif($item->moderation_status == 'rejected')
                                <span class="badge badge-soft-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                <div class="small text-danger mt-1" style="font-size: 0.7rem;">{{ $item->moderation_notes }}</div>
                            @else
                                <span class="badge badge-soft-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->moderation_status != 'approved')
                                <form action="{{ route('admin.products.moderate', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-sm btn-success px-3" title="Approve"><i class="fas fa-check"></i></button>
                                </form>
                            @endif
                            
                            @if($item->moderation_status != 'rejected')
                                <button type="button" class="btn btn-sm btn-outline-danger px-3" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $item->id }}" title="Reject"><i class="fas fa-times"></i></button>
                            @endif
                        </td>
                    </tr>

                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="fw-bold"><i class="fas fa-times-circle text-danger me-2"></i> Tolak Produk: {{ $item->kode_barang }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.products.moderate', $item->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <div class="modal-body p-4">
                                        <label class="form-label small fw-bold">Alasan Penolakan</label>
                                        <textarea name="notes" rows="3" class="form-control" placeholder="Contoh: Terindikasi barang palsu / deskripsi tidak sesuai gambar." required></textarea>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger btn-sm px-4">Tolak & Sembunyikan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada produk yang diupload.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
