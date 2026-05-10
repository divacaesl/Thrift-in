<?php
session_start();
require '../../config/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit; }

$brg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_barang, status FROM barang WHERE id=$id"));

if (!$brg) {
    $_SESSION['flash'] = ['danger', 'Barang tidak ditemukan.'];
} elseif (in_array($brg['status'], ['terjual','dicairkan'])) {
    $_SESSION['flash'] = ['danger', 'Barang sudah terjual/dicairkan, tidak bisa dihapus!'];
} else {
    mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
    $_SESSION['flash'] = ['success', "Barang <b>{$brg['nama_barang']}</b> berhasil dihapus."];
}

header("Location: index.php"); exit;
