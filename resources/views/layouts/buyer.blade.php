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
    <title>@yield('title', 'ThriftIn - Preloved Online')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary:       #5C8A6B; /* Sage */
            --primary-hover: #4a7358;
            --accent:        #D4956A; /* Terracotta */
            --bg-body:       #F8F6F2; /* Cream Light */
            --bg-card:       #ffffff;
            --text-dark:     #2B2A27;
            --text-muted:    #7D7B76;
            --border-color:  #E8E6E0;
            --transition:    all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --nav-bg:        rgba(255, 255, 255, 0.85);
        }
        
        .dark-mode {
            --primary:       #82A98F;
            --primary-hover: #9EC0A9;
            --accent:        #E0A37E;
            --bg-body:       #121212;
            --bg-card:       #1E1E1E;
            --text-dark:     #F3F2EE;
            --text-muted:    #A3A19C;
            --border-color:  #2D2C2A;
            --nav-bg:        rgba(30, 30, 30, 0.85);
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            font-family: 'Outfit', sans-serif;
            transition: var(--transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Glassmorphism Navbar */
        .navbar {
            background: var(--nav-bg);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1050;
            transition: var(--transition);
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-brand span {
            color: var(--accent);
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            transition: var(--transition);
        }
        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* Cards */
        .card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            transition: var(--transition);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: 10px;
            font-weight: 600;
            padding: 10px 20px;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            border-radius: 10px;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .text-muted {
            color: var(--text-muted) !important;
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
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 450px;
            width: 100%;
        }
        .search-input {
            width: 100%;
            background-color: var(--bg-body);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 8px 16px 8px 40px;
            color: var(--text-dark);
            transition: var(--transition);
            outline: none;
        }
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(92, 138, 107, 0.15);
        }
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
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

        .dropdown-menu {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }
        .dropdown-item {
            color: var(--text-dark);
            font-weight: 500;
            transition: var(--transition);
        }
        .dropdown-item:hover {
            background-color: var(--border-color);
            color: var(--primary);
        }

        .footer {
            background-color: var(--bg-card);
            border-top: 1px solid var(--border-color);
            padding: 40px 0 20px 0;
            margin-top: auto;
            color: var(--text-muted);
            transition: var(--transition);
        }

        /* Micro-animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .pulse-hover:hover {
            animation: pulse 1s infinite;
        }
    </style>
    @stack('css')
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container py-1">
            <!-- Brand Logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-bag-shopping me-1"></i> Thrift<span>In</span>
            </a>

            <!-- Search bar -->
            <form action="{{ url('/') }}" method="GET" class="search-container mx-lg-4 d-none d-lg-block">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="q" class="search-input" value="{{ request('q') }}" placeholder="{{ $lang == 'en' ? 'Search vintage jacket, leather bag, etc...' : 'Cari jaket vintage, tas kulit, dll...' }}">
            </form>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <!-- Mobile Search -->
                <form action="{{ url('/') }}" method="GET" class="search-container my-3 d-lg-none">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" class="search-input" value="{{ request('q') }}" placeholder="{{ $lang == 'en' ? 'Search...' : 'Cari...' }}">
                </form>

                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <!-- Home link -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ url('/') }}">
                            {{ $lang == 'en' ? 'Home' : 'Beranda' }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ route('buyer.support') }}">
                            {{ $lang == 'en' ? 'CS Help' : 'Bantuan CS' }}
                        </a>
                    </li>

                    <!-- Language Selector -->
                    <li class="nav-item">
                        <a href="{{ route('buyer.lang', $lang == 'id' ? 'en' : 'id') }}" class="btn-toggle-lang">
                            <i class="fas fa-globe"></i> {{ $lang == 'id' ? 'EN' : 'ID' }}
                        </a>
                    </li>

                    <!-- Theme Toggler -->
                    <li class="nav-item">
                        <button type="button" class="btn-toggle-theme" id="themeTogglerBtn">
                            <i class="fas {{ $theme == 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                        </button>
                    </li>

                    @auth
                        <!-- Chat icon -->
                        <li class="nav-item">
                            <a href="{{ route('buyer.chat') }}" class="icon-badge-wrapper pulse-hover" title="{{ $lang == 'en' ? 'Chats' : 'Pesan' }}">
                                <i class="fas fa-comment-dots"></i>
                                @php
                                    $unreadChats = \App\Models\Chat::where('receiver_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unreadChats > 0)
                                    <span class="icon-badge">{{ $unreadChats }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Wishlist icon -->
                        <li class="nav-item">
                            <a href="{{ route('buyer.dashboard') }}#wishlist" class="icon-badge-wrapper pulse-hover" title="{{ $lang == 'en' ? 'Wishlist' : 'Favorit' }}">
                                <i class="fas fa-heart"></i>
                                @php
                                    $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                                @endphp
                                @if($wishlistCount > 0)
                                    <span class="icon-badge">{{ $wishlistCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Cart icon -->
                        <li class="nav-item">
                            <a href="{{ route('buyer.cart') }}" class="icon-badge-wrapper pulse-hover" title="{{ $lang == 'en' ? 'Cart' : 'Keranjang' }}">
                                <i class="fas fa-shopping-cart"></i>
                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->where('is_saved_for_later', false)->count();
                                @endphp
                                @if($cartCount > 0)
                                    <span class="icon-badge">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>

                        <!-- Notifications Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="icon-badge-wrapper dropdown-toggle no-caret" href="#" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                @php
                                    $notifs = \App\Models\Notifikasi::where('user_id', auth()->id())->get();
                                    $unreadNotifs = $notifs->where('is_read', false)->count();
                                @endphp
                                @if($unreadNotifs > 0)
                                    <span class="icon-badge">{{ $unreadNotifs }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end p-2 mt-2 shadow" aria-labelledby="notifDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                                    <h6 class="mb-0 fw-bold">{{ $lang == 'en' ? 'Notifications' : 'Notifikasi' }}</h6>
                                    <form action="{{ route('buyer.notif.readall') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-primary p-0 text-decoration-none small fw-semibold" style="font-size: 0.8rem;">
                                            {{ $lang == 'en' ? 'Clear All' : 'Baca Semua' }}
                                        </button>
                                    </form>
                                </div>
                                @if($notifs->isEmpty())
                                    <div class="text-center py-3 text-muted">
                                        <i class="fas fa-bell-slash mb-2 fs-4"></i>
                                        <p class="mb-0 small">{{ $lang == 'en' ? 'No notifications yet' : 'Belum ada notifikasi' }}</p>
                                    </div>
                                @else
                                    @foreach($notifs->take(5) as $n)
                                        <li class="p-2 mb-1 rounded {{ !$n->is_read ? 'bg-light border-start border-primary border-3' : '' }}" style="font-size: 0.85rem;" onclick="markAsRead({{ $n->id }})">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-dark">{{ $n->judul }}</span>
                                                <span class="text-muted small" style="font-size: 0.75rem;">{{ $n->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="mb-0 text-muted small mt-1">{{ $n->pesan }}</p>
                                        </li>
                                    @endforeach
                                @endif
                                <div class="border-top pt-2 text-center">
                                    <a href="{{ route('buyer.dashboard') }}#notifications" class="text-primary text-decoration-none small fw-bold" style="font-size: 0.8rem;">
                                        {{ $lang == 'en' ? 'View All Activity' : 'Lihat Semua Aktivitas' }}
                                    </a>
                                </div>
                            </ul>
                        </li>

                        <!-- User Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 fw-semibold" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('uploads/profiles/' . auth()->user()->foto_profil) }}" onerror="this.src='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/icons/person-circle.svg'" alt="profile" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
                                <span class="d-none d-lg-inline">{{ auth()->user()->nama }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="profileDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('buyer.dashboard') }}">
                                        <i class="fas fa-user-circle me-2"></i> {{ $lang == 'en' ? 'Buyer Dashboard' : 'Dashboard Pembeli' }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('buyer.profile') }}">
                                        <i class="fas fa-cog me-2"></i> {{ $lang == 'en' ? 'Edit Profile & Address' : 'Edit Profil & Alamat' }}
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'kasir')
                                    <li>
                                        <a class="dropdown-item text-primary" href="{{ route('dashboard') }}">
                                            <i class="fas fa-shield-halved me-2"></i> {{ $lang == 'en' ? 'Seller Panel' : 'Panel Penjual' }}
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <form action="{{ route('buyer.logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> {{ $lang == 'en' ? 'Logout' : 'Keluar' }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest login / register buttons -->
                        <li class="nav-item">
                            <a class="btn btn-outline-primary" href="{{ route('buyer.login') }}">{{ $lang == 'en' ? 'Login' : 'Masuk' }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('buyer.register') }}">{{ $lang == 'en' ? 'Register' : 'Daftar' }}</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container my-4 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
                <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-bag-shopping text-primary me-2"></i>ThriftIn</h5>
                    <p class="small text-muted">{{ $lang == 'en' ? 'Modern preloved marketplace safely powered by Escrow systems. Find vintage clothing, bags, shoes, electronics and collectibles with live price negotiations.' : 'Marketplace barang preloved modern yang ditenagai oleh sistem Escrow yang aman. Temukan pakaian vintage, tas, sepatu, elektronik, dan koleksi dengan negosiasi harga langsung.' }}</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-dark mb-3">{{ $lang == 'en' ? 'Categories' : 'Kategori' }}</h6>
                    <ul class="list-unstyled small">
                        <li><a href="{{ url('/?kategori=1') }}" class="text-decoration-none text-muted">Fashion Pria</a></li>
                        <li><a href="{{ url('/?kategori=2') }}" class="text-decoration-none text-muted">Fashion Wanita</a></li>
                        <li><a href="{{ url('/?kategori=3') }}" class="text-decoration-none text-muted">Sepatu</a></li>
                        <li><a href="{{ url('/?kategori=4') }}" class="text-decoration-none text-muted">Tas</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold text-dark mb-3">ThriftIn</h5>
                    <ul class="list-unstyled small">
                        <li><a href="{{ route('buyer.support') }}" class="text-decoration-none text-muted">FAQ</a></li>
                        <li><a href="{{ route('buyer.support') }}#complaint" class="text-decoration-none text-muted">{{ $lang == 'en' ? 'Refund Form' : 'Form Pengembalian' }}</a></li>
                        <li><a href="{{ route('buyer.support') }}#chat" class="text-decoration-none text-muted">{{ $lang == 'en' ? 'Live Support' : 'Live CS Admin' }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold text-dark mb-3">{{ $lang == 'en' ? 'Escrow Guarantee' : 'Jaminan Rekening Bersama' }}</h6>
                    <div class="p-3 rounded border" style="background-color: var(--bg-body);">
                        <p class="small mb-0 text-muted"><i class="fas fa-shield-halved text-primary me-2"></i>{{ $lang == 'en' ? 'Funds are safely held until you confirm delivery of your preloved items. Fraud protected.' : 'Dana ditahan dengan aman hingga Anda mengonfirmasi penerimaan barang preloved. Terlindungi dari penipuan.' }}</p>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: var(--border-color);">
            <div class="text-center small">
                &copy; {{ date('Y') }} ThriftIn. {{ $lang == 'en' ? 'All rights reserved.' : 'Hak Cipta Dilindungi.' }}
            </div>
        </div>
    </footer>

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
