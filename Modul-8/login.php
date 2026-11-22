<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth.php';

redirectIfLoggedIn();

$error = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'belum_login') {
        $error = "Silakan login terlebih dahulu untuk mengakses sistem.";
    } elseif ($_GET['error'] === 'akses_ditolak') {
        $error = "Anda tidak memiliki hak akses ke halaman tersebut.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Username dan password wajib diisi.";
    } else {
        $passwordMd5 = md5($password);

        $stmt = mysqli_prepare($conn, "SELECT id_user, username, nama, level FROM user WHERE username = ? AND password = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "ss", $username, $passwordMd5);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['login']    = true;
            $_SESSION['id_user']  = (int)$user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama']     = $user['nama'];
            $_SESSION['level']    = (int)$user['level'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password tidak valid.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penjualan</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #eef2f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #ffffff;
            width: 360px;
            border-radius: 6px;
            box-shadow: 0 4px 16px rgba(0, 56, 107, 0.12);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(180deg, #00529b 0%, #00386b 100%);
            color: #ffffff;
            padding: 16px 20px;
            font-weight: 700;
            font-size: 16px;
        }
        .login-body {
            padding: 22px;
        }
        .form-group {
            margin-bottom: 14px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13.5px;
            color: #334155;
            font-weight: 600;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 9px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #004c91;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        button:hover {
            background: #00386b;
        }
        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 14px;
            font-size: 13px;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">Login - Sistem Penjualan</div>
    <div class="login-body">
        <?php if ($error !== ""): ?>
            <div class="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Masuk</button>
        </form>
    </div>
</div>
</body>
</html>
