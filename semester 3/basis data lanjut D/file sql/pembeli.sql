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
-- Table structure for table `pembeli`
--

CREATE TABLE `pembeli` (
  `ID_Pembeli` int(11) NOT NULL,
  `Nama_Pembeli` varchar(100) DEFAULT NULL,
  `Jalan_Pembeli` varchar(100) DEFAULT NULL,
  `Kota_Pembeli` varchar(50) DEFAULT NULL,
  `No_Telepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembeli`
--

INSERT INTO `pembeli` (`ID_Pembeli`, `Nama_Pembeli`, `Jalan_Pembeli`, `Kota_Pembeli`, `No_Telepon`) VALUES
(2401, 'abi', 'Jl.Menganti', 'Gresik', '081234567890'),
(2402, 'bima', 'Jl.Wonocolo', 'Surabaya', '085678901234'),
(2403, 'candra', 'Jl.Gunungsari', 'Surabaya', '087654321098'),
(2404, 'dimas', 'Jl.Kebayoran lama', 'Jakarta', '089012345678'),
(2405, 'elsa', 'Jl. Darmo', 'Surabaya', '081345678901'),
(2406, 'farhan', 'Jl.Keputih', 'Surabaya', '085901234567'),
(2407, 'gilang', 'Jl.Entong gendut', 'Jakarta', '087123456789'),
(2408, 'herman', 'Jl. Kebayoran baru', 'Jakarta', '089234567890'),
(2409, 'indri', 'Jl.Kepanjen', 'Malang', '081678901234'),
(2410, 'joko', 'Jl.Hayam wuruk', 'Jakarta', '085456789012'),
(2411, 'Anita Wijaya', 'Jalan Mawar Indah', 'Surabaya', '081234567890'),
(2412, 'Hadi Santoso', 'Jalan Anggrek Selatan', 'Surabaya', '087654321098'),
(2413, 'Rina Fitriani', 'Jalan Cempaka Putih Raya', 'Surabaya', '085670912345'),
(2414, 'Budi Setiawan', 'Jalan Dahlia Selatan', 'Surabaya', '089078945612'),
(2415, 'Siska Amelia', 'Jalan Melati Barat', 'Surabaya', '082367890123'),
(2416, 'Hendra Gunawan', 'Jalan Flamboyan Utara', 'Malang', '084563210987'),
(2417, 'Dewi Cahyani', 'Jalan Wijaya Kusuma Raya', 'Malang', '086789012345'),
(2418, 'Fajar Pratama', 'Jalan Merbabu Selatan', 'Malang', '088907856341'),
(2419, 'Siti Nurhayati', 'Jalan Mawar Merah Selatan', 'Malang', '083451278906'),
(2420, 'Adi Kusuma', 'Jalan Cempaka Timur', 'Malang', '085612349078'),
(2421, 'Rini Susanti', 'Jalan Anggrek Ungu', 'Semarang', '081234567890'),
(2422, 'Andi Saputra', 'Jalan Muria Raya', 'Semarang', '087654321098'),
(2423, 'Nina Permata', 'Jalan Dahlia Selatan', 'Semarang', '085670912345'),
(2424, 'Yoga Pradana', 'Jalan Melati Barat', 'Semarang', '089078945612'),
(2425, 'Lina Setiawati', 'Jalan Matahari Selatan', 'Yogyakarta', '082367890123'),
(2426, 'Bayu Nugroho', 'Jalan Malioboro Raya', 'Yogyakarta', '084563210987'),
(2427, 'Linda Sari', 'Jalan Kaliurang Selatan', 'Yogyakarta', '086789012345'),
(2428, 'Eko Wibowo', 'Jalan Kemuning Selatan', 'Yogyakarta', '088907856341'),
(2429, 'Rita Maulana', 'Jalan Mangga Besar', 'Yogyakarta', '083451278906'),
(2430, 'Rudi Hartono', 'Jalan Teuku Umar', 'Manado', '085612349078'),
(2431, 'Maya Anggraini', 'Jalan Diponegoro', 'Bengkulu', '081234567890'),
(2432, 'Agus Suryadi', 'Jalan Jenderal Sudirman', 'Palu', '087654321098'),
(2433, 'Sari Utami', 'Jalan Sultan Hasanuddin', 'Tarakan', '085670912345'),
(2434, 'Farhan Ramadhan', 'Jalan Kapten Muslim', 'Sorong', '089078945612'),
(2435, 'Diana Putri', 'Jalan HR Rasuna Said', 'Palangkaraya', '082367890123'),
(2436, 'Ilham Pratama', 'Jalan Pattimura', 'Mamuju', '084563210987'),
(2437, 'Nia Septiani', 'Jalan Sam Ratulangi', 'Banda Aceh', '086789012345'),
(2438, 'Yusuf Rahman', 'Jalan KH Agus Salim', 'Tanjung Pinang', '088907856341'),
(2439, 'Dini Cahaya', 'Jalan Urip Sumoharjo', 'Singkawang', '083451278906'),
(2440, 'Aldi Firmansyah', 'Jalan Sultan Agung', 'Mojokerto', '085612349078'),
(2441, 'Siska Rahayu', 'Jalan Darmo', 'Tuban', '081234567890'),
(2442, 'Arif Susanto', 'Jalan Asia Afrika', 'Malang', '087654321098'),
(2443, 'Rina Puspita', 'Jalan Imam Bonjol', 'Denpasar', '085670912345'),
(2444, 'Rizki Kurniawan', 'Jalan Ahmad Yani', 'Bekasi', '089078945612'),
(2445, 'Fitri Indah', 'Jalan Dipati Ukur', 'Tangerang', '082367890123'),
(2446, 'Firman Jaya', 'Jalan Pemuda', 'Bogor', '084563210987'),
(2447, 'Dewi Lestari', 'Jalan Gajah Mada', 'Pekanbaru', '086789012345'),
(2448, 'Anto Purnomo', 'Jalan S Parman', 'Depok', '088907856341'),
(2449, 'Lia Putri', 'Jalan MT Haryono', 'Batam', '083451278906'),
(2450, 'Bambang Wijaya', 'Jalan Ahmad Dahlan', 'Padang', '085612349078');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`ID_Pembeli`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
