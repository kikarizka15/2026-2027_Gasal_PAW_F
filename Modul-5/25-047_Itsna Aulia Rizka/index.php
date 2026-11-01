<?php
include 'koneksi.php';

// Proses Hapus Data jika ada parameter id_hapus
if (isset($_GET['id_hapus'])) {
    $id = $_GET['id_hapus'];
    $query_hapus = "DELETE FROM supplier WHERE id = '$id'";
    if (mysqli_query($koneksi, $query_hapus)) {
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus data!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Master Supplier</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .header-container { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .btn-tambah { background-color: #5cb85c; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #e0f2f1; text-align: left; padding: 10px; border: 1px solid #ddd; }
        td { padding: 10px; border: 1px solid #ddd; }
        .btn-edit { background-color: #d9534f; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; margin-right: 5px; }
        .btn-hapus { background-color: #8b0000; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
    <script>
        function konfirmasiHapus(id) {
            if (confirm("Anda yakin akan menghapus supplier ini?")) {
                window.location.href = "index.php?id_hapus=" + id;
            }
        }
    </script>
</head>
<body>

    <div class="header-container">
        <h2>Data Master Supplier</h2>
        <a href="tambah.php" class="btn-tambah">Tambah Data</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Telp</th>
                <th>Alamat</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY id ASC");
            while ($data = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($data['nama']); ?></td>
                <td><?= htmlspecialchars($data['telp']); ?></td>
                <td><?= htmlspecialchars($data['alamat']); ?></td>
                <td>
                    <a href="edit.php?id=<?= $data['id']; ?>" class="btn-edit">Edit</a>
                    <button onclick="konfirmasiHapus(<?= $data['id']; ?>)" class="btn-hapus">Hapus</button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>