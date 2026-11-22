<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$dari   = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';

$sql = "
    SELECT t.id, t.waktu_transaksi, t.keterangan, t.total,
           p.nama AS nama_pelanggan,
           COALESCE(pb.metode, '-') AS metode
    FROM transaksi t
    LEFT JOIN pelanggan p ON t.pelanggan_id = p.id
    LEFT JOIN pembayaran pb ON pb.transaksi_id = t.id
    WHERE 1=1
";

$params = [];
$types  = "";

if ($dari !== '') {
    $sql .= " AND t.waktu_transaksi >= ?";
    $params[] = $dari;
    $types .= "s";
}
if ($sampai !== '') {
    $sql .= " AND t.waktu_transaksi <= ?";
    $params[] = $sampai;
    $types .= "s";
}

$sql .= " ORDER BY t.waktu_transaksi ASC, t.id ASC";

$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$rows = [];
$grandTotal = 0;
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
    $grandTotal += (int)$r['total'];
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Sistem Penjualan</title>
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
        <h2 style="margin-top:0;">Laporan Penjualan</h2>

        <div class="panel">
            <form method="GET" action="">
                <div class="form-grid">
                    <div>
                        <label>Dari Tanggal (YYYY-MM-DD)</label>
                        <input type="text" name="dari" value="<?= htmlspecialchars($dari, ENT_QUOTES, 'UTF-8') ?>" placeholder="2026-05-01">
                    </div>
                    <div>
                        <label>Sampai Tanggal (YYYY-MM-DD)</label>
                        <input type="text" name="sampai" value="<?= htmlspecialchars($sampai, ENT_QUOTES, 'UTF-8') ?>" placeholder="2026-05-31">
                    </div>
                    <div style="display:flex;align-items:flex-end;gap:8px;">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="laporan.php" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="panel">
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
                    <?php if (empty($rows)): ?>
                        <tr><td colspan="6" style="text-align:center;">Tidak ada data transaksi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= (int)$row['id'] ?></td>
                            <td><?= htmlspecialchars($row['waktu_transaksi'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nama_pelanggan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['keterangan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['metode'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>Rp <?= number_format((int)$row['total'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="5" style="text-align:right;font-weight:700;">Total Keseluruhan</td>
                            <td style="font-weight:700;">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
