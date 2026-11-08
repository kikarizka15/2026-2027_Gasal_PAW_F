<?php
include 'koneksi.php';

$transaksi_id = isset($_GET['transaksi_id']) ? intval($_GET['transaksi_id']) : 1;

$pesan = "";

// nomor 4
if (isset($_POST['tambah_detail'])) {
    $barang_id = intval($_POST['barang_id']);
    $qty       = intval($_POST['qty']);

    // ngambil harga barang
    $q_harga = mysqli_query($koneksi, "SELECT harga FROM barang WHERE id = '$barang_id'");
    $d_harga = mysqli_fetch_assoc($q_harga);
    $harga   = $d_harga['harga'];

    $insert = mysqli_query($koneksi, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES ('$transaksi_id', '$barang_id', '$harga', '$qty')");

    if ($insert) {
        // menghitung ulang
        $q_sum = mysqli_query($koneksi, "SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = '$transaksi_id'");
        $d_sum = mysqli_fetch_assoc($q_sum);
        $total_baru = $d_sum['total_baru'] ?? 0;

        // update
        mysqli_query($koneksi, "UPDATE transaksi SET total = '$total_baru' WHERE id = '$transaksi_id'");
        
        $pesan = "<p style='color:green;'>Detail barang berhasil ditambahkan dan total transaksi telah diperbarui!</p>";
    } else {
        $pesan = "<p style='color:red;'>Gagal menambahkan detail barang!</p>";
    }
}

// data master transaksi
$q_master = mysqli_query($koneksi, "SELECT t.*, p.nama AS nama_pelanggan FROM transaksi t LEFT JOIN pelanggan p ON t.pelanggan_id = p.id WHERE t.id = '$transaksi_id'");
$master   = mysqli_fetch_assoc($q_master);

// soal 2 untuk dropdown
$q_existing = mysqli_query($koneksi, "SELECT barang_id FROM transaksi_detail WHERE transaksi_id = '$transaksi_id'");
$existing_items = [];
while ($row = mysqli_fetch_assoc($q_existing)) {
    $existing_items[] = $row['barang_id'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Master Detail Transaksi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        .card { background: white; padding: 20px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .form-inline { display: flex; gap: 10px; align-items: center; }
        select, input, button { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #28a745; color: white; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Kelola Transaksi (Master-Detail)</h2>
    <?= $pesan; ?>

    <div class="card">
        <form method="GET" action="">
            <label><b>Pilih No. Transaksi:</b> </label>
            <select name="transaksi_id" onchange="this.form.submit()">
                <?php
                $q_all_t = mysqli_query($koneksi, "SELECT id, waktu_transaksi FROM transaksi");
                while ($t = mysqli_fetch_assoc($q_all_t)) {
                    $selected = ($t['id'] == $transaksi_id) ? 'selected' : '';
                    echo "<option value='{$t['id']}' $selected>Transaksi #{$t['id']} - {$t['waktu_transaksi']}</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <!-- form -->
    <div class="card">
        <h3>Master Transaksi #<?= $master['id']; ?></h3>
        <p><b>Tanggal:</b> <?= $master['waktu_transaksi']; ?></p>
        <p><b>Pelanggan:</b> <?= htmlspecialchars($master['nama_pelanggan'] ?? '-'); ?></p>
        <p><b>Keterangan:</b> <?= htmlspecialchars($master['keterangan']); ?></p>
        <p><b>Total Transaksi:</b> <span style="font-size: 1.2em; color: #d9534f; font-weight: bold;">Rp <?= number_format($master['total'], 0, ',', '.'); ?></span></p>
    </div>

    <!-- soal 2 -->
    <div class="card">
        <h3>Tambah Item Barang (Detail)</h3>
        <form method="POST" action="" class="form-inline">
            <select name="barang_id" required>
                <option value="">-- Pilih Barang --</option>
                <?php
                $q_barang = mysqli_query($koneksi, "SELECT * FROM barang");
                while ($b = mysqli_fetch_assoc($q_barang)) {
                    $is_used = in_array($b['id'], $existing_items);
                    $disabled = $is_used ? 'disabled' : '';
                    $label_suffix = $is_used ? ' (Sudah Ditambahkan)' : ' - Rp ' . number_format($b['harga'], 0, ',', '.');
                    echo "<option value='{$b['id']}' $disabled>{$b['nama_barang']} {$label_suffix}</option>";
                }
                ?>
            </select>
            <input type="number" name="qty" placeholder="Jumlah Qty" min="1" required>
            <button type="submit" name="tambah_detail">Tambah ke Detail</button>
        </form>
    </div>

    <!-- tabel detail transaksi -->
    <div class="card">
        <h3>Rincian Barang dalam Transaksi</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q_detail = mysqli_query($koneksi, "SELECT td.*, b.nama_barang FROM transaksi_detail td JOIN barang b ON td.barang_id = b.id WHERE td.transaksi_id = '$transaksi_id'");
                if (mysqli_num_rows($q_detail) > 0) {
                    while ($d = mysqli_fetch_assoc($q_detail)) {
                        $subtotal = $d['harga'] * $d['qty'];
                        echo "<tr>
                            <td>{$no}</td>
                            <td>" . htmlspecialchars($d['nama_barang']) . "</td>
                            <td>Rp " . number_format($d['harga'], 0, ',', '.') . "</td>
                            <td>{$d['qty']}</td>
                            <td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Belum ada item detail pada transaksi ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>