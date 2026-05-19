<!DOCTYPE html>
<html lang="{{ session('preferred_language', 'id') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - ThriftIn')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0F172A;
            --secondary: #334155;
            --accent: #3B82F6;
            --bg-body: #F1F5F9;
            --bg-card: #FFFFFF;
            --text-dark: #1E293B;
            --border-color: rgba(226, 232, 240, 0.8);
            --sidebar-width: 260px;
            --glass-bg: rgba(255, 255, 255, 0.7);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Custom Modern Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #0F172A 0%, #1E293B 100%);
            color: #fff;
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow-y: auto;
            box-shadow: 4px 0 24px rgba(0,0,0,0.06);
        }

        .sidebar-brand {
            padding: 24px 20px;
            font-size: 1.4rem;
            font-weight: 800;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .sidebar-menu {
            padding: 15px 0;
            list-style: none;
            margin: 0;
        }

        .menu-title {
            padding: 15px 20px 10px;
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 800;
            color: #64748B;
            letter-spacing: 1.5px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #94A3B8;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            margin: 4px 12px;
            border-radius: 10px;
            font-weight: 500;
        }

        .sidebar-menu li a:hover, .sidebar-menu li.active > a {
            background-color: rgba(59, 130, 246, 0.15);
            color: #fff;
        }
        .sidebar-menu li.active > a {
            box-shadow: inset 3px 0 0 var(--accent);
        }

        .sidebar-menu li a i {
            width: 24px;
            font-size: 1.1rem;
            margin-right: 12px;
            transition: transform 0.3s;
        }
        .sidebar-menu li a:hover i {
            transform: translateX(4px);
            color: var(--accent);
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: radial-gradient(circle at top left, #F8FAFC 0%, #F1F5F9 100%);
        }

        .topbar {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            height: 75px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .content-area {
            padding: 30px;
            flex-grow: 1;
        }

        /* Modern Card Styles */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03);
            background-color: var(--bg-card);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -4px rgba(0,0,0,0.08);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .badge-soft-danger { background-color: #FEE2E2; color: #EF4444; border-radius: 6px;}
        .badge-soft-warning { background-color: #FEF3C7; color: #D97706; border-radius: 6px;}
        .badge-soft-success { background-color: #D1FAE5; color: #059669; border-radius: 6px;}

        /* Modern Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-1px);
        }

        /* Table Aesthetics */
        .table {
            --bs-table-bg: transparent;
        }
        .table th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .table td {
            vertical-align: middle;
            padding: 1rem 0.5rem;
            border-bottom-color: var(--border-color);
        }

        @media (max-width: 991.98px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .sidebar.show { margin-left: 0; }
            .main-content { margin-left: 0; }
            .sidebar-toggler { display: block !important; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="bg-primary text-accent rounded-circle d-flex align-items-center justify-content-center border border-accent shadow-sm" style="width: 32px; height: 32px;">
                <i class="fas fa-shield-halved fs-6"></i>
            </div>
            <span>ThriftIn Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
            </li>

            <div class="menu-title">Manajemen Platform</div>
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}"><i class="fas fa-users-gear"></i> Users & Roles</a>
            </li>
            <li class="{{ request()->routeIs('admin.users.seller_kyc') ? 'active' : '' }}">
                <a href="{{ route('admin.users.seller_kyc') }}"><i class="fas fa-id-card"></i> Seller KYC</a>
            </li>
            <li class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}"><i class="fas fa-box-open"></i> Moderasi Produk</a>
            </li>
            <li class="{{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <a href="{{ route('admin.categories') }}"><i class="fas fa-tags"></i> Kategori</a>
            </li>

            <div class="menu-title">Transaksi & Keuangan</div>
            <li class="{{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <a href="{{ route('admin.transactions.index') }}"><i class="fas fa-money-check-dollar"></i> Transaksi & Dispute</a>
            </li>
            <li class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                <a href="{{ route('admin.finance.index') }}"><i class="fas fa-building-columns"></i> Pencairan & Kas</a>
            </li>

            <div class="menu-title">Operasional & Konten</div>
            <li class="{{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <a href="{{ route('admin.support.index') }}"><i class="fas fa-headset"></i> Support Tickets</a>
            </li>
            <li class="{{ request()->routeIs('admin.content.banners') ? 'active' : '' }}">
                <a href="{{ route('admin.content.banners') }}"><i class="fas fa-image"></i> Banners & Promo</a>
            </li>
            <li class="{{ request()->routeIs('admin.content.vouchers') ? 'active' : '' }}">
                <a href="{{ route('admin.content.vouchers') }}"><i class="fas fa-ticket"></i> Vouchers</a>
            </li>

            <div class="menu-title">Sistem</div>
            <li class="{{ request()->request->get('routeIs', 'admin.system.settings') ? 'active' : '' }}">
                <a href="{{ route('admin.system.settings') }}"><i class="fas fa-sliders"></i> Pengaturan Global</a>
            </li>
            <li class="{{ request()->routeIs('admin.system.logs') ? 'active' : '' }}">
                <a href="{{ route('admin.system.logs') }}"><i class="fas fa-server"></i> Activity Logs</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3 sidebar-toggler rounded-circle" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark d-none d-md-block" style="letter-spacing: -0.5px;">Admin Control Panel</h5>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <div class="dropdown">
                    <a href="#" class="text-secondary position-relative text-decoration-none hover-primary" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2" style="width: 320px;">
                        <h6 class="dropdown-header fw-bold text-dark border-bottom pb-2">System Alerts</h6>
                        <a class="dropdown-item py-3 border-bottom d-flex align-items-start gap-3" href="#">
                            <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-circle"><i class="fas fa-id-card"></i></div>
                            <div>
                                <div class="fw-bold small">New KYC Pending</div>
                                <div class="text-muted" style="font-size: 0.75rem;">1 seller is waiting for verification.</div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle border p-1 pe-3 rounded-pill bg-white shadow-sm" data-bs-toggle="dropdown">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 36px; height: 36px;">
                            {{ substr(Auth::user()->nama ?? 'A', 0, 1) }}
                        </div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1;">{{ Auth::user()->nama ?? 'Administrator' }}</div>
                            <small class="text-muted" style="font-size: 0.7rem;">{{ strtoupper(Auth::user()->role ?? 'ADMIN') }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                        <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-shield me-2 text-primary"></i> My Account</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2"><i class="fas fa-right-from-bracket me-2"></i> Keluar</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" data-aos="fade-down">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" data-aos="fade-down">
                    <i class="fas fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS safely without hiding all cards
            if(typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    once: true,
                    offset: 50
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
