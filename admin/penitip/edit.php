<?php
session_start();
$pageTitle  = 'Edit Penitip — ThriftIn';
$activePage = 'penitip';
require '../../config/koneksi.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: index.php"); exit; }

$penitip = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penitip WHERE id=$id"));
if (!$penitip) {
    $_SESSION['flash'] = ['danger', 'Penitip tidak ditemukan.'];
    header("Location: index.php"); exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $no_hp    = trim($_POST['no_hp'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');
    $bank     = trim($_POST['nama_bank'] ?? '');
    $rekening = trim($_POST['no_rekening'] ?? '');
    $status   = $_POST['status'] ?? 'aktif';

    if (empty($nama))  $errors[] = 'Nama wajib diisi.';
    if (empty($no_hp)) $errors[] = 'No HP wajib diisi.';
    if (!empty($no_hp) && !preg_match('/^[0-9]{9,15}$/', $no_hp))
        $errors[] = 'No HP tidak valid (hanya angka, 9-15 digit).';
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = 'Format email tidak valid.';

    if (empty($errors)) {
        $cek = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM penitip WHERE no_hp='".mysqli_real_escape_string($conn,$no_hp)."' AND id != $id"));
        if ($cek) $errors[] = 'No HP sudah dipakai penitip lain!';
    }

    if (empty($errors)) {
        $nama_s   = mysqli_real_escape_string($conn, $nama);
        $hp_s     = mysqli_real_escape_string($conn, $no_hp);
        $email_s  = mysqli_real_escape_string($conn, $email);
        $alamat_s = mysqli_real_escape_string($conn, $alamat);
        $bank_s   = mysqli_real_escape_string($conn, $bank);
        $rek_s    = mysqli_real_escape_string($conn, $rekening);
        $status_s = mysqli_real_escape_string($conn, $status);

        mysqli_query($conn,
            "UPDATE penitip SET
                nama='$nama_s', no_hp='$hp_s', email='$email_s',
                alamat='$alamat_s', nama_bank='$bank_s',
                no_rekening='$rek_s', status='$status_s'
             WHERE id=$id");

        $_SESSION['flash'] = ['success', "Data penitip <b>$nama</b> berhasil diperbarui."];
        header("Location: index.php"); exit;
    }

    // Repopulate from POST on error
    $penitip = array_merge($penitip, $_POST);
}

require '../../includes/header.php';
?>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header">
        <a href="index.php" class="btn btn-sm btn-outline-secondary me-2">
            <i class="fa fa-arrow-left"></i>
        </a><i class="fa fa-user-pen me-2"></i>Edit Penitip
        <code class="ms-2 small"><?= $penitip['kode_penitip'] ?></code>
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
                           value="<?= htmlspecialchars($penitip['nama']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">
                        No HP <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="no_hp" class="form-control"
                           placeholder="Contoh: 081234567890"
                           value="<?= htmlspecialchars($penitip['no_hp']) ?>" required>
                    <div class="form-text">Hanya angka, 9–15 digit</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Email</label>
                    <input type="email" name="email" class="form-control"
                           placeholder="opsional"
                           value="<?= htmlspecialchars($penitip['email'] ?? '') ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Alamat</label>
                    <input type="text" name="alamat" class="form-control"
                           placeholder="Alamat lengkap"
                           value="<?= htmlspecialchars($penitip['alamat'] ?? '') ?>">
                </div>

                <div class="col-12"><hr class="my-1"><small class="text-muted fw-semibold">Info Pembayaran</small></div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold small">Nama Bank / E-Wallet</label>
                    <select name="nama_bank" class="form-select">
                        <option value="">-- Pilih --</option>
                        <?php foreach(['BCA','Mandiri','BRI','BNI','BSI','GoPay','OVO','Dana','QRIS','Lainnya'] as $bk): ?>
                        <option value="<?= $bk ?>" <?= ($penitip['nama_bank']??'')===$bk?'selected':'' ?>><?= $bk ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold small">No Rekening / No Akun</label>
                    <input type="text" name="no_rekening" class="form-control"
                           placeholder="No rekening atau akun e-wallet"
                           value="<?= htmlspecialchars($penitip['no_rekening'] ?? '') ?>">
                </div>

                <div class="col-12"><hr class="my-1"><small class="text-muted fw-semibold">Status Akun</small></div>

                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status"
                               id="aktif" value="aktif"
                               <?= ($penitip['status']==='aktif')?'checked':'' ?>>
                        <label class="form-check-label small" for="aktif">
                            <span class="badge bg-success">Aktif</span>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status"
                               id="nonaktif" value="nonaktif"
                               <?= ($penitip['status']==='nonaktif')?'checked':'' ?>>
                        <label class="form-check-label small" for="nonaktif">
                            <span class="badge bg-secondary">Nonaktif</span>
                        </label>
                    </div>
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

<?php require '../../includes/footer.php'; ?>
