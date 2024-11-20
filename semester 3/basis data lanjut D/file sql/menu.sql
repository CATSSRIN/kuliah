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
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `ID_Menu` int(11) NOT NULL,
  `Nama_Menu` varchar(100) DEFAULT NULL,
  `Harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`ID_Menu`, `Nama_Menu`, `Harga`) VALUES
(3001, 'ayam geprek komplit', 20000),
(3002, 'paha bawah spicy', 15000),
(3003, 'ayam pedas dower', 13000),
(3004, 'ayam bekakak', 70000),
(3005, 'kentang crispy', 8000),
(3006, 'hydrococo', 8000),
(3007, 'paket hemat', 18000),
(3008, 'spicy wings', 15000),
(3009, 'chicken stick', 10000),
(3010, 'kulit crispy', 10000),
(3011, 'Ayam Goreng Original', 15000),
(3012, 'Ayam Goreng Pedas', 17000),
(3013, 'Ayam Goreng Geprek', 18000),
(3014, 'Ayam Goreng Saus Keju', 20000),
(3015, 'Ayam Goreng Saus Barbeque', 20000),
(3016, 'Ayam Goreng Saus Honey Mustard', 20000),
(3017, 'Ayam Goreng Saus Teriyaki', 20000),
(3018, 'Ayam Goreng Saus Asam Manis', 20000),
(3019, 'Kentang Goreng', 8000),
(3020, 'Kol Goreng', 5000),
(3021, 'Perkedel', 3000),
(3022, 'Tahu Goreng', 3000),
(3023, 'Tempe Goreng', 3000),
(3024, 'Mayonaise', 2000),
(3025, 'Acar', 2000),
(3026, 'Lalapan', 5000),
(3027, 'Emping', 3000),
(3028, 'Es Teh', 3000),
(3029, 'Es Jeruk', 5000),
(3030, 'Soft Drink', 5000),
(3031, 'Burger', 20000),
(3032, 'Sandwich', 15000),
(3033, 'Spaghetti', 25000),
(3034, 'Mie Goreng', 15000),
(3035, 'Kwetiau Goreng', 15000),
(3036, 'Dimsum', 20000),
(3037, 'Onion Rings', 15000),
(3038, 'Chicken Fingers', 12000),
(3039, 'Chicken Nuggets', 10000),
(3040, 'Es Krim', 5000),
(3041, 'Kopi', 5000),
(3042, 'Chicken Sandwich', 20000),
(3043, 'Jus', 10000),
(3044, 'Milkshake', 15000),
(3045, 'Smoothies', 15000),
(3046, 'Roti Bakar', 10000),
(3047, 'Pisang Goreng', 8000),
(3048, 'Bakwan', 5000),
(3049, 'Cimol', 7000),
(3050, 'Cireng', 6000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`ID_Menu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
