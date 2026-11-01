<?php
include 'koneksi.php';

$id = $_GET['id'];
$query_get = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id = '$id'");
$data = mysqli_fetch_assoc($query_get);

if (isset($_POST['update'])) {
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query_update = "UPDATE supplier SET nama='$nama', telp='$telp', alamat='$alamat' WHERE id='$id'";
    if (mysqli_query($koneksi, $query_update)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal memperbarui data!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-group { margin-bottom: 15px; display: flex; align-items: center; }
        .form-group label { width: 100px; font-weight: bold; }
        .form-group input { width: 300px; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-update { background-color: #5cb85c; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-batal { background-color: #d9534f; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-left: 10px; }
    </style>
</head>
<body>

    <h2>Edit Data Master Supplier</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="telp" value="<?= htmlspecialchars($data['telp']); ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']); ?>" required>
        </div>
        <div class="form-group">
            <div style="margin-left: 100px;">
                <button type="submit" name="update" class="btn-update">Update</button>
                <a href="index.php" class="btn-batal">Batal</a>
            </div>
        </div>
    </form>

</body>
</html>