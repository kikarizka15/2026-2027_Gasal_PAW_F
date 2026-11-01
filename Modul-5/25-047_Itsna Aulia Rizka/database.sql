-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2026 at 11:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int NOT NULL,
  `kode_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL,
  `supplier_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Pensil 2B', 3000, 100, 1),
(2, 'BRG002', 'Buku Tulis A5', 5000, 150, 1),
(3, 'BRG003', 'Penggaris 30cm', 4000, 80, 2),
(4, 'BRG004', 'Penghapus', 2000, 200, 2),
(5, 'BRG005', 'Spidol Boardmarker', 8000, 60, 3),
(6, 'BRG006', 'Kertas HVS A4', 45000, 50, 4),
(7, 'BRG007', 'Map Snelhecter', 6000, 120, 5),
(8, 'BRG008', 'Stapler Small', 15000, 40, 6),
(9, 'BRG009', 'Isi Stapler', 3000, 90, 6),
(10, 'BRG010', 'Tipe-X Liquid', 7000, 110, 3);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
('PLG001', 'Budi Santoso', 'L', '081234567890', 'Surabaya'),
('PLG002', 'Siti Aminah', 'P', '081345678901', 'Bangkalan'),
('PLG003', 'Ahmad Dahlan', 'L', '081456789012', 'Malang'),
('PLG004', 'Dewi Sartika', 'P', '081567890123', 'Sidoarjo'),
('PLG005', 'Eko Prasetyo', 'L', '081678901234', 'Gresik'),
('PLG006', 'Fitriani', 'P', '081789012345', 'Jakarta'),
('PLG007', 'Giri Susilo', 'L', '081890123456', 'Bandung'),
('PLG008', 'Hana Pertiwi', 'P', '081901234567', 'Semarang'),
('PLG009', 'Irfan Bachdim', 'L', '082012345678', 'Kediri'),
('PLG010', 'Joko Widodo', 'L', '082123456789', 'Solo');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int NOT NULL,
  `waktu_bayar` datetime NOT NULL,
  `total` int NOT NULL,
  `metode` enum('TUNAI','TRANSFER','EDC') NOT NULL,
  `transaksi_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2026-05-01 10:15:00', 35000, 'TUNAI', 1),
(2, '2026-05-02 11:30:00', 24000, 'TRANSFER', 2),
(3, '2026-05-03 14:20:00', 90000, 'EDC', 3),
(4, '2026-05-04 09:10:00', 15000, 'TUNAI', 4),
(5, '2026-05-05 16:45:00', 32000, 'TRANSFER', 5),
(6, '2026-05-06 13:00:00', 19000, 'TUNAI', 6),
(7, '2026-05-07 15:10:00', 36000, 'EDC', 7),
(8, '2026-05-08 10:00:00', 42000, 'TRANSFER', 8),
(9, '2026-05-09 11:25:00', 16000, 'TUNAI', 9),
(10, '2026-05-10 17:05:00', 150000, 'EDC', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telp` varchar(12) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT. Maju Bersama', '08123456', 'Surabaya'),
(2, 'PT. Senang Sekali', '081515563', 'Bangkalan'),
(3, 'PT. Segar Segar', '0845454663', 'Surabaya'),
(4, 'CV. Sumber Rejeki', '0812998877', 'Malang'),
(5, 'PT. Jaya Abadi', '0813776655', 'Sidoarjo'),
(6, 'CV. Makmur Jaya', '0852112233', 'Gresik'),
(7, 'PT. Megah Utama', '0878123412', 'Jakarta'),
(8, 'CV. Bintang Terang', '0896123456', 'Bandung'),
(9, 'PT. Anugerah Alam', '0811223344', 'Semarang'),
(10, 'CV. Sejahtera Bersama', '0822334455', 'Kediri');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `waktu_transaksi` date NOT NULL,
  `keterangan` text,
  `total` int NOT NULL,
  `pelanggan_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `waktu_transaksi`, `keterangan`, `total`, `pelanggan_id`) VALUES
(1, '2026-05-01', 'Pembelian ATK Kantor', 35000, 'PLG001'),
(2, '2026-05-02', 'Pembelian Perlengkapan Sekolah', 24000, 'PLG002'),
(3, '2026-05-03', 'Pembelian Kertas HVS', 90000, 'PLG003'),
(4, '2026-05-04', 'Pembelian Eceran', 15000, 'PLG004'),
(5, '2026-05-05', 'Pembelian Spidol & Board', 32000, 'PLG005'),
(6, '2026-05-06', 'Pembelian Buku & Pensil', 19000, 'PLG006'),
(7, '2026-05-07', 'Pembelian Map & Stapler', 36000, 'PLG007'),
(8, '2026-05-08', 'Pembelian Perlengkapan', 42000, 'PLG008'),
(9, '2026-05-09', 'Pembelian ATK', 16000, 'PLG009'),
(10, '2026-05-10', 'Pembelian Grosir ATK', 150000, 'PLG010');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `transaksi_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga` int NOT NULL,
  `qty` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`transaksi_id`, `barang_id`, `harga`, `qty`) VALUES
(1, 1, 3000, 5),
(1, 2, 5000, 4),
(2, 3, 4000, 3),
(2, 4, 2000, 6),
(3, 6, 45000, 2),
(4, 7, 6000, 1),
(4, 9, 3000, 3),
(5, 5, 8000, 4),
(6, 1, 3000, 3),
(6, 2, 5000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` tinyint NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(35) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(150) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `level` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin1', '0192023a7bbd73250516f069df18b500', 'Administrator Utama', 'Surabaya', '0812340001', 1),
(2, 'kasir1', 'de28f8f7998f23ab4194b51a6029416f', 'Rina Kasir', 'Bangkalan', '0812340002', 2),
(3, 'kasir2', 'de28f8f7998f23ab4194b51a6029416f', 'Deni Kasir', 'Surabaya', '0812340003', 2),
(4, 'manajer', '5fbb69663039903931daf8b26344b76b', 'Bambang Manajer', 'Malang', '0812340004', 1),
(5, 'stok1', '47f7fa8ae602858dc064642059932fe1', 'Agus Gudang', 'Sidoarjo', '0812340005', 3),
(6, 'kasir3', 'de28f8f7998f23ab4194b51a6029416f', 'Maya Kasir', 'Gresik', '0812340006', 2),
(7, 'stok2', '47f7fa8ae602858dc064642059932fe1', 'Rudi Gudang', 'Jakarta', '0812340007', 3),
(8, 'admin2', '0192023a7bbd73250516f069df18b500', 'Sari Admin', 'Bandung', '0812340008', 1),
(9, 'kasir4', 'de28f8f7998f23ab4194b51a6029416f', 'Tini Kasir', 'Semarang', '0812340009', 2),
(10, 'supervisor', 'f35364bc808b079853de5a1e343e7159', 'Hadi Supervisor', 'Kediri', '0812340010', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`transaksi_id`,`barang_id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` tinyint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
