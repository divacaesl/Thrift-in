<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin Ecosystem - ThriftIn')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4F46E5; /* Indigo primary */
            --secondary: #F8FAFC;
            --accent: #10B981; /* Emerald */
            --accent-glow: rgba(16, 185, 129, 0.2);
            --bg-body: #F1F5F9; /* Clean light gray */
            --bg-card: #FFFFFF;
            --text-dark: #0F172A;
            --text-muted: #64748B;
            --border-color: rgba(0, 0, 0, 0.08);
            --sidebar-width: 280px;
        }

        .dark-mode {
            --primary: #818CF8;
            --secondary: #0F172A;
            --accent: #10B981;
            --accent-glow: rgba(16, 185, 129, 0.4);
            --bg-body: #0B0F19; /* Deep dark tech */
            --bg-card: #131A2B;
            --text-dark: #F8FAFC;
            --text-muted: #94A3B8;
            --border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Light/Dark mode backgrounds decoration */
        .bg-decor {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            pointer-events: none;
            opacity: 0.6;
        }
        .dark-mode .bg-decor {
            opacity: 1;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
        .dark-mode ::-webkit-scrollbar-thumb { background: #334155; }
        .dark-mode ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid var(--border-color);
            color: var(--text-dark);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow-y: auto;
        }
        .dark-mode .sidebar {
            background: rgba(15, 23, 42, 0.6);
            color: #fff;
        }

        .sidebar-brand {
            padding: 24px 20px;
            font-size: 1.4rem;
            font-weight: 900;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--text-dark);
        }

        .sidebar-brand .icon-wrap {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            border-radius: 10px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px var(--accent-glow);
        }

        .sidebar-menu {
            padding: 15px 0;
            list-style: none;
            margin: 0;
        }

        .menu-title {
            padding: 20px 20px 10px;
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 800;
            color: var(--text-muted);
            letter-spacing: 1.5px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            margin: 2px 12px;
            border-radius: 8px;
            font-weight: 500;
        }

        .sidebar-menu li a:hover, .sidebar-menu li.active > a {
            background-color: rgba(79, 70, 229, 0.08);
            color: var(--primary);
        }
        .dark-mode .sidebar-menu li a:hover, .dark-mode .sidebar-menu li.active > a {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--accent);
        }
        .sidebar-menu li.active > a {
            font-weight: 700;
            box-shadow: inset 3px 0 0 var(--primary);
        }
        .dark-mode .sidebar-menu li.active > a {
            box-shadow: inset 3px 0 0 var(--accent);
        }

        .sidebar-menu li a i {
            width: 24px;
            font-size: 1rem;
            margin-right: 12px;
            transition: transform 0.3s;
        }
        .sidebar-menu li a:hover i {
            transform: translateX(4px);
        }

        /* Main Content Styles */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.8);
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
        .dark-mode .topbar {
            background: rgba(11, 15, 25, 0.8);
        }

        .content-area {
            padding: 30px;
            flex-grow: 1;
        }

        /* Modern Card Styles */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            background-color: var(--bg-card);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
            color: var(--text-dark);
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -4px rgba(0,0,0,0.06);
            border-color: rgba(79, 70, 229, 0.2);
        }
        .dark-mode .card:hover {
            box-shadow: 0 12px 30px -4px rgba(0,0,0,0.5);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .badge-soft-danger { background-color: rgba(239, 68, 68, 0.1); color: #B91C1C; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 6px; }
        .badge-soft-warning { background-color: rgba(245, 158, 11, 0.1); color: #B45309; border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 6px; }
        .badge-soft-success { background-color: rgba(16, 185, 129, 0.1); color: #047857; border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 6px; }

        .dark-mode .badge-soft-danger { color: #FCA5A5; }
        .dark-mode .badge-soft-warning { color: #FCD34D; }
        .dark-mode .badge-soft-success { color: #6EE7B7; }

        /* Table Aesthetics */
        .table {
            color: var(--text-dark) !important;
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-dark);
            --bs-table-hover-bg: rgba(255,255,255,0.02);
            --bs-table-hover-color: var(--text-dark);
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.5px;
            padding-top: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted) !important;
        }
        .table td {
            vertical-align: middle;
            padding: 1rem 0.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark) !important;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #059669);
            border: none;
            color: #fff !important;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #059669, #047857);
            box-shadow: 0 4px 15px var(--accent-glow);
            color: #fff !important;
        }
        .btn-outline-light {
            border-color: var(--border-color) !important;
            color: var(--text-dark) !important;
        }
        .btn-outline-light:hover {
            background-color: var(--border-color) !important;
            color: var(--text-dark) !important;
        }

        /* Custom Adaptive Text Overrides */
        h1, h2, h3, h4, h5, h6, .fw-bold {
            color: var(--text-dark) !important;
        }
        span, p, small, td, th {
            color: var(--text-dark);
        }
        .text-muted {
            color: var(--text-muted) !important;
        }
        /* Override hardcoded white class on titles/cards */
        .text-white {
            color: var(--text-dark) !important;
        }
        
        /* Ensure primary actions remain white text */
        .btn-primary, .btn-success, .btn-danger, .badge, .btn-primary *, .btn-success *, .btn-danger *, .badge * {
            color: #ffffff !important;
        }

        /* Inputs & Select Elements Adaptation */
        .form-control, .form-select, .form-control:focus, .form-select:focus {
            background-color: var(--bg-body) !important;
            color: var(--text-dark) !important;
            border-color: var(--border-color) !important;
        }
        .form-control::placeholder {
            color: var(--text-muted) !important;
            opacity: 0.6;
        }

        /* Modals & Layout Overlays */
        .modal-content {
            background-color: var(--bg-card) !important;
            color: var(--text-dark) !important;
            border: 1px solid var(--border-color) !important;
        }
        .modal-header, .modal-footer {
            border-color: var(--border-color) !important;
        }
        .btn-close {
            filter: var(--text-dark) === '#0F172A' ? 'none' : 'invert(1)';
        }
        .dark-mode .btn-close {
            filter: invert(1);
        }
    </style>
</head>
<body>
    <div class="bg-decor" style="background-image: radial-gradient(circle at 15% 50%, rgba(79, 70, 229, 0.05), transparent 25%), radial-gradient(circle at 85% 30%, rgba(59, 130, 246, 0.05), transparent 25%);"></div>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="icon-wrap">
                <i class="fas fa-bolt"></i>
            </div>
            <span>SuperAdmin</span>
        </div>
        <ul class="sidebar-menu">
            <!-- 1 & 2. Full Access & Dashboard -->
            <li class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('superadmin.dashboard') }}"><i class="fas fa-chart-line"></i> Main Dashboard</a>
            </li>

            <!-- 3. Manajemen Admin -->
            <div class="menu-title">Admin Management</div>
            <li class="{{ request()->routeIs('superadmin.admins.*') ? 'active' : '' }}">
                <a href="{{ route('superadmin.admins.index') }}"><i class="fas fa-users-gear"></i> Kelola Admin</a>
            </li>

            <!-- 4 & 5. Manajemen User Global & Seller Verification -->
            <div class="menu-title">User Network</div>
            <li class="{{ request()->routeIs('superadmin.users.global') ? 'active' : '' }}">
                <a href="{{ route('superadmin.users.global') }}"><i class="fas fa-users"></i> Global Users</a>
            </li>
            <li class="{{ request()->routeIs('superadmin.users.kyc') ? 'active' : '' }}">
                <a href="{{ route('superadmin.users.kyc') }}"><i class="fas fa-id-badge"></i> Seller Verification</a>
            </li>

            <!-- 6 & 7. Monitoring Produk & Transaksi Global -->
            <div class="menu-title">Global Monitoring</div>
            <li class="{{ request()->routeIs('superadmin.products.global') ? 'active' : '' }}">
                <a href="{{ route('superadmin.products.global') }}"><i class="fas fa-box-open"></i> Semua Produk</a>
            </li>
            <li class="{{ request()->routeIs('superadmin.transactions.global') ? 'active' : '' }}">
                <a href="{{ route('superadmin.transactions.global') }}"><i class="fas fa-globe"></i> Semua Transaksi</a>
            </li>

            <!-- 8 & 9. Sistem Keuangan & Payment Gateway -->
            <div class="menu-title">Financial & Gateway</div>
            <li class="{{ request()->routeIs('superadmin.finance.dashboard') ? 'active' : '' }}">
                <a href="{{ route('superadmin.finance.dashboard') }}"><i class="fas fa-vault"></i> Platform Revenue</a>
            </li>
            <li class="{{ request()->routeIs('superadmin.settings.payment') ? 'active' : '' }}">
                <a href="{{ route('superadmin.settings.payment') }}"><i class="fas fa-credit-card"></i> Payment Gateways</a>
            </li>

            <!-- 10 & 16. Website Global & CMS -->
            <div class="menu-title">Platform Content</div>
            <li class="{{ request()->routeIs('superadmin.cms.index') ? 'active' : '' }}">
                <a href="{{ route('superadmin.cms.index') }}"><i class="fas fa-laptop-code"></i> CMS & Pages</a>
            </li>

            <!-- 11 & 12. System Settings & Security -->
            <div class="menu-title">Core System & Security</div>
            <li class="{{ request()->routeIs('superadmin.security.server') ? 'active' : '' }}">
                <a href="{{ route('superadmin.security.server') }}"><i class="fas fa-server"></i> Server Monitor</a>
            </li>
            <li class="{{ request()->routeIs('superadmin.security.logs') ? 'active' : '' }}">
                <a href="{{ route('superadmin.security.logs') }}"><i class="fas fa-shield-halved"></i> Access & Security Logs</a>
            </li>
            
            <!-- 13 & 14. Chat Monitor & Broadcast -->
            <div class="menu-title">Communications</div>
            <li class="{{ request()->routeIs('superadmin.communication.broadcast') ? 'active' : '' }}">
                <a href="{{ route('superadmin.communication.broadcast') }}"><i class="fas fa-bullhorn"></i> Broadcast Notifications</a>
            </li>

            <!-- 15. Analytics & BI -->
            <div class="menu-title">Business Intelligence</div>
            <li class="{{ request()->routeIs('superadmin.analytics.bi') ? 'active' : '' }}">
                <a href="{{ route('superadmin.analytics.bi') }}"><i class="fas fa-chart-pie"></i> Advanced Analytics</a>
            </li>

            <!-- 17. Voucher -->
            <div class="menu-title">Promotions</div>
            <li class="{{ request()->routeIs('superadmin.promo.vouchers') ? 'active' : '' }}">
                <a href="{{ route('superadmin.promo.vouchers') }}"><i class="fas fa-ticket"></i> Voucher & Flash Sale</a>
            </li>

            <!-- 18. Komplain & Dispute -->
            <div class="menu-title">Resolution Center</div>
            <li class="{{ request()->routeIs('superadmin.dispute.index') ? 'active' : '' }}">
                <a href="{{ route('superadmin.dispute.index') }}"><i class="fas fa-gavel"></i> Global Disputes</a>
            </li>

            <!-- 19 & 20. API & Multi Platform -->
            <div class="menu-title">Integration & API</div>
            <li class="{{ request()->routeIs('superadmin.api.index') ? 'active' : '' }}">
                <a href="{{ route('superadmin.api.index') }}"><i class="fas fa-code"></i> API Keys & Mobile App</a>
            </li>
            <li class="pb-5 mb-5"></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-light btn-sm d-lg-none sidebar-toggler" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="d-none d-md-flex align-items-center gap-2">
                    <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50"><i class="fas fa-circle ms-1" style="font-size: 6px; vertical-align: middle;"></i> SYSTEM HEALTH: OPTIMAL</span>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <button class="btn btn-outline-light btn-sm text-dark px-3" onclick="toggleTheme()" style="border-color: var(--border-color); color: var(--text-dark) !important;">
                    <i class="fas fa-circle-half-stroke"></i>
                </button>

                <div class="dropdown">
                    <a href="#" class="text-muted position-relative text-decoration-none" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-dark rounded-circle"></span>
                    </a>
                </div>

                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle border border-secondary p-1 pe-3 rounded-pill" style="background: rgba(255,255,255,0.02);" data-bs-toggle="dropdown">
                        <div class="bg-accent text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2 shadow-sm" style="width: 34px; height: 34px; background: var(--accent);">
                            SA
                        </div>
                        <div class="d-none d-md-block text-start">
                            <div class="fw-bold text-white" style="font-size: 0.85rem; line-height: 1;">{{ Auth::user()->nama ?? 'God Mode' }}</div>
                            <small class="text-success" style="font-size: 0.7rem; letter-spacing: 0.5px;">SUPER ADMIN</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border border-secondary" style="background-color: var(--bg-card);">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2 hover-bg-dark"><i class="fas fa-power-off me-2"></i> Shutdown Session</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js (Required for SA BI) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Check local storage for theme
        if (localStorage.getItem('sa-theme') === 'dark') {
            document.documentElement.classList.add('dark-mode');
        }

        function toggleTheme() {
            const html = document.documentElement;
            if (html.classList.contains('dark-mode')) {
                html.classList.remove('dark-mode');
                localStorage.setItem('sa-theme', 'light');
            } else {
                html.classList.add('dark-mode');
                localStorage.setItem('sa-theme', 'dark');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
