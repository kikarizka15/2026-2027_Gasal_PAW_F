<?php
include 'koneksi.php';

$notifikasi = "";

// soal 3 (fitur hapus n ngecek data)
if (isset($_GET['hapus_id'])) {
    $barang_id = intval($_GET['hapus_id']);

    $q_cek = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM transaksi_detail WHERE barang_id = '$barang_id'");
    $d_cek = mysqli_fetch_assoc($q_cek);

    if ($d_cek['total'] > 0) {
        $notifikasi = "<script>alert('Gagal menghapus! Barang ini masih digunakan pada data transaksi detail.');</script>";
    } else {
        $q_del = mysqli_query($koneksi, "DELETE FROM barang WHERE id = '$barang_id'");
        if ($q_del) {
            $notifikasi = "<script>alert('Data barang berhasil dihapus.'); window.location.href='data_barang.php';</script>";
        } else {
            $notifikasi = "<script>alert('Gagal menghapus data barang.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #17a2b8; color: white; }
        .btn-hapus { background-color: #dc3545; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

    <h2>Daftar Master Barang</h2>
    <?= $notifikasi; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q_barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id ASC");
            while ($b = mysqli_fetch_assoc($q_barang)) {
            ?>
            <tr>
                <td><?= $b['id']; ?></td>
                <td><?= htmlspecialchars($b['kode_barang']); ?></td>
                <td><?= htmlspecialchars($b['nama_barang']); ?></td>
                <td>Rp <?= number_format($b['harga'], 0, ',', '.'); ?></td>
                <td><?= $b['stok']; ?></td>
                <td>
                    <a href="data_barang.php?hapus_id=<?= $b['id']; ?>" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>