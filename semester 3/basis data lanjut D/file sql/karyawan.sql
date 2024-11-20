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
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `ID_Karyawan` int(11) NOT NULL,
  `Nama_Karyawan` varchar(100) DEFAULT NULL,
  `Jabatan` varchar(100) DEFAULT NULL,
  `ID_Alamat` int(11) DEFAULT NULL,
  `ID_Cabang` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`ID_Karyawan`, `Nama_Karyawan`, `Jabatan`, `ID_Alamat`, `ID_Cabang`) VALUES
(2901, 'bambang', 'manager restoran', 4001, 8001),
(2902, 'darno', 'asissten', 4002, 8002),
(2903, 'butts', 'koki', 4003, 8003),
(2904, 'kiboy', 'kasir', 4004, 8004),
(2905, 'albert', 'staf dapur', 4005, 8005),
(2906, 'wawan', 'staf pelayanan pelanggan', 4006, 8006),
(2907, 'gojali', 'staf kebersihan', 4007, 8007),
(2908, 'azrul', 'staf administrasi', 4008, 8008),
(2909, 'sutikno', 'staf pemasaran', 4009, 8009),
(2910, 'budi', 'staf pengiriman', 4010, 8010),
(2911, 'Andi Pratama', 'Manajer Umum', 4011, 8011),
(2912, 'Dewi Kartika', 'Manajer Operasional', 4012, 8012),
(2913, 'Budi Santoso', 'Manajer Keuangan', 4013, 8013),
(2914, 'Siti Aminah', 'Manajer Sumber Daya Manusia', 4014, 8014),
(2915, 'Rizki Ramadhan', 'Manajer Pemasaran', 4015, 8015),
(2916, 'Lina Sari', 'Manajer Restoran', 4016, 8016),
(2917, 'Fajar Nugroho', 'Manajer Shift', 4017, 8017),
(2918, 'Wulan Rahmawati', 'Asisten Manajer Restoran', 4018, 8018),
(2919, 'Agus Setiawan', 'Supervisor Dapur', 4019, 8019),
(2920, 'Rina Amelia', 'Supervisor Layanan', 4020, 8020),
(2921, 'Joko Supriyanto', 'Kepala Koki', 4021, 8021),
(2922, 'Mira Andayani', 'Koki', 4022, 8022),
(2923, 'Hendra Kurniawan', 'Pembantu Koki', 4023, 8023),
(2924, 'Tina Maharani', 'Koki Goreng', 4024, 8024),
(2925, 'Dani Saputra', 'Koki Panggang', 4025, 8025),
(2926, 'Indah Permata', 'Koki Roti', 4026, 8026),
(2927, 'Yoga Pratama', 'Koki Pastry', 4027, 8027),
(2928, 'Nina Wijayanti', 'Pelayan', 4028, 8028),
(2929, 'Imam Sugiarto', 'Kasir', 4029, 8029),
(2930, 'Yuli Astuti', 'Pramusaji', 4030, 8030),
(2931, 'Aditia Putra', 'Pembawa Hidangan', 4031, 8031),
(2932, 'Susi Nuraini', 'Barista', 4032, 8032),
(2933, 'Bagus Priyanto', 'Pencuci Piring', 4033, 8033),
(2934, 'Maya Anggraeni', 'Petugas Kebersihan', 4034, 8034),
(2935, 'Eko Susanto', 'Koordinator Pemesanan', 4035, 8035),
(2936, 'Rani Wulandari', 'Koordinator Pengiriman', 4036, 8036),
(2937, 'Herman Wijaya', 'Pengemudi Pengiriman', 4037, 8037),
(2938, 'Desi Apriani', 'Petugas Drive-Thru', 4038, 8038),
(2939, 'Ali Maulana', 'Petugas Persediaan', 4039, 8039),
(2940, 'Yanti Susanti', 'Petugas Penyimpanan', 4040, 8040),
(2941, 'Roni Suryadi', 'Petugas Perlengkapan', 4041, 8041),
(2942, 'Dina Kurnia', 'Petugas Perbaikan', 4042, 8042),
(2943, 'Bayu Wijaya', 'Analis Kualitas', 4043, 8043),
(2944, 'Sari Wulandari', 'Petugas Keamanan', 4044, 8044),
(2945, 'Arif Budiman', 'Pengawas Keamanan Pangan', 4045, 8045),
(2946, 'Tina Susanti', 'Pengelola Media Sosial', 4046, 8046),
(2947, 'Naufal Pradipta', 'Petugas Layanan Pelanggan', 4047, 8047),
(2948, 'Rosa Maulani', 'Petugas Pelatihan', 4048, 8048),
(2949, 'Ahmad Fauzi', 'Petugas Administrasi', 4049, 8049),
(2950, 'Lilis Suryani', 'Petugas Riset dan Pengembangan', 4050, 8050);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`ID_Karyawan`),
  ADD KEY `fk_karyawan_alamat` (`ID_Alamat`),
  ADD KEY `fk_karyawan_cabang` (`ID_Cabang`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `fk_karyawan_alamat` FOREIGN KEY (`ID_Alamat`) REFERENCES `alamat_karyawan` (`ID_Alamat`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_karyawan_cabang` FOREIGN KEY (`ID_Cabang`) REFERENCES `cabang_toko` (`ID_Cabang`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
