<?php
session_start();
$pageTitle  = 'Katalog Barang — ThriftIn';
$activePage = 'barang';
require '../../config/koneksi.php';

// Hapus barang
if (isset($_GET['hapus'])) {
    $id  = (int)$_GET['hapus'];
    $brg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM barang WHERE id=$id"));
    if ($brg && in_array($brg['status'], ['terjual','dicairkan'])) {
        $_SESSION['flash'] = ['danger', 'Barang sudah terjual/dicairkan, tidak bisa dihapus!'];
    } else {
        mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
        $_SESSION['flash'] = ['success', 'Barang berhasil dihapus.'];
    }
    header("Location: index.php"); exit;
}

// Filter
$search     = trim($_GET['q']      ?? '');
$filter_st  = $_GET['status']      ?? '';
$filter_kat = (int)($_GET['kategori'] ?? 0);

$where = ["1=1"];
if ($search)     $where[] = "(b.nama_barang LIKE '%".mysqli_real_escape_string($conn,$search)."%' OR p.nama LIKE '%$search%' OR b.kode_barang LIKE '%$search%')";
if ($filter_st)  $where[] = "b.status='".mysqli_real_escape_string($conn,$filter_st)."'";
if ($filter_kat) $where[] = "b.kategori_id=$filter_kat";

$whereStr = implode(' AND ', $where);

$data = mysqli_query($conn,
    "SELECT b.*, p.nama as nama_penitip, k.nama_kategori
     FROM barang b
     JOIN penitip p ON b.penitip_id = p.id
     JOIN kategori k ON b.kategori_id = k.id
     WHERE $whereStr
     ORDER BY b.created_at DESC");

$kategoriList = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");

require '../../includes/header.php';
?>

<?php if (isset($_SESSION['flash'])): [$type,$msg] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show">
    <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fa fa-shirt me-2"></i>Katalog Barang Titipan</span>
        <div class="d-flex gap-2 flex-wrap">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Cari barang / penitip..." value="<?= htmlspecialchars($search) ?>" style="width:180px">
                <select name="status" class="form-select form-select-sm" style="width:130px">
                    <option value="">Semua Status</option>
                    <?php foreach(['menunggu','ditampilkan','terjual','dicairkan','ditarik'] as $st): ?>
                    <option value="<?= $st ?>" <?= $filter_st===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="kategori" class="form-select form-select-sm" style="width:140px">
                    <option value="">Semua Kategori</option>
                    <?php mysqli_data_seek($kategoriList,0); while($k=mysqli_fetch_assoc($kategoriList)): ?>
                    <option value="<?= $k['id'] ?>" <?= $filter_kat==$k['id']?'selected':'' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
                    <?php endwhile; ?>
                </select>
                <button class="btn btn-sm btn-outline-secondary">Filter</button>
                <a href="index.php" class="btn btn-sm btn-outline-danger">Reset</a>
            </form>
            <a href="tambah.php" class="btn btn-sm btn-sage">
                <i class="fa fa-plus me-1"></i>Tambah Barang
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead><tr>
                <th class="ps-3">#</th>
                <th>Kode</th>
                <th>Barang</th>
                <th>Penitip</th>
                <th>Harga Jual</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; $count = 0;
            while ($b = mysqli_fetch_assoc($data)): $count++; ?>
            <tr>
                <td class="ps-3"><?= $no++ ?></td>
                <td><code class="small"><?= $b['kode_barang'] ?></code></td>
                <td>
                    <strong><?= htmlspecialchars($b['nama_barang']) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars($b['nama_kategori']) ?></small>
                </td>
                <td><?= htmlspecialchars($b['nama_penitip']) ?></td>
                <td class="fw-semibold"><?= rupiah($b['harga_jual']) ?></td>
                <td><?= badgeKondisi($b['kondisi']) ?></td>
                <td><?= badgeStatus($b['status']) ?></td>
                <td>
                    <?php if (in_array($b['status'], ['menunggu','ditampilkan'])): ?>
                    <a href="edit.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <?php endif; ?>
                    <a href="status.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-warning" title="Ubah Status">
                        <i class="fa fa-arrows-rotate"></i>
                    </a>
                    <?php if (!in_array($b['status'], ['terjual','dicairkan'])): ?>
                    <a href="?hapus=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Hapus barang ini?')" title="Hapus">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile;
            if ($count === 0): ?>
            <tr><td colspan="8" class="text-center text-muted py-5">
                <i class="fa fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                <?= $search ? "Tidak ada hasil untuk \"$search\"" : 'Belum ada barang' ?>
            </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer text-muted small">
        Total: <?= $count ?> barang
    </div>
</div>

<?php require '../../includes/footer.php'; ?>
