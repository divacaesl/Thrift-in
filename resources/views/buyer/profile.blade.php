@extends('layouts.buyer')

@section('title', 'Kelola Profil - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp

<h3 class="fw-bold mb-4 text-dark"><i class="fas fa-cog text-primary me-2"></i>{{ $lang == 'en' ? 'Account Settings' : 'Pengaturan Akun' }}</h3>

<div class="row g-4">
    <!-- Left Column: Forms -->
    <div class="col-lg-6">
        <!-- Profile Update -->
        <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-4">{{ $lang == 'en' ? 'Edit Profile Information' : 'Edit Informasi Profil' }}</h5>
            
            <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 text-center">
                    <img src="{{ asset('uploads/profiles/' . $user->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" class="rounded-circle border mb-3" alt="avatar" style="width: 80px; height: 80px; object-fit: cover;">
                    <div class="mb-3">
                        <label for="foto_profil" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Change Photo' : 'Ubah Foto Profil' }}</label>
                        <input class="form-control form-control-sm" type="file" id="foto_profil" name="foto_profil" accept="image/*" style="border-radius: 8px;">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Full Name' : 'Nama Lengkap' }}</label>
                    <input type="text" name="nama" class="form-control" id="nama" value="{{ $user->nama }}" required style="border-radius: 10px;">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Username</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->username }}" readonly style="border-radius: 10px;">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-muted">Email</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->email }}" readonly style="border-radius: 10px;">
                </div>

                <div class="mb-3">
                    <label for="no_hp" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Phone Number' : 'Nomor HP' }}</label>
                    <input type="text" name="no_hp" class="form-control" id="no_hp" value="{{ $user->no_hp }}" required style="border-radius: 10px;">
                </div>

                <div class="mb-4">
                    <label for="metode_bayar_favorit" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Preferred Payment Method' : 'Metode Pembayaran Terfavorit' }}</label>
                    <select name="metode_bayar_favorit" id="metode_bayar_favorit" class="form-select" style="border-radius: 10px;">
                        <option value="">-- Pilih --</option>
                        <option value="bank_transfer" {{ $user->metode_bayar_favorit == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="qris" {{ $user->metode_bayar_favorit == 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="cod" {{ $user->metode_bayar_favorit == 'cod' ? 'selected' : '' }}>Cash On Delivery (COD)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2" style="border-radius: 10px;">
                    {{ $lang == 'en' ? 'Save Profile' : 'Simpan Perubahan' }}
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-4">{{ $lang == 'en' ? 'Change Password' : 'Ubah Kata Sandi' }}</h5>
            
            <form action="{{ route('buyer.profile.password') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="old_password" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Current Password' : 'Sandi Saat Ini' }}</label>
                    <input type="password" name="old_password" class="form-control" id="old_password" required placeholder="******" style="border-radius: 10px;">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'New Password' : 'Sandi Baru' }}</label>
                    <input type="password" name="password" class="form-control" id="password" required placeholder="******" style="border-radius: 10px;">
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Confirm New Password' : 'Konfirmasi Sandi Baru' }}</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required placeholder="******" style="border-radius: 10px;">
                </div>

                <button type="submit" class="btn btn-outline-primary w-100 py-2" style="border-radius: 10px;">
                    {{ $lang == 'en' ? 'Update Password' : 'Ganti Kata Sandi' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Shipping Addresses Manager -->
    <div class="col-lg-6">
        <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <h5 class="fw-bold text-dark mb-4"><i class="fas fa-map-location-dot text-primary me-2"></i>{{ $lang == 'en' ? 'Manage Shipping Addresses' : 'Daftar Alamat Pengiriman' }}</h5>
            
            @if($addresses->isEmpty())
                <p class="text-muted small text-center my-4">{{ $lang == 'en' ? 'No addresses found.' : 'Belum ada alamat pengiriman yang terdaftar.' }}</p>
            @else
                @foreach($addresses as $addr)
                    <div class="p-3 border rounded-4 mb-3 position-relative {{ $addr->is_utama ? 'border-primary bg-primary-subtle' : 'bg-white' }}">
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <span class="badge bg-secondary me-1">{{ $addr->label }}</span>
                                @if($addr->is_utama)
                                    <span class="badge bg-primary">{{ $lang == 'en' ? 'Default' : 'Utama' }}</span>
                                @endif
                            </div>
                            <!-- Delete and Main Address actions -->
                            <div class="d-flex gap-2">
                                @if(!$addr->is_utama)
                                    <a href="{{ route('buyer.address.utama', $addr->id) }}" class="text-primary small text-decoration-none fw-bold" style="font-size: 0.8rem;">Set Utama</a>
                                @endif
                                <a href="{{ route('buyer.address.delete', $addr->id) }}" class="text-danger small text-decoration-none" onclick="return confirm('Hapus alamat ini?')"><i class="far fa-trash-can"></i></a>
                            </div>
                        </div>
                        <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $addr->nama_penerima }} ({{ $addr->no_hp }})</strong>
                        <span class="text-muted small d-block">{{ $addr->alamat_lengkap }}, {{ $addr->kota }}, {{ $addr->kode_pos }}</span>
                    </div>
                @endforeach
            @endif

            <hr class="my-4">

            <!-- Add Address Form -->
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-plus me-1 text-primary"></i>{{ $lang == 'en' ? 'Add New Address' : 'Tambah Alamat Pengiriman Baru' }}</h6>
            <form action="{{ route('buyer.address.add') }}" method="POST">
                @csrf
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label for="label" class="form-label small fw-semibold text-muted">Label Alamat</label>
                        <input type="text" name="label" class="form-control form-control-sm" required placeholder="Rumah / Kantor" style="border-radius: 8px;">
                    </div>
                    <div class="col-6">
                        <label for="nama_penerima" class="form-label small fw-semibold text-muted">Penerima</label>
                        <input type="text" name="nama_penerima" class="form-control form-control-sm" required placeholder="Nama Penerima" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label for="no_hp_addr" class="form-label small fw-semibold text-muted">No. HP</label>
                        <input type="text" name="no_hp" id="no_hp_addr" class="form-control form-control-sm" required placeholder="0812345..." style="border-radius: 8px;">
                    </div>
                    <div class="col-6">
                        <label for="kota" class="form-label small fw-semibold text-muted">Kota / Kab</label>
                        <input type="text" name="kota" class="form-control form-control-sm" required placeholder="Surabaya" style="border-radius: 8px;">
                    </div>
                </div>
                <div class="mb-2">
                    <label for="alamat_lengkap" class="form-label small fw-semibold text-muted">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control form-control-sm" rows="2" required placeholder="Jalan, No Rumah, Kelurahan, Kecamatan" style="border-radius: 8px;"></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="kode_pos" class="form-label small fw-semibold text-muted">Kode Pos</label>
                        <input type="text" name="kode_pos" class="form-control form-control-sm" required placeholder="60231" style="border-radius: 8px;">
                    </div>
                    <div class="col-6 d-flex align-items-center mt-4">
                        <input type="checkbox" name="is_utama" value="1" id="is_utama" class="form-check-input me-2">
                        <label for="is_utama" class="form-check-label small text-dark fw-semibold">Set Utama / Default</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-outline-primary btn-sm w-100 py-2" style="border-radius: 8px;">
                    {{ $lang == 'en' ? 'Save Address' : 'Simpan Alamat Baru' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
