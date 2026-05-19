@extends('layouts.buyer')

@section('title', 'Daftar Akun - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="row justify-content-center py-5">
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm">
            <div class="card-body">
                <h3 class="fw-bold text-center text-dark mb-2">{{ $lang == 'en' ? 'Create Account' : 'Daftar Akun Baru' }}</h3>
                <p class="text-center text-muted small mb-4">{{ $lang == 'en' ? 'Register to start hunting preloved products' : 'Daftar untuk mulai berburu produk preloved pilihan' }}</p>
                
                <form action="{{ route('buyer.register') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label fw-semibold text-dark">{{ $lang == 'en' ? 'Full Name' : 'Nama Lengkap' }}</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" id="nama" value="{{ old('nama') }}" required placeholder="{{ $lang == 'en' ? 'e.g. Budi Santoso' : 'misal: Budi Santoso' }}" style="border-radius: 10px;">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-semibold text-dark">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" value="{{ old('username') }}" required placeholder="budi_s" style="border-radius: 10px;">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}" required placeholder="budi@email.com" style="border-radius: 10px;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label fw-semibold text-dark">{{ $lang == 'en' ? 'Phone Number' : 'Nomor HP' }}</label>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" value="{{ old('no_hp') }}" required placeholder="08123456789" style="border-radius: 10px;">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold text-dark">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" required placeholder="******" style="border-radius: 10px;">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark">{{ $lang == 'en' ? 'Confirm Password' : 'Konfirmasi Password' }}</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required placeholder="******" style="border-radius: 10px;">
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" value="" id="termsCheck" required>
                        <label class="form-check-label text-muted small" for="termsCheck">
                            {{ $lang == 'en' ? 'I agree to the Terms of Service and Privacy Policy.' : 'Saya menyetujui Ketentuan Layanan dan Kebijakan Privasi.' }}
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2" style="border-radius: 10px;">
                        {{ $lang == 'en' ? 'Register Account' : 'Daftar Akun Baru' }}
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">
                        {{ $lang == 'en' ? 'Already have an account?' : 'Sudah punya akun?' }} 
                        <a href="{{ route('buyer.login') }}" class="text-decoration-none fw-bold text-primary">{{ $lang == 'en' ? 'Sign In' : 'Masuk Saja' }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
