-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Sep 2025 pada 01.21
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stuarz`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('user','admin') DEFAULT 'user',
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `tasks_completed` int(11) DEFAULT 0,
  `attendance` int(11) DEFAULT 0,
  `certificates` int(11) DEFAULT 0,
  `average_grade` varchar(5) DEFAULT 'N/A',
  `banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `level`, `name`, `phone`, `address`, `join_date`, `class`, `avatar`, `bio`, `tasks_completed`, `attendance`, `certificates`, `average_grade`, `banner`) VALUES
(3, 'asd', 'asd@gmail.com', '$2y$10$rwPvW6jtP1pld95B9rYAG.UKsrGEFoQ9zXgJbV5uQcM8YeXno//82', 'user', 'Ran', '345634', 'Jl. Cimahi', '2025-09-10', NULL, 'uploads/avatars/1757854945_4bc40157c5f8.png', 'Aku adalah siswa', 0, 0, 0, 'N/A', NULL),
(4, 'rdet', 'rdethei05@gmail.com', '$2y$10$kWNXnq7jzBLW0MRupqrzIuza/eU2xttNuIx1lWNiIP1oNpz5Z4chC', 'user', NULL, '', '', '2025-09-10', '', 'uploads/avatars/1757857397_bdbd449028d7.jpg', NULL, 0, 0, 0, 'N/A', NULL),
(5, 'arch', 'arch@gmail.com', '$2y$10$z4dg8mlD0.v6G5J0Jen76.ZiEbMpUftM3kkoNO2Tg43KxOkizZxay', 'admin', 'Arch', '203495820394', 'Jl. Cipempek', '2025-09-10', 'XI-PPLG', 'uploads/avatars/1757860772_7544a05e5b7a.jpg', 'Aku adalah admin dari Stuarz.', 10, 10, 10, 'N/A', 'uploads/banners/1757890976_banner_90bed5b83e71.png'),
(6, 'afs', 'aff@gmail.com', '$2y$10$3jFG/zbEyc4zI62wYZE45.nXvaOzsHGFYg1J8U8s0MlKJrRiZn0xm', 'user', NULL, '', '', '2025-09-11', '', 'uploads/avatars/1757849576_e40e06245f3f.png', NULL, 0, 0, 0, 'N/A', 'uploads/banners/1757906728_banner_37ccb9789f2e.png'),
(16, 'sas', 'sad@gmail.com', '$2y$10$uM8FKwdRfPrJZFpiybmi/e.TExdCjWDrTX8.3YoWMyJfdpMpzSFHq', 'user', NULL, NULL, NULL, '2025-09-14', NULL, 'uploads/avatars/1757852338_3c1656f519c1.png', NULL, 0, 0, 0, 'N/A', 'uploads/banners/1757930361_banner_ce551ff6404d.png'),
(20, 'ag', 'ag@gmail.com', '$2y$10$x3kdRh6FhrRC.1gGo6Wd/.o4EID9U1M8zLr41kfVzRmqdUhwJ3w8S', 'user', NULL, NULL, NULL, '2025-09-15', NULL, 'uploads/avatars/1757932462_8673d5f36559.png', NULL, 0, 0, 0, 'N/A', 'uploads/banners/1757932462_banner_cbd29e5c51f0.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
