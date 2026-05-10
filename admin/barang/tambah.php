<?php
session_start();
$pageTitle  = 'Tambah Barang — ThriftIn';
$activePage = 'barang';
require '../../config/koneksi.php';

$errors = [];

// Ambil pengaturan harga minimum
$pengaturan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1"));
$hargaMin   = [
    'baru'         => $pengaturan['harga_min_baru']          ?? 10000,
    'seperti_baru' => $pengaturan['harga_min_seperti_baru']  ?? 5000,
    'bekas_layak'  => $pengaturan['harga_min_bekas_layak']   ?? 3000,
    'bekas'        => $pengaturan['harga_min_bekas']         ?? 1000,
];

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
    $tgl_masuk   = $_POST['tgl_masuk']           ?? date('Y-m-d');

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
        $kode  = generateKode('BRG', 'barang', 'kode_barang', $conn);
        $nama_s  = mysqli_real_escape_string($conn, $nama_barang);
        $desk_s  = mysqli_real_escape_string($conn, $deskripsi);
        $kond_s  = mysqli_real_escape_string($conn, $kondisi);
        $ukur_s  = mysqli_real_escape_string($conn, $ukuran);
        $warn_s  = mysqli_real_escape_string($conn, $warna);
        $tgl_s   = mysqli_real_escape_string($conn, $tgl_masuk);

        mysqli_query($conn,
            "INSERT INTO barang
             (kode_barang,penitip_id,kategori_id,nama_barang,deskripsi,kondisi,
              harga_titip,harga_jual,ukuran,warna,tgl_masuk,status)
             VALUES
             ('$kode',$penitip_id,$kategori_id,'$nama_s','$desk_s','$kond_s',
              $harga_titip,$harga_jual,'$ukur_s','$warn_s','$tgl_s','menunggu')");

        $_SESSION['flash'] = ['success', "Barang <b>$nama_barang</b> berhasil ditambahkan! Kode: <b>$kode</b>"];
        header("Location: index.php"); exit;
    }
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
        </a><i class="fa fa-plus-circle me-2"></i>Tambah Barang Titipan
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
                        <option value="<?= $p['id'] ?>" <?= ($_POST['penitip_id']??'')==$p['id']?'selected':'' ?>>
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
                        <option value="<?= $k['id'] ?>" <?= ($_POST['kategori_id']??'')==$k['id']?'selected':'' ?>>
                            <?= htmlspecialchars($k['nama_kategori']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" name="nama_barang" class="form-control"
                           placeholder="Contoh: Jaket Denim Vintage Lee"
                           value="<?= htmlspecialchars($_POST['nama_barang'] ?? '') ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold small">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="2"
                              placeholder="Detail barang, ukuran, bahan, dll."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Kondisi <span class="text-danger">*</span></label>
                    <select name="kondisi" class="form-select" id="kondisi" onchange="updateMinHarga()" required>
                        <option value="">-- Pilih --</option>
                        <?php
                        $kondisiOpt = ['baru'=>'Baru','seperti_baru'=>'Seperti Baru','bekas_layak'=>'Bekas Layak','bekas'=>'Bekas'];
                        foreach($kondisiOpt as $val=>$lbl): ?>
                        <option value="<?= $val ?>" <?= ($_POST['kondisi']??'')===$val?'selected':'' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text" id="hintMin"></div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Ukuran</label>
                    <input type="text" name="ukuran" class="form-control"
                           placeholder="S, M, L, XL, 38, dll."
                           value="<?= htmlspecialchars($_POST['ukuran'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Warna</label>
                    <input type="text" name="warna" class="form-control"
                           placeholder="Hitam, Biru, dll."
                           value="<?= htmlspecialchars($_POST['warna'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Harga Titip (diminta penitip) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_titip" class="form-control"
                               placeholder="0" min="0"
                               value="<?= $_POST['harga_titip'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Harga Jual (dipasang toko) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" class="form-control"
                               placeholder="0" min="0" id="harga_jual"
                               value="<?= $_POST['harga_jual'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_masuk" class="form-control"
                           value="<?= $_POST['tgl_masuk'] ?? date('Y-m-d') ?>" required>
                </div>

                <!-- Preview komisi -->
                <div class="col-12">
                    <div id="previewKomisi" class="rounded-3 p-3 d-none" style="background:var(--cream,#F5F0E8)">
                        <small class="text-muted fw-semibold d-block mb-2">Preview Komisi (<?= KOMISI_DEFAULT ?>%)</small>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="fw-bold" id="pvHarga">-</div>
                                <div class="text-muted" style="font-size:.75rem">Harga Jual</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold text-danger" id="pvKomisi">-</div>
                                <div class="text-muted" style="font-size:.75rem">Komisi Toko</div>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold" style="color:var(--sage,#5C8A6B)" id="pvPenitip">-</div>
                                <div class="text-muted" style="font-size:.75rem">Hasil Penitip</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-end border-top pt-3 mt-1">
                    <a href="index.php" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-sage px-4">
                        <i class="fa fa-save me-1"></i>Simpan Barang
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div></div>

<script>
const hargaMin = <?= json_encode($hargaMin) ?>;
const komisiPct = <?= KOMISI_DEFAULT ?>;

function updateMinHarga() {
    const kondisi = document.getElementById('kondisi').value;
    const hint    = document.getElementById('hintMin');
    if (kondisi && hargaMin[kondisi]) {
        hint.textContent = 'Harga jual minimal: Rp ' + hargaMin[kondisi].toLocaleString('id-ID');
    } else {
        hint.textContent = '';
    }
    hitungKomisi();
}

document.getElementById('harga_jual').addEventListener('input', hitungKomisi);

function hitungKomisi() {
    const harga  = parseFloat(document.getElementById('harga_jual').value) || 0;
    const box    = document.getElementById('previewKomisi');
    if (!harga) { box.classList.add('d-none'); return; }
    box.classList.remove('d-none');
    const komisi  = Math.round(harga * komisiPct / 100);
    const penitip = harga - komisi;
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
    document.getElementById('pvHarga').textContent   = fmt(harga);
    document.getElementById('pvKomisi').textContent  = fmt(komisi);
    document.getElementById('pvPenitip').textContent = fmt(penitip);
}

// Run on load if kondisi pre-selected
updateMinHarga();
</script>

<?php require '../../includes/footer.php'; ?>
