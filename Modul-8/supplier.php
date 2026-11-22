<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth.php';
requireOwner();

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $nama   = trim($_POST['nama'] ?? '');
    $telp   = trim($_POST['telp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE supplier SET nama=?, telp=?, alamat=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssi", $nama, $telp, $alamat, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $pesan = "Data supplier berhasil diperbarui.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO supplier (nama, telp, alamat) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nama, $telp, $alamat);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $pesan = "Data supplier berhasil ditambahkan.";
    }
}

if (isset($_GET['hapus'])) {
    $hapusId = (int)$_GET['hapus'];
    $stmt = mysqli_prepare($conn, "DELETE FROM supplier WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $hapusId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $pesan = "Data supplier berhasil dihapus.";
}

$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM supplier WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $editId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $editData = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
}

$rows = mysqli_query($conn, "SELECT * FROM supplier ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Supplier - Sistem Penjualan</title>
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
        <h2 style="margin-top:0;">Data Supplier</h2>
        <?php if ($pesan !== ""): ?>
            <div class="alert-success"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div class="panel">
            <h3><?= $editData ? 'Edit Supplier' : 'Tambah Supplier' ?></h3>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?= (int)($editData['id'] ?? 0) ?>">
                <div class="form-grid">
                    <div>
                        <label>Nama Supplier</label>
                        <input type="text" name="nama" maxlength="100" value="<?= htmlspecialchars($editData['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>
                    <div>
                        <label>No. Telp</label>
                        <input type="text" name="telp" maxlength="12" value="<?= htmlspecialchars($editData['telp'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div>
                        <label>Alamat</label>
                        <input type="text" name="alamat" value="<?= htmlspecialchars($editData['alamat'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
                <div style="margin-top:14px;">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <?php if ($editData): ?>
                        <a href="supplier.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="panel">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($rows)): ?>
                    <tr>
                        <td><?= (int)$row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['telp'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['alamat'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="supplier.php?edit=<?= (int)$row['id'] ?>" class="action-link">Edit</a> |
                            <a href="supplier.php?hapus=<?= (int)$row['id'] ?>" class="action-link danger" onclick="return confirm('Hapus supplier ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
