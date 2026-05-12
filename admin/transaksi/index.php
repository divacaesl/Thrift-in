<?php
session_start();
$pageTitle  = 'Transaksi Penjualan — ThriftIn';
$activePage = 'transaksi';
require '../../config/koneksi.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id     = (int)($_POST['barang_id']     ?? 0);
    $nama_pembeli  = trim($_POST['nama_pembeli']   ?? '');
    $no_hp_pembeli = trim($_POST['no_hp_pembeli']  ?? '');
    $metode        = $_POST['metode_bayar']         ?? 'tunai';

    if (!$barang_id)           $errors[] = 'Pilih barang yang akan dijual.';
    if (empty($nama_pembeli))  $errors[] = 'Nama pembeli wajib diisi.';
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
             (kode_transaksi, barang_id, nama_pembeli, no_hp_pembeli, harga_jual,
              komisi_persen, komisi_nominal, hasil_penitip, metode_bayar, kasir_id)
             VALUES
             ('$kode', $barang_id, '$nama_s', '$hp_s', $harga,
              $komisi_persen, $komisi_nom, $hasil_penitip, '$met_s', $kasir_id)");

        mysqli_query($conn,
            "UPDATE barang SET status='terjual', tgl_terjual=CURDATE() WHERE id=$barang_id");

        $_SESSION['flash'] = ['success',
            "Transaksi <b>$kode</b> berhasil! ".
            "Komisi toko: <b>".rupiah($komisi_nom)."</b> | ".
            "Hasil penitip: <b>".rupiah($hasil_penitip)."</b>"];
        header("Location: index.php"); exit;
    }
}

// Barang yang tersedia (status ditampilkan) — ambil foto juga
$barangList = mysqli_query($conn,
    "SELECT b.id, b.nama_barang, b.harga_jual, b.kondisi, b.foto,
            p.nama as nama_penitip, k.nama_kategori
     FROM barang b
     JOIN penitip p ON b.penitip_id = p.id
     JOIN kategori k ON b.kategori_id = k.id
     WHERE b.status = 'ditampilkan'
     ORDER BY b.nama_barang");

require '../../includes/header.php';
?>

<style>
/* ── Preview foto barang di form transaksi ──────────────── */
#previewFotoWrap {
    display: none;
    width: 100%;
    aspect-ratio: 1 / 1;
    border-radius: 14px;
    overflow: hidden;
    background: #f0f0f0;
    margin-bottom: .75rem;
    box-shadow: 0 4px 16px rgba(0,0,0,.10);
    position: relative;
}
#previewFotoWrap img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: opacity .2s;
}
#previewFotoWrap .foto-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.45) 0%, transparent 55%);
    border-radius: 14px;
}
#previewFotoWrap .foto-label {
    position: absolute;
    bottom: 10px; left: 12px; right: 12px;
    color: #fff;
    font-size: .8rem;
    font-weight: 600;
    line-height: 1.3;
}
/* kondisi badge di overlay */
#previewFotoWrap .foto-kondisi {
    position: absolute;
    top: 10px; right: 10px;
}
</style>

<?php if (isset($_SESSION['flash'])): [$type,$msg] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show">
    <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Simpan data barang untuk JS -->
<script>
const barangData = {
    <?php
    // Rewind result dan encode ke JS object
    mysqli_data_seek($barangList, 0);
    $jsEntries = [];
    while ($b = mysqli_fetch_assoc($barangList)) {
        $foto  = $b['foto'] ?: 'default.jpg';
        $jsEntries[] = sprintf(
            '%d: { foto: "%s", nama: "%s", penitip: "%s", kondisi: "%s", harga: %s }',
            $b['id'],
            '../../assets/uploads/' . addslashes($foto),
            addslashes($b['nama_barang']),
            addslashes($b['nama_penitip']),
            $b['kondisi'],
            $b['harga_jual']
        );
    }
    echo implode(",\n    ", $jsEntries);
    ?>
};

const kondisiLabel = {
    baru:         'Baru',
    seperti_baru: 'Seperti Baru',
    bekas_layak:  'Bekas Layak',
    bekas:        'Bekas'
};
const kondisiBadge = {
    baru:         'bg-success',
    seperti_baru: 'bg-primary',
    bekas_layak:  'bg-warning text-dark',
    bekas:        'bg-secondary'
};
</script>

<div class="row g-4">

    <!-- ── KOLOM FORM ──────────────────────────────────── -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-bag-shopping me-2"></i>Form Transaksi Baru
            </div>
            <div class="card-body">

                <?php foreach ($errors as $e): ?>
                <div class="alert alert-danger py-2 small">
                    <i class="fa fa-exclamation-circle me-1"></i><?= $e ?>
                </div>
                <?php endforeach; ?>

                <form method="POST" id="formTrx">

                    <!-- ── PREVIEW FOTO BARANG ── -->
                    <div id="previewFotoWrap">
                        <img id="previewFotoImg" src="" alt="Foto Barang"
                             onerror="this.src='../../assets/uploads/default.jpg'">
                        <div class="foto-overlay"></div>
                        <div class="foto-label">
                            <span id="previewFotoNama"></span><br>
                            <small id="previewFotoPenitip" class="opacity-75"></small>
                        </div>
                        <div class="foto-kondisi">
                            <span id="previewFotoKondisi" class="badge"></span>
                        </div>
                    </div>

                    <!-- Pilih Barang -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Pilih Barang <span class="text-danger">*</span>
                        </label>
                        <?php mysqli_data_seek($barangList, 0); ?>
                        <select name="barang_id" class="form-select"
                                id="selectBarang" onchange="updatePreview()" required>
                            <option value="">-- Pilih barang --</option>
                            <?php while ($b = mysqli_fetch_assoc($barangList)): ?>
                            <option value="<?= $b['id'] ?>"
                                    <?= ($_POST['barang_id']??'')==$b['id']?'selected':'' ?>>
                                <?= htmlspecialchars($b['nama_barang']) ?> —
                                <?= rupiah($b['harga_jual']) ?>
                                (<?= htmlspecialchars($b['nama_penitip']) ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Preview harga + komisi -->
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
                        <div class="d-flex justify-content-between small fw-bold"
                             style="color: var(--sage,#5C8A6B);">
                            <span>Hasil Penitip</span>
                            <span id="previewPenitip">-</span>
                        </div>
                    </div>

                    <!-- Nama Pembeli -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            Nama Pembeli <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="nama_pembeli" class="form-control"
                               placeholder="Nama lengkap pembeli"
                               value="<?= htmlspecialchars($_POST['nama_pembeli'] ?? '') ?>"
                               required>
                    </div>

                    <!-- No HP Pembeli -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">
                            No HP Pembeli <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="no_hp_pembeli" class="form-control"
                               placeholder="08xxx"
                               value="<?= htmlspecialchars($_POST['no_hp_pembeli'] ?? '') ?>"
                               required>
                    </div>

                    <!-- Metode Bayar -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Metode Bayar</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="metode_bayar" value="tunai" id="rtunai"
                                       <?= ($_POST['metode_bayar']??'tunai')==='tunai'?'checked':'' ?>>
                                <label class="form-check-label small" for="rtunai">💵 Tunai</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="metode_bayar" value="transfer" id="rtransfer"
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

    <!-- ── KOLOM RIWAYAT ───────────────────────────────── -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa fa-clock-rotate-left me-2"></i>Riwayat Transaksi</span>
                <a href="../laporan/index.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-chart-bar me-1"></i>Laporan
                </a>
            </div>
            <div class="card-body p-0">
                <?php
                $riwayat = mysqli_query($conn,
                    "SELECT t.*, b.nama_barang, b.foto, p.nama as nama_penitip
                     FROM transaksi t
                     JOIN barang b ON t.barang_id = b.id
                     JOIN penitip p ON b.penitip_id = p.id
                     ORDER BY t.tgl_transaksi DESC
                     LIMIT 20");
                ?>
                <table class="table table-hover mb-0 align-middle">
                    <thead><tr>
                        <th class="ps-3" style="width:52px">Foto</th>
                        <th>Kode</th>
                        <th>Barang</th>
                        <th>Harga</th>
                        <th>Komisi</th>
                        <th>Tgl</th>
                    </tr></thead>
                    <tbody>
                    <?php $c = 0;
                    while ($t = mysqli_fetch_assoc($riwayat)): $c++;
                        $foto    = $t['foto'] ?: 'default.jpg';
                        $fotoUrl = '../../assets/uploads/' . htmlspecialchars($foto);
                    ?>
                    <tr>
                        <!-- ── THUMBNAIL DI RIWAYAT ── -->
                        <td class="ps-3">
                            <div style="width:44px;height:44px;border-radius:8px;
                                        overflow:hidden;background:#eee;flex-shrink:0;">
                                <img src="<?= $fotoUrl ?>"
                                     alt="<?= htmlspecialchars($t['nama_barang']) ?>"
                                     style="width:100%;height:100%;object-fit:cover;"
                                     onerror="this.src='../../assets/uploads/default.jpg'"
                                     loading="lazy">
                            </div>
                        </td>
                        <td><code class="small"><?= $t['kode_transaksi'] ?></code></td>
                        <td>
                            <?= htmlspecialchars($t['nama_barang']) ?><br>
                            <small class="text-muted">
                                <?= htmlspecialchars($t['nama_pembeli']) ?>
                            </small>
                        </td>
                        <td><?= rupiah($t['harga_jual']) ?></td>
                        <td class="text-success fw-semibold">
                            <?= rupiah($t['komisi_nominal']) ?>
                        </td>
                        <td>
                            <small><?= date('d M Y', strtotime($t['tgl_transaksi'])) ?></small>
                        </td>
                    </tr>
                    <?php endwhile;
                    if ($c === 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-5">
                        <i class="fa fa-receipt fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada transaksi
                    </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const sel     = document.getElementById('selectBarang');
    const id      = parseInt(sel.value);
    const wrap    = document.getElementById('previewFotoWrap');
    const hargaEl = document.getElementById('previewHarga');

    if (!id || !barangData[id]) {
        wrap.style.display    = 'none';
        hargaEl.classList.add('d-none');
        return;
    }

    const b = barangData[id];

    // ── Update foto preview ──
    document.getElementById('previewFotoImg').src         = b.foto;
    document.getElementById('previewFotoNama').textContent    = b.nama;
    document.getElementById('previewFotoPenitip').textContent = 'Penitip: ' + b.penitip;

    const kdBadge = document.getElementById('previewFotoKondisi');
    kdBadge.textContent  = kondisiLabel[b.kondisi] ?? b.kondisi;
    kdBadge.className    = 'badge ' + (kondisiBadge[b.kondisi] ?? 'bg-secondary');

    wrap.style.display = 'block';

    // ── Update harga / komisi ──
    const komisiPct  = <?= KOMISI_DEFAULT ?>;
    const komisiNom  = Math.round(b.harga * komisiPct / 100);
    const penitipCut = b.harga - komisiNom;
    const fmt        = n => 'Rp ' + n.toLocaleString('id-ID');

    document.getElementById('previewTotal').textContent   = fmt(b.harga);
    document.getElementById('previewKomisi').textContent  = fmt(komisiNom);
    document.getElementById('previewPenitip').textContent = fmt(penitipCut);
    hargaEl.classList.remove('d-none');
}

// Trigger preview jika ada nilai terpilih saat load (misal setelah error POST)
window.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('selectBarang').value) updatePreview();
});
</script>

<?php require '../../includes/footer.php'; ?>