<?php
session_start();
$pageTitle  = 'Cairkan Dana — ThriftIn';
$activePage = 'pencairan';
require '../../config/koneksi.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $penitip_id = (int)($_POST['penitip_id'] ?? 0);
    $metode     = $_POST['metode']      ?? 'tunai';
    $bank       = trim($_POST['nama_bank']    ?? '');
    $rekening   = trim($_POST['no_rekening']  ?? '');
    $catatan    = trim($_POST['catatan']      ?? '');

    // Ambil total yang bisa dicairkan (barang terjual, belum dicairkan)
    if (!$penitip_id) {
        $errors[] = 'Pilih penitip.';
    } else {
        $sum = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT SUM(t.hasil_penitip) as total
             FROM transaksi t
             JOIN barang b ON t.barang_id = b.id
             WHERE b.penitip_id = $penitip_id AND b.status = 'terjual'"));
        $total = (float)($sum['total'] ?? 0);

        if ($total <= 0) $errors[] = 'Tidak ada dana yang bisa dicairkan untuk penitip ini.';
        if ($metode === 'transfer' && empty($bank)) $errors[] = 'Nama bank wajib diisi untuk metode transfer.';
        if ($metode === 'transfer' && empty($rekening)) $errors[] = 'No rekening wajib diisi untuk metode transfer.';
    }

    if (empty($errors)) {
        $kode     = generateKode('PCR', 'pencairan', 'kode_pencairan', $conn);
        $kasir_id = $_SESSION['user_id'];
        $bank_s   = mysqli_real_escape_string($conn, $bank);
        $rek_s    = mysqli_real_escape_string($conn, $rekening);
        $met_s    = mysqli_real_escape_string($conn, $metode);
        $cat_s    = mysqli_real_escape_string($conn, $catatan);

        // Simpan pencairan
        mysqli_query($conn,
            "INSERT INTO pencairan
             (kode_pencairan,penitip_id,total_nominal,metode,nama_bank,no_rekening,kasir_id,catatan)
             VALUES
             ('$kode',$penitip_id,$total,'$met_s','$bank_s','$rek_s',$kasir_id,'$cat_s')");

        // Update status barang jadi 'dicairkan'
        mysqli_query($conn,
            "UPDATE barang SET status='dicairkan', tgl_dicairkan=CURDATE()
             WHERE penitip_id=$penitip_id AND status='terjual'");

        $_SESSION['flash'] = ['success',
            "Pencairan <b>$kode</b> berhasil! Total: <b>" . rupiah($total) . "</b>"];
        header("Location: index.php"); exit;
    }
}

// Penitip dengan saldo yang bisa dicairkan
$penitipSaldo = mysqli_query($conn,
    "SELECT p.id, p.nama, p.kode_penitip, p.nama_bank, p.no_rekening,
            SUM(t.hasil_penitip) as saldo,
            COUNT(t.id) as jumlah_barang
     FROM penitip p
     JOIN barang b ON b.penitip_id = p.id
     JOIN transaksi t ON t.barang_id = b.id
     WHERE b.status = 'terjual'
     GROUP BY p.id
     HAVING saldo > 0
     ORDER BY p.nama");

require '../../includes/header.php';
?>

<?php if (isset($_SESSION['flash'])): [$type,$msg] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show">
    <?= $msg ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- Form Pencairan -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <a href="index.php" class="btn btn-sm btn-outline-secondary me-2">
                    <i class="fa fa-arrow-left"></i>
                </a><i class="fa fa-money-bill-wave me-2"></i>Form Pencairan Dana
            </div>
            <div class="card-body">

                <?php foreach($errors as $e): ?>
                <div class="alert alert-danger py-2 small"><?= $e ?></div>
                <?php endforeach; ?>

                <form method="POST" id="formPcr">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Pilih Penitip <span class="text-danger">*</span></label>
                        <select name="penitip_id" class="form-select" id="selPenitip"
                                onchange="updateInfoPenitip()" required>
                            <option value="">-- Pilih Penitip --</option>
                            <?php
                            $rows = [];
                            while ($r = mysqli_fetch_assoc($penitipSaldo)) $rows[] = $r;
                            foreach ($rows as $r): ?>
                            <option value="<?= $r['id'] ?>"
                                    data-saldo="<?= $r['saldo'] ?>"
                                    data-jml="<?= $r['jumlah_barang'] ?>"
                                    data-bank="<?= htmlspecialchars($r['nama_bank'] ?? '') ?>"
                                    data-rek="<?= htmlspecialchars($r['no_rekening'] ?? '') ?>"
                                    <?= ($_POST['penitip_id']??'')==$r['id']?'selected':'' ?>>
                                <?= htmlspecialchars($r['nama']) ?> (<?= $r['kode_penitip'] ?>)
                                — <?= rupiah($r['saldo']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (count($rows) === 0): ?>
                        <div class="form-text text-danger">Tidak ada penitip dengan saldo yang dapat dicairkan.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Preview saldo -->
                    <div id="infoPenitip" class="rounded-3 p-3 mb-3 d-none" style="background:var(--cream,#F5F0E8)">
                        <div class="d-flex justify-content-between small mb-1">
                            <span>Total Barang Terjual</span>
                            <strong id="infoJml">-</strong>
                        </div>
                        <div class="d-flex justify-content-between small fw-bold" style="color:var(--sage,#5C8A6B)">
                            <span>Saldo yang Akan Dicairkan</span>
                            <span id="infoSaldo">-</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Metode Pencairan</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode"
                                       value="tunai" id="rtunai"
                                       onchange="toggleTransfer()"
                                       <?= ($_POST['metode']??'tunai')==='tunai'?'checked':'' ?>>
                                <label class="form-check-label small" for="rtunai">💵 Tunai</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode"
                                       value="transfer" id="rtransfer"
                                       onchange="toggleTransfer()"
                                       <?= ($_POST['metode']??'')==='transfer'?'checked':'' ?>>
                                <label class="form-check-label small" for="rtransfer">📲 Transfer</label>
                            </div>
                        </div>
                    </div>

                    <div id="transferFields" class="<?= ($_POST['metode']??'')==='transfer'?'':'d-none' ?>">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Bank / E-Wallet</label>
                            <select name="nama_bank" id="selBank" class="form-select">
                                <option value="">-- Pilih --</option>
                                <?php foreach(['BCA','Mandiri','BRI','BNI','BSI','GoPay','OVO','Dana','QRIS','Lainnya'] as $bk): ?>
                                <option value="<?= $bk ?>" <?= ($_POST['nama_bank']??'')===$bk?'selected':'' ?>><?= $bk ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">No Rekening / Akun</label>
                            <input type="text" name="no_rekening" id="inpRek" class="form-control"
                                   placeholder="No rekening penitip"
                                   value="<?= htmlspecialchars($_POST['no_rekening'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="2"
                                  placeholder="Opsional"><?= htmlspecialchars($_POST['catatan'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-sage w-100"
                            <?= count($rows)===0?'disabled':'' ?>>
                        <i class="fa fa-check-circle me-1"></i>Proses Pencairan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel saldo penitip -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-list me-2"></i>Penitip dengan Saldo Tersedia
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr>
                        <th class="ps-3">Penitip</th>
                        <th>Barang Terjual</th>
                        <th>Saldo</th>
                    </tr></thead>
                    <tbody>
                    <?php if (count($rows) === 0): ?>
                    <tr><td colspan="3" class="text-center text-muted py-5">
                        <i class="fa fa-hand-holding-dollar fa-2x mb-2 d-block opacity-25"></i>
                        Tidak ada saldo yang perlu dicairkan
                    </td></tr>
                    <?php else: foreach($rows as $r): ?>
                    <tr>
                        <td class="ps-3">
                            <strong><?= htmlspecialchars($r['nama']) ?></strong><br>
                            <small class="text-muted"><?= $r['kode_penitip'] ?></small>
                        </td>
                        <td><?= $r['jumlah_barang'] ?> item</td>
                        <td class="fw-semibold text-success"><?= rupiah($r['saldo']) ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateInfoPenitip() {
    const sel  = document.getElementById('selPenitip');
    const opt  = sel.options[sel.selectedIndex];
    const info = document.getElementById('infoPenitip');
    const saldo = parseFloat(opt.dataset.saldo || 0);

    if (!saldo) { info.classList.add('d-none'); return; }

    info.classList.remove('d-none');
    document.getElementById('infoJml').textContent   = opt.dataset.jml + ' item';
    document.getElementById('infoSaldo').textContent = 'Rp ' + saldo.toLocaleString('id-ID');

    // Auto-fill bank & rekening dari data penitip
    if (opt.dataset.bank) {
        const selBank = document.getElementById('selBank');
        for (let o of selBank.options) {
            if (o.value === opt.dataset.bank) { o.selected = true; break; }
        }
    }
    if (opt.dataset.rek) {
        document.getElementById('inpRek').value = opt.dataset.rek;
    }
}

function toggleTransfer() {
    const isTrf = document.getElementById('rtransfer').checked;
    document.getElementById('transferFields').classList.toggle('d-none', !isTrf);
}

// Run on load
updateInfoPenitip();
</script>

<?php require '../../includes/footer.php'; ?>
