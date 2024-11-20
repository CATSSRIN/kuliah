-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 02:59 AM
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
-- Table structure for table `cabang_toko`
--

CREATE TABLE `cabang_toko` (
  `ID_Cabang` int(11) NOT NULL,
  `Nama_Cabang` varchar(100) DEFAULT NULL,
  `Jalan_Cabang_Toko` varchar(255) DEFAULT NULL,
  `Kota_Cabang_Toko` varchar(100) DEFAULT NULL,
  `Tanggal_Pembukaan` date DEFAULT NULL,
  `No_Telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cabang_toko`
--

INSERT INTO `cabang_toko` (`ID_Cabang`, `Nama_Cabang`, `Jalan_Cabang_Toko`, `Kota_Cabang_Toko`, `Tanggal_Pembukaan`, `No_Telepon`) VALUES
(8001, 'Hisana Fried Chicken', 'Jl. Raya Rungkut', 'Surabaya', '2019-02-10', '0812-3456-7890'),
(8002, 'Hisana Fried Chicken', 'Jl. Raya Sukodono', 'Sidoarjo', '2020-05-05', '0876-5432-1098'),
(8003, 'Hisana Fried Chicken', 'Jl. Ibrahim Singadilaga', 'Purwakarta', '2021-10-20', '0899-1234-5678'),
(8004, 'Hisana Fried Chicken', 'Jl. Sunan Giri', 'Gresik', '2018-07-15', '0857-8765-4321'),
(8005, 'Hisana Fried Chicken', 'Jl. Candi Mendut Barat', 'Malang', '2020-12-03', '0821-9876-5432'),
(8006, 'Hisana Fried Chicken', 'Jl. Hos Cokroaminoto', 'Probolinggo', '2022-09-08', '0888-2345-6789'),
(8007, 'Hisana Fried Chicken', 'Jl. Basuki Rahmat', 'Banyuwangi', '2021-01-12', '0831-6543-2109'),
(8008, 'Hisana Fried Chicken', 'Jl.Jati, Cemani', 'Sukoharjo', '2019-08-28', '0865-4321-0987'),
(8009, 'Hisana Fried Chicken', 'Jl. Hos Cokroaminoto', 'Kudus', '2020-03-17', '0845-6789-1230'),
(8010, 'Hisana Fried Chicken', 'Jl. Werkudoro', 'Tegal', '2021-06-25', '0896-5432-1098'),
(8011, 'Hisana Fried Chicken', 'Jl. Pahlawan', 'Jakarta Selatan', '2010-02-03', '0812-345-67890'),
(8012, 'Hisana Fried Chicken', 'Jl. Merdeka', 'Bandung', '2005-05-15', '0856-789-01234'),
(8013, 'Hisana Fried Chicken', 'Jl. Raya Jaya', 'Surabaya', '2017-08-07', '0876-543-21098'),
(8014, 'Hisana Fried Chicken', 'Jl. Maju Mundur', 'Medan', '2012-10-22', '0823-456-78901'),
(8015, 'Hisana Fried Chicken', 'Jl. Makmur', 'Semarang', '2014-09-11', '0890-123-45678'),
(8016, 'Hisana Fried Chicken', 'Jl. Harmoni', 'Yogyakarta', '2008-06-28', '0845-678-90123'),
(8017, 'Hisana Fried Chicken', 'Jl. Indah Permai', 'Bali', '2019-04-05', '0889-012-34567'),
(8018, 'Hisana Fried Chicken', 'Jl. Gembira', 'Makassar', '2011-01-19', '0834-567-89012'),
(8019, 'Hisana Fried Chicken', 'Jl. Cemerlang', 'Palembang', '2016-03-10', '0867-890-12345'),
(8020, 'Hisana Fried Chicken', 'Jl. Bahagia', 'Solo', '2007-12-25', '0801-234-56789'),
(8021, 'Hisana Fried Chicken', 'Jl. Sejahtera', 'Tangerang', '2013-10-08', '0854-321-09876'),
(8022, 'Hisana Fried Chicken', 'Jl. Damai', 'Malang', '2009-07-12', '0878-901-23456'),
(8023, 'Hisana Fried Chicken', 'Jl. Serasi', 'Samarinda', '2006-11-01', '0821-098-76543'),
(8024, 'Hisana Fried Chicken', 'Jl. Mulya', 'Pekanbaru', '2018-02-14', '0898-765-43210'),
(8025, 'Hisana Fried Chicken', 'Jl. Sentosa', 'Batam', '2015-05-06', '0843-210-98765'),
(8026, 'Hisana Fried Chicken', 'Jl. Bersama', 'Pontianak', '2010-08-20', '0887-654-32109'),
(8027, 'Hisana Fried Chicken', 'Jl. Sentral', 'Balikpapan', '2004-06-09', '0832-109-87654'),
(8028, 'Hisana Fried Chicken', 'Jl. Merah Delima', 'Mataram', '2012-03-04', '0865-432-10987'),
(8029, 'Hisana Fried Chicken', 'Jl. Bintang', 'Manado', '2018-09-17', '0810-987-65432'),
(8030, 'Hisana Fried Chicken', 'Jl. Berjaya', 'Lampung', '2013-04-30', '0856-789-01234'),
(8031, 'Hisana Fried Chicken', 'Jl. Harapan Baru', 'Bengkulu', '2011-11-23', '0876-543-21098'),
(8032, 'Hisana Fried Chicken', 'Jl. Sentosa Indah', 'Jambi', '2006-07-16', '0823-456-78901'),
(8033, 'Hisana Fried Chicken', 'Jl. Aman Jaya', 'Sorong', '2019-01-29', '0890-123-45678'),
(8034, 'Hisana Fried Chicken', 'Jl. Sukses Abadi', 'Palu', '2003-10-18', '0845-678-90123'),
(8035, 'Hisana Fried Chicken', 'Jl. Gagah Perkasa', 'Jayapura', '2017-12-13', '0889-012-34567'),
(8036, 'Hisana Fried Chicken', 'Jl. Purnama', 'Ambon', '2008-02-02', '0834-567-89012'),
(8037, 'Hisana Fried Chicken', 'Jl. Lestari', 'Padang', '2014-05-24', '0867-890-12345'),
(8038, 'Hisana Fried Chicken', 'Jl. Sejahtera Raya', 'Denpasar', '2005-08-21', '0801-234-56789'),
(8039, 'Hisana Fried Chicken', 'Jl. Makmur Jaya', 'Banjarmasin', '2010-06-26', '0854-321-09876'),
(8040, 'Hisana Fried Chicken', 'Jl. Riang Gembira', 'Kendari', '2016-03-31', '0878-901-23456'),
(8041, 'Hisana Fried Chicken', 'Jl. Bahagia Sentosa', 'Tarakan', '2009-11-27', '0821-098-76543'),
(8042, 'Hisana Fried Chicken', 'Jl. Jaya Lestari', 'Gorontalo', '2011-01-07', '0898-765-43210'),
(8043, 'Hisana Fried Chicken', 'Jl. Harmoni Cinta', 'Palangkaraya', '2007-04-05', '0843-210-98765'),
(8044, 'Hisana Fried Chicken', 'Jl. Damai Sejahtera', 'Ambon', '2015-10-10', '0887-654-32109'),
(8045, 'Hisana Fried Chicken', 'Jl. Suka Makmur', 'Kupang', '2012-07-15', '0832-109-87654'),
(8046, 'Hisana Fried Chicken', 'Jl. Sentosa Indah', 'Pangkal Pinang', '2018-09-08', '0865-432-10987'),
(8047, 'Hisana Fried Chicken', 'Jl. Asri Makmur', 'Mamuju', '2006-05-03', '0810-987-65432'),
(8048, 'Hisana Fried Chicken', 'Jl. Makmur Bersama', 'Biak', '2013-08-22', '0856-789-01234'),
(8049, 'Hisana Fried Chicken', 'Jl. Gembira Makmur', 'Nabire', '2014-11-11', '0876-543-21098'),
(8050, 'Hisana Fried Chicken', 'Jl. Damai Sentosa', 'Merauke', '2020-06-19', '0823-456-78901');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cabang_toko`
--
ALTER TABLE `cabang_toko`
  ADD PRIMARY KEY (`ID_Cabang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
