<!-- includes/sidebar.php -->
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? 'kasir';

$menus = [
    [
        'icon' => 'bi-grid-1x2',
        'label' => 'Dashboard',
        'href'  => '/thriftin/admin/dashboard.php',
        'file'  => 'dashboard.php',
        'role'  => 'all'
    ],

    [
        'icon' => 'bi-people',
        'label' => 'Data Penitip',
        'href'  => '/thriftin/admin/penitip/index.php',
        'file'  => 'index.php',
        'role'  => 'all'
    ],

    [
        'icon' => 'bi-tags',
        'label' => 'Katalog Barang',
        'href'  => '/thriftin/admin/barang/index.php',
        'file'  => 'index.php',
        'role'  => 'all'
    ],

    [
        'icon' => 'bi-bag-check',
        'label' => 'Transaksi Jual',
        'href'  => '/thriftin/admin/transaksi/index.php',
        'file'  => 'index.php',
        'role'  => 'all'
    ],

    [
        'icon' => 'bi-wallet2',
        'label' => 'Pencairan Dana',
        'href'  => '/thriftin/admin/pencairan/index.php',
        'file'  => 'index.php',
        'role'  => 'all'
    ],

    [
        'icon' => 'bi-bar-chart',
        'label' => 'Laporan',
        'href'  => '/thriftin/admin/laporan/index.php',
        'file'  => 'index.php',
        'role'  => 'all'
    ],
];
?>

<style>
:root {
    --sage:       #87A878;
    --sage-light: #B5C9AC;
    --cream:      #F5F0E8;
    --terracotta: #D4785A;
    --dark:       #2C2C2C;
    --mid:        #6B6B6B;
    --sidebar-w:  240px;
}

body {
    font-family: 'DM Sans', sans-serif;
    background: #F8F5EF;
    color: var(--dark);
}

/* ---- SIDEBAR ---- */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    width: var(--sidebar-w);
    height: 100vh;
    background: var(--dark);
    padding: 0;
    z-index: 100;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.sidebar-brand {
    padding: 28px 24px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    flex-shrink: 0;
}

.sidebar-logo {
    font-family: 'Syne', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}
.sidebar-logo span { color: var(--terracotta); }

.sidebar-tagline {
    font-size: 10px;
    color: rgba(255,255,255,0.4);
    letter-spacing: 1px;
    margin-top: 4px;
}

.sidebar-nav {
    padding: 16px 12px;
    flex: 1;
}

.nav-section-label {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.3);
    padding: 12px 12px 6px;
}

.nav-item-custom {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: 12px;
    color: rgba(255,255,255,0.6);
    text-decoration: none;
    font-size: 13.5px;
    font-weight: 400;
    margin-bottom: 2px;
    transition: all .2s;
}

.nav-item-custom:hover {
    background: rgba(255,255,255,0.07);
    color: #fff;
}

.nav-item-custom.active {
    background: var(--terracotta);
    color: #fff;
    font-weight: 500;
}

.nav-item-custom i {
    font-size: 16px;
    flex-shrink: 0;
}

.sidebar-footer {
    padding: 16px 24px;
    border-top: 1px solid rgba(255,255,255,0.08);
    flex-shrink: 0;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}

.avatar {
    width: 34px; height: 34px;
    background: var(--sage);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 700;
    font-size: 13px;
    color: #fff;
    flex-shrink: 0;
}

.user-name  { font-size: 13px; font-weight: 500; color: #fff; }
.user-role  { font-size: 10px; color: rgba(255,255,255,0.4); text-transform: capitalize; }

.btn-logout {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 9px 14px;
    background: rgba(212,120,90,0.15);
    color: var(--terracotta);
    border: 1px solid rgba(212,120,90,0.2);
    border-radius: 10px;
    font-size: 12.5px;
    font-weight: 500;
    text-decoration: none;
    transition: .2s;
}
.btn-logout:hover {
    background: var(--terracotta);
    color: #fff;
    border-color: var(--terracotta);
}

/* ---- MAIN CONTENT ---- */
.main-content {
    margin-left: var(--sidebar-w);
    min-height: 100vh;
    padding: 0;
}

.topbar {
    background: #fff;
    padding: 16px 28px;
    border-bottom: 1px solid #EDE8E0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 50;
}

.page-title {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.page-body { padding: 28px; }

/* ---- CARDS ---- */
.stat-card {
    background: #fff;
    border-radius: 18px;
    padding: 22px;
    border: 1px solid #EDE8E0;
    transition: .2s;
}
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(44,44,44,0.08); }

.stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
    margin-bottom: 14px;
}

.stat-number {
    font-family: 'Syne', sans-serif;
    font-size: 26px;
    font-weight: 800;
    color: var(--dark);
    line-height: 1;
}

.stat-label {
    font-size: 12px;
    color: var(--mid);
    margin-top: 4px;
}

/* ---- TABLE ---- */
.table-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #EDE8E0;
    overflow: hidden;
}

.table-card-header {
    padding: 18px 22px;
    border-bottom: 1px solid #EDE8E0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.table-card-title {
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.table { margin: 0; }
.table th {
    background: #F8F5EF;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: var(--mid);
    border: none;
    padding: 12px 16px;
}
.table td {
    padding: 13px 16px;
    font-size: 13.5px;
    border-color: #F0EBE2;
    vertical-align: middle;
}

/* ---- BADGES ---- */
.badge-status {
    font-size: 11px;
    font-weight: 500;
    padding: 5px 11px;
    border-radius: 20px;
}
.status-menunggu    { background: #FFF8E7; color: #B8860B; }
.status-ditampilkan { background: #EAF4EA; color: #2E7D32; }
.status-terjual     { background: #E8F0FE; color: #1565C0; }
.status-dicairkan   { background: #F3E5F5; color: #6A1B9A; }
.status-ditarik     { background: #FBE9E7; color: #BF360C; }

/* ---- FORM CARD ---- */
.form-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #EDE8E0;
    padding: 28px;
}

.form-control, .form-select {
    border-radius: 10px;
    border: 1.5px solid #E8E3DB;
    padding: 10px 14px;
    font-size: 14px;
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    transition: .2s;
}
.form-control:focus, .form-select:focus {
    border-color: var(--sage);
    box-shadow: 0 0 0 3px rgba(135,168,120,0.15);
    background: #fff;
}

.btn-primary-custom {
    background: var(--dark);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 22px;
    font-family: 'Syne', sans-serif;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: .2s;
}
.btn-primary-custom:hover {
    background: var(--terracotta);
    color: #fff;
}

.btn-terra {
    background: var(--terracotta);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 9px 18px;
    font-size: 13px;
    font-weight: 500;
    transition: .2s;
}
.btn-terra:hover { background: var(--terra-dark, #B85E42); color: #fff; }

/* ---- RESPONSIVE ---- */
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); transition: .3s; }
    .sidebar.show { transform: translateX(0); }
    .main-content { margin-left: 0; }
}
</style>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<!-- SIDEBAR HTML -->
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo">Thrift<span>In</span></div>
        <div class="sidebar-tagline">PRELOVED PLATFORM</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Menu</div>
        <?php foreach ($menus as $menu): ?>
            <?php if ($menu['role'] === 'all' || $menu['role'] === $role): ?>
            <a href="<?= $menu['href'] ?>"
               class="nav-item-custom <?= $currentPage === $menu['file'] ? 'active' : '' ?>">
                <i class="<?= $menu['icon'] ?>"></i>
                <?= $menu['label'] ?>
            </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar"><?= strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)) ?></div>
            <div>
                <div class="user-name"><?= htmlspecialchars($_SESSION['nama'] ?? '') ?></div>
                <div class="user-role"><?= $_SESSION['role'] ?? '' ?></div>
            </div>
        </div>
   <a href="/thriftin/logout.php" class="btn-logout">
            <i class="bi bi-box-arrow-right"></i> Keluar
        </a>
    </div>
</div>
