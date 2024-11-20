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
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `ID_Pemasukan` int(11) NOT NULL,
  `Tanggal_Pemasukan` date DEFAULT NULL,
  `Deskripsi_Pemasukan` varchar(255) DEFAULT NULL,
  `Jumlah_Pemasukan` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`ID_Pemasukan`, `Tanggal_Pemasukan`, `Deskripsi_Pemasukan`, `Jumlah_Pemasukan`) VALUES
(7001, '2023-01-19', 'Penjualan paket fried chicken spesial', 1267795.00),
(7002, '2023-10-05', 'Pemasukan dari promo \"buy one get one\" fried chicken', 1347772.00),
(7003, '2023-08-30', 'Penjualan makanan pendamping (side dishes) untuk fried chicken', 1522058.00),
(7004, '2022-09-12', 'Pemasukan dari pemesanan katering fried chicken untuk acara', 1765018.00),
(7005, '2022-11-21', 'Penjualan menu fried chicken combo', 1339980.00),
(7006, '2023-06-17', 'Pembayaran dari pelanggan setia yang memesan fried chicken', 719066.00),
(7007, '2023-03-11', 'Pemasukan dari penjualan minuman dingin', 1702272.00),
(7008, '2022-08-24', 'Penjualan fried chicken dengan saus spesial', 994544.00),
(7009, '2023-04-29', 'Pemasukan dari acara ulang tahun yang dipesan', 1404501.00),
(7010, '2023-09-07', 'Penjualan paket family meal fried chicken', 1228630.00),
(7011, '2022-12-01', 'Pemasukan dari program delivery', 1311195.00),
(7012, '2022-07-08', 'Penjualan fried chicken crispy', 760140.00),
(7013, '2023-02-23', 'Pemasukan dari promosi weekend', 1113379.00),
(7014, '2022-09-30', 'Penjualan fried chicken dengan porsi besar', 1190127.00),
(7015, '2022-12-27', 'Pembayaran dari acara BBQ', 1577670.00),
(7016, '2023-05-21', 'Penjualan menu fried chicken spicy', 1064513.00),
(7017, '2023-07-09', 'Pemasukan dari paket rombongan', 1756808.00),
(7018, '2022-08-12', 'Penjualan snack fried chicken bites', 1446234.00),
(7019, '2023-01-02', 'Pemasukan dari kerja sama dengan aplikasi pemesanan makanan', 531903.00),
(7020, '2023-08-15', 'Penjualan hidangan penutup yang cocok dengan fried chicken', 731071.00),
(7021, '2023-05-03', 'Pemasukan dari event live cooking', 1710391.00),
(7022, '2023-06-26', 'Penjualan makanan siap saji untuk takeaway', 1508131.00),
(7023, '2023-02-09', 'Pemasukan dari penjualan voucher makan', 1310057.00),
(7024, '2023-03-29', 'Penjualan paket fried chicken untuk acara olahraga', 1993044.00),
(7025, '2022-11-06', 'Pemasukan dari menu spesial hari raya', 494562.00),
(7026, '2023-04-10', 'Penjualan fried chicken dengan bumbu rahasia', 946772.00),
(7027, '2022-10-01', 'Pemasukan dari promosi hari jadi restoran', 1925373.00),
(7028, '2023-07-23', 'Penjualan makanan ringan (snack) seperti kentang goreng', 1417654.00),
(7029, '2023-09-21', 'Pemasukan dari kelas memasak fried chicken', 1899482.00),
(7030, '2023-01-26', 'Penjualan menu fried chicken untuk anak-anak', 442296.00),
(7031, '2022-10-14', 'Pemasukan dari sponsorship acara', 1397396.00),
(7032, '2023-06-03', 'Penjualan paket makan malam keluarga', 1392497.00),
(7033, '2023-08-01', 'Pemasukan dari event charity dinner', 1706402.00),
(7034, '2023-09-13', 'Penjualan minuman manis untuk pendamping fried chicken', 1396648.00),
(7035, '2022-12-13', 'Pemasukan dari promo akhir pekan', 1214666.00),
(7036, '2022-07-29', 'Penjualan hidangan penutup khas restoran', 519299.00),
(7037, '2023-04-17', 'Pembayaran dari acara team building', 1280028.00),
(7038, '2023-02-02', 'Penjualan fried chicken dengan saus sambal', 1964548.00),
(7039, '2022-09-04', 'Pemasukan dari donasi pelanggan', 422180.00),
(7040, '2022-10-27', 'Penjualan fried chicken untuk pameran makanan', 551110.00),
(7041, '2023-05-30', 'Pemasukan dari kolaborasi dengan chef tamu', 1599350.00),
(7042, '2023-07-12', 'Penjualan paket spesial bulan Ramadhan', 843280.00),
(7043, '2023-01-08', 'Pemasukan dari acara food truck', 1325180.00),
(7044, '2023-03-20', 'Penjualan produk frozen fried chicken', 1550155.00),
(7045, '2022-11-17', 'Pemasukan dari event makanan internasional', 1741951.00),
(7046, '2023-02-28', 'Penjualan paket fried chicken dan salad', 432187.00),
(7047, '2023-05-15', 'Pemasukan dari event anniversary restoran', 821527.00),
(7048, '2022-08-07', 'Penjualan fried chicken dengan porsi mini', 627204.00),
(7049, '2023-07-03', 'Pemasukan dari program loyalitas pelanggan', 1644551.00),
(7050, '2022-10-19', 'Penjualan fried chicken dengan bumbu rempah lokal', 1632006.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`ID_Pemasukan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
