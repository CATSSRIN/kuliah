-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 02:53 AM
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
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `ID_Pembayaran` int(11) NOT NULL,
  `Total_Harga` varchar(20) DEFAULT NULL,
  `Nominal_Pembayaran` varchar(20) DEFAULT NULL,
  `Metode_Pembayaran` varchar(50) DEFAULT NULL,
  `Tanggal_Pembayaran` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`ID_Pembayaran`, `Total_Harga`, `Nominal_Pembayaran`, `Metode_Pembayaran`, `Tanggal_Pembayaran`) VALUES
(6001, 'Rp41.000', 'Rp41.000', 'qris', '2024-03-19'),
(6002, 'Rp21.000', 'Rp21.000', 'tunai', '2024-02-19'),
(6003, 'Rp21.000', 'Rp21.000', 'tunai', '2024-01-01'),
(6004, 'Rp55.000', 'Rp55.000', 'tunai', '2024-01-02'),
(6005, 'Rp17.000', 'Rp17.000', 'qris', '2024-03-03'),
(6006, 'Rp27.000', 'Rp27.000', 'qris', '2024-01-09'),
(6007, 'Rp21.000', 'Rp21.000', 'tunai', '2024-02-10'),
(6008, 'Rp38.000', 'Rp38.000', 'qris', '2024-03-09'),
(6009, 'Rp24.000', 'Rp24.000', 'qris', '2024-02-03'),
(6010, 'Rp33,000', 'Rp33,000', 'tunai', '2024-03-01'),
(6011, 'Rp79,000', 'Rp79,000', 'transfer', '2023-01-11'),
(6012, 'Rp65,000', 'Rp65,000', 'e wallet', '2023-02-22'),
(6013, 'Rp88,000', 'Rp88,000', 'tunai', '2023-03-15'),
(6014, 'Rp72,000', 'Rp72,000', 'qris', '2023-04-07'),
(6015, 'Rp53,000', 'Rp53,000', 'e wallet', '2023-05-19'),
(6016, 'Rp97,000', 'Rp97,000', 'tunai', '2023-06-30'),
(6017, 'Rp61,000', 'Rp61,000', 'transfer', '2023-07-10'),
(6018, 'Rp84,000', 'Rp84,000', 'qris', '2023-08-03'),
(6019, 'Rp55,000', 'Rp55,000', 'tunai', '2023-09-14'),
(6020, 'Rp80,000', 'Rp80,000', 'e wallet', '2023-10-25'),
(6021, 'Rp68,000', 'Rp68,000', 'qris', '2023-11-08'),
(6022, 'Rp93,000', 'Rp93,000', 'transfer', '2023-12-29'),
(6023, 'Rp58,000', 'Rp58,000', 'qris', '2023-01-02'),
(6024, 'Rp74,000', 'Rp74,000', 'transfer', '2023-02-18'),
(6025, 'Rp62,000', 'Rp62,000', 'e wallet', '2023-03-27'),
(6026, 'Rp87,000', 'Rp87,000', 'tunai', '2023-04-09'),
(6027, 'Rp56,000', 'Rp56,000', 'qris', '2023-05-23'),
(6028, 'Rp91,000', 'Rp91,000', 'e wallet', '2023-06-05'),
(6029, 'Rp67,000', 'Rp67,000', 'transfer', '2023-07-17'),
(6030, 'Rp76,000', 'Rp76,000', 'tunai', '2023-08-28'),
(6031, 'Rp54,000', 'Rp54,000', 'qris', '2023-09-01'),
(6032, 'Rp89,000', 'Rp89,000', 'transfer', '2023-10-13'),
(6033, 'Rp70,000', 'Rp70,000', 'tunai', '2023-11-26'),
(6034, 'Rp83,000', 'Rp83,000', 'e wallet', '2023-12-31'),
(6035, 'Rp59,000', 'Rp59,000', 'e wallet', '2023-01-04'),
(6036, 'Rp99,000', 'Rp99,000', 'qris', '2023-02-19'),
(6037, 'Rp64,000', 'Rp64,000', 'tunai', '2023-03-12'),
(6038, 'Rp77,000', 'Rp77,000', 'transfer', '2023-04-21'),
(6039, 'Rp52,000', 'Rp52,000', 'qris', '2023-05-06'),
(6040, 'Rp85,000', 'Rp85,000', 'e wallet', '2023-06-24'),
(6041, 'Rp60,000', 'Rp60,000', 'tunai', '2023-07-11'),
(6042, 'Rp95,000', 'Rp95,000', 'transfer', '2023-08-22'),
(6043, 'Rp71,000', 'Rp71,000', 'qris', '2023-09-05'),
(6044, 'Rp86,000', 'Rp86,000', 'e wallet', '2023-10-18'),
(6045, 'Rp57,000', 'Rp57,000', 'tunai', '2023-11-07'),
(6046, 'Rp98,000', 'Rp98,000', 'transfer', '2023-12-20'),
(6047, 'Rp63,000', 'Rp63,000', 'qris', '2023-01-03'),
(6048, 'Rp75,000', 'Rp75,000', 'e wallet', '2023-02-15'),
(6049, 'Rp66,000', 'Rp66,000', 'tunai', '2023-03-27'),
(6050, 'Rp81,000', 'Rp81,000', 'e wallet', '2023-04-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`ID_Pembayaran`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
