-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2026 at 03:13 PM
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
-- Database: `anvo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id_admin`, `nama_admin`, `email`, `password`, `no_hp`, `role`) VALUES
(1, 'Pak Bakir', 'bakir@anvo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+628111222333', 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `id_kereta` int(11) NOT NULL,
  `id_koridor` int(11) DEFAULT NULL,
  `stasiun_asal` varchar(100) NOT NULL,
  `stasiun_tujuan` varchar(100) NOT NULL,
  `stasiun_transit` text DEFAULT NULL,
  `jam_berangkat` time NOT NULL,
  `jam_tiba` time NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `jenis_jadwal` enum('Harian','Khusus') DEFAULT 'Harian'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_kereta`, `id_koridor`, `stasiun_asal`, `stasiun_tujuan`, `stasiun_transit`, `jam_berangkat`, `jam_tiba`, `tanggal_mulai`, `tanggal_akhir`, `harga`, `jenis_jadwal`) VALUES
(7, 1, 1, 'Cirebon (CN) - Cirebon', 'Tawang (SMT) - Semarang', '[\"Cirebon (CN) - Cirebon\",\"Tegalluar (TGL) - Bandung\",\"Halim (HLM) - Jakarta\",\"Tawang (SMT) - Semarang\"]', '08:00:00', '13:00:00', '2026-09-22', '2026-09-22', 150000.00, 'Harian'),
(8, 1, 1, 'Cirebon (CN) - Cirebon', 'Tawang (SMT) - Semarang', '[\"Cirebon (CN) - Cirebon\",\"Halim (HLM) - Jakarta\",\"Tawang (SMT) - Semarang\"]', '12:00:00', '13:00:00', '2026-09-23', '2026-11-30', 500000.00, 'Harian'),
(9, 2, 1, 'Cirebon (CN) - Cirebon', 'Tawang (SMT) - Semarang', '[\"Cirebon (CN) - Cirebon\",\"Tegalluar (TGL) - Bandung\",\"Tawang (SMT) - Semarang\"]', '12:21:00', '12:32:00', '0026-04-23', '2026-04-25', 50000.00, 'Harian');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_penugasan_kru`
--

CREATE TABLE `jadwal_penugasan_kru` (
  `id_penugasan` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_kru` int(11) NOT NULL,
  `tanggal_tugas` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_penugasan_kru`
--

INSERT INTO `jadwal_penugasan_kru` (`id_penugasan`, `id_jadwal`, `id_kru`, `tanggal_tugas`) VALUES
(8, 7, 1, '2026-09-22'),
(9, 8, 3, '2026-09-30'),
(10, 8, 2, '2026-09-30'),
(11, 8, 1, '2026-09-30'),
(14, 8, 3, '2026-09-23'),
(15, 8, 2, '2026-09-23'),
(19, 8, 3, '2026-09-25'),
(20, 8, 2, '2026-09-25'),
(21, 8, 1, '2026-09-25');

-- --------------------------------------------------------

--
-- Table structure for table `kereta`
--

CREATE TABLE `kereta` (
  `id_kereta` int(11) NOT NULL,
  `id_koridor` int(11) DEFAULT NULL,
  `nama_kereta` varchar(100) NOT NULL,
  `jenis_kelas` text NOT NULL,
  `layout_kursi` text NOT NULL,
  `jenis_mesin` varchar(100) DEFAULT 'Electric Multiple Unit (EMU-Gen4)',
  `kecepatan_maksimal` int(11) DEFAULT 350,
  `status_operasional` enum('Aktif','Standby','Maintenance','Rusak','Arsip/Gudang') DEFAULT 'Aktif',
  `kapasitas_kursi` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kereta`
--

INSERT INTO `kereta` (`id_kereta`, `id_koridor`, `nama_kereta`, `jenis_kelas`, `layout_kursi`, `jenis_mesin`, `kecepatan_maksimal`, `status_operasional`, `kapasitas_kursi`) VALUES
(1, 1, 'G101 Antasena Evo', '[\"Executive Prime\", \"Luminary Capsule\"]', '[\"Executive Prime (2-2)\", \"Capsule Unit (1-1)\"]', 'Electric Multiple Unit (EMU-Gen4)', 350, 'Aktif', 250),
(2, 1, 'G102 Luminary Express', '[\"VVIP Skybox Suite\"]', '[\"Private Suite (Lounge)\"]', 'Maglev Dual-Stator', 400, 'Aktif', 180),
(3, 3, 'Armada Baru nihh', '[\"Executive Prime\",\"Luminary Capsule\",\"VVIP Skybox Suite\"]', '[\"Executive Prime (2-2)\",\"Capsule Unit (1-1)\",\"Private Suite (Lounge)\"]', 'Electric Multiple Unit (EMU-Gen4)', 350, 'Aktif', 100),
(4, 2, 'Armada Baru nih 2', '[\"Luminary Capsule\",\"Executive Prime\"]', '[\"Capsule Unit (1-1)\",\"Executive Prime (2-2)\"]', 'Electric Multiple Unit (EMU-Gen4)', 350, 'Maintenance', 100);

-- --------------------------------------------------------

--
-- Table structure for table `koridor`
--

CREATE TABLE `koridor` (
  `id_koridor` int(11) NOT NULL,
  `nama_koridor` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status_koridor` enum('Aktif','Non-Aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koridor`
--

INSERT INTO `koridor` (`id_koridor`, `nama_koridor`, `keterangan`, `status_koridor`) VALUES
(1, 'Jakarta Bandung Express', 'Koridor utama kereta cepat Jakarta - Bandung', 'Aktif'),
(2, 'Java Intercity Line', 'Jalur penghubung lintas utara Jawa', 'Aktif'),
(3, 'Surabaya Pasuruan', 'SUPAS', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `koridor_stasiun`
--

CREATE TABLE `koridor_stasiun` (
  `id_koridor_stasiun` int(11) NOT NULL,
  `id_koridor` int(11) NOT NULL,
  `nama_stasiun` varchar(120) NOT NULL,
  `urutan` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koridor_stasiun`
--

INSERT INTO `koridor_stasiun` (`id_koridor_stasiun`, `id_koridor`, `nama_stasiun`, `urutan`) VALUES
(1, 1, 'Halim (HLM) - Jakarta', 3),
(2, 1, 'Tegalluar (TGL) - Bandung', 2),
(3, 1, 'Cirebon (CN) - Cirebon', 1),
(4, 1, 'Makassar (MKS) - Makassar', 4),
(5, 3, 'Gubeng (SBY) - Surabaya', 1),
(6, 3, 'Malang (MLG) - Malang', 2),
(7, 3, 'Denpasar (DPS) - Bali', 3),
(8, 1, 'Tawang (SMT) - Semarang', 5);

-- --------------------------------------------------------

--
-- Table structure for table `master_kru`
--

CREATE TABLE `master_kru` (
  `id_kru` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `pendidikan_terakhir` varchar(50) DEFAULT NULL,
  `alamat_lengkap` text DEFAULT NULL,
  `status_pernikahan` enum('Belum Menikah','Menikah','Cerai') DEFAULT 'Belum Menikah',
  `kontak_darurat` varchar(50) DEFAULT NULL,
  `riwayat_penyakit` text DEFAULT NULL,
  `posisi` enum('Masinis','Kondektur','Teknisi','Pramugari/a') NOT NULL,
  `status_kru` enum('Aktif','Cuti','Sakit','Resign') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_kru`
--

INSERT INTO `master_kru` (`id_kru`, `nip`, `nik`, `nama_lengkap`, `tanggal_lahir`, `agama`, `foto`, `email`, `no_telepon`, `pendidikan_terakhir`, `alamat_lengkap`, `status_pernikahan`, `kontak_darurat`, `riwayat_penyakit`, `posisi`, `status_kru`) VALUES
(1, 'K-00000001', '3515153011050100', 'Adrian Syahputra', '0005-11-30', 'Islam', 'kru_1790172688.png', 'syahputraadrian995@gmail.com', '085176923011', 'D3 Perkeretaapian', 'Sidokerto, Buduran, Sidoarjo', 'Belum Menikah', '089682212995', '-', 'Masinis', 'Aktif'),
(2, 'K-00000002', '3515153011050101', 'Adrian Alendrina', '0005-11-30', 'Islam', 'kru_1790172679.png', 'syahputraadrian243@gmail.com', '085176923011', 'D3 Perkeretaapian', 'Sidokerto, Buduran, Sidoarjo', 'Belum Menikah', '089682212995', '-', 'Kondektur', 'Aktif'),
(3, 'K-00000003', '3515153011050102', 'Adrian Aldinata', '0005-11-30', 'Islam', 'kru_1790172768.png', 'lyrdadr.business@gmail.com', '085176923011', 'S1 Ekonomi', 'Sidokerto, Buduran, Sidoarjo', 'Menikah', '089682212995', '-', 'Pramugari/a', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id_pembayaran` int(11) NOT NULL,
  `id_reservasi` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `jumlah_tagihan` decimal(10,2) NOT NULL,
  `jumlah_bayar` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `status_bayar` enum('pending','lunas','gagal') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penumpangs`
--

CREATE TABLE `penumpangs` (
  `id_penumpang` int(11) NOT NULL,
  `id_reservasi` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `nomor_kursi` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservasis`
--

CREATE TABLE `reservasis` (
  `id_reservasi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_stasiun_asal` int(11) NOT NULL,
  `id_stasiun_tujuan` int(11) NOT NULL,
  `tanggal_keberangkatan` date NOT NULL,
  `jumlah_tiket` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stasiun`
--

CREATE TABLE `stasiun` (
  `id_stasiun` int(11) NOT NULL,
  `kode_stasiun` varchar(10) NOT NULL,
  `nama_stasiun` varchar(100) NOT NULL,
  `kota` varchar(50) NOT NULL,
  `julukan` varchar(100) DEFAULT 'Kota Metropolitan',
  `image_url` varchar(255) DEFAULT 'https://images.pexels.com/photos/3162012/pexels-photo-3162012.jpeg',
  `is_top_destination` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stasiun`
--

INSERT INTO `stasiun` (`id_stasiun`, `kode_stasiun`, `nama_stasiun`, `kota`, `julukan`, `image_url`, `is_top_destination`) VALUES
(1, 'HLM', 'Halim (HLM) - Jakarta', 'Jakarta', 'Kota Metropolitan', 'https://commons.wikimedia.org/wiki/Special:FilePath/Monumen_Nasional.jpg?width=600', 1),
(2, 'TGL', 'Tegalluar (TGL) - Bandung', 'Bandung', 'Kota Kembang', 'https://commons.wikimedia.org/wiki/Special:FilePath/Gedung_Sate_Bandung_Jawa_Barat.jpg?width=600', 1),
(3, 'SMT', 'Tawang (SMT) - Semarang', 'Semarang', 'Kota Atlas', 'https://commons.wikimedia.org/wiki/Special:FilePath/Masjid_Agung_Jawa_Tengah.jpg?width=600', 1),
(4, 'SBY', 'Gubeng (SBY) - Surabaya', 'Surabaya', 'Kota Pahlawan', 'https://commons.wikimedia.org/wiki/Special:FilePath/Tugu_Pahlawan_Surabaya.jpg?width=600', 1),
(5, 'YOG', 'Tugu (YK) - Yogyakarta', 'Yogyakarta', 'Kota Pelajar', 'https://commons.wikimedia.org/wiki/Special:FilePath/Prambanan.jpg?width=600', 1),
(6, 'MLG', 'Malang (MLG) - Malang', 'Malang', 'Kota Apel', 'https://commons.wikimedia.org/wiki/Special:FilePath/Balai_Kota_Malang.jpg?width=600', 1),
(7, 'MDN', 'Medan (MDN) - Medan', 'Medan', 'Kota Melayu Deli', 'https://commons.wikimedia.org/wiki/Special:FilePath/Istana_Maimun.jpg?width=600', 1),
(8, 'PLM', 'Kertapati (PLM) - Palembang', 'Palembang', 'Bumi Sriwijaya', 'https://commons.wikimedia.org/wiki/Special:FilePath/Ampera_Bridge.jpg?width=600', 1),
(9, 'DPS', 'Denpasar (DPS) - Bali', 'Bali', 'Pulau Dewata', 'https://commons.wikimedia.org/wiki/Special:FilePath/Tanah_Lot.jpg?width=600', 1),
(10, 'MKS', 'Makassar (MKS) - Makassar', 'Makassar', 'Kota Daeng', 'https://commons.wikimedia.org/wiki/Special:FilePath/Pantai_Losari.jpg?width=600', 1),
(11, 'BDJ', 'Banjarmasin (BDJ) - Banjarmasin', 'Banjarmasin', 'Kota Seribu Sungai', 'https://commons.wikimedia.org/wiki/Special:FilePath/Pasar_Terapung.jpg?width=600', 1),
(12, 'PNK', 'Pontianak (PNK) - Pontianak', 'Pontianak', 'Kota Khatulistiwa', 'https://commons.wikimedia.org/wiki/Special:FilePath/Tugu_Khatulistiwa.jpg?width=600', 1),
(13, 'PDG', 'Padang (PDG) - Padang', 'Padang', 'Kota Tercinta', 'https://commons.wikimedia.org/wiki/Special:FilePath/Istano_Basa_Pagaruyung.jpg?width=600', 1),
(14, 'AMP', 'Ampenan (AMP) - Mataram', 'Mataram', 'Pulau Seribu Masjid', 'https://commons.wikimedia.org/wiki/Special:FilePath/Mount_Rinjani.jpg?width=600', 1),
(15, 'JAP', 'Jayapura (JAP) - Jayapura', 'Jayapura', 'Kota Port Numbay', 'https://commons.wikimedia.org/wiki/Special:FilePath/Jembatan_Youtefa.jpg?width=600', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `reset_token` varchar(6) DEFAULT NULL,
  `token_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `tanggal_lahir`, `nik`, `email`, `password`, `no_hp`, `reset_token`, `token_expire`) VALUES
(3, 'Adrian Syahputra', '2005-11-30', '3515153011050002', 'syahputraadrian5011@gmail.com', '$2y$10$whOeG9BsnAN/YaachvtmROPp6PMS92z8RNvH/cbKZ6Y8IJhhJsYpu', '+6285176923011', NULL, NULL),
(6, 'Adrian Syahputra', '2006-11-30', '3515153011050020', 'lyrdadr.business@gmail.com', '$2y$10$1OPFjJkRmPPthzDSduzYMuYqMgSinWRNSG5J/0GhJWHjZMCET8mTS', '+6289682212995', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_kereta` (`id_kereta`);

--
-- Indexes for table `jadwal_penugasan_kru`
--
ALTER TABLE `jadwal_penugasan_kru`
  ADD PRIMARY KEY (`id_penugasan`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_kru` (`id_kru`);

--
-- Indexes for table `kereta`
--
ALTER TABLE `kereta`
  ADD PRIMARY KEY (`id_kereta`),
  ADD KEY `fk_kereta_koridor` (`id_koridor`);

--
-- Indexes for table `koridor`
--
ALTER TABLE `koridor`
  ADD PRIMARY KEY (`id_koridor`);

--
-- Indexes for table `koridor_stasiun`
--
ALTER TABLE `koridor_stasiun`
  ADD PRIMARY KEY (`id_koridor_stasiun`),
  ADD KEY `id_koridor` (`id_koridor`);

--
-- Indexes for table `master_kru`
--
ALTER TABLE `master_kru`
  ADD PRIMARY KEY (`id_kru`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_reservasi` (`id_reservasi`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `penumpangs`
--
ALTER TABLE `penumpangs`
  ADD PRIMARY KEY (`id_penumpang`),
  ADD KEY `id_reservasi` (`id_reservasi`);

--
-- Indexes for table `reservasis`
--
ALTER TABLE `reservasis`
  ADD PRIMARY KEY (`id_reservasi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `reservasis_ibfk_3` (`id_jadwal`),
  ADD KEY `reservasis_ibfk_4` (`id_stasiun_asal`),
  ADD KEY `reservasis_ibfk_5` (`id_stasiun_tujuan`);

--
-- Indexes for table `stasiun`
--
ALTER TABLE `stasiun`
  ADD PRIMARY KEY (`id_stasiun`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `idx_no_hp` (`no_hp`),
  ADD UNIQUE KEY `idx_nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jadwal_penugasan_kru`
--
ALTER TABLE `jadwal_penugasan_kru`
  MODIFY `id_penugasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `kereta`
--
ALTER TABLE `kereta`
  MODIFY `id_kereta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `koridor`
--
ALTER TABLE `koridor`
  MODIFY `id_koridor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `koridor_stasiun`
--
ALTER TABLE `koridor_stasiun`
  MODIFY `id_koridor_stasiun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `master_kru`
--
ALTER TABLE `master_kru`
  MODIFY `id_kru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penumpangs`
--
ALTER TABLE `penumpangs`
  MODIFY `id_penumpang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservasis`
--
ALTER TABLE `reservasis`
  MODIFY `id_reservasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stasiun`
--
ALTER TABLE `stasiun`
  MODIFY `id_stasiun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_kereta`) REFERENCES `kereta` (`id_kereta`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_penugasan_kru`
--
ALTER TABLE `jadwal_penugasan_kru`
  ADD CONSTRAINT `jadwal_penugasan_kru_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_penugasan_kru_ibfk_2` FOREIGN KEY (`id_kru`) REFERENCES `master_kru` (`id_kru`) ON DELETE CASCADE;

--
-- Constraints for table `kereta`
--
ALTER TABLE `kereta`
  ADD CONSTRAINT `fk_kereta_koridor` FOREIGN KEY (`id_koridor`) REFERENCES `koridor` (`id_koridor`) ON DELETE SET NULL;

--
-- Constraints for table `koridor_stasiun`
--
ALTER TABLE `koridor_stasiun`
  ADD CONSTRAINT `koridor_stasiun_ibfk_1` FOREIGN KEY (`id_koridor`) REFERENCES `koridor` (`id_koridor`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_ibfk_1` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasis` (`id_reservasi`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayarans_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admins` (`id_admin`) ON DELETE SET NULL;

--
-- Constraints for table `penumpangs`
--
ALTER TABLE `penumpangs`
  ADD CONSTRAINT `penumpangs_ibfk_1` FOREIGN KEY (`id_reservasi`) REFERENCES `reservasis` (`id_reservasi`) ON DELETE CASCADE;

--
-- Constraints for table `reservasis`
--
ALTER TABLE `reservasis`
  ADD CONSTRAINT `reservasis_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasis_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admins` (`id_admin`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservasis_ibfk_3` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservasis_ibfk_4` FOREIGN KEY (`id_stasiun_asal`) REFERENCES `stasiun` (`id_stasiun`),
  ADD CONSTRAINT `reservasis_ibfk_5` FOREIGN KEY (`id_stasiun_tujuan`) REFERENCES `stasiun` (`id_stasiun`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
