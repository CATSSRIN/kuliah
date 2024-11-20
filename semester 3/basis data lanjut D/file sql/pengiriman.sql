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
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `ID_Pengiriman` int(11) NOT NULL,
  `ID_Penjualan` int(11) DEFAULT NULL,
  `Metode_Pengiriman` varchar(50) DEFAULT NULL,
  `Biaya_Pengiriman` varchar(20) DEFAULT NULL,
  `Status_Pengiriman` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`ID_Pengiriman`, `ID_Penjualan`, `Metode_Pengiriman`, `Biaya_Pengiriman`, `Status_Pengiriman`) VALUES
(5001, 6001, 'grab', 'Rp5.000', 'dalam proses'),
(5002, 6002, 'grab', 'Rp6.000', 'sedang diambil'),
(5003, 6003, 'grab', 'Rp6.000', 'hampir tiba'),
(5004, 6004, 'gojek', 'Rp5.000', 'dalam perjalanan'),
(5005, 6005, 'shopee food', 'Rp7.000', 'dalam perjalanan'),
(5006, 6006, 'shopee food', 'Rp7.000', 'selesai'),
(5007, 6007, 'grab', 'Rp5.000', 'selesai'),
(5008, 6008, 'gojek', 'Rp6.000', 'sedang diambil'),
(5009, 6009, 'gojek', 'Rp6.000', 'selesai'),
(5010, 6010, 'grab', 'Rp7.000', 'dalam proses'),
(5011, 6011, 'GrabFood', 'Rp10.000', 'dalam perjalanan'),
(5012, 6012, 'GoFood', 'Rp7.000', 'hampir tiba'),
(5013, 6013, 'ShopeeFood', 'Rp10.000', 'selesai'),
(5014, 6014, 'Gojek Food', 'Rp8.000', 'sedang diambil'),
(5015, 6015, 'GrabEats', 'Rp12.000', 'dalam proses'),
(5016, 6016, 'Shopee Express', 'Rp15.000', 'selesai'),
(5017, 6017, 'Shopee Mart', 'Rp20.000', 'sedang diambil'),
(5018, 6018, 'GrabMart', 'Rp25.000', 'dalam perjalanan'),
(5019, 6019, 'Gojek Mart', 'Rp30.000', 'dalam proses'),
(5020, 6020, 'GrabBike', 'Rp18.000', 'hampir tiba'),
(5021, 6021, 'GrabCar', 'Rp100.000', 'selesai'),
(5022, 6022, 'GoCar', 'Rp110.000', 'sedang diambil'),
(5023, 6023, 'Shopee Rider', 'Rp35.000', 'dalam perjalanan'),
(5024, 6024, 'Grab Express', 'Rp40.000', 'dalam perjalanan'),
(5025, 6025, 'Gojek Express', 'Rp32.000', 'selesai'),
(5026, 6026, 'Grab Food Delivery', 'Rp38.000', 'sedang diambil'),
(5027, 6027, 'Gojek Food Delivery', 'Rp42.000', 'dalam proses'),
(5028, 6028, 'Shopee Food Delivery', 'Rp45.000', 'hampir tiba'),
(5029, 6029, 'GrabFood Express', 'Rp50.000', 'sedang diambil'),
(5030, 6030, 'GoFood Express', 'Rp55.000', 'selesai'),
(5031, 6031, 'ShopeeFood Express', 'Rp60.000', 'dalam proses'),
(5032, 6032, 'GrabBike Delivery', 'Rp65.000', 'sedang diambil'),
(5033, 6033, 'Gojek Bike', 'Rp70.000', 'dalam perjalanan'),
(5034, 6034, 'GrabFood Rider', 'Rp75.000', 'dalam perjalanan'),
(5035, 6035, 'GoFood Rider', 'Rp80.000', 'hampir tiba'),
(5036, 6036, 'ShopeeFood Rider', 'Rp85.000', 'selesai'),
(5037, 6037, 'GrabFood Delivery Service', 'Rp90.000', 'sedang diambil'),
(5038, 6038, 'GoFood Delivery Service', 'Rp95.000', 'dalam proses'),
(5039, 6039, 'ShopeeFood Delivery Service', 'Rp22.000', 'selesai'),
(5040, 6040, 'GrabKitchen', 'Rp27.000', 'sedang diambil'),
(5041, 6041, 'GoKitchen', 'Rp40.000', 'dalam perjalanan'),
(5042, 6042, 'Shopee Kitchen', 'Rp77.000', 'hampir tiba'),
(5043, 6043, 'GrabFood Merchant', 'Rp45.000', 'dalam proses'),
(5044, 6044, 'GoFood Merchant', 'Rp55.000', 'selesai'),
(5045, 6045, 'ShopeeFood Merchant', 'Rp60.000', 'sedang diambil'),
(5046, 6046, 'GrabFood Order', 'Rp70.000', 'dalam perjalanan'),
(5047, 6047, 'GoFood Order', 'Rp80.000', 'hampir tiba'),
(5048, 6048, 'ShopeeFood Order', 'Rp90.000', 'dalam proses'),
(5049, 6049, 'GrabFood App', 'Rp65.000', 'selesai'),
(5050, 6050, 'GoFood App', 'Rp50.000', 'sedang diambil');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`ID_Pengiriman`),
  ADD KEY `fk_pengiriman_penjualan` (`ID_Penjualan`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD CONSTRAINT `fk_pengiriman_penjualan` FOREIGN KEY (`ID_Penjualan`) REFERENCES `pembayaran` (`ID_Pembayaran`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
