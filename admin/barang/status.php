<?php
session_start();
$pageTitle  = 'Ubah Status Barang — ThriftIn';
$activePage = 'barang';
require '../../config/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit; }

$barang = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT b.*, p.nama as nama_penitip, k.nama_kategori
     FROM barang b
     JOIN penitip p ON b.penitip_id = p.id
     JOIN kategori k ON b.kategori_id = k.id
     WHERE b.id=$id"));

if (!$barang) {
    $_SESSION['flash'] = ['danger', 'Barang tidak ditemukan.'];
    header("Location: index.php"); exit;
}

// Flow status: menunggu → ditampilkan → terjual → dicairkan | ditampilkan → ditarik
$nextStatus = [
    'menunggu'    => 'ditampilkan',
    'ditampilkan' => 'terjual',    // normalnya lewat transaksi
];
$canPull = ($barang['status'] === 'ditampilkan'); // bisa ditarik

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['new_status'] ?? '';
    $allowed    = [];

    if ($barang['status'] === 'menunggu')    $allowed[] = 'ditampilkan';
    if ($barang['status'] === 'ditampilkan') { $allowed[] = 'ditarik'; }
    // terjual dan dicairkan tidak bisa diubah dari sini

    if (!in_array($new_status, $allowed)) {
        $errors[] = 'Perubahan status tidak valid.';
    }

    if (empty($errors)) {
        mysqli_query($conn, "UPDATE barang SET status='$new_status' WHERE id=$id");
        $_SESSION['flash'] = ['success', "Status barang <b>{$barang['nama_barang']}</b> diubah menjadi <b>$new_status</b>."];
        header("Location: index.php"); exit;
    }
}

require '../../includes/header.php';
?>

<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card">
    <div class="card-header">
        <a href="index.php" class="btn btn-sm btn-outline-secondary me-2">
            <i class="fa fa-arrow-left"></i>
        </a><i class="fa fa-arrows-rotate me-2"></i>Ubah Status Barang
    </div>
    <div class="card-body">

        <?php foreach($errors as $e): ?>
        <div class="alert alert-danger py-2 small"><?= $e ?></div>
        <?php endforeach; ?>

        <!-- Info Barang -->
        <div class="rounded-3 p-3 mb-4" style="background:var(--cream,#F5F0E8)">
            <div class="fw-bold mb-1"><?= htmlspecialchars($barang['nama_barang']) ?></div>
            <div class="small text-muted mb-2">
                <code><?= $barang['kode_barang'] ?></code> &bull;
                <?= htmlspecialchars($barang['nama_penitip']) ?> &bull;
                <?= htmlspecialchars($barang['nama_kategori']) ?>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span>Status saat ini:</span>
                <?= badgeStatus($barang['status']) ?>
                <?= badgeKondisi($barang['kondisi']) ?>
            </div>
            <div class="small mt-1 fw-semibold">Harga Jual: <?= rupiah($barang['harga_jual']) ?></div>
        </div>

        <?php if ($barang['status'] === 'terjual' || $barang['status'] === 'dicairkan'): ?>
        <div class="alert alert-info small">
            <i class="fa fa-info-circle me-1"></i>
            Barang ini sudah <strong><?= $barang['status'] ?></strong> dan statusnya tidak bisa diubah dari sini.
            <?php if ($barang['status'] === 'terjual'): ?>
            Proses pencairan melalui menu <a href="../pencairan/index.php">Pencairan Dana</a>.
            <?php endif; ?>
        </div>
        <?php elseif ($barang['status'] === 'ditarik'): ?>
        <div class="alert alert-secondary small">
            <i class="fa fa-info-circle me-1"></i>
            Barang ini sudah ditarik oleh penitip. Status final, tidak bisa diubah.
        </div>
        <?php else: ?>
        <form method="POST">
            <p class="fw-semibold small text-muted mb-3">Pilih tindakan:</p>
            <div class="d-flex flex-column gap-2">

                <?php if ($barang['status'] === 'menunggu'): ?>
                <button type="submit" name="new_status" value="ditampilkan"
                        class="btn btn-primary"
                        onclick="return confirm('Tampilkan barang ini ke katalog?')">
                    <i class="fa fa-eye me-2"></i>Tampilkan ke Katalog
                    <small class="d-block opacity-75">menunggu → ditampilkan</small>
                </button>
                <?php endif; ?>

                <?php if ($barang['status'] === 'ditampilkan'): ?>
                <div class="alert alert-info small">
                    <i class="fa fa-bag-shopping me-1"></i>
                    Untuk memproses penjualan, gunakan menu
                    <a href="../transaksi/index.php" class="fw-semibold">Transaksi</a>.
                </div>
                <button type="submit" name="new_status" value="ditarik"
                        class="btn btn-outline-danger"
                        onclick="return confirm('Yakin barang ini ditarik oleh penitip?')">
                    <i class="fa fa-rotate-left me-2"></i>Tandai sebagai Ditarik
                    <small class="d-block opacity-75">Penitip minta barangnya kembali</small>
                </button>
                <?php endif; ?>

            </div>
        </form>
        <?php endif; ?>

    </div>
</div>
</div></div>

<?php require '../../includes/footer.php'; ?>
