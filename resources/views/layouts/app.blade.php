<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - ThriftIn</title>
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --sage:       #5C8A6B;
            --terracotta: #D4956A;
            --cream:      #F5F0E8;
            --bg-color:   #F8FAFC;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', system-ui, sans-serif;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #fcfdfd 100%);
            color: #333;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
            border-right: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
        }
        .sidebar-brand {
            padding: 24px 20px;
            font-size: 24px;
            font-weight: 800;
            color: var(--sage);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }
        .sidebar-brand span { color: var(--terracotta); }
        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 15px 0;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            padding: 2px 16px;
        }
        .sidebar-menu a {
            color: #64748B;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(92, 138, 107, 0.1);
            color: var(--sage);
            transform: translateX(4px);
        }
        .sidebar-menu a.active {
            background-color: var(--sage);
            color: white;
            box-shadow: 0 4px 15px rgba(92, 138, 107, 0.3);
            transform: none;
        }
        .sidebar-menu a i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
            transition: transform 0.3s;
        }
        .sidebar-menu a:hover i { transform: scale(1.1); }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: radial-gradient(circle at top left, #ffffff 0%, var(--bg-color) 100%);
            transition: all 0.3s ease;
        }
        .topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 15px 30px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .user-chip {
            background: linear-gradient(135deg, rgba(92, 138, 107, 0.1), rgba(212, 149, 106, 0.1));
            border: 1px solid rgba(92, 138, 107, 0.2);
            border-radius: 20px;
            padding: 0.5rem 1.2rem;
            font-size: 0.85rem;
            color: var(--sage);
            font-weight: 600;
            transition: all 0.3s;
        }
        .user-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(92, 138, 107, 0.15);
        }
        .content {
            padding: 30px;
            flex: 1;
        }
        
        /* Modern Cards */
        .card {
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 20px;
            box-shadow: 0 4px 20px -2px rgba(0,0,0,0.02);
            background-color: #fff;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -4px rgba(0,0,0,0.08);
            border-color: rgba(92, 138, 107, 0.2);
        }

        /* Modern Buttons */
        .btn-primary { 
            background: linear-gradient(135deg, var(--sage), #4a7358);
            border: none; 
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(92, 138, 107, 0.3);
        }
        .btn-primary:hover { 
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(92, 138, 107, 0.4);
        }
    </style>
    @stack('css')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" style="width: 35px; border-radius: 6px;">
            Thrift<span>In</span>
        </div>
        <div class="sidebar-menu-wrapper">
            <ul class="sidebar-menu">
                <li>
                    <div class="px-3 py-2 text-muted small fw-bold text-uppercase">Menu Utama</div>
                </li>
                @php
                    $dashRoute = 'buyer.dashboard';
                    if(auth()->check()){
                        $r = auth()->user()->role;
                        if($r == 'super_admin') $dashRoute = 'superadmin.dashboard';
                        elseif(in_array($r, ['admin', 'admin_produk', 'admin_keuangan', 'cs', 'kasir'])) $dashRoute = 'admin.dashboard';
                        elseif($r == 'penjual') $dashRoute = 'seller.dashboard';
                    }
                @endphp
                <li>
                    <a href="{{ route($dashRoute) }}" class="{{ request()->routeIs($dashRoute) ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>

                <li>
                    <div class="px-3 mt-3 py-2 text-muted small fw-bold text-uppercase">Data Master</div>
                </li>
                <li>
                    <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i> Kategori Barang
                    </a>
                </li>
                <li>
                    <a href="{{ route('penitip.index') }}" class="{{ request()->routeIs('penitip.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Data Penitip
                    </a>
                </li>
                <li>
                    <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i> Barang Titipan
                    </a>
                </li>

                <li>
                    <div class="px-3 mt-3 py-2 text-muted small fw-bold text-uppercase">Transaksi</div>
                </li>
                <li>
                    <a href="{{ route('transaksi.index') }}" class="{{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i> Penjualan
                    </a>
                </li>
                @if(auth()->user()->role == 'admin')
                <li>
                    <a href="{{ route('pencairan.index') }}" class="{{ request()->routeIs('pencairan.*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-usd"></i> Pencairan Dana
                    </a>
                </li>
                <li>
                    <div class="px-3 mt-3 py-2 text-muted small fw-bold text-uppercase">Laporan</div>
                </li>
                <li>
                    <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Laporan
                    </a>
                </li>
                @endif
                <!-- Padding Bawah agar tidak tertutup taskbar -->
                <li class="mb-5 pb-5"></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h4 class="mb-0 text-dark">@yield('title', 'Dashboard')</h4>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="user-chip">
                    <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->nama }}
                </div>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm rounded-circle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenu">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} ThriftIn - Platform Manajemen Titip Jual Preloved. All rights reserved.
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(typeof AOS !== 'undefined') {
                AOS.init({ duration: 600, once: true, offset: 50 });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
