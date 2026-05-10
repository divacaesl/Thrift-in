<?php
session_start();
$pageTitle  = 'Dashboard — ThriftIn';
$activePage = 'dashboard';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../includes/header.php';

// Statistik
$totalBarang    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM barang"))['c'];
$barangDitampil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM barang WHERE status='ditampilkan'"))['c'];
$barangTerjual  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM barang WHERE status='terjual'"))['c'];
$totalPenitip   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM penitip WHERE status='aktif'"))['c'];
$pendapatanBulan = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(komisi_nominal),0) c FROM transaksi
     WHERE MONTH(tgl_transaksi)=MONTH(NOW()) AND YEAR(tgl_transaksi)=YEAR(NOW())"))['c'];

// Transaksi terbaru
$recentTrx = mysqli_query($conn,
    "SELECT t.*, b.nama_barang, p.nama as nama_penitip
     FROM transaksi t
     JOIN barang b ON t.barang_id = b.id
     JOIN penitip p ON b.penitip_id = p.id
     ORDER BY t.tgl_transaksi DESC LIMIT 5");

// Barang masuk terbaru
$recentBarang = mysqli_query($conn,
    "SELECT b.*, p.nama as nama_penitip, k.nama_kategori
     FROM barang b
     JOIN penitip p ON b.penitip_id = p.id
     JOIN kategori k ON b.kategori_id = k.id
     ORDER BY b.tgl_masuk DESC LIMIT 5");
?>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #5C8A6B, #4a7358);">
            <div class="fw-bold fs-4"><?= $totalBarang ?></div>
            <div class="small opacity-75">Total Barang</div>
            <i class="fa fa-shirt stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6ea8fe, #3d8bfd);">
            <div class="fw-bold fs-4"><?= $barangDitampil ?></div>
            <div class="small opacity-75">Sedang Ditampilkan</div>
            <i class="fa fa-tag stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #D4956A, #c07d54);">
            <div class="fw-bold fs-4"><?= $barangTerjual ?></div>
            <div class="small opacity-75">Barang Terjual</div>
            <i class="fa fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #6f42c1, #5930a8);">
            <div class="fw-bold fs-4"><?= rupiah($pendapatanBulan) ?></div>
            <div class="small opacity-75">Komisi Bulan Ini</div>
            <i class="fa fa-coins stat-icon"></i>
        </div>
    </div>
</div>

<!-- TABEL BAWAH -->
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa fa-bag-shopping me-2 text-success"></i>Transaksi Terbaru</span>
                <a href="transaksi/index.php" class="btn btn-sm btn-sage">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th class="ps-3">Kode</th><th>Barang</th>
                        <th>Harga</th><th>Tanggal</th>
                    </tr></thead>
                    <tbody>
                    <?php if (mysqli_num_rows($recentTrx) > 0):
                        while ($t = mysqli_fetch_assoc($recentTrx)): ?>
                        <tr>
                            <td class="ps-3"><code><?= $t['kode_transaksi'] ?></code></td>
                            <td><?= htmlspecialchars($t['nama_barang']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($t['nama_penitip']) ?></small></td>
                            <td><?= rupiah($t['harga_jual']) ?></td>
                            <td><small><?= date('d M Y', strtotime($t['tgl_transaksi'])) ?></small></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada transaksi</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa fa-shirt me-2 text-primary"></i>Barang Masuk Terbaru</span>
                <a href="barang/index.php" class="btn btn-sm btn-sage">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th class="ps-3">Nama</th><th>Kondisi</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                    <?php if (mysqli_num_rows($recentBarang) > 0):
                        while ($b = mysqli_fetch_assoc($recentBarang)): ?>
                        <tr>
                            <td class="ps-3">
                                <?= htmlspecialchars($b['nama_barang']) ?><br>
                                <small class="text-muted"><?= htmlspecialchars($b['nama_penitip']) ?></small>
                            </td>
                            <td><?= badgeKondisi($b['kondisi']) ?></td>
                            <td><?= badgeStatus($b['status']) ?></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">Belum ada barang</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
