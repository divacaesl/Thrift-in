@php
    $lang = session('preferred_language', 'id');
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'en' ? 'Seller Login - ThriftIn' : 'Masuk Seller - ThriftIn' }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #4F46E5 0%, #312E81 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            transition: all 0.3s ease;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #E2E8F0;
            background-color: #F8FAFC;
        }
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            border-color: #4F46E5;
            background-color: #fff;
        }
        .btn-primary {
            background-color: #4F46E5;
            border-color: #4F46E5;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #4338CA;
            border-color: #4338CA;
        }
    </style>
</head>
<body>

    <div class="login-card p-4 p-md-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary"><i class="fas fa-store me-2"></i>ThriftIn Seller</h3>
            <p class="text-muted small">{{ $lang == 'en' ? 'Access your merchant panel to manage preloved items.' : 'Akses panel merchant Anda untuk mengelola produk preloved.' }}</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 small mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 small mb-3">{{ session('error') }}</div>
        @endif

        <form action="{{ route('seller.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">{{ $lang == 'en' ? 'Email Address' : 'Alamat Email' }}</label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">{{ $lang == 'en' ? 'Password' : 'Kata Sandi' }}</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label small text-secondary" for="remember">{{ $lang == 'en' ? 'Remember Me' : 'Ingat Saya' }}</label>
                </div>
                <a href="#" class="small text-primary text-decoration-none" data-bs-toggle="modal" data-bs-target="#otpModal">{{ $lang == 'en' ? 'Login via OTP' : 'Masuk via OTP' }}</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">{{ $lang == 'en' ? 'Sign In' : 'Masuk Panel Seller' }}</button>
        </form>

        <div class="text-center">
            <span class="small text-muted">{{ $lang == 'en' ? "Don't have a shop yet?" : 'Belum memiliki toko?' }}</span>
            <a href="{{ route('seller.register') }}" class="small text-primary fw-bold text-decoration-none ms-1">{{ $lang == 'en' ? 'Register Store' : 'Daftar Toko' }}</a>
        </div>

        <!-- Login Device History (Security Section 14) -->
        <div class="mt-4 pt-4 border-top">
            <h6 class="fw-bold small text-secondary mb-2"><i class="fas fa-shield-halved me-1"></i> {{ $lang == 'en' ? 'Login Security Info' : 'Info Keamanan Login' }}</h6>
            <p class="text-muted" style="font-size: 0.75rem; line-height: 1.4;">
                {{ $lang == 'en' ? 'For security reasons, your sessions are protected with escrow transaction holds. Active log detection is enabled.' : 'Demi keamanan, saldo penjualan Anda dilindungi dengan sistem penahanan escrow. Deteksi log sesi aktif diaktifkan.' }}
            </p>
        </div>
    </div>

    <!-- OTP Modal -->
    <div class="modal fade" id="otpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fas fa-key me-2"></i> {{ $lang == 'en' ? 'OTP Login Helper' : 'Masuk Sesi OTP' }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Enter Phone / Email' : 'Masukkan Nomor HP / Email Toko' }}</label>
                        <input type="text" class="form-control" placeholder="08xxxxxx atau email@toko.com">
                    </div>
                    <button type="button" class="btn btn-primary w-100" onclick="alert('Kode OTP (Simulasi) telah dikirim via SMS ke nomor HP terdaftar. Masukkan 1234 untuk login.'); document.getElementById('otpField').style.display='block';">
                        {{ $lang == 'en' ? 'Send OTP Code' : 'Kirim Kode OTP' }}
                    </button>
                    
                    <div id="otpField" class="mt-4" style="display: none;">
                        <label class="form-label fw-semibold small">{{ $lang == 'en' ? 'Enter 4-Digit OTP' : 'Masukkan 4 Digit Kode OTP' }}</label>
                        <input type="text" class="form-control text-center fs-4 fw-bold" maxlength="4" placeholder="••••">
                        <button type="button" class="btn btn-success w-100 mt-2" onclick="alert('Verifikasi OTP Berhasil! Anda dialihkan ke Dasbor Seller.'); window.location.href='{{ route('seller.dashboard') }}';">
                            {{ $lang == 'en' ? 'Verify and Login' : 'Verifikasi & Masuk' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
