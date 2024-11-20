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
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `ID_Barang` int(11) NOT NULL,
  `Nama_Barang` varchar(100) DEFAULT NULL,
  `Harga` int(11) DEFAULT NULL,
  `Stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`ID_Barang`, `Nama_Barang`, `Harga`, `Stok`) VALUES
(1001, 'beras', 300000, 50),
(1002, 'tepung tapioka', 180000, 50),
(1003, 'saos tomat', 230000, 50),
(1004, 'saos sambal', 225000, 45),
(1005, 'kecap', 150000, 35),
(1006, 'garam', 100000, 60),
(1007, 'micin', 120000, 30),
(1008, 'kentang', 150000, 25),
(1009, 'teh', 270000, 20),
(1010, 'mie', 110000, 33),
(1011, 'gula', 700000, 35),
(1012, 'susu kental manis', 600000, 40),
(1013, 'kopi', 220000, 30),
(1014, 'mayonaise', 450000, 25),
(1015, 'gelas plastik', 400000, 45),
(1016, 'tahu', 100000, 43),
(1017, 'tempe', 100000, 25),
(1018, 'roti', 300000, 30),
(1019, 'air mineral', 500000, 20),
(1020, 'tepung terigu', 200000, 15),
(1021, 'pisang', 200000, 20),
(1022, 'es krim', 500000, 25),
(1023, 'jeruk', 300000, 25),
(1024, 'kol', 200000, 30),
(1025, 'timun', 200000, 15),
(1026, 'bawang putih', 250000, 25),
(1027, 'bawang merah', 250000, 30),
(1028, 'kemiri', 120000, 50),
(1029, 'lada', 120000, 50),
(1030, 'merica', 120000, 50),
(1031, 'ayam', 700000, 45),
(1032, 'alpukat', 200000, 35),
(1033, 'semangka', 180000, 30),
(1034, 'melon', 250000, 25),
(1035, 'buah naga', 250000, 16),
(1036, 'sirsak', 230000, 25),
(1037, 'sabun cuci piring', 500000, 30),
(1038, 'es batu', 200000, 45),
(1039, 'minyak goreng', 400000, 50),
(1040, 'gas lpg', 300000, 25),
(1041, 'air galon', 150000, 15),
(1042, 'cabe merah', 250000, 40),
(1043, 'cabe keriting', 250000, 15),
(1044, 'saus bbq', 230000, 25),
(1045, 'saus keju', 225000, 30),
(1046, 'saus teriyaki', 235000, 14),
(1047, 'saos mustard', 220000, 26),
(1048, 'sedotan', 200000, 45),
(1049, 'kantong plastik', 200000, 30),
(1050, 'plastik es', 230000, 25);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`ID_Barang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
