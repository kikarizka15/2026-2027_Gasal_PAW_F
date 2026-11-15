<?php 
require "koneksi.php";

$tanggal_mulai = $_GET['mulai'] ?? '';
$tanggal_selesai = $_GET['selesai'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan dan Grafik</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body tyle="font-family: Arial";>
    <div class="no-print">
        <h2>Filter Laporan Penjualan</h2>
        <form method="GET">
            Tanggal Mulai: <input type="date" name="mulai" value="<?= htmlspecialchars($tanggal_mulai); ?>" required>
            Sampai: <input type="date" name="selesai" value="<?= htmlspecialchars($tanggal_selesai); ?>" required>
            <button type="submit">Tampilkan</button>
        </form>
        <br>
        <button onclick="window.print()">Cetak</button>
        <a href="excel.php?mulai=<?= urlencode($tanggal_mulai); ?>&selesai=<?= urlencode($tanggal_selesai); ?>"><button>Excel</button></a>
        <hr>
    </div>

    <?php
    $labels = [];
    $data_total = [];
    $rekap = [];
    $total_pendapatan = 0;
    $total_pelanggan = 0;

    if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
        $q = "SELECT DATE(waktu_transaksi) AS tgl, SUM(total) AS total_harian 
              FROM transaksi 
              WHERE waktu_transaksi BETWEEN '$tanggal_mulai' AND '$tanggal_selesai' 
              GROUP BY DATE(waktu_transaksi) ORDER BY tgl ASC";
        $res = mysqli_query($conn, $q);
        
        while ($r = mysqli_fetch_assoc($res)) {
            $labels[] = $r['tgl'];
            $data_total[] = $r['total_harian'];
            $rekap[] = $r;
            $total_pendapatan += $r['total_harian'];
        }

        $qp = "SELECT COUNT(DISTINCT pelanggan_id) AS jml 
               FROM transaksi 
               WHERE waktu_transaksi BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
        $resp = mysqli_query($conn, $qp);
        $rp = mysqli_fetch_assoc($resp);
        $total_pelanggan = $rp['jml'] ?? 0;
    }
    ?>

    <?php if (!empty($labels)): ?>
        <h3 align="center">Rekap Laporan Penjualan Periode <?= htmlspecialchars($tanggal_mulai); ?> s.d <?= htmlspecialchars($tanggal_selesai); ?></h3>

        <h2>Grafik Penjualan</h2>
        <div style="width: 600px; height: 300px;">
            <canvas id="myChart"></canvas>
        </div>

        <h2>Rekapitulasi</h2>
        <table border="1" width="100%" cellspacing="0" cellpadding="6">
            <tr>
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>
            <?php $no = 1; foreach ($rekap as $row): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>Rp. <?= number_format($row['total_harian'], 0, ',', '.'); ?></td>
                <td><?= $row['tgl']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <br>
        <div>
            <p><b>Jumlah Pelanggan Unik:</b> <?= $total_pelanggan; ?> Orang</p>
            <p><b>Total Pendapatan Keseluruhan:</b> Rp. <?= number_format($total_pendapatan, 0, ',', '.'); ?></p>
        </div>

        <script>
          const ctx = document.getElementById('myChart').getContext('2d');
          new Chart(ctx, {
            type: 'bar', 
            data: {
              labels: <?= json_encode($labels); ?>, 
              datasets: [{
                label: 'Total Penjualan',
                data: <?= json_encode($data_total); ?>, 
                borderWidth: 1
              }]
            },
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
        </script>
    <?php else: ?>
        <p><i>Silakan pilih rentang tanggal pada form di atas lalu klik tombol "Tampilkan" untuk melihat grafik, tabel rekapitulasi, dan ringkasan total.</i></p>
    <?php endif; ?>

</body>
</html>