<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $waktu_transaksi = $_POST['waktu_transaksi'] ?? date('Y-m-d');
    $pelanggan_id    = !empty($_POST['pelanggan_id']) ? trim($_POST['pelanggan_id']) : null;
    $keterangan      = trim($_POST['keterangan'] ?? '');
    $barang_id       = (int)($_POST['barang_id'] ?? 0);
    $qty             = (int)($_POST['qty'] ?? 0);
    $metode          = in_array($_POST['metode'] ?? 'TUNAI', ['TUNAI', 'TRANSFER', 'EDC'], true) ? $_POST['metode'] : 'TUNAI';

    if ($barang_id > 0 && $qty > 0) {
        $stmtBrg = mysqli_prepare($conn, "SELECT harga, stok FROM barang WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmtBrg, "i", $barang_id);
        mysqli_stmt_execute($stmtBrg);
        $resBrg = mysqli_stmt_get_result($stmtBrg);
        $brg = mysqli_fetch_assoc($resBrg);
        mysqli_stmt_close($stmtBrg);

        if ($brg && (int)$brg['stok'] >= $qty) {
            $harga = (int)$brg['harga'];
            $total = $harga * $qty;

            mysqli_begin_transaction($conn);
            try {
                $stmtTrx = mysqli_prepare($conn, "INSERT INTO transaksi (waktu_transaksi, keterangan, total, pelanggan_id) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmtTrx, "ssis", $waktu_transaksi, $keterangan, $total, $pelanggan_id);
                mysqli_stmt_execute($stmtTrx);
                $transaksiId = mysqli_insert_id($conn);
                mysqli_stmt_close($stmtTrx);

                $stmtDet = mysqli_prepare($conn, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmtDet, "iiii", $transaksiId, $barang_id, $harga, $qty);
                mysqli_stmt_execute($stmtDet);
                mysqli_stmt_close($stmtDet);

                $waktuBayar = $waktu_transaksi . ' ' . date('H:i:s');
                $stmtPay = mysqli_prepare($conn, "INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmtPay, "sisi", $waktuBayar, $total, $metode, $transaksiId);
                mysqli_stmt_execute($stmtPay);
                mysqli_stmt_close($stmtPay);

                $stmtStok = mysqli_prepare($conn, "UPDATE barang SET stok = stok - ? WHERE id = ?");
                mysqli_stmt_bind_param($stmtStok, "ii", $qty, $barang_id);
                mysqli_stmt_execute($stmtStok);
                mysqli_stmt_close($stmtStok);

                mysqli_commit($conn);
                $pesan = "Transaksi #" . $transaksiId . " berhasil disimpan.";
            } catch (Throwable $e) {
                mysqli_rollback($conn);
                $pesan = "Gagal menyimpan transaksi: " . $e->getMessage();
            }
        } else {
            $pesan = "Stok barang tidak mencukupi.";
        }
    }
}

$pelangganRes = mysqli_query($conn, "SELECT id, nama FROM pelanggan ORDER BY nama ASC");
$pelangganList = [];
while ($p = mysqli_fetch_assoc($pelangganRes)) {
    $pelangganList[] = $p;
}

$barangRes = mysqli_query($conn, "SELECT id, kode_barang, nama_barang, harga, stok FROM barang ORDER BY id ASC");
$barangList = [];
while ($b = mysqli_fetch_assoc($barangRes)) {
    $barangList[] = $b;
}

$trxRows = mysqli_query($conn, "
    SELECT t.id, t.waktu_transaksi, t.keterangan, t.total, p.nama AS nama_pelanggan, pb.metode
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
    LEFT JOIN pembayaran pb ON pb.transaksi_id = t.id
    ORDER BY t.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi - Sistem Penjualan</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f6f9;
            color: #1e293b;
        }
        .container {
            max-width: 1120px;
            margin: 24px auto;
            padding: 0 20px;
        }
        .panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .panel h3 {
            margin-top: 0;
            margin-bottom: 14px;
            font-size: 16px;
            color: #00386b;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }
        input[type="text"], input[type="number"], input[type="password"], select, textarea {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: inherit;
            font-size: 13.5px;
        }
        .btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-primary {
            background: #004c91;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #00386b;
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #1e293b;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 16px;
            font-size: 13.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 9px 12px;
            text-align: left;
        }
        th {
            background: #00386b;
            color: #ffffff;
            font-weight: 600;
        }
        tr:nth-child(even) td {
            background: #f8fafc;
        }
        .action-link {
            color: #004c91;
            text-decoration: none;
            font-weight: 600;
        }
        .action-link.danger {
            color: #dc2626;
        }
</style>
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="container">
        <h2 style="margin-top:0;">Transaksi Penjualan</h2>
        <?php if ($pesan !== ""): ?>
            <div class="alert-success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div class="panel">
            <h3>Input Transaksi Baru</h3>
            <form method="POST" action="">
                <div class="form-grid">
                    <div>
                        <label>Tanggal Transaksi</label>
                        <input type="text" name="waktu_transaksi" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div>
                        <label>Pelanggan</label>
                        <select name="pelanggan_id">
                            <option value="">-- Umum / Tanpa Pelanggan --</option>
                            <?php foreach ($pelangganList as $plg): ?>
                                <option value="<?= htmlspecialchars($plg['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($plg['id'] . ' - ' . $plg['nama'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Barang</label>
                        <select name="barang_id" required>
                            <?php foreach ($barangList as $brg): ?>
                                <option value="<?= (int)$brg['id'] ?>">
                                    <?= htmlspecialchars($brg['kode_barang'] . ' - ' . $brg['nama_barang'] . ' (Stok: ' . $brg['stok'] . ')', ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label>Qty</label>
                        <input type="number" name="qty" min="1" value="1" required>
                    </div>
                    <div>
                        <label>Metode Pembayaran</label>
                        <select name="metode">
                            <option value="TUNAI">TUNAI</option>
                            <option value="TRANSFER">TRANSFER</option>
                            <option value="EDC">EDC</option>
                        </select>
                    </div>
                    <div>
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: Pembelian ATK">
                    </div>
                </div>
                <div style="margin-top:14px;">
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>

        <div class="panel">
            <h3>Daftar Transaksi</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Keterangan</th>
                        <th>Metode</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($trxRows)): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars($row['waktu_transaksi'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['keterangan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['metode'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>Rp <?= number_format((int)$row['total'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
