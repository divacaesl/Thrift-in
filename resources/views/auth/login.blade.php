<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Thriftin</title>
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f0f0f0;
            text-align: center;
            padding: 30px;
            border-radius: 10px 10px 0 0 !important;
        }
        .card-header h4 {
            color: #5C8A6B; /* Sage */
            font-weight: 800;
        }
        .card-header h4 span { color: #D4956A; } /* Terracotta */
        .brand-logo {
            width: 70px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #5C8A6B;
            border-color: #5C8A6B;
        }
        .btn-primary:hover {
            background-color: #4a7358;
            border-color: #4a7358;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <img src="{{ asset('assets/img/logo.jpg') }}" class="brand-logo" alt="Logo">
                    <h4>Thrift<span>In</span></h4>
                    <p class="text-muted mb-0">Manajemen Titip Jual Preloved</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control" required placeholder="Masukkan username" value="{{ old('username') }}">
                            </div>
                            @error('username')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4">
                <p class="text-muted small">&copy; {{ date('Y') }} ThriftIn. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
