<?php
session_start();
$pageTitle  = 'Pencairan Dana — ThriftIn';
$activePage = 'pencairan';
require '../../config/koneksi.php';

$data = mysqli_query($conn,
    "SELECT pc.*, p.nama as nama_penitip, p.kode_penitip, u.nama as nama_kasir
     FROM pencairan pc
     JOIN penitip p ON pc.penitip_id = p.id
     JOIN users u ON pc.kasir_id = u.id
     ORDER BY pc.tgl_pencairan DESC");

require '../../includes/header.php';
?>

<?php if (isset($_SESSION['flash'])): [$type,$msg] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show">
    <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fa fa-money-bill-transfer me-2"></i>Riwayat Pencairan Dana</span>
        <a href="tambah.php" class="btn btn-sm btn-sage">
            <i class="fa fa-plus me-1"></i>Cairkan Dana
        </a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr>
                <th class="ps-3">#</th>
                <th>Kode</th>
                <th>Penitip</th>
                <th>Nominal</th>
                <th>Metode</th>
                <th>Kasir</th>
                <th>Tanggal</th>
                <th>Catatan</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; $count = 0; $total = 0;
            while ($pc = mysqli_fetch_assoc($data)): $count++; $total += $pc['total_nominal']; ?>
            <tr>
                <td class="ps-3"><?= $no++ ?></td>
                <td><code class="small"><?= $pc['kode_pencairan'] ?></code></td>
                <td>
                    <strong><?= htmlspecialchars($pc['nama_penitip']) ?></strong><br>
                    <small class="text-muted"><?= $pc['kode_penitip'] ?></small>
                </td>
                <td class="fw-semibold text-success"><?= rupiah($pc['total_nominal']) ?></td>
                <td>
                    <?php if ($pc['metode'] === 'tunai'): ?>
                        <span class="badge bg-success">💵 Tunai</span>
                    <?php else: ?>
                        <span class="badge bg-info text-dark">📲 Transfer</span>
                        <?php if ($pc['nama_bank']): ?>
                        <br><small class="text-muted"><?= htmlspecialchars($pc['nama_bank']) ?>
                        <?= $pc['no_rekening'] ? '- '.$pc['no_rekening'] : '' ?></small>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td class="small"><?= htmlspecialchars($pc['nama_kasir']) ?></td>
                <td><small><?= date('d M Y H:i', strtotime($pc['tgl_pencairan'])) ?></small></td>
                <td><small class="text-muted"><?= htmlspecialchars($pc['catatan'] ?? '-') ?></small></td>
            </tr>
            <?php endwhile;
            if ($count === 0): ?>
            <tr><td colspan="8" class="text-center text-muted py-5">
                <i class="fa fa-hand-holding-dollar fa-2x mb-2 d-block opacity-25"></i>
                Belum ada pencairan dana
            </td></tr>
            <?php endif; ?>
            </tbody>
            <?php if ($count > 0): ?>
            <tfoot>
                <tr class="table-light fw-semibold">
                    <td colspan="3" class="ps-3">Total <?= $count ?> transaksi pencairan</td>
                    <td class="text-success"><?= rupiah($total) ?></td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>
