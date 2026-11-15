<?php
require "koneksi.php";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

$tanggal_mulai = $_GET['mulai'] ?? '';
$tanggal_selesai = $_GET['selesai'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Excel Laporan Penjualan</title>
</head>
<body>
    <h3>Rekapitulasi Laporan Penjualan</h3>
    <?php if (!empty($tanggal_mulai) && !empty($tanggal_selesai)): ?>
        <p>Periode: <?= htmlspecialchars($tanggal_mulai); ?> sampai <?= htmlspecialchars($tanggal_selesai); ?></p>
    <?php endif; ?>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Total Pendapatan</th>
            <th>Tanggal Transaksi</th>
        </tr>
        <?php
        if ($tanggal_mulai != "" && $tanggal_selesai != "") {
            $query = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian 
                      FROM transaksi 
                      WHERE waktu_transaksi BETWEEN '$tanggal_mulai' AND '$tanggal_selesai' 
                      GROUP BY DATE(waktu_transaksi) ORDER BY tgl ASC";
        } else {
            $query = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian 
                      FROM transaksi 
                      GROUP BY DATE(waktu_transaksi) ORDER BY tgl ASC";
        }
        
        $result = mysqli_query($conn, $query);
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>Rp. " . number_format($row['total_harian'], 0, ',', '.') . "</td>";
            echo "<td>" . $row['tgl'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>