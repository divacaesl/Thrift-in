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
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            z-index: 10;
        }

        /* Glassmorphism Card */
        .card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
            border-radius: 20px;
            overflow: hidden;
            transform: translateY(30px);
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            text-align: center;
            padding: 40px 30px 20px;
        }

        .card-header h4 {
            color: #1E293B;
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.5px;
        }
        .card-header h4 span { color: #3B82F6; }
        
        .brand-logo {
            width: 80px;
            border-radius: 16px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .brand-logo:hover {
            transform: scale(1.05) rotate(-3deg);
        }

        .form-control {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(0,0,0,0.1);
            padding: 12px 15px;
            border-radius: 12px;
            transition: all 0.3s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            border-color: #3B82F6;
            background: #fff;
        }
        .input-group-text {
            background: transparent;
            border: 1px solid rgba(0,0,0,0.1);
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #64748B;
        }
        .form-control {
            border-left: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }

        /* Floating elements in background */
        .shape {
            position: absolute;
            filter: blur(60px);
            z-index: 1;
            opacity: 0.6;
        }
        .shape-1 {
            width: 300px; height: 300px;
            background: #F59E0B;
            border-radius: 50%;
            top: -100px; left: -100px;
        }
        .shape-2 {
            width: 400px; height: 400px;
            background: #8B5CF6;
            border-radius: 50%;
            bottom: -150px; right: -100px;
        }
    </style>
</head>
<body>
    <!-- Abstract shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="container d-flex justify-content-center">
        <div class="login-container">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert" style="backdrop-filter: blur(10px); background: rgba(254, 226, 226, 0.9);">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <img src="{{ asset('assets/img/logo.jpg') }}" onerror="this.src='https://via.placeholder.com/80?text=TI'" class="brand-logo" alt="Logo">
                    <h4>Thrift<span>In</span></h4>
                    <p class="text-muted mb-0 small fw-medium">Secure Platform Login</p>
                </div>
                <div class="card-body p-4 p-md-5 pt-3">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted ms-1">Email / Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control" required placeholder="admin@thriftin.com" value="{{ old('username') }}">
                            </div>
                            @error('username')
                                <small class="text-danger ms-1 mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1 ms-1">
                                <label class="form-label small fw-bold text-muted mb-0">Password</label>
                                <a href="#" class="small text-decoration-none text-primary">Lupa password?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control" required placeholder="••••••••">
                            </div>
                            @error('password')
                                <small class="text-danger ms-1 mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 mt-2">
                            Sign In <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-center mt-4 position-relative" style="z-index: 10;">
                <p class="text-white text-opacity-75 small fw-medium">&copy; {{ date('Y') }} ThriftIn. Premium Ecosystem.</p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
