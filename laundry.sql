-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2022 at 08:02 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `qty` double NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_detail_transaksi`
--

INSERT INTO `tb_detail_transaksi` (`id`, `id_transaksi`, `id_paket`, `qty`, `keterangan`) VALUES
(13, 10, 1, 3, ' '),
(14, 10, 12, 1, ' '),
(27, 19, 3, 2, ''),
(28, 19, 13, 1, ' '),
(36, 37, 1, 2, ' '),
(37, 37, 12, 2, ' '),
(38, 37, 13, 1, ' '),
(39, 38, 1, 9, ' '),
(40, 38, 3, 9, ''),
(41, 38, 4, 9, ' '),
(42, 38, 12, 9, ' '),
(43, 38, 13, 9, ' '),
(44, 39, 1, 12, ' '),
(45, 40, 1, 5, ' '),
(46, 40, 4, 5, ' '),
(51, 43, 1, 3, ' zz'),
(52, 43, 3, 3, ''),
(70, 50, 4, 2, ' qqq'),
(72, 51, 1, 2, ' ww'),
(73, 51, 4, 2, ' eee'),
(132, 72, 1, 3, ' kaos 3 kg'),
(133, 72, 3, 3, ' bed cover 3 kg'),
(134, 72, 12, 7, ' kiloan 7 kg'),
(135, 72, 13, 3, ' boneka 3 kg'),
(139, 73, 3, 3, ' '),
(140, 73, 12, 4, ' '),
(144, 74, 1, 3, ' '),
(145, 74, 3, 3, ' '),
(146, 74, 4, 3, ' '),
(147, 74, 12, 3, ' '),
(148, 74, 13, 3, ' '),
(149, 75, 1, 2, ' '),
(150, 76, 13, 5, ' ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_paket`
--

CREATE TABLE `tb_paket` (
  `id` int(11) UNSIGNED NOT NULL,
  `jenis` enum('kiloan','selimut','bed_cover','kaos','lain') NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_paket`
--

INSERT INTO `tb_paket` (`id`, `jenis`, `nama_paket`, `harga`) VALUES
(1, 'kaos', 'Kaos', 5000),
(3, 'bed_cover', 'Bed Cover', 8000),
(4, 'selimut', 'Selimut', 6000),
(12, 'kiloan', 'Kiloan', 5000),
(13, 'lain', 'Lainnya', 7000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_pelanggan`
--

CREATE TABLE `tb_pelanggan` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `tlp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pelanggan`
--

INSERT INTO `tb_pelanggan` (`id`, `nama`, `alamat`, `jenis_kelamin`, `tlp`) VALUES
(1, 'Gino', 'Jumantono', 'Laki-laki', '+6281234547878'),
(3, 'Tarnia', 'Jumantono', 'Perempuan', '+62812325526'),
(4, 'Sukimin', 'Kebak, Jumantono', 'Laki-laki', '+62812325526'),
(9, 'Yanti', 'Kopenan', 'Perempuan', '+6281236829883'),
(11, 'Sri', 'Jumantono', 'Perempuan', '+6281295220561');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_invoice` varchar(100) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `batas_waktu` datetime NOT NULL,
  `tgl_bayar` datetime NOT NULL,
  `diskon` double NOT NULL,
  `status` enum('baru','proses','selesai','diambil') NOT NULL,
  `dibayar` enum('dibayar','belum_dibayar') NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `kode_invoice`, `id_pelanggan`, `tgl`, `batas_waktu`, `tgl_bayar`, `diskon`, `status`, `dibayar`, `id_user`) VALUES
(10, 'INV-WCSI8L1', 3, '2022-11-02 11:02:00', '2022-11-04 11:02:00', '2022-11-02 13:33:00', 0, 'selesai', 'dibayar', 5),
(19, 'INV-5BK3W9D', 3, '2022-11-03 10:26:00', '2022-11-04 10:26:00', '2022-11-03 10:26:00', 0, 'proses', 'dibayar', 5),
(37, 'INV-40JQBV1', 3, '2022-11-03 13:59:00', '2022-11-06 13:59:00', '0000-00-00 00:00:00', 0, 'baru', 'belum_dibayar', 5),
(38, 'INV-1RU3ZSB', 1, '2022-11-04 10:51:00', '2022-11-05 10:51:00', '2022-11-04 10:51:00', 0, 'baru', 'dibayar', 5),
(39, 'INV-GVOPU8A', 1, '2022-11-07 05:19:00', '2022-11-08 05:19:00', '2022-11-07 05:19:00', 0, 'baru', 'dibayar', 5),
(40, 'INV-MIFH8DE', 3, '2022-11-07 05:22:00', '2022-11-10 05:22:00', '2022-11-07 05:22:00', 0, 'baru', 'dibayar', 5),
(43, 'INV-QKU5Y2E', 1, '2022-11-07 05:32:00', '2022-11-10 05:32:00', '0000-00-00 00:00:00', 0, 'baru', 'belum_dibayar', 5),
(50, 'INV-IR80WAO', 1, '2022-11-07 08:14:00', '2022-11-12 08:14:00', '0000-00-00 00:00:00', 0, 'baru', 'belum_dibayar', 5),
(51, 'INV-M1USHKW', 1, '2022-11-07 08:18:00', '2022-11-10 08:18:00', '0000-00-00 00:00:00', 0, 'baru', 'belum_dibayar', 5),
(72, 'INV-K4EWXY8', 9, '2022-11-08 08:42:00', '2022-11-11 08:42:00', '2022-11-08 08:42:00', 0, 'baru', 'dibayar', 5),
(73, 'INV-607ENBH', 11, '2022-11-12 14:15:00', '2022-11-13 14:15:00', '2022-11-12 14:15:00', 0, 'baru', 'dibayar', 44),
(74, 'INV-BQCMZNK', 11, '2022-11-13 20:24:00', '2022-11-13 20:24:00', '2022-11-13 20:24:00', 0, 'baru', 'dibayar', 5),
(75, 'INV-MPKIOB9', 9, '2022-11-13 21:08:00', '2022-11-14 21:08:00', '2022-11-13 21:08:00', 0, 'baru', 'dibayar', 5),
(76, 'INV-SZVIA6H', 4, '2022-11-14 10:38:00', '2022-11-15 10:38:00', '2022-11-14 10:38:00', 0, 'baru', 'dibayar', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `role` enum('admin','kasir') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `role`) VALUES
(4, 'Gilangg', 'admin', '12345', 'admin'),
(5, 'Lala', 'kasir', 'lala', 'kasir'),
(44, 'Nana', 'nana', 'nana', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `tb_paket`
--
ALTER TABLE `tb_paket`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
