<?php
session_start();
$pageTitle  = 'Laporan Pendapatan — ThriftIn';
$activePage = 'laporan';
require '../../config/koneksi.php';

$tgl_dari = $_GET['dari'] ?? date('Y-m-01');
$tgl_ke   = $_GET['ke']   ?? date('Y-m-d');

$dari_s = mysqli_real_escape_string($conn, $tgl_dari);
$ke_s   = mysqli_real_escape_string($conn, $tgl_ke);

// Summary
$summary = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total_trx,
            SUM(harga_jual) as total_penjualan,
            SUM(komisi_nominal) as total_komisi,
            SUM(hasil_penitip) as total_penitip
     FROM transaksi
     WHERE DATE(tgl_transaksi) BETWEEN '$dari_s' AND '$ke_s'"));

// Detail
$detail = mysqli_query($conn,
    "SELECT t.*, b.nama_barang, p.nama as nama_penitip, u.nama as nama_kasir
     FROM transaksi t
     JOIN barang b ON t.barang_id = b.id
     JOIN penitip p ON b.penitip_id = p.id
     JOIN users u ON t.kasir_id = u.id
     WHERE DATE(t.tgl_transaksi) BETWEEN '$dari_s' AND '$ke_s'
     ORDER BY t.tgl_transaksi DESC");

require '../../includes/header.php';
?>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="<?= $tgl_dari ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Sampai Tanggal</label>
                <input type="date" name="ke" class="form-control" value="<?= $tgl_ke ?>">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-sage w-100"><i class="fa fa-filter me-1"></i>Filter</button>
                <a href="?" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#5C8A6B,#4a7358);">
            <div class="fw-bold fs-4"><?= $summary['total_trx'] ?? 0 ?></div>
            <div class="small opacity-75">Total Transaksi</div>
            <i class="fa fa-receipt stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#6ea8fe,#3d8bfd);">
            <div class="fw-bold" style="font-size:1.1rem"><?= rupiah($summary['total_penjualan']??0) ?></div>
            <div class="small opacity-75">Total Penjualan</div>
            <i class="fa fa-money-bill stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#D4956A,#c07d54);">
            <div class="fw-bold" style="font-size:1.1rem"><?= rupiah($summary['total_komisi']??0) ?></div>
            <div class="small opacity-75">Komisi Toko</div>
            <i class="fa fa-coins stat-icon"></i>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card" style="background: linear-gradient(135deg,#6f42c1,#5930a8);">
            <div class="fw-bold" style="font-size:1.1rem"><?= rupiah($summary['total_penitip']??0) ?></div>
            <div class="small opacity-75">Dibayar ke Penitip</div>
            <i class="fa fa-users stat-icon"></i>
        </div>
    </div>
</div>

<!-- Tabel Detail -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fa fa-table me-2"></i>Detail Transaksi
            <small class="text-muted fw-normal">
                (<?= date('d M Y', strtotime($tgl_dari)) ?> – <?= date('d M Y', strtotime($tgl_ke)) ?>)
            </small>
        </span>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-print me-1"></i>Print
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th class="ps-3">Kode</th>
                <th>Barang</th><th>Penitip</th>
                <th>Harga Jual</th><th>Komisi</th>
                <th>Hasil Penitip</th>
                <th>Metode</th><th>Tgl</th>
            </tr></thead>
            <tbody>
            <?php $c = 0; while ($t = mysqli_fetch_assoc($detail)): $c++; ?>
            <tr>
                <td class="ps-3"><code class="small"><?= $t['kode_transaksi'] ?></code></td>
                <td><?= htmlspecialchars($t['nama_barang']) ?></td>
                <td><?= htmlspecialchars($t['nama_penitip']) ?></td>
                <td><?= rupiah($t['harga_jual']) ?></td>
                <td class="text-success fw-semibold"><?= rupiah($t['komisi_nominal']) ?></td>
                <td><?= rupiah($t['hasil_penitip']) ?></td>
                <td>
                    <?= $t['metode_bayar'] === 'tunai'
                        ? "<span class='badge bg-success'>💵 Tunai</span>"
                        : "<span class='badge bg-info text-dark'>📲 Transfer</span>" ?>
                </td>
                <td><small><?= date('d M Y', strtotime($t['tgl_transaksi'])) ?></small></td>
            </tr>
            <?php endwhile;
            if ($c === 0): ?>
            <tr><td colspan="8" class="text-center text-muted py-5">
                <i class="fa fa-chart-bar fa-2x mb-2 d-block opacity-25"></i>
                Tidak ada transaksi pada periode ini
            </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>
