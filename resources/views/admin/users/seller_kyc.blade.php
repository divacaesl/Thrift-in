@extends('layouts.admin')

@section('title', 'Verifikasi Identitas Seller - Admin')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="fas fa-id-card text-primary me-2"></i> Antrean Verifikasi Seller (KYC)</h5>
            <p class="text-muted small mb-0">Periksa dan validasi dokumen KTP dan foto selfie penjual untuk memberikan badge "Verified Seller".</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($sellers as $seller)
            <div class="col-md-6 col-lg-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ asset('uploads/profiles/' . $seller->user->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/shop.svg'" alt="profile" class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $seller->nama }}</h6>
                                <div class="small text-muted">{{ $seller->email }}</div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="small fw-bold text-muted mb-1">Foto KTP</div>
                                <a href="{{ asset('uploads/kyc/' . $seller->ktp) }}" target="_blank">
                                    <img src="{{ asset('uploads/kyc/' . $seller->ktp) }}" onerror="this.src='https://via.placeholder.com/300x200?text=KTP'" class="img-fluid rounded border" alt="KTP">
                                </a>
                            </div>
                            <div class="col-6">
                                <div class="small fw-bold text-muted mb-1">Selfie dgn KTP</div>
                                <a href="{{ asset('uploads/kyc/' . $seller->selfie) }}" target="_blank">
                                    <img src="{{ asset('uploads/kyc/' . $seller->selfie) }}" onerror="this.src='https://via.placeholder.com/300x200?text=Selfie'" class="img-fluid rounded border" alt="Selfie">
                                </a>
                            </div>
                        </div>

                        <div class="small bg-light p-2 rounded mb-3">
                            <strong>Alamat:</strong> {{ $seller->alamat }}<br>
                            <strong>No. HP:</strong> {{ $seller->no_hp }}
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <form action="{{ route('admin.users.verify_seller', $seller->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-success w-100 btn-sm fw-bold"><i class="fas fa-check-circle me-1"></i> Approve</button>
                            </form>
                            <form action="{{ route('admin.users.reject_seller', $seller->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 btn-sm fw-bold" onclick="return confirm('Tolak dan hapus dokumen?');"><i class="fas fa-times-circle me-1"></i> Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-check-double text-success fs-1 mb-3"></i>
                <h6 class="fw-bold">Tidak ada antrean verifikasi.</h6>
                <p class="text-muted small">Semua seller telah diverifikasi atau belum ada yang mengajukan.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
