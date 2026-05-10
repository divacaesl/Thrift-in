<?php
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3307);
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'thriftin_db');
define('KOMISI_DEFAULT', 20);
define('BASE_URL', '/thriftin/');

$conn = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME,
    DB_PORT
);

if (!$conn) {
    die("<div style='font-family:sans-serif;padding:20px;color:red;'>
            <h3>Koneksi Gagal</h3>
            <p>" . mysqli_connect_error() . "</p>
        </div>");
}

mysqli_set_charset($conn, 'utf8mb4');

function generateKode($prefix, $table, $kolom, $conn) {
    $tgl = date('ymd');
    $result = mysqli_query($conn, "SELECT COUNT(*) as total FROM $table WHERE $kolom LIKE '$prefix-$tgl%'");
    $row = mysqli_fetch_assoc($result);
    $urut = str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);
    return "$prefix-$tgl-$urut";
}

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function badgeKondisi($kondisi) {
    $map = [
        'baru'         => ['success', 'Baru'],
        'seperti_baru' => ['primary', 'Seperti Baru'],
        'bekas_layak'  => ['warning', 'Bekas Layak'],
        'bekas'        => ['secondary', 'Bekas'],
    ];

    $b = $map[$kondisi] ?? ['dark', $kondisi];

    return "<span class='badge bg-{$b[0]}'>{$b[1]}</span>";
}

function badgeStatus($status) {
    $map = [
        'diterima'     => ['secondary', 'Diterima'],
        'diverifikasi' => ['info', 'Diverifikasi'],
        'ditampilkan'  => ['primary', 'Ditampilkan'],
        'terjual'      => ['success', 'Terjual ✓'],
        'dicairkan'    => ['dark', 'Dicairkan'],
        'ditarik'      => ['danger', 'Ditarik'],
    ];

    $b = $map[$status] ?? ['secondary', $status];

    return "<span class='badge bg-{$b[0]}'>{$b[1]}</span>";
}