<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$level = (int)($_SESSION['level'] ?? 0);
$namaUser = htmlspecialchars($_SESSION['nama'] ?? 'User', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistem Penjualan</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f6f9;
            color: #1e293b;
        }
        .container {
            max-width: 1100px;
            margin: 24px auto;
            padding: 0 20px;
        }
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <div class="container">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'akses_ditolak'): ?>
            <div class="alert-danger">
                Akses ditolak! Menu Data Master hanya dapat diakses oleh user level 1.
            </div>
        <?php endif; ?>

        <div class="card">
            <h2 style="margin-top:0;">Selamat Datang, <?= $namaUser ?>!</h2>
            <p style="margin-bottom:0;">
                Anda masuk sebagai <strong>Level <?= $level ?></strong>
                (<?= $level === 1 ? 'Owner / Admin — Akses penuh termasuk Data Master, Transaksi, dan Laporan' : 'Kasir — Akses Transaksi dan Laporan' ?>).
            </p>
        </div>
    </div>
</body>
</html>
