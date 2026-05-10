<?php
session_start();
require 'config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: admin/dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan password wajib diisi!';
    } else {
        $username_safe = mysqli_real_escape_string($conn, $username);
        $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username_safe' LIMIT 1");
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] === 'nonaktif') {
                $error = 'Akun kamu nonaktif. Hubungi admin ya!';
            } else {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['nama']      = $user['nama'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['username']  = $user['username'];
                header("Location: admin/dashboard.php");
                exit;
            }
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ThriftIn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sage:       #5C8A6B;
            --terracotta: #D4956A;
            --cream:      #F5F0E8;
            --dark:       #2C2C2C;
        }
        body {
            background-color: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .brand-logo {
            font-size: 2rem;
            font-weight: 800;
            color: var(--sage);
            letter-spacing: -1px;
        }
        .brand-logo span { color: var(--terracotta); }
        .tagline {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 2rem;
        }
        .form-control:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 0.2rem rgba(92,138,107,0.2);
        }
        .btn-login {
            background-color: var(--sage);
            border: none;
            color: #fff;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .btn-login:hover { background-color: #4a7358; color: #fff; }
        .input-group-text {
            background: var(--cream);
            border-right: none;
            color: var(--sage);
        }
        .form-control { border-left: none; }
        .alert-custom {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            font-size: 0.875rem;
        }
        .hint-box {
            background: var(--cream);
            border-radius: 10px;
            font-size: 0.78rem;
            color: #666;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-logo">Thrift<span>In</span></div>
        <div class="tagline">Titip, Jual, Cuan — No Ribet ✨</div>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-custom p-3 mb-3 d-flex align-items-center gap-2">
        <i class="fa fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label fw-semibold small">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user fa-sm"></i></span>
                <input type="text" name="username" class="form-control"
                       placeholder="Masukkan username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold small">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock fa-sm"></i></span>
                <input type="password" name="password" id="inputPassword"
                       class="form-control" placeholder="Masukkan password" required>
                <button class="btn btn-outline-secondary border-start-0" type="button"
                        onclick="togglePass()" tabindex="-1">
                    <i class="fa fa-eye fa-sm" id="eyeIcon"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn btn-login w-100">
            <i class="fa fa-sign-in-alt me-2"></i>Masuk
        </button>
    </form>

    <div class="hint-box p-3 mt-4 text-center">
        <i class="fa fa-info-circle me-1"></i>
        Default login: <b>admin</b> / <b>password</b>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePass() {
    const input = document.getElementById('inputPassword');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
