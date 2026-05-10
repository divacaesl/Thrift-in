<?php
session_start();
$pageTitle  = 'Transaksi Penjualan — ThriftIn';
$activePage = 'transaksi';
require '../../config/koneksi.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id     = (int)($_POST['barang_id'] ?? 0);
    $nama_pembeli  = trim($_POST['nama_pembeli'] ?? '');
    $no_hp_pembeli = trim($_POST['no_hp_pembeli'] ?? '');
    $metode        = $_POST['metode_bayar'] ?? 'tunai';

    if (!$barang_id)         $errors[] = 'Pilih barang yang akan dijual.';
    if (empty($nama_pembeli)) $errors[] = 'Nama pembeli wajib diisi.';
    if (empty($no_hp_pembeli)) $errors[] = 'No HP pembeli wajib diisi.';

    if (empty($errors)) {
        $barang = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT * FROM barang WHERE id=$barang_id AND status='ditampilkan'"));
        if (!$barang) $errors[] = 'Barang tidak ditemukan atau sudah tidak tersedia.';
    }

    if (empty($errors)) {
        $harga         = $barang['harga_jual'];
        $komisi_persen = KOMISI_DEFAULT;
        $komisi_nom    = round($harga * $komisi_persen / 100);
        $hasil_penitip = $harga - $komisi_nom;
        $kode          = generateKode('TRX', 'transaksi', 'kode_transaksi', $conn);
        $kasir_id      = $_SESSION['user_id'];

        $nama_s = mysqli_real_escape_string($conn, $nama_pembeli);
        $hp_s   = mysqli_real_escape_string($conn, $no_hp_pembeli);
        $met_s  = mysqli_real_escape_string($conn, $metode);

        mysqli_query($conn,
            "INSERT INTO transaksi
             (kode_transaksi,barang_id,nama_pembeli,no_hp_pembeli,harga_jual,
              komisi_persen,komisi_nominal,hasil_penitip,metode_bayar,kasir_id)
             VALUES
             ('$kode',$barang_id,'$nama_s','$hp_s',$harga,
              $komisi_persen,$komisi_nom,$hasil_penitip,'$met_s',$kasir_id)");

        // Update status barang
        mysqli_query($conn, "UPDATE barang SET status='terjual', tgl_terjual=CURDATE() WHERE id=$barang_id");

        $_SESSION['flash'] = ['success',
            "Transaksi <b>$kode</b> berhasil! Komisi toko: <b>".rupiah($komisi_nom)."</b> | Hasil penitip: <b>".rupiah($hasil_penitip)."</b>"];
        header("Location: index.php"); exit;
    }
}

// Barang tersedia
$barangList = mysqli_query($conn,
    "SELECT b.*, p.nama as nama_penitip, k.nama_kategori
     FROM barang b
     JOIN penitip p ON b.penitip_id = p.id
     JOIN kategori k ON b.kategori_id = k.id
     WHERE b.status = 'ditampilkan'
     ORDER BY b.nama_barang");

require '../../includes/header.php';
?>

<?php if (isset($_SESSION['flash'])): [$type,$msg] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show">
    <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form Transaksi -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header"><i class="fa fa-bag-shopping me-2"></i>Form Transaksi Baru</div>
            <div class="card-body">
                <?php foreach($errors as $e): ?>
                <div class="alert alert-danger py-2 small"><?= $e ?></div>
                <?php endforeach; ?>

                <form method="POST" id="formTrx">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pilih Barang <span class="text-danger">*</span></label>
                        <select name="barang_id" class="form-select" id="selectBarang" onchange="hitungKomisi()" required>
                            <option value="">-- Pilih barang --</option>
                            <?php while ($b = mysqli_fetch_assoc($barangList)): ?>
                            <option value="<?= $b['id'] ?>"
                                    data-harga="<?= $b['harga_jual'] ?>"
                                    data-penitip="<?= htmlspecialchars($b['nama_penitip']) ?>"
                                    data-kondisi="<?= $b['kondisi'] ?>"
                                    <?= ($_POST['barang_id']??'')==$b['id']?'selected':'' ?>>
                                <?= htmlspecialchars($b['nama_barang']) ?> —
                                <?= rupiah($b['harga_jual']) ?>
                                (<?= $b['nama_penitip'] ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Preview harga -->
                    <div id="previewHarga" class="d-none mb-3 p-3 rounded-3"
                         style="background: var(--cream,#F5F0E8);">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Harga Jual</span>
                            <strong id="previewTotal">-</strong>
                        </div>
                        <div class="d-flex justify-content-between small mb-1 text-muted">
                            <span>Komisi Toko (<?= KOMISI_DEFAULT ?>%)</span>
                            <span id="previewKomisi">-</span>
                        </div>
                        <div class="d-flex justify-content-between small fw-bold" style="color: var(--sage,#5C8A6B);">
                            <span>Hasil Penitip</span>
                            <span id="previewPenitip">-</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Pembeli <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pembeli" class="form-control"
                               placeholder="Nama lengkap pembeli"
                               value="<?= htmlspecialchars($_POST['nama_pembeli'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">No HP Pembeli <span class="text-danger">*</span></label>
                        <input type="text" name="no_hp_pembeli" class="form-control"
                               placeholder="08xxx"
                               value="<?= htmlspecialchars($_POST['no_hp_pembeli'] ?? '') ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Metode Bayar</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_bayar"
                                       value="tunai" id="rtunai"
                                       <?= ($_POST['metode_bayar']??'tunai')==='tunai'?'checked':'' ?>>
                                <label class="form-check-label small" for="rtunai">💵 Tunai</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_bayar"
                                       value="transfer" id="rtransfer"
                                       <?= ($_POST['metode_bayar']??'')==='transfer'?'checked':'' ?>>
                                <label class="form-check-label small" for="rtransfer">📲 Transfer</label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sage w-100">
                        <i class="fa fa-check-circle me-1"></i>Proses Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fa fa-clock-rotate-left me-2"></i>Riwayat Transaksi</span>
                <a href="../laporan/index.php" class="btn btn-sm btn-outline-secondary">Laporan</a>
            </div>
            <div class="card-body p-0">
                <?php
                $riwayat = mysqli_query($conn,
                    "SELECT t.*, b.nama_barang, p.nama as nama_penitip
                     FROM transaksi t
                     JOIN barang b ON t.barang_id = b.id
                     JOIN penitip p ON b.penitip_id = p.id
                     ORDER BY t.tgl_transaksi DESC LIMIT 20");
                ?>
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th class="ps-3">Kode</th><th>Barang</th>
                        <th>Harga</th><th>Komisi</th><th>Tgl</th>
                    </tr></thead>
                    <tbody>
                    <?php $c = 0; while ($t = mysqli_fetch_assoc($riwayat)): $c++; ?>
                    <tr>
                        <td class="ps-3"><code class="small"><?= $t['kode_transaksi'] ?></code></td>
                        <td>
                            <?= htmlspecialchars($t['nama_barang']) ?><br>
                            <small class="text-muted"><?= htmlspecialchars($t['nama_pembeli']) ?></small>
                        </td>
                        <td><?= rupiah($t['harga_jual']) ?></td>
                        <td class="text-success fw-semibold"><?= rupiah($t['komisi_nominal']) ?></td>
                        <td><small><?= date('d M Y', strtotime($t['tgl_transaksi'])) ?></small></td>
                    </tr>
                    <?php endwhile;
                    if ($c === 0): ?>
                    <tr><td colspan="5" class="text-center text-muted py-5">
                        <i class="fa fa-receipt fa-2x mb-2 d-block opacity-25"></i>Belum ada transaksi
                    </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function hitungKomisi() {
    const sel     = document.getElementById('selectBarang');
    const opt     = sel.options[sel.selectedIndex];
    const preview = document.getElementById('previewHarga');
    const harga   = parseFloat(opt.dataset.harga || 0);

    if (!harga) { preview.classList.add('d-none'); return; }

    const komisiPct  = <?= KOMISI_DEFAULT ?>;
    const komisiNom  = Math.round(harga * komisiPct / 100);
    const penitipCut = harga - komisiNom;

    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
    document.getElementById('previewTotal').textContent   = fmt(harga);
    document.getElementById('previewKomisi').textContent  = fmt(komisiNom);
    document.getElementById('previewPenitip').textContent = fmt(penitipCut);
    preview.classList.remove('d-none');
}
</script>

<?php require '../../includes/footer.php'; ?>
