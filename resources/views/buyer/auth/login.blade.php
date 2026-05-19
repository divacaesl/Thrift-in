@extends('layouts.buyer')

@section('title', 'Masuk Akun - ThriftIn')

@section('content')
@php
    $lang = session('preferred_language', 'id');
@endphp
<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card p-4 border-0 shadow-sm">
            <div class="card-body">
                <h3 class="fw-bold text-center text-dark mb-2">{{ $lang == 'en' ? 'Welcome Back' : 'Selamat Datang Kembali' }}</h3>
                <p class="text-center text-muted small mb-4">{{ $lang == 'en' ? 'Sign in to access your preloved shopping' : 'Masuk untuk mengakses belanja preloved Anda' }}</p>
                
                <form action="{{ route('buyer.login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="login_id" class="form-label fw-semibold text-dark">{{ $lang == 'en' ? 'Email Address or Username' : 'Alamat Email atau Username' }}</label>
                        <input type="text" name="login_id" class="form-control @error('login_id') is-invalid @enderror" id="login_id" required placeholder="{{ $lang == 'en' ? 'e.g. budi@email.com' : 'misal: budi@email.com' }}" style="border-radius: 10px;">
                        @error('login_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label for="password" class="form-label fw-semibold text-dark">{{ $lang == 'en' ? 'Password' : 'Kata Sandi' }}</label>
                            <a href="#" class="text-decoration-none small text-primary" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">{{ $lang == 'en' ? 'Forgot Password?' : 'Lupa Sandi?' }}</a>
                        </div>
                        <input type="password" name="password" class="form-control" id="password" required placeholder="******" style="border-radius: 10px;">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mt-2 mb-3" style="border-radius: 10px;">
                        {{ $lang == 'en' ? 'Sign In' : 'Masuk' }}
                    </button>
                </form>

                <!-- Divider -->
                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1">
                    <span class="px-2 text-muted small">{{ $lang == 'en' ? 'OR' : 'ATAU' }}</span>
                    <hr class="flex-grow-1">
                </div>

                <!-- Google Login Simulation -->
                <a href="{{ route('buyer.login.google') }}" class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2 mb-3" style="border-radius: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google text-danger" viewBox="0 0 16 16">
                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0c2.204 0 4.093.818 5.54 2.15l-2.427 2.427C10.075 3.5 9.17 3 8 3c-2.73 0-4.96 2.23-4.96 4.96S5.27 12.92 8 12.92c3.08 0 4.223-2.15 4.407-3.233H8v-3.13h7.545z"/>
                    </svg>
                    {{ $lang == 'en' ? 'Sign In with Google' : 'Masuk dengan Google' }}
                </a>

                <!-- Phone OTP Verification Simulation -->
                <a href="#" class="btn btn-outline-primary w-100 py-2 d-flex align-items-center justify-content-center gap-2 mb-3" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#otpModal">
                    <i class="fas fa-mobile-screen text-primary"></i>
                    {{ $lang == 'en' ? 'Verify via OTP / SMS' : 'Verifikasi lewat OTP / SMS' }}
                </a>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">
                        {{ $lang == 'en' ? "Don't have an account?" : "Belum punya akun?" }} 
                        <a href="{{ route('buyer.register') }}" class="text-decoration-none fw-bold text-primary">{{ $lang == 'en' ? 'Register Now' : 'Daftar Sekarang' }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">{{ $lang == 'en' ? 'Reset Password' : 'Lupa Kata Sandi' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('buyer.forgot') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <p class="text-muted small mb-3">{{ $lang == 'en' ? 'Enter your email address and we will send you a password reset link.' : 'Masukkan email terdaftar Anda dan kami akan mengirimkan link untuk mengatur ulang kata sandi.' }}</p>
                    <div class="mb-3">
                        <label for="forgot_email" class="form-label small fw-semibold text-dark">{{ $lang == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                        <input type="email" name="email" class="form-control" id="forgot_email" required placeholder="budi@email.com" style="border-radius: 10px;">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">{{ $lang == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">{{ $lang == 'en' ? 'Send Link' : 'Kirim Link' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OTP Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">{{ $lang == 'en' ? 'OTP Verification (Simulation)' : 'Verifikasi OTP (Simulasi)' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('buyer.verify.otp') }}" method="POST">
                @csrf
                <div class="modal-body py-4 text-center">
                    <i class="fas fa-shield-halved text-primary fs-1 mb-3"></i>
                    <p class="text-muted small mb-4">{{ $lang == 'en' ? 'We have sent a 6-digit OTP code to your phone number via SMS. Enter it below.' : 'Kami telah mengirimkan 6 digit kode OTP ke nomor handphone Anda melalui SMS. Masukkan di bawah ini.' }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <input type="text" name="otp_code" class="form-control text-center fw-bold fs-4" maxlength="6" style="width: 150px; letter-spacing: 5px; border-radius: 10px;" placeholder="123456" required>
                    </div>
                    <span class="text-muted small">{{ $lang == 'en' ? "Didn't receive code?" : 'Tidak menerima kode?' }} <a href="#" class="text-decoration-none text-primary fw-semibold">{{ $lang == 'en' ? 'Resend' : 'Kirim Ulang' }}</a></span>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">{{ $lang == 'en' ? 'Cancel' : 'Batal' }}</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 10px;">{{ $lang == 'en' ? 'Verify OTP' : 'Verifikasi' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
