<?php
session_start();
$pageTitle  = 'Manajemen Penitip — ThriftIn';
$activePage = 'penitip';
require '../../config/koneksi.php';

if (isset($_GET['hapus'])) {
    $id  = (int)$_GET['hapus'];
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM barang WHERE penitip_id=$id"));
    if ($cek['c'] > 0) {
        $_SESSION['flash'] = ['danger', 'Penitip tidak bisa dihapus karena masih punya barang!'];
    } else {
        mysqli_query($conn, "DELETE FROM penitip WHERE id=$id");
        $_SESSION['flash'] = ['success', 'Penitip berhasil dihapus.'];
    }
    header("Location: index.php"); exit;
}

$search = trim($_GET['q'] ?? '');
$where  = $search ? "WHERE nama LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR no_hp LIKE '%$search%'" : "";
$data   = mysqli_query($conn, "SELECT * FROM penitip $where ORDER BY created_at DESC");

require '../../includes/header.php';
?>

<?php if (isset($_SESSION['flash'])): [$type,$msg] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show">
    <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fa fa-users me-2"></i>Data Penitip</span>
        <div class="d-flex gap-2 flex-wrap">
            <form class="d-flex" method="GET">
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Cari nama / no HP..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-sm btn-outline-secondary ms-1">Cari</button>
            </form>
            <a href="tambah.php" class="btn btn-sm btn-sage">
                <i class="fa fa-plus me-1"></i>Tambah
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead><tr>
                <th class="ps-3">#</th>
                <th>Kode</th><th>Nama</th>
                <th>No HP</th><th>Bank</th>
                <th>Status</th><th>Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; $count = 0;
            while ($p = mysqli_fetch_assoc($data)): $count++; ?>
            <tr>
                <td class="ps-3"><?= $no++ ?></td>
                <td><code><?= $p['kode_penitip'] ?></code></td>
                <td>
                    <strong><?= htmlspecialchars($p['nama']) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars($p['email'] ?? '-') ?></small>
                </td>
                <td><?= htmlspecialchars($p['no_hp']) ?></td>
                <td>
                    <?= htmlspecialchars($p['nama_bank'] ?? '-') ?><br>
                    <small class="text-muted"><?= $p['no_rekening'] ?? '' ?></small>
                </td>
                <td><?= $p['status']==='aktif'
                    ? "<span class='badge bg-success'>Aktif</span>"
                    : "<span class='badge bg-secondary'>Nonaktif</span>" ?></td>
                <td>
                    <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a href="?hapus=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Hapus penitip ini?')">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile;
            if ($count === 0): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">
                <i class="fa fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                <?= $search ? "Tidak ada hasil untuk \"$search\"" : 'Belum ada penitip' ?>
            </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require '../../includes/footer.php'; ?>
