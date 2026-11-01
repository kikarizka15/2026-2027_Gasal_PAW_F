<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO supplier (nama, telp, alamat) VALUES ('$nama', '$telp', '$alamat')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal menambahkan data!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Master Supplier Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .form-group { margin-bottom: 15px; display: flex; align-items: center; }
        .form-group label { width: 100px; font-weight: bold; }
        .form-group input { width: 300px; padding: 6px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-simpan { background-color: #5cb85c; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-batal { background-color: #d9534f; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-left: 10px; }
    </style>
</head>
<body>

    <h2>Tambah Data Master Supplier Baru</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Nama" required>
        </div>
        <div class="form-group">
            <label>Telp</label>
            <input type="text" name="telp" placeholder="telp" required>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" placeholder="alamat" required>
        </div>
        <div class="form-group">
            <div style="margin-left: 100px;">
                <button type="submit" name="simpan" class="btn-simpan">Simpan</button>
                <a href="index.php" class="btn-batal">Batal</a>
            </div>
        </div>
    </form>

</body>
</html>