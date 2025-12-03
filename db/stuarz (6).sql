-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 02:33 AM
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
-- Database: `stuarz`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `photo`, `created_by`, `created_at`) VALUES
(3, 'Contoh sahaja', 'ini hanya contoh', 'uploads/announcements/1763103008_d66fecb8.png', 1, '2025-11-14 06:50:08'),
(4, 'sdfasdf', 'sfasdfsadfa', 'uploads/announcements/1763605836_155dabbf.png', 1, '2025-11-20 02:30:36'),
(5, 'sdfg', 'sdfgsd', 'uploads/announcements/1763606496_f8f3ebbb.jpg', 1, '2025-11-20 02:41:36'),
(6, 'asdfas', 'sdfasadsfgasddfaasdfas', 'uploads/announcements/1764203045_1be3ce1f.png', 1, '2025-11-20 02:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `announcement_comments`
--

CREATE TABLE `announcement_comments` (
  `id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archives`
--

CREATE TABLE `archives` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `report_type` enum('attendance','grades','tasks','summary') NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expire_at` timestamp NOT NULL DEFAULT (current_timestamp() + interval 1 year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','late','absent','sick','excused') DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `class_id`, `user_id`, `date`, `check_in`, `check_out`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(6, 7, 1, '2025-11-06', '17:48:49', NULL, 'late', NULL, '2025-11-06 10:48:49', NULL),
(7, 7, 1, '2025-11-07', '19:26:10', '19:26:17', 'late', NULL, '2025-11-07 12:26:10', '2025-11-07 12:26:17'),
(8, 7, 1, '2025-11-08', '17:15:32', '17:15:38', 'late', NULL, '2025-11-08 10:15:32', '2025-11-08 10:15:38'),
(9, 7, 1, '2025-11-17', '11:04:32', NULL, 'late', NULL, '2025-11-17 04:04:32', NULL),
(10, 7, 1, '2025-11-27', '07:58:26', '12:45:49', 'present', NULL, '2025-11-27 00:58:26', '2025-11-27 05:45:49'),
(11, 7, 4, '2025-11-27', '10:39:46', '10:39:53', 'present', NULL, '2025-11-27 03:39:46', '2025-11-27 03:40:32'),
(12, 7, 1, '2025-11-28', '13:14:22', '13:14:26', 'late', NULL, '2025-11-28 06:14:22', '2025-11-28 06:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `average_grade`
--

CREATE TABLE `average_grade` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `grade` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `issued_by` varchar(255) DEFAULT NULL,
  `issued_at` date DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `title`, `description`, `issued_by`, `issued_at`, `file_path`, `created_at`) VALUES
(15, 1, 'asfdas', 'fasdf', 'asdf', '2025-11-27', 'uploads/certificates/1764203308_certificate_69279b2cb656b.jpg', '2025-11-27 00:28:28');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `code`, `description`, `created_by`, `created_at`) VALUES
(7, 'Arch', '12345', 'asdf', 1, '2025-11-06 09:29:56'),
(8, 'XII-PPLG', '43CC59', '', 1, '2025-11-27 03:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `class_members`
--

CREATE TABLE `class_members` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('student','teacher','admin') DEFAULT 'student',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_members`
--

INSERT INTO `class_members` (`id`, `class_id`, `user_id`, `role`, `joined_at`) VALUES
(6, 7, 3, 'student', '2025-11-24 04:11:29'),
(7, 7, 4, 'student', '2025-11-27 01:44:22'),
(8, 8, 1, 'admin', '2025-11-27 03:38:19'),
(9, 8, 6, 'student', '2025-11-28 06:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `documentation`
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
-- Dumping data for table `documentation`
--

INSERT INTO `documentation` (`id`, `section`, `slug`, `title`, `description`, `content`) VALUES
(1, 'Stuarz', 'starting-with-what-is-stuarz', 'Starting with what is Stuarz?', 'What is Stuarz actually is?', 'Stuarz adalah sebuah website pendidikan yang sangat mendukung siswa dan siswi yang ingin berkembang bersama dengan teknologi. Tidak ada yang bisa menghentikan siswa/siswi untuk belajar kecuali kematian, maka dari itu, Stuarz ada untuk memenuhi seluruh kebutuhan itu. Dengan Stuarz, pembelajaran jarak jauh pun dimungkinkan, tidak ada yang tidak bisa dilakukan oleh Stuarz terutama dengan pendidikan.Dan kami berhasil mengembangkan Stuarz ini adalah dengan kecintaan kami kepada pengetahuan dan kepada para pelajar yang tak senantiasa berhenti untuk terus menuntut ilmu yang banyak.'),
(2, 'Stuarz', 'how-stuarz-actually-work', 'How Stuarz actually work?', 'How does Stuarz work?', 'Stuarz akan bekerja dengan bantuan dari para guru-guru, mahasiswa, pelajar dari tingkatan sd-smp-sma/smk yang ingin menuntut ilmu dengan menyenangkan dan modern.\r\nPernahkah terpikirkan dalam benakmu bagaimana seseorang belajar dan dapat melihat statistik keberhasilan dan kerajinannya dalam bentuk angka dan grafik?\r\nTidak pernah ada yang bisa memikirkannya selama ini, maka dari itu, saya selaku pelajar yang juga memiliki keinginan untuk memajukan dunia pendidikan membuat website bernama Stuarz ini untuk perkembangan dan juga mengembangkan.'),
(3, 'Actor', 'who-is-the-actor-for-stuarz', 'Who is the actor for Stuarz?', 'Who are the actor?', 'Stuarz mungkin adalah sebuah website pendidikan, tapi Stuarz tanpa actor dari dunia pendidikan hanyalah hal mati, maka dari itu, dengan bantuan dari para siswa/siswi, guru-guru dan para mahasiswa, saya ingin membuat Stuarz ini menjadi tempat berbagai ilmu pengetahuan dan juga saranan komunikasi antar sesama penuntut ilmu.\r\nMarilah kita buat Stuarz menjadi tempat yang ramai.');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `score` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `user_id`, `class_id`, `subject_id`, `task_id`, `score`, `created_at`) VALUES
(1, 3, 8, 6, 9, 100.00, '2025-12-03 01:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `author` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `category`, `thumbnail`, `author`, `created_at`) VALUES
(1, 'Tangan', 'Aku juga tidak tahu apa yang sebenarnya terjadi.', 'Ilmu Pengetahuan', 'uploads/news/1762654677_Shorekeeper_Icon_.jfif', 'arch', '2025-09-17 08:24:00'),
(5, 'Kaki', 'asdfasd', 'Pendidikan', 'uploads/news/1763103129_afb1181e-b7fb-4402-8b21-8c3b0640a70a.jpg', 'arch', '2025-11-14 00:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('announcement','task','message') NOT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `reference_id`, `message`, `is_read`, `created_at`) VALUES
(16, 1, '', 7, 'Akun diperbarui: asd', 1, '2025-11-27 06:14:55'),
(17, 1, '', 1, 'Akun diperbarui: arch', 1, '2025-11-28 07:49:14'),
(18, 1, '', 1, 'Akun diperbarui: arch', 1, '2025-11-28 07:49:27'),
(20, 2, '', 2, 'Akun diperbarui: Kate', 1, '2025-12-03 01:03:00'),
(21, 2, 'task', 9, 'Tugas baru: sdfas', 0, '2025-12-03 01:21:23'),
(22, 3, 'task', 9, 'Tugas baru: sdfas', 1, '2025-12-03 01:21:23'),
(23, 4, 'task', 9, 'Tugas baru: sdfas', 0, '2025-12-03 01:21:23'),
(24, 1, 'task', 9, 'Restu Rudiansyah mengumpulkan tugas (percobaan #1).', 0, '2025-12-03 01:27:12'),
(25, 3, 'task', 9, 'Status tugas Anda telah diperbarui.', 0, '2025-12-03 01:27:52'),
(26, 3, 'task', 9, 'Status tugas Anda telah diperbarui.', 0, '2025-12-03 01:28:00'),
(27, 3, 'task', 9, 'Nilai untuk \"sdfas\" sudah tersedia.', 0, '2025-12-03 01:28:13'),
(28, 3, 'task', 9, 'Nilai tugas Anda sudah tersedia.', 0, '2025-12-03 01:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `class` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `day` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `class`, `subject`, `teacher_id`, `class_id`, `day`, `start_time`, `end_time`) VALUES
(17, 'Lab Komputer', 'PAI', 2, 7, 'Senin', '08:09:00', '08:11:00'),
(18, 'Lab Komputer', 'MTK', 1, 7, 'Senin', '09:02:00', '09:03:00'),
(19, 'Lab aja ab', 'PWPB', 5, 7, 'Selasa', '09:04:00', '12:07:00'),
(26, 'Kelas aja anjir, ngapain di lab', 'MTK', 5, 8, 'Sabtu', '13:32:00', '17:32:00'),
(27, 'asdf', 'MTK', 2, 8, 'Senin', '13:50:00', '17:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `description`, `teacher_id`) VALUES
(6, 'MTK', 'Kari', 1),
(7, 'PAI', 'Agama adalah sesuatu', 2),
(8, 'PWPB', 'asdfas', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tasks_completed`
--

CREATE TABLE `tasks_completed` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `approval_required` tinyint(1) NOT NULL DEFAULT 0,
  `grading_rubric` longtext DEFAULT NULL,
  `max_attempts` int(11) NOT NULL DEFAULT 1,
  `reminder_at` datetime DEFAULT NULL,
  `reminder_sent_at` datetime DEFAULT NULL,
  `allow_late` tinyint(1) NOT NULL DEFAULT 0,
  `late_deadline` datetime DEFAULT NULL,
  `workflow_state` enum('draft','published','in_review','closed') NOT NULL DEFAULT 'published',
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks_completed`
--

INSERT INTO `tasks_completed` (`id`, `user_id`, `title`, `description`, `status`, `approval_required`, `grading_rubric`, `max_attempts`, `reminder_at`, `reminder_sent_at`, `allow_late`, `late_deadline`, `workflow_state`, `deadline`, `created_at`, `class_id`, `subject_id`) VALUES
(9, 1, 'sdfas', 'asdfasd', 'pending', 0, NULL, 1, NULL, NULL, 0, NULL, 'published', '2025-12-03', '2025-12-03 01:21:23', 8, 6);

-- --------------------------------------------------------

--
-- Table structure for table `task_reminders`
--

CREATE TABLE `task_reminders` (
  `id` int(10) UNSIGNED NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reminder_type` enum('deadline','revision','general') NOT NULL DEFAULT 'deadline',
  `message` text DEFAULT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_submissions`
--

CREATE TABLE `task_submissions` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('submitted','graded') DEFAULT 'submitted',
  `attempt_no` int(11) NOT NULL DEFAULT 1,
  `is_final` tinyint(1) NOT NULL DEFAULT 0,
  `review_status` enum('pending','in_review','needs_revision','approved','graded') NOT NULL DEFAULT 'pending',
  `grade` decimal(5,2) DEFAULT NULL,
  `grade_breakdown` longtext DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_submissions`
--

INSERT INTO `task_submissions` (`id`, `task_id`, `user_id`, `class_id`, `file_path`, `submitted_at`, `status`, `attempt_no`, `is_final`, `review_status`, `grade`, `grade_breakdown`, `feedback`, `reviewed_by`, `reviewed_at`) VALUES
(1, 9, 3, 8, 'public/uploads/task_submissions/1764725232_Ocean_Eyes.jpg', '2025-12-03 01:27:12', 'graded', 1, 1, 'graded', 100.00, '[]', 'Kalau bisa jangan jadi orang tolol ya', 1, '2025-12-03 02:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `name`, `email`, `created_at`) VALUES
(1, 'asd', 'asd@gmail.com', '2025-11-07 13:36:37'),
(2, 'asd', 'asd@gmail.com', '2025-11-07 13:36:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('user','admin','guru') DEFAULT 'user',
  `name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `tasks_completed` int(11) DEFAULT 0,
  `attendance` int(11) DEFAULT 0,
  `certificates` int(11) DEFAULT 0,
  `average_grade` varchar(5) DEFAULT 'N/A',
  `banner` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `level`, `name`, `phone`, `address`, `join_date`, `class`, `role`, `avatar`, `bio`, `tasks_completed`, `attendance`, `certificates`, `average_grade`, `banner`) VALUES
(1, 'arch', 'arcadial076@gmail.com', '$2y$10$EGg4L1dXaLUmw5loHJHTveIMMGdxde1gLlQ.8JrTWhT2rpo0leo6a', 'admin', 'Restu Rudiansyah', '085793344459', 'Kp. Nangela RT/RW 002/003', '2025-11-02', 'XII-PPLG', 'teacher', 'uploads/avatars/1764316166_006d880ba31d.png', 'Buset banget', 0, 0, 0, 'N/A', 'uploads/banners/1764316154_banner_b54fbf42dfc8.jpg'),
(2, 'Kate', 'as@gmail.com', '$2y$10$OwG0JsKggczl10qPCw5Gk.hH3wTtHegUqLNC6VlMPVKFmDp0y265C', 'user', 'Feline Krausher', '08579334445', 'Kp. Nangela RT/RW 002/003', '2025-11-09', 'XII-PPLG', '', 'uploads/avatars/1764723780_c1e07b9c706c.jpg', 'Cukup sahaja', 0, 0, 0, 'N/A', 'uploads/banners/1764723780_banner_149d0c47db73.jpg'),
(3, 'sand', 'fdgs@gmail.com', '$2y$10$0hmoBFHYqxAy1ZWVN2UeGeITRYgPrTCo/fztuPApTh39tArDQkx..', 'user', 'Restu Rudiansyah', '085793344459', 'Kp. Nangela RT/RW 002/003', '2025-11-14', 'XII-PPLG', 'student', 'uploads/avatars/1763611662_691e940e2fb41.png', 'asdf', 0, 0, 0, 'N/A', 'uploads/banners/1763611662_691e940e2fe58.jpg'),
(4, 'manusia', 'manusia@gmail.com', '$2y$10$UVscC7jAqjd6Y4YqEfPOjeYsHG2g3gd/xGw7J9cR1IZl2aKBr048C', 'user', 'manusia', '2345235', 'Kp. Kemanusiaan', '2025-11-27', 'XII-PPLG', '', 'uploads/avatars/1764635912_89316f509630.png', 'asdfasdasdfsafd', 0, 0, 0, 'N/A', 'uploads/banners/1764635912_banner_7003e0b4996c.jpg'),
(5, 'afs', 'asa@gmail.com', '$2y$10$151Q6HAm7kjue1C5eVdCbebTnSRozG2TlRHCSxIoB/TvT5/yF5mBO', 'guru', 'asfasd', '456345', 'fasdf', '2025-11-27', NULL, 'teacher', 'assets/default-avatar.png', 'sdfasd', 0, 0, 0, 'N/A', 'assets/default-banner.png'),
(6, 'human', 'human@gmail.com', '$2y$10$ezROawN8Yo8e7pOuuBMIaeTn45MjHe7vUTNlcgMhF3e3yuCmCVwvy', 'user', 'human', '23453452', 'Kp. Human', '2025-11-27', '', NULL, 'uploads/avatars/1764223828_6927eb54a58df.jpg', 'eradfg', 0, 0, 0, 'N/A', 'uploads/banners/1764223828_6927eb54a5c28.jpg'),
(7, 'asd', 'asd@gmail.com', '$2y$10$BTV6uV33bjnsnQpziQ5A.uSusTTSQxbQc0i94vsTPnCriMYn2oxiG', 'user', 'ASD', '2453234', 'Kp. Bojongsoang', '2025-11-27', '', '', 'uploads/avatars/1764223953_6927ebd197f8d.png', 'sadf', 0, 0, 0, 'N/A', 'uploads/banners/1764223953_6927ebd1982bc.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `announcement_comments`
--
ALTER TABLE `announcement_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcement_id` (`announcement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`class_id`,`user_id`,`date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `average_grade`
--
ALTER TABLE `average_grade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `class_members`
--
ALTER TABLE `class_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `documentation`
--
ALTER TABLE `documentation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `tasks_completed`
--
ALTER TABLE `tasks_completed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `idx_tasks_completed_subject_id` (`subject_id`),
  ADD KEY `idx_tasks_workflow_state` (`workflow_state`),
  ADD KEY `idx_tasks_reminder_at` (`reminder_at`);

--
-- Indexes for table `task_reminders`
--
ALTER TABLE `task_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_task_reminders_task` (`task_id`),
  ADD KEY `idx_task_reminders_user` (`user_id`);

--
-- Indexes for table `task_submissions`
--
ALTER TABLE `task_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `idx_task_submissions_review_status` (`review_status`),
  ADD KEY `idx_task_submissions_user` (`user_id`),
  ADD KEY `idx_task_submissions_task_user` (`task_id`,`user_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `announcement_comments`
--
ALTER TABLE `announcement_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archives`
--
ALTER TABLE `archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `average_grade`
--
ALTER TABLE `average_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `class_members`
--
ALTER TABLE `class_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `documentation`
--
ALTER TABLE `documentation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tasks_completed`
--
ALTER TABLE `tasks_completed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `task_reminders`
--
ALTER TABLE `task_reminders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_submissions`
--
ALTER TABLE `task_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcement_comments`
--
ALTER TABLE `announcement_comments`
  ADD CONSTRAINT `announcement_comments_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcement_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `archives`
--
ALTER TABLE `archives`
  ADD CONSTRAINT `archives_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `archives_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `average_grade`
--
ALTER TABLE `average_grade`
  ADD CONSTRAINT `average_grade_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_members`
--
ALTER TABLE `class_members`
  ADD CONSTRAINT `class_members_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`task_id`) REFERENCES `tasks_completed` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `grades_ibfk_4` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tasks_completed`
--
ALTER TABLE `tasks_completed`
  ADD CONSTRAINT `fk_tasks_completed_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_completed_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_completed_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_reminders`
--
ALTER TABLE `task_reminders`
  ADD CONSTRAINT `fk_task_reminders_task` FOREIGN KEY (`task_id`) REFERENCES `tasks_completed` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_submissions`
--
ALTER TABLE `task_submissions`
  ADD CONSTRAINT `task_submissions_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks_completed` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_submissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_submissions_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
