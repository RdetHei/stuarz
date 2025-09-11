-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 11 Sep 2025 pada 17.04
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
-- Struktur dari tabel `documentation`
--

CREATE TABLE `documentation` (
  `id` int(11) NOT NULL,
  `section` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `documentation`
--

INSERT INTO `documentation` (`id`, `section`, `slug`, `title`, `description`, `content`) VALUES
(1, 'Getting Started', 'installation', 'Installation', NULL, '<p>Step by step install...</p>'),
(2, 'Getting Started', 'configuration', 'Configuration', NULL, '<p>How to configure...</p>'),
(3, 'Components', 'buttons', 'Buttons', NULL, '<p>Button styles...</p>'),
(4, 'Stuarz', 'History', 'History', NULL, 'Stuarz is a site made by student for student.'),
(5, 'Stuarz', 'Owner', 'Owner', NULL, 'The owner of the Stuarz are the Demi-human named Rdethei.'),
(6, 'Components', 'Setting', 'Setting', 'This is some information about Setting', 'Just read it you dumbass.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `tasks_completed` int(11) DEFAULT 0,
  `attendance` int(11) DEFAULT 0,
  `certificates` int(11) DEFAULT 0,
  `average_grade` varchar(5) DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `student`
--

INSERT INTO `student` (`id`, `name`, `email`, `phone`, `address`, `join_date`, `class`, `profile_picture`, `tasks_completed`, `attendance`, `certificates`, `average_grade`) VALUES
(1, 'Budi Santoso', 'budi@example.com', '+62 813 9876 5432', 'Jl. Merdeka No. 45, Bandung', '2024-02-01', 'XI IPS 1', '/uploads/profiles/budi.jpg', 18, 10, 2, 'B'),
(2, 'Citra Lestari', 'citra@example.com', '+62 811 2222 3333', 'Jl. Mawar No. 7, Surabaya', '2024-03-10', 'XII IPA 3', '/uploads/profiles/citra.jpg', 30, 15, 5, 'A');

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
  `profile_picture` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `tasks_completed` int(11) DEFAULT 0,
  `attendance` int(11) DEFAULT 0,
  `certificates` int(11) DEFAULT 0,
  `average_grade` varchar(5) DEFAULT 'N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `level`, `name`, `phone`, `address`, `join_date`, `class`, `profile_picture`, `avatar`, `bio`, `tasks_completed`, `attendance`, `certificates`, `average_grade`) VALUES
(3, 'asd', 'asd@gmail.com', '$2y$10$vU3oBCWuluMeBZkzp0MIsu0Xw70zc5Gu2vPxGtmB0z0zTqFGnwLk2', 'user', 'Ran', '345634', 'Jl. Cimahi', '2025-09-10', NULL, 'https://ui-avatars.com/api/?name=asd&background=0D8ABC&color=fff', 'https://ui-avatars.com/api/?name=asd&background=0D8ABC&color=fff\r\n', 'Aku adalah siswa', 0, 0, 0, 'N/A'),
(4, 'rdet', 'rdethei05@gmail.com', '$2y$10$VkhpHKSyDTjmXYGPLLOFvuHETCU8UmHK2dsxCoJmXuH1ncYeYWJYC', 'user', NULL, '', '', '2025-09-10', '', 'https://ui-avatars.com/api/?name=rdet&background=0D8ABC&color=fff', 'https://ui-avatars.com/api/?name=rdet&background=0D8ABC&color=fff', NULL, 0, 0, 0, 'N/A'),
(5, 'arc', 'arc@gmail.com', '$2y$10$xKo8YLDAcH01pytfn/SKvOSDDrXt5h2OLFSACjXq6GRxv6GCbCsoO', 'admin', 'ARC', '203495820394', 'Jl. Cipempek', '2025-09-10', 'XI-PPLG', 'https://ui-avatars.com/api/?name=arc&background=0D8ABC&color=fff', 'https://ui-avatars.com/api/?name=arc&background=0D8ABC&color=fff', 'Aku adalah seorang siswa.', 10, 10, 10, 'N/A'),
(6, 'aff', 'aff@gmail.com', '$2y$10$6c.u5unOBAy33Zjat4cFH.bKDnpIyQyEIORWEtxXJIKhxyq2BBY.2', 'user', NULL, '', '', '2025-09-11', '', 'https://ui-avatars.com/api/?name=aff&background=0D8ABC&color=fff', 'https://ui-avatars.com/api/?name=aff&background=0D8ABC&color=fff', NULL, 0, 0, 0, 'N/A');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `documentation`
--
ALTER TABLE `documentation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT untuk tabel `documentation`
--
ALTER TABLE `documentation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
