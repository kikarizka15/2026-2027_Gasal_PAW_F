<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth.php';
requireOwner();

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $kode_barang = trim($_POST['kode_barang'] ?? '');
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $harga       = (int)($_POST['harga'] ?? 0);
    $stok        = (int)($_POST['stok'] ?? 0);
    $supplier_id = !empty($_POST['supplier_id']) ? (int)$_POST['supplier_id'] : null;

    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE barang SET kode_barang=?, nama_barang=?, harga=?, stok=?, supplier_id=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssiiii", $kode_barang, $nama_barang, $harga, $stok, $supplier_id, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $pesan = "Data barang berhasil diperbarui.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssiii", $kode_barang, $nama_barang, $harga, $stok, $supplier_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $pesan = "Data barang berhasil ditambahkan.";
    }
}

if (isset($_GET['hapus'])) {
    $hapusId = (int)$_GET['hapus'];
    $stmt = mysqli_prepare($conn, "DELETE FROM barang WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $hapusId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $pesan = "Data barang berhasil dihapus.";
}

$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM barang WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $editId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $editData = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
}

$suppliers = mysqli_query($conn, "SELECT id, nama FROM supplier ORDER BY nama ASC");
$supplierList = [];
while ($s = mysqli_fetch_assoc($suppliers)) {
    $supplierList[] = $s;
}

$barangRows = mysqli_query($conn, "
    SELECT b.*, s.nama AS nama_supplier
    FROM barang b
    LEFT JOIN supplier s ON b.supplier_id = s.id
    ORDER BY b.id ASC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Barang - Sistem Penjualan</title>
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
        <h2 style="margin-top:0;">Data Barang</h2>
        <?php if ($pesan !== ""): ?>
            <div class="alert-success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div class="panel">
            <h3><?= $editData ? 'Edit Barang' : 'Tambah Barang' ?></h3>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?= (int)($editData['id'] ?? 0) ?>">
                <div class="form-grid">
                    <div>
                        <label>Kode Barang</label>
                        <input type="text" name="kode_barang" maxlength="10" value="<?= htmlspecialchars($editData['kode_barang'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div>
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" maxlength="100" value="<?= htmlspecialchars($editData['nama_barang'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div>
                        <label>Harga</label>
                        <input type="number" name="harga" min="0" value="<?= (int)($editData['harga'] ?? 0) ?>" required>
                    </div>
                    <div>
                        <label>Stok</label>
                        <input type="number" name="stok" min="0" value="<?= (int)($editData['stok'] ?? 0) ?>" required>
                    </div>
                    <div>
                        <label>Supplier</label>
                        <select name="supplier_id">
                            <option value="">-- Pilih Supplier --</option>
                            <?php foreach ($supplierList as $sup): ?>
                                <option value="<?= (int)$sup['id'] ?>" <?= ((int)($editData['supplier_id'] ?? 0) === (int)$sup['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($sup['nama'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="margin-top:14px;">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <?php if ($editData): ?>
                        <a href="barang.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Supplier</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($barangRows)): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars($row['kode_barang'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['nama_barang'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>Rp <?= number_format((int)$row['harga'], 0, ',', '.') ?></td>
                        <td><?= (int)$row['stok'] ?></td>
                        <td><?= htmlspecialchars($row['nama_supplier'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="barang.php?edit=<?= (int)$row['id'] ?>" class="action-link">Edit</a> |
                            <a href="barang.php?hapus=<?= (int)$row['id'] ?>" class="action-link danger" onclick="return confirm('Hapus barang ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
