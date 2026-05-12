<?php
session_start();
$pageTitle  = 'Katalog Barang — ThriftIn';
$activePage = 'barang';
require '../../config/koneksi.php';

// Hapus barang
if (isset($_GET['hapus'])) {
    $id  = (int)$_GET['hapus'];
    $brg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status, foto FROM barang WHERE id=$id"));
    if ($brg && in_array($brg['status'], ['terjual','dicairkan'])) {
        $_SESSION['flash'] = ['danger', 'Barang sudah terjual/dicairkan, tidak bisa dihapus!'];
    } else {
        // Hapus file foto jika bukan default
        if ($brg && $brg['foto'] !== 'default.jpg') {
            $fotoPath = __DIR__ . '/../../assets/uploads/' . $brg['foto'];
            if (file_exists($fotoPath)) unlink($fotoPath);
        }
        mysqli_query($conn, "DELETE FROM barang WHERE id=$id");
        $_SESSION['flash'] = ['success', 'Barang berhasil dihapus.'];
    }
    header("Location: index.php"); exit;
}

// Filter
$search     = trim($_GET['q']         ?? '');
$filter_st  = $_GET['status']         ?? '';
$filter_kat = (int)($_GET['kategori'] ?? 0);

$where = ["1=1"];
if ($search)     $where[] = "(b.nama_barang LIKE '%".mysqli_real_escape_string($conn,$search)."%'
                              OR p.nama LIKE '%$search%'
                              OR b.kode_barang LIKE '%$search%')";
if ($filter_st)  $where[] = "b.status='".mysqli_real_escape_string($conn,$filter_st)."'";
if ($filter_kat) $where[] = "b.kategori_id=$filter_kat";

$whereStr = implode(' AND ', $where);

$data = mysqli_query($conn,
    "SELECT b.*, p.nama as nama_penitip, k.nama_kategori
     FROM barang b
     JOIN penitip p ON b.penitip_id = p.id
     JOIN kategori k ON b.kategori_id = k.id
     WHERE $whereStr
     ORDER BY b.tgl_masuk DESC");

$kategoriList = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");

require '../../includes/header.php';
?>

<style>
/* ── Foto thumbnail ─────────────────────────────────────── */
.thumb-wrap {
    width: 58px; height: 58px;
    border-radius: 10px;
    overflow: hidden;
    background: #f0f0f0;
    flex-shrink: 0;
    cursor: pointer;
    transition: transform .15s;
}
.thumb-wrap:hover { transform: scale(1.08); }
.thumb-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
}

/* ── Modal foto besar ───────────────────────────────────── */
#modalFoto .modal-body { padding: 0; }
#modalFotoImg {
    width: 100%; max-height: 80vh;
    object-fit: contain;
    background: #111;
    border-radius: 0 0 .5rem .5rem;
}
#modalFotoLabel { font-weight: 700; }
</style>

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
                       placeholder="Cari barang / penitip..."
                       value="<?= htmlspecialchars($search) ?>" style="width:180px">
                <select name="status" class="form-select form-select-sm" style="width:135px">
                    <option value="">Semua Status</option>
                    <?php foreach(['diterima','diverifikasi','ditampilkan','terjual','dicairkan','ditarik'] as $st): ?>
                    <option value="<?= $st ?>" <?= $filter_st===$st?'selected':'' ?>>
                        <?= ucfirst($st) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <select name="kategori" class="form-select form-select-sm" style="width:145px">
                    <option value="">Semua Kategori</option>
                    <?php mysqli_data_seek($kategoriList,0);
                    while ($k = mysqli_fetch_assoc($kategoriList)): ?>
                    <option value="<?= $k['id'] ?>" <?= $filter_kat==$k['id']?'selected':'' ?>>
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                    <?php endwhile; ?>
                </select>
                <button class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-filter me-1"></i>Filter
                </button>
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
                <th class="ps-3" style="width:40px">#</th>
                <th style="width:70px">Foto</th>  <!-- ← KOLOM BARU -->
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Penitip</th>
                <th>Harga</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; $count = 0;
            while ($b = mysqli_fetch_assoc($data)): $count++;
                $foto    = $b['foto'] ?: 'default.jpg';
                $fotoUrl = '../../assets/uploads/' . htmlspecialchars($foto);
            ?>
            <tr>
                <td class="ps-3"><?= $no++ ?></td>

                <!-- ── FOTO THUMBNAIL ── -->
                <td>
                    <div class="thumb-wrap"
                         onclick="lihatFoto('<?= $fotoUrl ?>', '<?= htmlspecialchars(addslashes($b['nama_barang'])) ?>')">
                        <img src="<?= $fotoUrl ?>"
                             alt="<?= htmlspecialchars($b['nama_barang']) ?>"
                             loading="lazy"
                             onerror="this.src='../../assets/uploads/default.jpg'">
                    </div>
                </td>

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
                    <?php if (in_array($b['status'], ['diterima','diverifikasi','ditampilkan'])): ?>
                    <a href="edit.php?id=<?= $b['id'] ?>"
                       class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <?php endif; ?>
                    <a href="status.php?id=<?= $b['id'] ?>"
                       class="btn btn-sm btn-outline-warning" title="Ubah Status">
                        <i class="fa fa-arrows-rotate"></i>
                    </a>
                    <?php if (!in_array($b['status'], ['terjual','dicairkan'])): ?>
                    <a href="?hapus=<?= $b['id'] ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Hapus barang \'<?= htmlspecialchars(addslashes($b['nama_barang'])) ?>\'?')"
                       title="Hapus">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile;
            if ($count === 0): ?>
            <tr><td colspan="9" class="text-center text-muted py-5">
                <i class="fa fa-box-open fa-2x mb-2 d-block opacity-25"></i>
                <?= $search
                    ? "Tidak ada hasil untuk <b>\"$search\"</b>"
                    : 'Belum ada barang yang terdaftar' ?>
            </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer text-muted small">
        Menampilkan <strong><?= $count ?></strong> barang
    </div>
</div>

<!-- ── MODAL LIHAT FOTO BESAR ───────────────────────────── -->
<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="modalFotoLabel">Foto Barang</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img id="modalFotoImg" src="" alt="Foto Barang">
            </div>
        </div>
    </div>
</div>

<script>
function lihatFoto(src, nama) {
    document.getElementById('modalFotoImg').src   = src;
    document.getElementById('modalFotoLabel').textContent = nama;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}
</script>

<?php require '../../includes/footer.php'; ?>