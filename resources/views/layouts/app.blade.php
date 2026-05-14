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
    <!-- Custom CSS -->
    <style>
        :root {
            --sage:       #5C8A6B;
            --terracotta: #D4956A;
            --cream:      #F5F0E8;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            height: 100vh;
            background-color: #fff;
            color: #333;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
            border-right: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 20px;
            font-size: 24px;
            font-weight: 800;
            color: var(--sage);
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }
        .sidebar-brand span { color: var(--terracotta); }
        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
        }
        /* Custom scrollbar for sidebar */
        .sidebar-menu-wrapper::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-menu-wrapper::-webkit-scrollbar-thumb {
            background: #eee;
            border-radius: 10px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-menu li {
            padding: 2px 12px;
        }
        .sidebar-menu a {
            color: #555;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 8px 15px;
            border-radius: 8px;
            transition: 0.2s;
            font-size: 0.88rem;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: var(--cream);
            color: var(--sage);
            font-weight: 600;
        }
        .sidebar-menu i {
            width: 25px;
            text-align: center;
            margin-right: 8px;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background-color: #fff;
            padding: 12px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .user-chip {
            background: var(--cream);
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            color: var(--sage);
            font-weight: 600;
        }
        .content {
            padding: 25px;
            flex: 1;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .btn-primary { background-color: var(--sage); border: none; }
        .btn-primary:hover { background-color: #4a7358; }
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
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
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
    @stack('scripts')
</body>
</html>
