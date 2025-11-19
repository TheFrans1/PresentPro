-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 19, 2025 at 06:53 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_smartpresencepro`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `foto_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_absensi` enum('Hadir','Terlambat','Alpha','Izin','Sakit') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pulang` enum('Tepat Waktu','Pulang Cepat','Diabsenkan Sistem') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `durasi_bekerja` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ket_status_msk` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `user_id`, `tanggal`, `jam_masuk`, `jam_keluar`, `foto_masuk`, `foto_pulang`, `status_absensi`, `status_pulang`, `durasi_bekerja`, `ket_status_msk`, `created_at`, `updated_at`) VALUES
(1, 3, '2025-11-19', NULL, NULL, NULL, NULL, 'Izin', NULL, NULL, 'acara keluarga diluar kota', '2025-11-19 03:23:02', '2025-11-19 03:23:02'),
(2, 3, '2025-11-20', NULL, NULL, NULL, NULL, 'Izin', NULL, NULL, 'acara keluarga diluar kota', '2025-11-19 03:23:02', '2025-11-19 03:23:02'),
(3, 3, '2025-11-21', NULL, NULL, NULL, NULL, 'Izin', NULL, NULL, 'acara keluarga diluar kota', '2025-11-19 03:23:02', '2025-11-19 03:23:02'),
(4, 2, '2025-11-19', '10:29:01', '10:30:35', 'absen_masuk/absn_msk_frans_theo_20251119-102901.jpeg', 'absen_keluar/absn_plg_frans_theo_20251119-103035.jpeg', 'Terlambat', 'Pulang Cepat', '00 jam 1 menit', 'Terlambat 2 jam 19 menit', '2025-11-19 03:29:01', '2025-11-19 03:30:35'),
(5, 4, '2025-11-19', '10:33:18', '21:00:00', 'absen_masuk/absn_msk_kukubima_20251119-103318.jpeg', NULL, 'Terlambat', 'Diabsenkan Sistem', '10 jam 26 menit', 'Terlambat 2 jam 23 menit', '2025-11-19 03:33:18', '2025-11-19 04:10:06'),
(6, 5, '2025-11-19', NULL, NULL, NULL, NULL, 'Sakit', NULL, NULL, 'demam', '2025-11-19 04:01:31', '2025-11-19 04:01:31'),
(7, 5, '2025-11-20', NULL, NULL, NULL, NULL, 'Sakit', NULL, NULL, 'demam', '2025-11-19 04:01:31', '2025-11-19 04:01:31'),
(8, 6, '2025-11-19', '11:06:51', '11:08:03', 'absen_masuk/absn_msk_amal_budi_pekerti_20251119-110651.jpeg', 'absen_keluar/absn_plg_amal_budi_pekerti_20251119-110803.jpeg', 'Terlambat', 'Pulang Cepat', '00 jam 1 menit', 'Terlambat 2 jam 56 menit', '2025-11-19 04:06:52', '2025-11-19 04:08:03'),
(9, 7, '2025-11-19', '11:09:08', '21:00:00', 'absen_masuk/absn_msk_frans_theo33_20251119-110908.jpeg', NULL, 'Hadir', 'Diabsenkan Sistem', '09 jam 50 menit', NULL, '2025-11-19 04:09:08', '2025-11-19 04:10:06'),
(10, 8, '2025-11-19', NULL, NULL, NULL, NULL, 'Alpha', NULL, NULL, 'Alpha / Tidak ada keterangan', '2025-11-19 04:13:29', '2025-11-19 04:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `izin`
--

CREATE TABLE `izin` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `jenis` enum('Izin','Sakit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_bukti` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_approval` enum('Pending','Disetujui','Ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `izin`
--

INSERT INTO `izin` (`id`, `user_id`, `jenis`, `tanggal_mulai`, `tanggal_selesai`, `keterangan`, `file_bukti`, `status_approval`, `tanggal_pengajuan`, `created_at`, `updated_at`) VALUES
(1, 3, 'Izin', '2025-11-19', '2025-11-21', 'acara keluarga diluar kota', 'izin_cady_t_1763522540.jpg', 'Disetujui', '2025-11-19 03:22:21', '2025-11-19 03:22:21', '2025-11-19 03:23:02'),
(2, 5, 'Sakit', '2025-11-19', '2025-11-20', 'demam', 'sakit_gopal_ganteng_1763524833.jpg', 'Disetujui', '2025-11-19 04:00:33', '2025-11-19 04:00:33', '2025-11-19 04:01:31');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kerja`
--

CREATE TABLE `jadwal_kerja` (
  `id` bigint UNSIGNED NOT NULL,
  `hari` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_keluar` time NOT NULL,
  `toleransi` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kerja`
--

INSERT INTO `jadwal_kerja` (`id`, `hari`, `jam_masuk`, `jam_keluar`, `toleransi`, `created_at`, `updated_at`) VALUES
(1, 'Senin', '11:05:00', '11:10:00', 3, NULL, '2025-11-19 04:08:26'),
(2, 'Selasa', '08:00:00', '16:00:00', 10, NULL, '2025-11-19 04:08:26'),
(3, 'Rabu', '11:07:00', '11:11:00', 3, NULL, '2025-11-19 04:08:26'),
(4, 'Kamis', '08:00:00', '16:00:00', 10, NULL, '2025-11-19 04:08:26'),
(5, 'Jumat', '08:00:00', '16:00:00', 10, NULL, '2025-11-19 04:08:26'),
(6, 'Sabtu', '00:00:00', '00:00:00', 0, NULL, '2025-11-19 04:08:26'),
(7, 'Minggu', '00:00:00', '00:00:00', 0, NULL, '2025-11-19 04:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kalender_kerja`
--

CREATE TABLE `kalender_kerja` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('Libur Nasional','Cuti Bersama') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_09_151416_create_izin_table', 1),
(5, '2025_11_09_151417_create_jadwal_kerja_table', 1),
(6, '2025_11_09_151417_create_kalender_kerja_table', 1),
(7, '2025_11_14_161155_create_absensis_table', 1),
(8, '2025_11_18_113633_add_absensi_id_to_izin_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` enum('Divisi IT','Keuangan','HRD','Pemasaran','Operasional','Administrator') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  `role` enum('admin','karyawan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `nik`, `username`, `jabatan`, `alamat`, `no_hp`, `password`, `foto`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@smartpresence.com', '0000', 'admin', 'Administrator', 'Kantor Pusat', '08123456789', '$2y$12$TS1vskO9pQXR97BcRhq8iukFViIsIyynoP2fJyYkbH9/8AUgf0EPm', 'default.png', 'admin', 'aktif', NULL, '2025-11-19 02:14:24', '2025-11-19 02:14:24'),
(2, 'frans theo', 'sembiringpandia8655@gmail.com', '0001', '0001', 'Keuangan', 'gang.serasi', '081269392357', '$2y$12$pzoiCNbqW2JKMF7V0/YwiOelhEYI9o6oKkT4JZ7JTwDI74E7nAsOu', 'default.png', 'karyawan', 'aktif', NULL, '2025-11-19 02:15:40', '2025-11-19 03:20:00'),
(3, 'cady t', 'sembiringpandia86555@gmail.com', '0002', '0002', 'Keuangan', 'gang.serasi3', '081269392357', '$2y$12$IgU0V7LMVEyebhiBM0HElePeDXP/469Lsa04drzHMQK57yMoVdcRq', 'default.png', 'karyawan', 'aktif', NULL, '2025-11-19 03:19:06', '2025-11-19 03:19:48'),
(4, 'kukubima', 'sembiringpandia865ss5@gmail.com', '0003', '0003', 'HRD', 'gang.serasi44', '081269392357', '$2y$12$QMJtxdq4xjKwDN3Ni9aQpe5tCYj6aLAfEltiBFR3CCIxoyPLNSj.G', 'default.png', 'karyawan', 'aktif', NULL, '2025-11-19 03:31:45', '2025-11-19 03:31:45'),
(5, 'gopal ganteng', 'sembiringpandia865544@gmail.com', '4321', '4321', 'HRD', 'gang.serasi33', '081269392357', '$2y$12$NZpGRbEiG89q8ZQsyveUAed3LVAl7QjiqGYZNtZ1vUyyC0HS9dGgO', 'default.png', 'karyawan', 'aktif', NULL, '2025-11-19 03:58:20', '2025-11-19 03:58:58'),
(6, 'amal budi pekerti', 'sembiringpandia8622255@gmail.com', '1212', '1212', 'HRD', 'gang.serasi', '081269392357', '$2y$12$iJ3qJUPTfsC8TMTxkrgp3O.iaE9yBjXJAQeBiPQA.Cq4ENjeSTCZC', 'default.png', 'karyawan', 'aktif', NULL, '2025-11-19 04:05:11', '2025-11-19 04:05:11'),
(7, 'frans theo33', 'sembiringpandia86qqq55@gmail.com', '3333', '3333', 'Pemasaran', 'gang.serasi', '081269392357', '$2y$12$nnZ7WL2iv8xswg.V7lwCZOTCaCtDQlwCtBMLbD9SHPMVeFbtsfVCe', 'default.png', 'karyawan', 'aktif', NULL, '2025-11-19 04:08:51', '2025-11-19 04:08:51'),
(8, 'theo3332', 'sembiringpandia833655@gmail.com', '5555', '5555', 'HRD', 'gang.serasi', '081269392357', '$2y$12$XPQYR9BiD5DxBIX2HD05pOppqCGI3FizCObJLYTMDLCMNfI37k1t2', 'default.png', 'karyawan', 'aktif', NULL, '2025-11-19 04:11:41', '2025-11-19 04:11:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensi_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `izin`
--
ALTER TABLE `izin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `izin_user_id_foreign` (`user_id`);

--
-- Indexes for table `jadwal_kerja`
--
ALTER TABLE `jadwal_kerja`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kalender_kerja`
--
ALTER TABLE `kalender_kerja`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kalender_kerja_tanggal_unique` (`tanggal`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `izin`
--
ALTER TABLE `izin`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_kerja`
--
ALTER TABLE `jadwal_kerja`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kalender_kerja`
--
ALTER TABLE `kalender_kerja`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `izin`
--
ALTER TABLE `izin`
  ADD CONSTRAINT `izin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
