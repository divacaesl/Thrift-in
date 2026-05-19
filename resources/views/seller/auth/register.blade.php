@php
    $lang = session('preferred_language', 'id');
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'en' ? 'Seller Registration - ThriftIn' : 'Daftar Seller Toko - ThriftIn' }}</title>
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
            padding: 40px 20px;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.2);
            width: 100%;
            max-width: 680px;
        }
        .form-control {
            border-radius: 12px;
            padding: 10px 14px;
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
        .step-title {
            position: relative;
            padding-left: 15px;
        }
        .step-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 4px;
            background-color: #4F46E5;
            border-radius: 2px;
        }
    </style>
</head>
<body>

    <div class="register-card p-4 p-md-5">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-primary"><i class="fas fa-store-slash me-2"></i>{{ $lang == 'en' ? 'Create Seller Account' : 'Pendaftaran Merchant Baru' }}</h3>
            <p class="text-muted small">{{ $lang == 'en' ? 'Fill out the form below to start selling your preloved collection.' : 'Lengkapi formulir berikut untuk mulai menjual pakaian preloved Anda.' }}</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 small">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.register') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Step 1: User Account details -->
            <h6 class="fw-bold text-secondary mb-3 step-title">{{ $lang == 'en' ? 'Step 1: Account Information' : 'Bagian 1: Akun Pemilik' }}</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Full Name' : 'Nama Lengkap' }}</label>
                    <input type="text" name="nama" class="form-control" placeholder="Budi Santoso" value="{{ old('nama') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="budisantoso" value="{{ old('username') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="budi@email.com" value="{{ old('email') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Phone Number' : 'Nomor Handphone' }}</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="0812345678" value="{{ old('no_hp') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Password' : 'Kata Sandi' }}</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Confirm Password' : 'Konfirmasi Kata Sandi' }}</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Step 2: Shop details -->
            <h6 class="fw-bold text-secondary mb-3 step-title">{{ $lang == 'en' ? 'Step 2: Shop & Storefront Profile' : 'Bagian 2: Profil Toko' }}</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Shop / Store Name' : 'Nama Toko (Akan Ditampilkan ke Pembeli)' }}</label>
                    <input type="text" name="nama_toko" class="form-control" placeholder="Budi Vintage Sneakers" value="{{ old('nama_toko') }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Shop Complete Address' : 'Alamat Lengkap Toko' }}</label>
                    <textarea name="alamat_toko" rows="2" class="form-control" placeholder="Jalan Raya No. 1, Kota Surabaya, Jawa Timur" required>{{ old('alamat_toko') }}</textarea>
                </div>
            </div>

            <!-- Step 3: Identity verification -->
            <h6 class="fw-bold text-secondary mb-3 step-title">{{ $lang == 'en' ? 'Step 3: Identity Verification (Required for Escrow & Payouts)' : 'Bagian 3: Verifikasi Identitas (KTP & Selfie)' }}</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Upload KTP / ID Card Photo' : 'Unggah Foto KTP' }}</label>
                    <input type="file" name="ktp_file" class="form-control" accept="image/*">
                    <small class="text-muted" style="font-size: 0.75rem;">{{ $lang == 'en' ? 'Make sure the text is clear.' : 'Pastikan tulisan terbaca jelas.' }}</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary">{{ $lang == 'en' ? 'Upload Selfie with KTP' : 'Unggah Foto Selfie Memegang KTP' }}</label>
                    <input type="file" name="selfie_file" class="form-control" accept="image/*">
                    <small class="text-muted" style="font-size: 0.75rem;">{{ $lang == 'en' ? 'Face and ID card must match.' : 'Wajah dan KTP harus terlihat utuh.' }}</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">{{ $lang == 'en' ? 'Register Store' : 'Daftar Toko & Buka Lapak' }}</button>
        </form>

        <div class="text-center">
            <span class="small text-muted">{{ $lang == 'en' ? 'Already have a seller account?' : 'Sudah memiliki akun seller?' }}</span>
            <a href="{{ route('seller.login') }}" class="small text-primary fw-bold text-decoration-none ms-1">{{ $lang == 'en' ? 'Log In' : 'Masuk Sini' }}</a>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
