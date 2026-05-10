<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'ThriftIn' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sage:       #5C8A6B;
            --terracotta: #D4956A;
            --cream:      #F5F0E8;
        }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #eee;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--sage);
            border-bottom: 1px solid #eee;
            letter-spacing: -0.5px;
        }
        .sidebar-brand span { color: var(--terracotta); }
        .sidebar-brand small {
            display: block;
            font-size: 0.7rem;
            font-weight: 400;
            color: #aaa;
            letter-spacing: 0;
        }
        .sidebar .nav-link {
            color: #555;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            margin: 2px 8px;
            font-size: 0.875rem;
            transition: all 0.15s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--cream);
            color: var(--sage);
            font-weight: 600;
        }
        .sidebar .nav-link i { width: 20px; }
        .sidebar-section {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #bbb;
            padding: 1rem 1.25rem 0.25rem;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .topbar .page-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: #222;
        }
        .user-chip {
            background: var(--cream);
            border-radius: 20px;
            padding: 0.4rem 0.9rem;
            font-size: 0.8rem;
            color: var(--sage);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .content-area { padding: 1.5rem; }
        .card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            border-radius: 14px 14px 0 0 !important;
            padding: 1rem 1.25rem;
            font-weight: 700;
        }
        .btn-sage {
            background-color: var(--sage);
            color: #fff;
            border: none;
            border-radius: 8px;
        }
        .btn-sage:hover { background-color: #4a7358; color: #fff; }
        .btn-terra {
            background-color: var(--terracotta);
            color: #fff;
            border: none;
            border-radius: 8px;
        }
        .btn-terra:hover { background-color: #c07d54; color: #fff; }
        .stat-card {
            border-radius: 14px;
            padding: 1.25rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .stat-card .stat-icon {
            font-size: 2.5rem;
            opacity: 0.25;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }
        .table > thead > tr > th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            font-weight: 600;
            border-bottom: 2px solid #f0f0f0;
        }
        .table > tbody > tr > td { vertical-align: middle; font-size: 0.875rem; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">
        Thrift<span>In</span>
        <small>Preloved Management System</small>
    </div>

    <nav class="nav flex-column mt-2 flex-grow-1">
        <div class="sidebar-section">Main</div>
        <a href="../admin/dashboard.php" class="nav-link <?= ($activePage??'') === 'dashboard' ? 'active' : '' ?>">
            <i class="fa fa-chart-pie me-2"></i> Dashboard
        </a>

        <div class="sidebar-section">Kelola</div>
        <a href="../admin/penitip/index.php" class="nav-link <?= ($activePage??'') === 'penitip' ? 'active' : '' ?>">
            <i class="fa fa-users me-2"></i> Penitip
        </a>
        <a href="../admin/barang/index.php" class="nav-link <?= ($activePage??'') === 'barang' ? 'active' : '' ?>">
            <i class="fa fa-shirt me-2"></i> Katalog Barang
        </a>
        <a href="../admin/transaksi/index.php" class="nav-link <?= ($activePage??'') === 'transaksi' ? 'active' : '' ?>">
            <i class="fa fa-bag-shopping me-2"></i> Transaksi
        </a>
        <a href="../admin/pencairan/index.php" class="nav-link <?= ($activePage??'') === 'pencairan' ? 'active' : '' ?>">
            <i class="fa fa-money-bill-transfer me-2"></i> Pencairan Dana
        </a>

        <div class="sidebar-section">Laporan</div>
        <a href="../admin/laporan/index.php" class="nav-link <?= ($activePage??'') === 'laporan' ? 'active' : '' ?>">
            <i class="fa fa-file-chart-column me-2"></i> Laporan
        </a>

        <div class="mt-auto p-3 border-top" style="font-size:0.75rem;color:#bbb;">
            <i class="fa fa-user-circle me-1"></i>
            <?= htmlspecialchars($_SESSION['nama']) ?> •
            <span class="text-capitalize"><?= $_SESSION['role'] ?></span>
        </div>
    </nav>
</div>

<!-- MAIN -->
<div class="main-content">
    <div class="topbar">
        <div class="page-title"><?= $pageTitle ?? 'ThriftIn' ?></div>
        <div class="d-flex align-items-center gap-3">
            <div class="user-chip">
                <i class="fa fa-circle-user"></i>
                <?= htmlspecialchars($_SESSION['nama']) ?>
            </div>
            <a href="../logout.php" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                <i class="fa fa-sign-out-alt me-1"></i>Keluar
            </a>
        </div>
    </div>
    <div class="content-area">
