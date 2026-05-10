<?php
session_start();
$pageTitle  = 'Edit Barang — ThriftIn';
$activePage = 'barang';
require '../../config/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit; }

$barang = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM barang WHERE id=$id"));
if (!$barang || in_array($barang['status'], ['terjual','dicairkan'])) {
    $_SESSION['flash'] = ['danger', 'Barang tidak dapat diedit (tidak ditemukan atau sudah terjual).'];
    header("Location: index.php"); exit;
}

$pengaturan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1"));
$hargaMin   = [
    'baru'         => $pengaturan['harga_min_baru']          ?? 10000,
    'seperti_baru' => $pengaturan['harga_min_seperti_baru']  ?? 5000,
    'bekas_layak'  => $pengaturan['harga_min_bekas_layak']   ?? 3000,
    'bekas'        => $pengaturan['harga_min_bekas']         ?? 1000,
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $penitip_id  = (int)($_POST['penitip_id']  ?? 0);
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $nama_barang = trim($_POST['nama_barang']   ?? '');
    $deskripsi   = trim($_POST['deskripsi']     ?? '');
    $kondisi     = $_POST['kondisi']            ?? '';
    $harga_titip = (float)($_POST['harga_titip'] ?? 0);
    $harga_jual  = (float)($_POST['harga_jual']  ?? 0);
    $ukuran      = trim($_POST['ukuran']         ?? '');
    $warna       = trim($_POST['warna']          ?? '');
    $tgl_masuk   = $_POST['tgl_masuk']           ?? $barang['tgl_masuk'];

    if (!$penitip_id)         $errors[] = 'Pilih penitip.';
    if (!$kategori_id)        $errors[] = 'Pilih kategori.';
    if (empty($nama_barang))  $errors[] = 'Nama barang wajib diisi.';
    if (!$kondisi)            $errors[] = 'Pilih kondisi barang.';
    if ($harga_jual <= 0)     $errors[] = 'Harga jual harus lebih dari 0.';
    if ($harga_titip <= 0)    $errors[] = 'Harga titip harus lebih dari 0.';

    if ($kondisi && $harga_jual > 0) {
        $minH = $hargaMin[$kondisi] ?? 0;
        if ($harga_jual < $minH)
            $errors[] = "Harga jual minimal untuk kondisi ini adalah " . rupiah($minH) . ".";
    }

    if (empty($errors)) {
        $nama_s  = mysqli_real_escape_string($conn, $nama_barang);
        $desk_s  = mysqli_real_escape_string($conn, $deskripsi);
        $kond_s  = mysqli_real_escape_string($conn, $kondisi);
        $ukur_s  = mysqli_real_escape_string($conn, $ukuran);
        $warn_s  = mysqli_real_escape_string($conn, $warna);
        $tgl_s   = mysqli_real_escape_string($conn, $tgl_masuk);

        mysqli_query($conn,
            "UPDATE barang SET
                penitip_id=$penitip_id, kategori_id=$kategori_id,
                nama_barang='$nama_s', deskripsi='$desk_s',
                kondisi='$kond_s', harga_titip=$harga_titip, harga_jual=$harga_jual,
                ukuran='$ukur_s', warna='$warn_s', tgl_masuk='$tgl_s'
             WHERE id=$id");

        $_SESSION['flash'] = ['success', "Barang <b>$nama_barang</b> berhasil diperbarui."];
        header("Location: index.php"); exit;
    }

    $barang = array_merge($barang, $_POST);
}

$penitipList  = mysqli_query($conn, "SELECT id,nama,kode_penitip FROM penitip WHERE status='aktif' ORDER BY nama");
$kategoriList = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");

require '../../includes/header.php';
?>

<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header">
        <a href="index.php" class="btn btn-sm btn-outline-secondary me-2">
            <i class="fa fa-arrow-left"></i>
        </a><i class="fa fa-pen me-2"></i>Edit Barang
        <code class="ms-2 small"><?= $barang['kode_barang'] ?></code>
    </div>
    <div class="card-body">

        <?php foreach($errors as $e): ?>
        <div class="alert alert-danger py-2 small"><i class="fa fa-exclamation-circle me-1"></i><?= $e ?></div>
        <?php endforeach; ?>

        <form method="POST" novalidate>
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Penitip <span class="text-danger">*</span></label>
                    <select name="penitip_id" class="form-select" required>
                        <option value="">-- Pilih Penitip --</option>
                        <?php while($p = mysqli_fetch_assoc($penitipList)): ?>
                        <option value="<?= $p['id'] ?>" <?= $barang['penitip_id']==$p['id']?'selected':'' ?>>
                            <?= htmlspecialchars($p['nama']) ?> (<?= $p['kode_penitip'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php while($k = mysqli_fetch_assoc($kategoriList)): ?>
                        <option value="<?= $k['id'] ?>" <?= $barang['kategori_id']==$k['id']?'selected':'' ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control"
                           value="<?= htmlspecialchars($barang['nama_barang']) ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="2"><?= htmlspecialchars($barang['deskripsi'] ?? '') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-select" id="kondisi" onchange="updateMinHarga()" required>
                        <?php
                        $kondisiOpt = ['baru'=>'Baru','seperti_baru'=>'Seperti Baru','bekas_layak'=>'Bekas Layak','bekas'=>'Bekas'];
                        foreach($kondisiOpt as $val=>$lbl): ?>
                        <option value="<?= $val ?>" <?= $barang['kondisi']===$val?'selected':'' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text" id="hintMin"></div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Ukuran</label>
                    <input type="text" name="ukuran" class="form-control"
                           value="<?= htmlspecialchars($barang['ukuran'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Warna</label>
                    <input type="text" name="warna" class="form-control"
                           value="<?= htmlspecialchars($barang['warna'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Harga Titip <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_titip" class="form-control"
                               value="<?= $barang['harga_titip'] ?>" min="0" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Harga Jual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" class="form-control" id="harga_jual"
                               value="<?= $barang['harga_jual'] ?>" min="0" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Tanggal Masuk</label>
                    <input type="date" name="tgl_masuk" class="form-control"
                           value="<?= $barang['tgl_masuk'] ?>" required>
                </div>

                <div class="col-12 text-end border-top pt-3 mt-1">
                    <a href="index.php" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-sage px-4">
                        <i class="fa fa-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div></div>

<script>
const hargaMin = <?= json_encode($hargaMin) ?>;
function updateMinHarga() {
    const kondisi = document.getElementById('kondisi').value;
    const hint    = document.getElementById('hintMin');
    hint.textContent = kondisi && hargaMin[kondisi]
        ? 'Harga jual minimal: Rp ' + hargaMin[kondisi].toLocaleString('id-ID') : '';
}
updateMinHarga();
</script>

<?php require '../../includes/footer.php'; ?>
