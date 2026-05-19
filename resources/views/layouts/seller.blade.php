@php
    $lang = session('preferred_language', 'id');
    $theme = session('theme_mode', 'light');
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" class="{{ $theme == 'dark' ? 'dark-mode' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard - ThriftIn')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary:       #4F46E5; /* Indigo */
            --primary-hover: #4338CA;
            --accent:        #F59E0B; /* Amber */
            --bg-sidebar:    #0F172A; /* Slate 900 */
            --bg-body:       #F8FAFC; /* Slate 50 */
            --bg-card:       #ffffff;
            --text-dark:     #1E293B;
            --text-muted:    #64748B;
            --border-color:  rgba(226, 232, 240, 0.8);
            --transition:    all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .dark-mode {
            --primary:       #818CF8;
            --primary-hover: #6366F1;
            --accent:        #FBBF24;
            --bg-sidebar:    #020617;
            --bg-body:       #0F172A;
            --bg-card:       #1E293B;
            --text-dark:     #F8FAFC;
            --text-muted:    #94A3B8;
            --border-color:  rgba(51, 65, 85, 0.8);
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            font-family: 'Outfit', sans-serif;
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
        }

        /* Custom Modern Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
        .dark-mode ::-webkit-scrollbar-thumb { background: #475569; }

        /* Sidebar styling */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--bg-sidebar) 0%, #1E293B 100%);
            min-height: 100vh;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: var(--transition);
            box-shadow: 4px 0 24px rgba(0,0,0,0.06);
        }
        .sidebar-brand {
            padding: 24px;
            font-size: 22px;
            font-weight: 800;
            color: var(--primary) !important;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }
        .sidebar-brand span {
            color: var(--accent);
        }
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }
        .sidebar-item {
            margin: 4px 16px;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #94A3B8;
            text-decoration: none;
            font-weight: 500;
            border-radius: 12px;
            transition: var(--transition);
        }
        .sidebar-link:hover, .sidebar-item.active .sidebar-link {
            background-color: rgba(255,255,255,0.06);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar-item.active .sidebar-link {
            background-color: var(--primary);
            color: #fff;
            transform: none;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        }
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            background-color: rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        /* Main Content wrapper */
        .main-wrapper {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
            background: radial-gradient(circle at top left, var(--bg-body) 0%, transparent 100%);
        }

        /* Header Navbar */
        .seller-header {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
            transition: var(--transition);
        }
        .dark-mode .seller-header { background-color: rgba(30, 41, 59, 0.7); }

        /* Cards */
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: var(--transition);
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.06);
            border-color: rgba(79, 70, 229, 0.3);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 8px 18px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        /* Badge Icon Wrapper */
        .icon-badge-wrapper {
            position: relative;
            display: inline-block;
            padding: 8px;
            border-radius: 50%;
            background-color: var(--border-color);
            color: var(--text-dark);
            transition: var(--transition);
            text-decoration: none;
        }
        .icon-badge-wrapper:hover {
            background-color: var(--primary);
            color: white;
            transform: rotate(10deg);
        }
        .icon-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: var(--accent);
            color: white;
            border-radius: 50%;
            padding: 3px 6px;
            font-size: 0.65rem;
            font-weight: 700;
            line-height: 1;
        }

        /* Table */
        .table-responsive {
            background-color: var(--bg-card);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
            color: var(--text-dark);
            --bs-table-bg: transparent;
        }
        .table th {
            background-color: rgba(0,0,0,0.02);
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        .table td {
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        /* Theme & Language Switchers */
        .btn-toggle-theme, .btn-toggle-lang {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-toggle-theme:hover, .btn-toggle-lang:hover {
            background-color: var(--border-color);
            transform: scale(1.05);
        }

        /* Print Media Styles */
        @media print {
            .sidebar, .seller-header, .no-print {
                display: none !important;
            }
            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }
            body {
                background-color: white !important;
                color: black !important;
            }
        }
    </style>
    @stack('css')
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <a href="{{ route('seller.dashboard') }}" class="sidebar-brand">
            <i class="fas fa-store"></i> Thrift<span>In</span> Seller
        </a>
        
        <ul class="sidebar-menu">
            <li class="sidebar-item {{ Request::routeIs('seller.dashboard') ? 'active' : '' }}">
                <a href="{{ route('seller.dashboard') }}" class="sidebar-link">
                    <i class="fas fa-chart-pie"></i>
                    <span>{{ $lang == 'en' ? 'Dashboard' : 'Dasbor Utama' }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('seller.product.*') ? 'active' : '' }}">
                <a href="{{ route('seller.product.index') }}" class="sidebar-link">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>{{ $lang == 'en' ? 'Manage Products' : 'Kelola Produk' }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('seller.order.*') ? 'active' : '' }}">
                <a href="{{ route('seller.order.index') }}" class="sidebar-link">
                    <i class="fas fa-receipt"></i>
                    <span>{{ $lang == 'en' ? 'Orders List' : 'Manajemen Pesanan' }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('seller.finance.*') ? 'active' : '' }}">
                <a href="{{ route('seller.finance.index') }}" class="sidebar-link">
                    <i class="fas fa-wallet"></i>
                    <span>{{ $lang == 'en' ? 'Finance & Payout' : 'Keuangan & Saldo' }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('seller.chat') ? 'active' : '' }}">
                <a href="{{ route('seller.chat') }}" class="sidebar-link">
                    <i class="fas fa-comment-dots"></i>
                    <span>{{ $lang == 'en' ? 'Chats & Nego' : 'Chat & Negosiasi' }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('seller.promo.*') ? 'active' : '' }}">
                <a href="{{ route('seller.promo.index') }}" class="sidebar-link">
                    <i class="fas fa-rectangle-ad"></i>
                    <span>{{ $lang == 'en' ? 'Discounts & Boost' : 'Promosi Produk' }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('seller.review.index') ? 'active' : '' }}">
                <a href="{{ route('seller.review.index') }}" class="sidebar-link">
                    <i class="fas fa-star"></i>
                    <span>{{ $lang == 'en' ? 'Reviews' : 'Ulasan Pembeli' }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ Request::routeIs('seller.profile') ? 'active' : '' }}">
                <a href="{{ route('seller.profile') }}" class="sidebar-link">
                    <i class="fas fa-cog"></i>
                    <span>{{ $lang == 'en' ? 'Shop Settings' : 'Profil & Toko' }}</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-user-shield text-accent"></i>
                <div style="font-size: 0.85rem;">
                    <p class="mb-0 fw-semibold">{{ Auth::user()->nama }}</p>
                    <small class="text-muted">{{ $lang == 'en' ? 'Verified Seller' : 'Penjual Terverifikasi' }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="seller-header">
            <div class="d-flex align-items-center gap-2">
                <h5 class="mb-0 fw-bold d-none d-md-block">
                    @auth
                        @if(Auth::user()->penitip)
                            {{ Auth::user()->penitip->nama }}
                        @else
                            {{ Auth::user()->nama }} Shop
                        @endif
                    @endauth
                </h5>
                <a href="{{ route('buyer.home') }}" class="btn btn-sm btn-outline-primary ms-3">
                    <i class="fas fa-arrow-left me-1"></i> {{ $lang == 'en' ? 'Storefront' : 'Halaman Utama' }}
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Lang -->
                <a href="{{ route('buyer.lang', $lang == 'id' ? 'en' : 'id') }}" class="btn-toggle-lang">
                    <i class="fas fa-globe"></i> {{ $lang == 'id' ? 'EN' : 'ID' }}
                </a>

                <!-- Theme -->
                <button type="button" class="btn-toggle-theme" id="themeTogglerBtn">
                    <i class="fas {{ $theme == 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                </button>

                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    @php
                        $notifs = \App\Models\Notifikasi::where('user_id', Auth::id())->latest()->get();
                        $unreadNotifs = $notifs->where('is_read', false)->count();
                    @endphp
                    <a class="icon-badge-wrapper dropdown-toggle no-caret" href="#" id="notifDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        @if($unreadNotifs > 0)
                            <span class="icon-badge">{{ $unreadNotifs }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2 mt-2 shadow" style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <h6 class="pb-2 mb-2 border-bottom fw-bold">{{ $lang == 'en' ? 'Store Activity' : 'Aktivitas Toko' }}</h6>
                        @if($notifs->isEmpty())
                            <div class="text-center py-3 text-muted">
                                <p class="mb-0 small">{{ $lang == 'en' ? 'No store activities yet' : 'Belum ada aktivitas' }}</p>
                            </div>
                        @else
                            @foreach($notifs->take(5) as $n)
                                <li class="p-2 mb-1 rounded {{ !$n->is_read ? 'bg-light border-start border-primary border-3' : '' }}" style="font-size: 0.85rem;" onclick="markAsRead({{ $n->id }})">
                                    <span class="fw-bold text-dark">{{ $n->judul }}</span>
                                    <p class="mb-0 text-muted small mt-1">{{ $n->pesan }}</p>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown">
                    <a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" href="#" id="profileMenu" data-bs-toggle="dropdown">
                        <img src="{{ asset('uploads/profiles/' . Auth::user()->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" alt="profile" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileMenu">
                        <li><a class="dropdown-item" href="{{ route('seller.profile') }}"><i class="fas fa-store me-2"></i>{{ $lang == 'en' ? 'Store Profile' : 'Profil Toko' }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('seller.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>{{ $lang == 'en' ? 'Logout' : 'Keluar' }}</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="container-fluid my-4 px-4 flex-grow-1">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme switching handler
        const toggler = document.getElementById('themeTogglerBtn');
        if (toggler) {
            toggler.addEventListener('click', function() {
                const html = document.documentElement;
                const icon = toggler.querySelector('i');
                
                if (html.classList.contains('dark-mode')) {
                    html.classList.remove('dark-mode');
                    icon.className = 'fas fa-moon';
                    saveThemePreference('light');
                } else {
                    html.classList.add('dark-mode');
                    icon.className = 'fas fa-sun';
                    saveThemePreference('dark');
                }
            });
        }

        function saveThemePreference(theme) {
            fetch("{{ url('/buyer/toggle-theme') }}/" + theme, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
        }

        // Notification reading handler
        function markAsRead(id) {
            fetch("{{ url('/buyer/notification/read') }}/" + id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                window.location.reload();
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
