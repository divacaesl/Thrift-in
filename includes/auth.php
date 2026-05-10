<?php
// includes/auth.php — Taruh di baris pertama semua halaman admin
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Cek role kalau perlu (opsional)
// Contoh penggunaan: requireRole('admin');
function requireRole($role) {
    if ($_SESSION['role'] !== $role) {
        header("Location: dashboard.php?error=akses_ditolak");
        exit;
    }
}
