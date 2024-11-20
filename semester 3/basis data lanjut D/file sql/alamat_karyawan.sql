-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2024 at 02:58 AM
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
-- Table structure for table `alamat_karyawan`
--

CREATE TABLE `alamat_karyawan` (
  `ID_Alamat` int(11) NOT NULL,
  `Alamat` varchar(255) DEFAULT NULL,
  `Kecamatan` varchar(100) DEFAULT NULL,
  `Kota` varchar(100) DEFAULT NULL,
  `Kode_Pos` varchar(10) DEFAULT NULL,
  `Provinsi` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alamat_karyawan`
--

INSERT INTO `alamat_karyawan` (`ID_Alamat`, `Alamat`, `Kecamatan`, `Kota`, `Kode_Pos`, `Provinsi`) VALUES
(4001, 'Jl. Ahmad Yani', 'genteng', 'Surabaya', '60275', 'jawa timur'),
(4002, 'Jl.Wonocolo', 'tegalsari', 'Surabaya', '60241', 'jawa timur'),
(4003, 'Jl.Darmo', 'simokerto', 'Surabaya', '60141', 'jawa timur'),
(4004, 'Jl.Pandugo', 'bubutan', 'Surabaya', '60281', 'jawa timur'),
(4005, 'Jl.Kalirungkut', 'gubeng', 'Surabaya', '60293', 'jawa timur'),
(4006, 'Jl.Kenjeran', 'sukolilo', 'Surabaya', '60141', 'jawa timur'),
(4007, 'Jl.trenggilis', 'wonokromo', 'Surabaya', '60251', 'jawa timur'),
(4008, 'Jl.Ngagel', 'rungkut', 'Surabaya', '60171', 'jawa timur'),
(4009, 'Jl.Jemursari', 'tandes', 'Surabaya', '60275', 'jawa timur'),
(4010, 'Jl.Ketintang', 'sawahan', 'Surabaya', '60262', 'jawa timur'),
(4011, 'Jl. Merpati', 'kebayoran lama', 'Jakarta Selatan', '12130', 'jawa barat'),
(4012, 'Jl. Mawar', 'cicendo', 'Bandung', '40181', 'jawa barat'),
(4013, 'Jl. Melati', 'tandes', 'Surabaya', '60293', 'jawa timur'),
(4014, 'Jl. Cendrawasih', 'gondokusuman', 'Yogyakarta', '55231', 'jawa tengah'),
(4015, 'Jl. Jenderal Sudirman', 'tembalang', 'Semarang', '50132', 'jawa tengah'),
(4016, 'Jl. Pahlawan', 'denpasar timur', 'Denpasar', '80119', 'Bali'),
(4017, 'Jl. Cempaka Putih', 'medan kota', 'Medan', '20212', 'Sumatera utara'),
(4018, 'Jl. Gajah Mada', 'rappocini', 'Makassar', '90231', 'Sulawesi selatan'),
(4019, 'Jl. Diponegoro', 'klojen', 'Malang', '65111', 'jawa timur'),
(4020, 'Jl. Kenanga', 'batuceper', 'Tangerang', '15121', 'jawa barat'),
(4021, 'Jl. Melawai', 'kebayoran baru', 'Jakarta Barat', '12910', 'jawa barat'),
(4022, 'Jl. Dago', 'lengkong', 'Bandung', '40231', 'jawa barat'),
(4023, 'Jl. Raya Darmo', 'gubeng', 'Surabaya', '60187', 'jawa timur'),
(4024, 'Jl. Malioboro', 'danurejan', 'Yogyakarta', '55211', 'jawa tengah'),
(4025, 'Jl. Pandanaran', 'semarang barat', 'Semarang', '50271', 'jawa tengah'),
(4026, 'Jl. Teuku Umar', 'denpasar barat', 'Denpasar', '80231', 'Bali'),
(4027, 'Jl. Krakatau', 'medan baru', 'Medan', '20124', 'Sumatera utara'),
(4028, 'Jl. Ratulangi', 'tamalate', 'Makassar', '90141', 'Sulawesi selatan'),
(4029, 'Jl. Soekarno-Hatta', 'lowok waru', 'Malang', '65125', 'jawa timur'),
(4030, 'Jl. Bintaro', 'karawaci', 'Tangerang', '15121', 'jawa barat'),
(4031, 'Jl. Prapanca', 'tebet', 'Jakarta Timur', '12810', 'jawa barat'),
(4032, 'Jl. Cihampelas', 'cicendo', 'Bandung', '40231', 'jawa barat'),
(4033, 'Jl. Tunjungan', 'wonokromo', 'Surabaya', '60241', 'jawa timur'),
(4034, 'Jl. Parangtritis', 'tegal rejo', 'Yogyakarta', '55244', 'jawa tengah'),
(4035, 'Jl. Siliwangi', 'semarang tengah', 'Semarang', '50132', 'jawa tengah'),
(4036, 'Jl. Dewi Sartika', 'denpasar selatan', 'Denpasar', '80235', 'Bali'),
(4037, 'Jl. Gatot Subroto', 'medan helvetia', 'Medan', '20124', 'Sumatera utara'),
(4038, 'Jl. Pettarani', 'panakkukang', 'Makassar', '90231', 'Sulawesi selatan'),
(4039, 'Jl. Basuki Rahmat', 'sukun', 'Malang', '65141', 'jawa timur'),
(4040, 'Jl. Matraman', 'pasar minggu', 'Jakarta Utara', '15146', 'jawa barat'),
(4041, 'Jl. Asia Afrika', 'lengkong', 'Bandung', '40251', 'jawa barat'),
(4042, 'Jl. Gubeng', 'rungkut', 'Surabaya', '60141', 'jawa timur'),
(4043, 'Jl. Diponegoro', 'jetis', 'Yogyakarta', '55244', 'jawa tengah'),
(4044, 'Jl. Pemuda', 'tembalang', 'Semarang', '50132', 'jawa tengah'),
(4045, 'Jl. Imam Bonjol', 'denpasar utara', 'Denpasar', '80235', 'Bali'),
(4046, 'Jl. Medan Merdeka', 'medan kota', 'Medan', '20231', 'Sumatera utara'),
(4047, 'Jl. AP Pettarani', 'rappocini', 'Makassar', '90141', 'Sulawesi selatan'),
(4048, 'Jl. Ijen', 'blimbing', 'Malang', '65111', 'jawa timur'),
(4049, 'Jl. Ahmad Yani', 'karawaci', 'Tangerang', '15121', 'jawa barat'),
(4050, 'Jl. Sudirman', 'cipondoh', 'Tangerang', '15114', 'jawa barat');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alamat_karyawan`
--
ALTER TABLE `alamat_karyawan`
  ADD PRIMARY KEY (`ID_Alamat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
