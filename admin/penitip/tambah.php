<?php
session_start();
$pageTitle  = 'Tambah Penitip — ThriftIn';
$activePage = 'penitip';
require '../../config/koneksi.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $no_hp    = trim($_POST['no_hp'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');
    $bank     = trim($_POST['nama_bank'] ?? '');
    $rekening = trim($_POST['no_rekening'] ?? '');

    if (empty($nama))  $errors[] = 'Nama wajib diisi.';
    if (empty($no_hp)) $errors[] = 'No HP wajib diisi.';
    if (!empty($no_hp) && !preg_match('/^[0-9]{9,15}$/', $no_hp))
        $errors[] = 'No HP tidak valid (hanya angka, 9-15 digit).';
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Format email tidak valid.';

    if (empty($errors)) {
        $cek = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM penitip WHERE no_hp='".mysqli_real_escape_string($conn,$no_hp)."'"));
        if ($cek) $errors[] = 'No HP sudah terdaftar untuk penitip lain!';
    }

    if (empty($errors)) {
        $kode     = generateKode('PNT', 'penitip', 'kode_penitip', $conn);
        $nama_s   = mysqli_real_escape_string($conn, $nama);
        $hp_s     = mysqli_real_escape_string($conn, $no_hp);
        $email_s  = mysqli_real_escape_string($conn, $email);
        $alamat_s = mysqli_real_escape_string($conn, $alamat);
        $bank_s   = mysqli_real_escape_string($conn, $bank);
        $rek_s    = mysqli_real_escape_string($conn, $rekening);

        mysqli_query($conn,
            "INSERT INTO penitip (kode_penitip,nama,no_hp,email,alamat,nama_bank,no_rekening)
             VALUES ('$kode','$nama_s','$hp_s','$email_s','$alamat_s','$bank_s','$rek_s')");

        $_SESSION['flash'] = ['success', "Penitip <b>$nama</b> berhasil ditambahkan! Kode: <b>$kode</b>"];
        header("Location: index.php"); exit;
    }
}

require '../../includes/header.php';
?>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header">
        <a href="index.php" class="btn btn-sm btn-outline-secondary me-2">
            <i class="fa fa-arrow-left"></i>
        </a><i class="fa fa-user-plus me-2"></i>Tambah Penitip Baru
    </div>
    <div class="card-body">

        <?php foreach($errors as $e): ?>
        <div class="alert alert-danger py-2 small"><i class="fa fa-exclamation-circle me-1"></i><?= $e ?></div>
        <?php endforeach; ?>

        <form method="POST" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama" class="form-control"
                           placeholder="Nama penitip"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">
                        No HP <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="no_hp" class="form-control"
                           placeholder="Contoh: 081234567890"
                           value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>" required>
                    <div class="form-text">Hanya angka, 9–15 digit</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Email</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="opsional"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Alamat</label>
                    <input type="text" name="alamat" class="form-control"
                           placeholder="Alamat lengkap"
                           value="<?= htmlspecialchars($_POST['alamat'] ?? '') ?>">
                </div>

                <div class="col-12"><hr class="my-1"><small class="text-muted fw-semibold">Info Pembayaran (untuk pencairan dana)</small></div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nama Bank / E-Wallet</label>
                    <select name="nama_bank" class="form-select">
                        <option value="">-- Pilih --</option>
                        <?php foreach(['BCA','Mandiri','BRI','BNI','BSI','GoPay','OVO','Dana','QRIS','Lainnya'] as $bk): ?>
                        <option value="<?= $bk ?>" <?= ($_POST['nama_bank']??'')===$bk?'selected':'' ?>><?= $bk ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">No Rekening / No Akun</label>
                    <input type="text" name="no_rekening" class="form-control"
                           placeholder="No rekening atau akun e-wallet"
                           value="<?= htmlspecialchars($_POST['no_rekening'] ?? '') ?>">
                </div>

                <div class="col-12 text-end border-top pt-3 mt-1">
                    <a href="index.php" class="btn btn-outline-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-sage px-4">
                        <i class="fa fa-save me-1"></i>Simpan Penitip
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
</div></div>

<?php require '../../includes/footer.php'; ?>
