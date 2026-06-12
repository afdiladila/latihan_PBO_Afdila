-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2026 at 03:06 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_latihan_pbo_trpl1a_afdila_dwiyani`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_tiket`
--

CREATE TABLE `tabel_tiket` (
  `id_tiket` varchar(10) NOT NULL,
  `nama_film` varchar(100) NOT NULL,
  `jadwal_tayang` timestamp NOT NULL,
  `jumlah_kursi` int NOT NULL,
  `harga_dasar_tiket` decimal(10,2) NOT NULL,
  `jenis_studio` varchar(20) NOT NULL,
  `tipe_audio` varchar(30) DEFAULT NULL,
  `lokasi_baris` varchar(20) DEFAULT NULL,
  `kacamata_3d_efek` varchar(50) DEFAULT NULL,
  `efek_gerak_fitur` varchar(50) DEFAULT NULL,
  `bantal_selimut_pack` varchar(50) DEFAULT NULL,
  `layanan_butler` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_tiket`
--

INSERT INTO `tabel_tiket` (`id_tiket`, `nama_film`, `jadwal_tayang`, `jumlah_kursi`, `harga_dasar_tiket`, `jenis_studio`, `tipe_audio`, `lokasi_baris`, `kacamata_3d_efek`, `efek_gerak_fitur`, `bantal_selimut_pack`, `layanan_butler`) VALUES
('TKT001', 'Avengers: Secret Wars', '2026-07-15 06:00:00', 1, 50000.00, 'Regular', 'Dolby Atmos', 'Row G', NULL, NULL, NULL, NULL),
('TKT002', 'Avengers: Secret Wars', '2026-07-15 06:00:00', 2, 50000.00, 'Regular', 'Dolby Atmos', 'Row G', NULL, NULL, NULL, NULL),
('TKT003', 'Batman: Resurrection', '2026-07-15 07:30:00', 1, 45000.00, 'Regular', 'Standard Stereo', 'Row E', NULL, NULL, NULL, NULL),
('TKT004', 'Batman: Resurrection', '2026-07-15 07:30:00', 1, 45000.00, 'Regular', 'Standard Stereo', 'Row F', NULL, NULL, NULL, NULL),
('TKT005', 'The Matrix 5', '2026-07-16 12:00:00', 2, 55000.00, 'Regular', 'Dolby Atmos', 'Row C', NULL, NULL, NULL, NULL),
('TKT006', 'The Matrix 5', '2026-07-16 12:00:00', 1, 55000.00, 'Regular', 'Dolby Atmos', 'Row C', NULL, NULL, NULL, NULL),
('TKT007', 'Avatar 4: The Tulkun Rider', '2026-07-15 08:00:00', 1, 75000.00, '3D', 'Dolby Atmos', 'Row D', 'Kacamata Pasif RealD', NULL, NULL, NULL),
('TKT008', 'Avatar 4: The Tulkun Rider', '2026-07-15 08:00:00', 1, 75000.00, '3D', 'Dolby Atmos', 'Row D', 'Kacamata Pasif RealD', NULL, NULL, NULL),
('TKT009', 'Avatar 4: The Tulkun Rider', '2026-07-15 11:30:00', 2, 75000.00, '3D', 'Dolby Atmos', 'Row E', 'Kacamata Pasif RealD', NULL, NULL, NULL),
('TKT010', 'Frozen 3', '2026-07-16 04:00:00', 1, 70000.00, '3D', 'DTS:X', 'Row F', 'Kacamata Anak Khusus', NULL, NULL, NULL),
('TKT011', 'Frozen 3', '2026-07-16 04:00:00', 1, 70000.00, '3D', 'DTS:X', 'Row F', 'Kacamata Anak Khusus', NULL, NULL, NULL),
('TKT012', 'Fast X: Part 2', '2026-07-15 09:00:00', 1, 110000.00, '4DX', 'Dolby 7.1', 'Row B', NULL, 'Motion, Water, Wind, Scent', NULL, NULL),
('TKT013', 'Fast X: Part 2', '2026-07-15 09:00:00', 1, 110000.00, '4DX', 'Dolby 7.1', 'Row B', NULL, 'Motion, Water, Wind, Scent', NULL, NULL),
('TKT014', 'Fast X: Part 2', '2026-07-15 12:30:00', 1, 120000.00, '4DX', 'Dolby 7.1', 'Row C', NULL, 'Motion, Water, Wind, Scent', NULL, NULL),
('TKT015', 'Twisters 2', '2026-07-16 07:00:00', 2, 10000.00, '4DX', 'Dolby Atmos', 'Row D', NULL, 'Heavy Wind, Rain, Lightning', NULL, NULL),
('TKT016', 'Twisters 2', '2026-07-16 07:00:00', 1, 100000.00, '4DX', 'Dolby Atmos', 'Row D', NULL, 'Heavy Wind, Rain, Lightning', NULL, NULL),
('TKT017', 'Twisters 2', '2026-07-16 07:00:00', 1, 100000.00, '4DX', 'Dolby Atmos', 'Row E', NULL, 'Heavy Wind, Rain, Lightning', NULL, NULL),
('TKT018', 'Oppenheimer: Heritage', '2026-07-15 13:00:00', 1, 150000.00, 'Premiere', 'Dolby Atmos', 'Row A', NULL, NULL, 'Sutra Blanket & Pillow Pack', 'Personal Butler Service'),
('TKT019', 'Oppenheimer: Heritage', '2026-07-15 13:00:00', 1, 150000.00, 'Premiere', 'Dolby Atmos', 'Row A', NULL, NULL, 'Sutra Blanket & Pillow Pack', 'Personal Butler Service'),
('TKT020', 'Batman: Resurrection', '2026-07-16 14:00:00', 2, 150000.00, 'Premiere', 'DTS:X', 'Row B', NULL, NULL, 'Standard Blanket & Pillow Pack', 'On-Call Button Service'),
('TKT021', 'Batman: Resurrection', '2026-07-16 14:00:00', 1, 150000.00, 'Premiere', 'DTS:X', 'Row B', NULL, NULL, 'Standard Blanket & Pillow Pack', 'On-Call Button Service'),
('TKT022', 'The Matrix 5', '2026-07-17 12:00:00', 1, 175000.00, 'Premiere', 'Dolby Atmos', 'Row A', NULL, NULL, 'Premium Velvet Pack', 'Welcome Drink + Butler'),
('TKT023', 'The Matrix 5', '2026-07-17 12:00:00', 1, 175000.00, 'Premiere', 'Dolby Atmos', 'Row A', NULL, NULL, 'Premium Velvet Pack', 'Welcome Drink + Butler'),
('TKT024', 'Avengers: Secret Wars', '2026-07-17 06:00:00', 2, 200000.00, 'Premiere', 'Dolby Atmos', 'Row VIP', NULL, NULL, 'Luxury Pack', 'Full Service Butler'),
('TKT025', 'Avengers: Secret Wars', '2026-07-17 06:00:00', 1, 200000.00, 'Premiere', 'Dolby Atmos', 'Row VIP', NULL, NULL, 'Luxury Pack', 'Full Service Butler');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_tiket`
--
ALTER TABLE `tabel_tiket`
  ADD PRIMARY KEY (`id_tiket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
