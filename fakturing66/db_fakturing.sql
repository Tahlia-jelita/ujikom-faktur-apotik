-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2026 at 01:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_fakturing`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `email_customer` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `tgl_lahir`, `no_telp`, `email_customer`, `alamat`) VALUES
(1, 'Firman Syah', '1998-04-12', '081299887766', 'Firmansyah@gmail.com', 'Jl. Tambak medokan ayu 3c No 23'),
(3, 'Siti Aminah', '1995-11-02', '081344556677', 'Sitiaminah@gmail.com', 'Jl. Sudirman No. 45'),
(4, 'Andi Wijaya', '2002-01-15', '089677889900', 'Andi@gmail.com', 'Jl. Gatot Subroto'),
(5, 'Dewi Lestari', '2004-05-20', '082155443322', 'Dewi@gmail.com', 'Jl. Cipondoh Raya'),
(6, 'Haiqal Aqmal Suryanto', '2000-07-17', '0897654667', 'haiqalaqmal.s@gmail.com', 'Jl. Poris Indah');

-- --------------------------------------------------------

--
-- Table structure for table `detail_faktur`
--

CREATE TABLE `detail_faktur` (
  `id_produk` int(11) NOT NULL,
  `no_faktur` varchar(50) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_faktur`
--

INSERT INTO `detail_faktur` (`id_produk`, `no_faktur`, `qty`, `price`) VALUES
(1, 'FKT-2004-641', 1, 1500.00),
(1, 'FKT-2026-001', 2, 1500.00),
(2, 'FKT-2004-378', 3, 2500.00),
(2, 'FKT-2026-001', 3, 2500.00),
(3, 'FKT-2026-001', 5, 12000.00),
(4, 'FKT-2004-927', 4, 4500.00),
(5, 'FKT-2004-614', 7, 3000.00),
(5, 'FKT-2026-002', 5, 3000.00);

-- --------------------------------------------------------

--
-- Table structure for table `faktur`
--

CREATE TABLE `faktur` (
  `no_fatur` varchar(50) NOT NULL,
  `tgl_faktur` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `metode_bayar` varchar(50) DEFAULT NULL,
  `resep_dokter` varchar(50) DEFAULT NULL,
  `ppn` decimal(10,2) DEFAULT NULL,
  `dp` decimal(10,2) DEFAULT NULL,
  `grand_total` decimal(10,2) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `id_perusahaan` int(11) DEFAULT NULL,
  `id_karyawan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faktur`
--

INSERT INTO `faktur` (`no_fatur`, `tgl_faktur`, `due_date`, `metode_bayar`, `resep_dokter`, `ppn`, `dp`, `grand_total`, `user`, `id_customer`, `id_perusahaan`, `id_karyawan`) VALUES
('FKT-2004-378', '2026-05-21', '2026-05-21', 'TRANSFER', NULL, 750.00, 0.00, 8250.00, 'Alif', 6, 1, NULL),
('FKT-2004-614', '2026-05-21', '2026-05-21', 'TUNAI', 'Ada Resep Dokter', 2100.00, 0.00, 23100.00, 'Alif', 5, 1, 1),
('FKT-2004-641', '2026-05-21', '2026-05-28', 'TUNAI', NULL, 150.00, 0.00, 1650.00, 'Alif', 1, 1, NULL),
('FKT-2004-927', '2026-05-21', '2026-05-28', 'TUNAI', NULL, 1800.00, 0.00, 19800.00, 'Alif', 4, 1, NULL),
('FKT-2026-001', '2026-05-21', '2026-06-21', 'TUNAI', NULL, 6950.00, 0.00, 76450.00, 'Alif', 1, 1, NULL),
('FKT-2026-002', '2026-05-21', '2026-05-28', 'TRANSFER', NULL, 0.00, 0.00, 15000.00, 'Alif', 2, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int(11) NOT NULL,
  `nama_karyawan` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `no_lisensi` varchar(50) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `nama_karyawan`, `jabatan`, `no_lisensi`, `no_telp`) VALUES
(1, 'apt. Alif Rahman, S.Farm.', 'Apoteker Pengelola', 'SIPA/1994/2026/0024', '08123456789'),
(2, 'Siti Sarah', 'Asisten Apoteker', 'SIAA/2004/2026/0112', '08571234567'),
(3, 'Nixon Clementius', 'Staff Kasir', 'SIPA/1994/2026/0024', '08123456789');

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id_perusahaan` int(11) NOT NULL,
  `nama_perusahaan` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_siup` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id_perusahaan`, `nama_perusahaan`, `email`, `no_siup`, `alamat`, `no_telp`, `fax`) VALUES
(1, 'APOTEK LUXURY ROYAL MEDIKA', 'management@royalmedika.com', 'NIB: 9120202451951 / SIUP: 503/00646/DPM-PTSP/XI/2', 'Jl. Ratu Agung No. 12A, RT. 09 / RW. 03, Cipondoh, Tangerang', '021-5551951', '021-5551952');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `price`, `jenis`, `stock`) VALUES
(2, 'VITAMIN C 50 MG KF 10 TABLET (500mg)', 2500.00, 'Vitamin', 147),
(3, 'Paracetamol Ekstra 20mg', 12000.00, 'Obat', 80),
(4, 'Amoxicillin 500mg', 4500.00, 'Antibiotik', 56),
(5, 'Sanaflu', 3000.00, 'Obat', 193),
(6, 'Paramex', 5000.00, 'Obat', 50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `detail_faktur`
--
ALTER TABLE `detail_faktur`
  ADD PRIMARY KEY (`id_produk`,`no_faktur`),
  ADD KEY `no_faktur` (`no_faktur`);

--
-- Indexes for table `faktur`
--
ALTER TABLE `faktur`
  ADD PRIMARY KEY (`no_fatur`),
  ADD KEY `id_customer` (`id_customer`),
  ADD KEY `id_perusahaan` (`id_perusahaan`),
  ADD KEY `fk_faktur_karyawan` (`id_karyawan`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id_perusahaan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id_perusahaan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_faktur`
--
ALTER TABLE `detail_faktur`
  ADD CONSTRAINT `detail_faktur_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `detail_faktur_ibfk_2` FOREIGN KEY (`no_faktur`) REFERENCES `faktur` (`no_fatur`);

--
-- Constraints for table `faktur`
--
ALTER TABLE `faktur`
  ADD CONSTRAINT `faktur_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`),
  ADD CONSTRAINT `faktur_ibfk_2` FOREIGN KEY (`id_perusahaan`) REFERENCES `perusahaan` (`id_perusahaan`),
  ADD CONSTRAINT `fk_faktur_karyawan` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
