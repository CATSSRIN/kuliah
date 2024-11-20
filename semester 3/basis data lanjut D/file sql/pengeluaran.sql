-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 03:00 AM
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
-- Database: `hisana`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `ID_Pengeluaran` int(11) NOT NULL,
  `Tanggal_Pengeluaran` date DEFAULT NULL,
  `Deskripsi_Pengeluaran` varchar(255) DEFAULT NULL,
  `Jumlah_Pengeluaran` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`ID_Pengeluaran`, `Tanggal_Pengeluaran`, `Deskripsi_Pengeluaran`, `Jumlah_Pengeluaran`) VALUES
(2301, '2024-08-19', 'Pembelian ayam segar', 3000000.00),
(2302, '2024-08-20', 'Pembelian bumbu dapur (garam, bawang, cabai)', 500000.00),
(2303, '2024-08-21', 'Tagihan listrik bulanan', 2500000.00),
(2304, '2024-08-22', 'Gaji koki', 4500000.00),
(2305, '2024-08-23', 'Gaji pelayan', 3000000.00),
(2306, '2024-08-24', 'Pembelian minyak goreng', 1200000.00),
(2307, '2024-08-25', 'Penggantian pisau dapur', 300000.00),
(2308, '2024-08-26', 'Perbaikan instalasi listrik', 750000.00),
(2309, '2024-08-27', 'Pembelian sabun dan alat kebersihan', 200000.00),
(2310, '2024-08-28', 'Iklan di media sosial', 1000000.00),
(2311, '2024-08-29', 'Biaya pengiriman bahan baku', 500000.00),
(2312, '2024-08-30', 'Pembayaran sewa bulanan', 8000000.00),
(2313, '2024-08-31', 'Pembelian sayuran segar', 400000.00),
(2314, '2024-09-01', 'Pengecatan ulang dinding restoran', 1500000.00),
(2315, '2024-09-02', 'Gaji kasir', 2500000.00),
(2316, '2024-09-03', 'Pembelian tepung dan bahan pelapis ayam', 800000.00),
(2317, '2024-09-04', 'Pengisian ulang tabung gas', 600000.00),
(2318, '2024-09-05', 'Pembelian saus dan kecap', 350000.00),
(2319, '2024-09-06', 'Pembelian nota dan alat tulis', 150000.00),
(2320, '2024-09-07', 'Gaji security', 2000000.00),
(2321, '2024-09-08', 'Pembelian kentang untuk kentang goreng', 700000.00),
(2322, '2024-09-09', 'Pembayaran sewa tempat parkir', 1500000.00),
(2323, '2024-09-10', 'Cetak brosur dan flyer', 300000.00),
(2324, '2024-09-11', 'Pembelian minuman ringan', 500000.00),
(2325, '2024-09-12', 'Perbaikan oven', 1200000.00),
(2326, '2024-09-13', 'Pengeluaran untuk jasa kebersihan', 600000.00),
(2327, '2024-09-14', 'Pembelian ayam beku', 2800000.00),
(2328, '2024-09-15', 'Bonus karyawan harian', 1000000.00),
(2329, '2024-09-16', 'Biaya parkir pengiriman bahan baku', 50000.00),
(2330, '2024-09-17', 'Pembelian garam dan rempah lainnya', 200000.00),
(2331, '2024-09-18', 'Pembelian talenan baru', 250000.00),
(2332, '2024-09-19', 'Perbaikan genteng bocor', 900000.00),
(2333, '2024-09-20', 'Lembur karyawan dapur', 1500000.00),
(2334, '2024-09-21', 'Pembelian keju untuk menu baru', 300000.00),
(2335, '2024-09-22', 'Pembelian telur untuk adonan', 250000.00),
(2336, '2024-09-23', 'Pengisian ulang tabung gas', 600000.00),
(2337, '2024-09-24', 'Tagihan listrik bulanan', 2500000.00),
(2338, '2024-09-25', 'Pembelian kain pel', 100000.00),
(2339, '2024-09-26', 'Biaya iklan digital', 2000000.00),
(2340, '2024-09-27', 'Pembelian ayam segar', 3200000.00),
(2341, '2024-09-28', 'Pembelian bumbu dan rempah', 600000.00),
(2342, '2024-09-29', 'Biaya pengiriman ayam segar', 400000.00),
(2343, '2024-09-30', 'Penggantian wajan rusak', 500000.00),
(2344, '2024-10-01', 'Gaji bagian kebersihan', 2000000.00),
(2345, '2024-10-02', 'Pembelian bahan pelengkap salad', 350000.00),
(2346, '2024-10-03', 'Pembayaran sewa tempat bulanan', 8000000.00),
(2347, '2024-10-04', 'Pembelian air minum galon', 200000.00),
(2348, '2024-10-05', 'Gaji supervisor', 5000000.00),
(2349, '2024-10-06', 'Pembelian kompor baru', 2000000.00),
(2350, '2024-10-07', 'Biaya transportasi ke supplier', 300000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`ID_Pengeluaran`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
