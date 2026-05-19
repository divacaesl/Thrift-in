@extends('layouts.seller')

@section('title', 'Toko & Profil - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="row g-4">
    <!-- Store Identity Card -->
    <div class="col-lg-4">
        <div class="card p-4 text-center">
            <!-- Banner Preview -->
            <div class="rounded-3 mb-3 overflow-hidden" style="height: 120px; background-color: #E2E8F0; position: relative;">
                <img src="{{ asset('uploads/shops/' . $penitip->banner_toko) }}" onerror="this.src='https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=600&q=80'" alt="banner" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <!-- Logo Preview -->
            <div class="mx-auto rounded-circle overflow-hidden mb-3 border border-4 border-white shadow-sm" style="width: 100px; height: 100px; margin-top: -60px; position: relative; z-index: 10;">
                <img src="{{ asset('uploads/shops/' . $penitip->logo_toko) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/shop.svg'" alt="logo" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <h5 class="fw-bold mb-1">{{ $penitip->nama }}</h5>
            <p class="text-muted small mb-3">{{ $penitip->kode_penitip }}</p>

            <div class="mb-4">
                @if($penitip->is_verified)
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill small">
                        <i class="fas fa-check-circle me-1"></i> {{ $lang == 'en' ? 'Store Verified' : 'Toko Terverifikasi (KTP)' }}
                    </span>
                @else
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill small">
                        <i class="fas fa-clock me-1"></i> {{ $lang == 'en' ? 'Pending Verification' : 'Menunggu Verifikasi KTP' }}
                    </span>
                @endif
            </div>

            <div class="border-top pt-3 text-start">
                <h6 class="fw-bold mb-2 small text-secondary"><i class="fas fa-shield-halved me-1"></i> {{ $lang == 'en' ? 'Security & Identity Documents' : 'Dokumen Keamanan & Identitas' }}</h6>
                <ul class="list-unstyled small mb-0 text-muted">
                    <li class="mb-2"><i class="fas fa-address-card me-2"></i> KTP: <span class="text-dark">{{ $penitip->ktp ? 'Telah Diunggah' : 'Belum Ada' }}</span></li>
                    <li><i class="fas fa-camera me-2"></i> Selfie KTP: <span class="text-dark">{{ $penitip->selfie ? 'Telah Diunggah' : 'Belum Ada' }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Store Profile Form -->
    <div class="col-lg-8">
        <div class="card p-4">
            <h5 class="fw-bold mb-4"><i class="fas fa-store-slash text-primary me-2"></i>{{ $lang == 'en' ? 'Configure Shop Settings' : 'Konfigurasi Pengaturan Toko' }}</h5>
            
            <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Shop Name' : 'Nama Toko' }}</label>
                        <input type="text" name="nama" class="form-control" value="{{ $penitip->nama }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Upload Store Logo' : 'Unggah Logo Toko' }}</label>
                        <input type="file" name="logo_file" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Upload Store Banner' : 'Unggah Banner Toko' }}</label>
                        <input type="file" name="banner_file" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Shop Description' : 'Deskripsi Toko' }}</label>
                        <textarea name="deskripsi_toko" rows="3" class="form-control" placeholder="{{ $lang == 'en' ? 'Describe what your shop sells...' : 'Jelaskan koleksi preloved yang dijual toko Anda...' }}">{{ $penitip->deskripsi_toko }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Pickup Address' : 'Alamat Penjemputan Kurir / Toko' }}</label>
                        <textarea name="alamat" rows="2" class="form-control" required>{{ $penitip->alamat }}</textarea>
                    </div>
                </div>

                <!-- Payout/Withdrawal Settings -->
                <h5 class="fw-bold mb-3 border-top pt-4"><i class="fas fa-building-columns text-success me-2"></i>{{ $lang == 'en' ? 'Withdrawal Payment Method' : 'Tujuan Rekening Pencairan Saldo' }}</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Bank Name / E-Wallet' : 'Nama Bank / E-Wallet' }}</label>
                        <input type="text" name="nama_bank" class="form-control" value="{{ $penitip->nama_bank }}" placeholder="BCA / Mandiri / GoPay / OVO">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Account Number / Wallet ID' : 'Nomor Rekening / Wallet ID' }}</label>
                        <input type="text" name="no_rekening" class="form-control" value="{{ $penitip->no_rekening }}" placeholder="1234567890">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary px-4">{{ $lang == 'en' ? 'Save Settings' : 'Simpan Pengaturan Profil' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
