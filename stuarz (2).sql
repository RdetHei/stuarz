-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Okt 2025 pada 06.19
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
-- Struktur dari tabel `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `photo`, `created_by`, `created_at`, `class_id`) VALUES
(2, 'asd', 'asdf', '', 5, '2025-10-23 00:51:38', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcement_comments`
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
-- Struktur dari tabel `archives`
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
-- Struktur dari tabel `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('hadir','alfa','izin','sakit','terlambat') DEFAULT 'hadir',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `average_grade`
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
-- Struktur dari tabel `certificates`
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
-- Dumping data untuk tabel `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `title`, `description`, `issued_by`, `issued_at`, `file_path`, `created_at`) VALUES
(4, 6, 'fas', 'fase', 'Institut Meme Timpa Text', '2025-09-11', 'uploads/certificates/1757946266_certificate_68c8219a4b980.png', '2025-09-15 14:24:26'),
(5, 5, 'Sertifikat ajah', 'Ini itu cuman contoh', 'Admin', '2025-09-18', 'uploads/certificates/1758188331_certificate_68cbd32b537fc.png', '2025-09-18 09:38:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `classes`
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
-- Dumping data untuk tabel `classes`
--

INSERT INTO `classes` (`id`, `name`, `code`, `description`, `created_by`, `created_at`) VALUES
(1, '10 PPLG 2', '12345', 'ASDFAS', 5, '2025-10-23 00:24:14'),
(2, 'asd', '9351', 'asdfasd', 3, '2025-10-23 01:50:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_members`
--

CREATE TABLE `class_members` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` enum('student','teacher','admin') DEFAULT 'student',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `class_members`
--

INSERT INTO `class_members` (`id`, `class_id`, `user_id`, `role`, `joined_at`) VALUES
(2, 1, 5, '', '2025-10-23 00:45:38'),
(3, 1, 3, '', '2025-10-23 00:45:42');

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
(3, 'Components', 'buttons', 'Buttons', 'Ini hanya test', 'Button untuk styles'),
(4, 'Stuarz', 'History', 'History', NULL, 'Stuarz is a site made by student for student.'),
(5, 'Stuarz', 'Owner', 'Owner', NULL, 'The owner of the Stuarz are the Demi-human named Rdethei.'),
(6, 'Components', 'Setting', 'Setting', 'This is some information about Setting', 'Just read it you dumbass.'),
(7, 'Stuarz', 'Stuarz', 'Wondering', 'Just a text', 'maybe it is for'),
(8, 'Usage', 'Usage', 'The Function', 'This is some usage you can see.', 'Maybe a little bit.'),
(9, 'E-Learning', 'platform-belajar-online', 'Platform Belajar Online Interaktif', 'Platform pembelajaran online dengan konten interaktif untuk semua jenjang pendidikan', 'Konten lengkap tentang platform belajar online...'),
(10, 'EdTech', 'aplikasi-belajar-mobile', 'Aplikasi Belajar Mobile untuk Siswa', 'Aplikasi mobile yang membantu siswa belajar kapan saja dan di mana saja', 'Konten tentang aplikasi belajar mobile...'),
(11, 'Kursus', 'kursus-pemrograman-dasar', 'Kursus Pemrograman Dasar untuk Pelajar', 'Kursus coding dasar yang dirancang khusus untuk siswa sekolah menengah', 'Materi lengkap kursus pemrograman...'),
(12, 'Platform Guru', 'platform-manajemen-guru', 'Platform Manajemen untuk Guru', 'Tools digital untuk membantu guru mengelola kelas dan materi pembelajaran', 'Konten platform manajemen guru...'),
(13, 'Sistem Sekolah', 'sistem-erp-sekolah', 'Sistem ERP untuk Institusi Pendidikan', 'Software manajemen sekolah terintegrasi untuk administrasi yang efisien', 'Detail sistem ERP sekolah...'),
(14, 'E-Learning', 'virtual-classroom', 'Virtual Classroom Solution', 'Solusi kelas virtual dengan fitur video conference dan whiteboard', 'Konten virtual classroom...'),
(15, 'Game Edukasi', 'game-matematika-anak', 'Game Matematika untuk Anak SD', 'Game edukasi yang membuat belajar matematika menjadi menyenangkan', 'Deskripsi game matematika...'),
(16, 'Platform Tes', 'platform-ujian-online', 'Platform Ujian Online Terpadu', 'Sistem ujian online dengan pengawasan dan penilaian otomatis', 'Fitur platform ujian online...'),
(17, 'Kursus Bahasa', 'kursus-bahasa-inggris', 'Kursus Bahasa Inggris Online', 'Kursus bahasa Inggris dengan native speaker dan materi interaktif', 'Metode pembelajaran bahasa...'),
(18, 'Teknologi AR/VR', 'ar-vr-education', 'Teknologi AR/VR untuk Pendidikan', 'Penggunaan augmented dan virtual reality dalam proses belajar', 'Implementasi AR/VR di pendidikan...'),
(19, 'Platform Tugas', 'manajemen-tugas-sekolah', 'Sistem Manajemen Tugas Sekolah', 'Platform untuk memberikan dan mengumpulkan tugas sekolah secara digital', 'Fitur manajemen tugas...'),
(20, 'E-Library', 'perpustakaan-digital', 'Perpustakaan Digital Sekolah', 'Koleksi buku digital dan materi pembelajaran online', 'Katalog perpustakaan digital...'),
(21, 'Kursus Musik', 'kursus-musik-online', 'Kursus Musik Daring untuk Pelajar', 'Belajar alat musik secara online dengan guru profesional', 'Metode kursus musik online...'),
(22, 'Platform Orang Tua', 'aplikasi-orangtua-siswa', 'Aplikasi Monitoring untuk Orang Tua', 'Aplikasi untuk orang tua memantau perkembangan akademik anak', 'Fitur monitoring orang tua...'),
(23, 'Sistem Absensi', 'absensi-digital-sekolah', 'Sistem Absensi Digital Sekolah', 'Sistem absensi menggunakan QR code dan fingerprint', 'Teknologi absensi digital...'),
(24, 'Kursus Robotik', 'kursus-robotik-siswa', 'Kursus Robotik untuk Siswa', 'Pengenalan dan pembelajaran robotik untuk jenjang SMP-SMA', 'Materi kursus robotik...'),
(25, 'Platform Beasiswa', 'platform-beasiswa-online', 'Platform Pencarian Beasiswa', 'Sistem terpadu untuk mencari dan mendaftar beasiswa', 'Database beasiswa...'),
(26, 'E-Learning', 'learning-management-system', 'Learning Management System', 'Platform manajemen pembelajaran dengan fitur lengkap', 'Fitur LMS lengkap...'),
(27, 'Kursus Seni', 'kursus-seni-digital', 'Kursus Seni Digital untuk Pelajar', 'Belajar desain grafis dan seni digital untuk siswa', 'Materi seni digital...'),
(28, 'Platform Tutor', 'platform-tutor-privat', 'Platform Mencari Tutor Privat', 'Menghubungkan siswa dengan tutor privat berkualitas', 'Sistem pencarian tutor...'),
(29, 'Sistem Pembayaran', 'pembayaran-sekolah-digital', 'Sistem Pembayaran Sekolah Digital', 'Platform pembayaran SPP dan biaya sekolah online', 'Metode pembayaran digital...'),
(30, 'Kursus Kewirausahaan', 'kursus-wirausaha-siswa', 'Kursus Kewirausahaan untuk Siswa', 'Mengajarkan dasar-dasar bisnis dan kewirausahaan', 'Materi kewirausahaan...'),
(31, 'Platform Kompetisi', 'platform-kompetisi-siswa', 'Platform Kompetisi Akademik', 'Wadah untuk kompetisi dan olimpiade siswa', 'Jenis kompetisi yang tersedia...'),
(32, 'E-Learning', 'adaptive-learning', 'Sistem Adaptive Learning', 'Platform belajar yang menyesuaikan dengan kemampuan siswa', 'Algoritma adaptive learning...'),
(33, 'Kursus Coding', 'coding-bootcamp-siswa', 'Coding Bootcamp untuk Pelajar', 'Program intensif belajar coding dalam waktu singkat', 'Kurikulum coding bootcamp...'),
(34, 'Platform Magang', 'platform-magang-siswa', 'Platform Magang untuk Siswa SMA', 'Menghubungkan siswa dengan perusahaan untuk magang', 'Sistem magang online...'),
(35, 'Sistem Sertifikasi', 'sertifikasi-online-siswa', 'Platform Sertifikasi Online', 'Sertifikasi keahlian untuk siswa secara online', 'Jenis sertifikasi yang tersedia...'),
(36, 'Kursus Public Speaking', 'public-speaking-siswa', 'Kursus Public Speaking untuk Pelajar', 'Melatih kemampuan presentasi dan public speaking', 'Teknik public speaking...'),
(37, 'Platform Study Group', 'study-group-online', 'Platform Study Group Online', 'Fasilitas belajar kelompok secara virtual', 'Fitur study group...'),
(38, 'E-Learning', 'microlearning-platform', 'Platform Microlearning', 'Pembelajaran dalam bentuk konten singkat dan fokus', 'Konsep microlearning...'),
(39, 'Kursus Data Science', 'data-science-siswa', 'Pengenalan Data Science untuk Siswa', 'Dasar-dasar data science dan analisis data', 'Materi data science...'),
(40, 'Platform Career Guidance', 'bimbingan-karir-siswa', 'Platform Bimbingan Karir', 'Membantu siswa menentukan jurusan dan karir masa depan', 'Tes minat bakat...'),
(41, 'Sistem Progress Report', 'laporan-perkembangan-siswa', 'Sistem Laporan Perkembangan Siswa', 'Monitoring dan laporan perkembangan akademik siswa', 'Format laporan perkembangan...'),
(42, 'Kursus Financial Literacy', 'literasi-keuangan-siswa', 'Kursus Literasi Keuangan untuk Pelajar', 'Pengelolaan keuangan pribadi untuk siswa', 'Materi literasi keuangan...'),
(43, 'Platform Project Based Learning', 'project-based-learning', 'Platform Project Based Learning', 'Pembelajaran berbasis proyek dengan bimbingan online', 'Metode project based learning...'),
(44, 'E-Learning', 'gamified-learning', 'Platform Gamified Learning', 'Sistem belajar dengan elemen game dan reward', 'Mekanisme gamifikasi...'),
(45, 'Kursus Digital Marketing', 'digital-marketing-siswa', 'Kursus Digital Marketing untuk Pelajar', 'Dasar-dasar pemasaran digital untuk generasi muda', 'Materi digital marketing...'),
(46, 'Platform Mental Health', 'kesehatan-mental-siswa', 'Platform Kesehatan Mental Siswa', 'Konseling dan support mental health untuk pelajar', 'Layanan kesehatan mental...'),
(47, 'Sistem Alumni', 'jaringan-alumni-sekolah', 'Platform Jaringan Alumni Sekolah', 'Menghubungkan alumni dengan siswa dan sekolah', 'Fitur jaringan alumni...'),
(48, 'Kursus Creative Writing', 'menulis-kreatif-siswa', 'Kursus Menulis Kreatif untuk Pelajar', 'Mengembangkan kemampuan menulis kreatif siswa', 'Teknik menulis kreatif...'),
(49, 'Platform Science Lab Virtual', 'lab-virtual-sains', 'Laboratorium Virtual Sains', 'Simulasi praktikum sains secara virtual', 'Eksperimen virtual yang tersedia...'),
(50, 'E-Learning', 'blended-learning', 'Platform Blended Learning', 'Kombinasi pembelajaran online dan offline', 'Model blended learning...'),
(51, 'Kursus AI Basics', 'kecerdasan-buatan-dasar', 'Pengenalan Kecerdasan Buatan untuk Siswa', 'Dasar-dasar AI dan machine learning untuk pelajar', 'Konsep AI dasar...'),
(52, 'Platform Peer Review', 'peer-review-assignment', 'Platform Peer Review Tugas', 'Sistem review tugas antar siswa dengan bimbingan guru', 'Mekanisme peer review...'),
(53, 'Sistem Talent Scout', 'pemantau-bakat-siswa', 'Sistem Pemantauan Bakat Siswa', 'Identifikasi dan pengembangan bakat siswa', 'Jenis bakat yang dipantau...'),
(54, 'Kursus Leadership', 'kepemimpinan-siswa', 'Kursus Kepemimpinan untuk Pelajar', 'Melatih jiwa kepemimpinan dan organisasi siswa', 'Materi leadership...'),
(55, 'Platform Collaborative Learning', 'pembelajaran-kolaboratif', 'Platform Pembelajaran Kolaboratif', 'Belajar bersama dalam kelompok dengan tools digital', 'Tools kolaborasi yang tersedia...'),
(56, 'E-Learning', 'personalized-learning-path', 'Jalur Belajar Personalisasi', 'Rekomendasi jalur belajar sesuai minat dan kemampuan', 'Algoritma personalisasi...'),
(57, 'Kursus UI/UX Design', 'ui-ux-design-siswa', 'Kursus UI/UX Design untuk Pelajar', 'Pengenalan desain antarmuka dan pengalaman pengguna', 'Prinsip UI/UX design...'),
(58, 'Platform Research', 'platform-penelitian-siswa', 'Platform Penelitian untuk Siswa', 'Fasilitas melakukan penelitian ilmiah dengan bimbingan', 'Metodologi penelitian...'),
(59, 'Sistem Reward', 'sistem-reward-siswa', 'Sistem Reward dan Motivasi Belajar', 'Program reward untuk memotivasi belajar siswa', 'Jenis reward yang ditawarkan...'),
(60, 'Kursus Photography', 'fotografi-dasar-siswa', 'Kursus Fotografi Dasar untuk Pelajar', 'Belajar teknik fotografi dasar dan editing', 'Materi fotografi dasar...'),
(61, 'Platform Debate', 'platform-debat-siswa', 'Platform Debat dan Diskusi Siswa', 'Wadah untuk debat dan diskusi akademik online', 'Format debat yang tersedia...'),
(62, 'E-Learning', 'competency-based-education', 'Pendidikan Berbasis Kompetensi', 'Sistem belajar berdasarkan penguasaan kompetensi', 'Model competency based education...'),
(63, 'Kursus Cybersecurity', 'keamanan-siber-siswa', 'Pengenalan Keamanan Siber untuk Pelajar', 'Dasar-dasar cybersecurity dan perlindungan data', 'Materi cybersecurity...'),
(64, 'Platform Internship', 'program-internship-siswa', 'Platform Program Internship', 'Magang profesional untuk siswa dan fresh graduate', 'Jenis internship yang tersedia...'),
(65, 'Sistem Portfolio', 'portfolio-digital-siswa', 'Sistem Portfolio Digital Siswa', 'Membuat dan mengelola portfolio akademik digital', 'Format portfolio digital...'),
(66, 'Kursus Animation', 'animasi-dasar-siswa', 'Kursus Animasi Dasar untuk Pelajar', 'Belajar dasar-dasar animasi 2D dan 3D', 'Software animasi yang diajarkan...'),
(67, 'Platform Mentorship', 'program-mentor-siswa', 'Platform Program Mentorship', 'Menghubungkan siswa dengan mentor profesional', 'Sistem matching mentor...'),
(68, 'E-Learning', 'flipped-classroom', 'Platform Flipped Classroom', 'Model pembelajaran flipped classroom dengan video', 'Implementasi flipped classroom...'),
(69, 'Kursus E-commerce', 'e-commerce-dasar-siswa', 'Kursus Dasar E-commerce untuk Pelajar', 'Membangun dan mengelola bisnis online dasar', 'Materi e-commerce dasar...'),
(70, 'Platform Science Fair', 'science-fair-virtual', 'Science Fair Virtual', 'Pameran sains dan inovasi secara virtual', 'Kategori science fair...'),
(71, 'Sistem Feedback', 'sistem-feedback-belajar', 'Sistem Feedback Pembelajaran', 'Umpan balik real-time untuk proses belajar mengajar', 'Mekanisme feedback...'),
(72, 'Kursus Social Media', 'manajemen-media-sosial', 'Kursus Manajemen Media Sosial untuk Pelajar', 'Pengelolaan konten dan strategi media sosial', 'Platform media sosial yang dibahas...'),
(73, 'Platform Coding Challenge', 'tantangan-coding-siswa', 'Platform Tantangan Coding', 'Kompetisi coding dan pemecahan masalah programming', 'Jenis tantangan coding...'),
(74, 'E-Learning', 'mobile-learning-optimized', 'Platform Mobile Learning', 'Platform belajar yang dioptimalkan untuk mobile device', 'Fitur mobile learning...'),
(75, 'Kursus Podcasting', 'produksi-podcast-siswa', 'Kursus Produksi Podcast untuk Pelajar', 'Belajar membuat dan memproduksi konten podcast', 'Equipment dan software podcast...'),
(76, 'Platform Cultural Exchange', 'pertukaran-budaya-siswa', 'Platform Pertukaran Budaya Siswa', 'Program pertukaran budaya secara virtual', 'Destinasi pertukaran budaya...'),
(77, 'Sistem Analytics', 'analytics-pembelajaran', 'Sistem Analytics Pembelajaran', 'Analisis data pembelajaran untuk peningkatan kualitas', 'Metrik yang dianalisis...'),
(78, 'Kursus Video Production', 'produksi-video-siswa', 'Kursus Produksi Video untuk Pelajar', 'Belajar membuat konten video dari dasar', 'Teknik produksi video...'),
(79, 'Platform STEM Education', 'pendidikan-stem', 'Platform Pendidikan STEM Terpadu', 'Pembelajaran Science, Technology, Engineering, Math', 'Kurikulum STEM...'),
(80, 'E-Learning', 'social-emotional-learning', 'Platform Social Emotional Learning', 'Pengembangan keterampilan sosial dan emosional', 'Komponen social emotional learning...'),
(81, 'Kursus Blockchain', 'blockchain-dasar-siswa', 'Pengenalan Blockchain untuk Pelajar', 'Dasar-dasar teknologi blockchain dan cryptocurrency', 'Konsep blockchain...'),
(82, 'Platform Scholarship', 'beasiswa-prestasi', 'Platform Beasiswa Berbasis Prestasi', 'Sistem beasiswa berdasarkan pencapaian akademik', 'Kriteria beasiswa prestasi...'),
(83, 'Sistem Assessment', 'assessment-berkelanjutan', 'Sistem Assessment Berkelanjutan', 'Penilaian terus-menerus untuk monitoring belajar', 'Jenis assessment...'),
(84, 'Kursus Graphic Design', 'desain-grafis-siswa', 'Kursus Desain Grafis untuk Pelajar', 'Belajar dasar-dasar desain grafis dan software', 'Tools desain grafis...'),
(85, 'Platform Language Exchange', 'pertukaran-bahasa', 'Platform Pertukaran Bahasa', 'Belajar bahasa dengan native speaker secara timbal balik', 'Bahasa yang tersedia...'),
(86, 'E-Learning', 'inclusive-education', 'Platform Pendidikan Inklusif', 'Pembelajaran untuk siswa dengan kebutuhan khusus', 'Fitur aksesibilitas...'),
(87, 'Kursus Entrepreneurship', 'kewirausahaan-sosial', 'Kursus Kewirausahaan Sosial untuk Pelajar', 'Membangun bisnis dengan dampak sosial positif', 'Konsep social entrepreneurship...'),
(88, 'Platform Coding for Kids', 'coding-anak-sekolah', 'Platform Coding untuk Anak Sekolah', 'Belajar coding untuk siswa sekolah dasar', 'Metode belajar coding anak...'),
(89, 'Sistem Parent-Teacher', 'komunikasi-orangtua-guru', 'Platform Komunikasi Orang Tua-Guru', 'Fasilitas komunikasi antara orang tua dan guru', 'Fitur komunikasi yang tersedia...'),
(90, 'Kursus Music Production', 'produksi-musik-siswa', 'Kursus Produksi Musik Digital', 'Belajar produksi musik menggunakan software digital', 'DAW yang diajarkan...'),
(91, 'Platform Virtual Field Trip', 'field-trip-virtual', 'Platform Virtual Field Trip', 'Tur virtual ke museum, laboratorium, dan tempat edukasi', 'Destinasi virtual field trip...'),
(92, 'E-Learning', 'mastery-based-learning', 'Platform Mastery Based Learning', 'Belajar hingga benar-benar menguasai materi', 'Prinsip mastery learning...'),
(93, 'Kursus Digital Art', 'seni-digital-siswa', 'Kursus Seni Digital dan Ilustrasi', 'Belajar menggambar digital dan ilustrasi komputer', 'Tablet dan software digital art...'),
(94, 'Platform Student Council', 'dewan-siswa-digital', 'Platform Dewan Siswa Digital', 'Tools untuk organisasi dan kegiatan dewan siswa', 'Fitur organisasi siswa...'),
(95, 'Sistem Progress Tracking', 'pelacakan-perkembangan', 'Sistem Pelacakan Perkembangan Belajar', 'Monitoring kemajuan belajar secara real-time', 'Metrik perkembangan...'),
(96, 'Kursus Public Relations', 'public-relations-siswa', 'Kursus Public Relations Dasar', 'Pengenalan hubungan masyarakat untuk pelajar', 'Prinsip public relations...'),
(97, 'Platform Science Competition', 'kompetisi-sains-online', 'Platform Kompetisi Sains Online', 'Olimpiade sains dan matematika secara daring', 'Jenis kompetisi sains...'),
(98, 'E-Learning', 'project-based-assessment', 'Assessment Berbasis Proyek', 'Sistem penilaian melalui penyelesaian proyek nyata', 'Rubrik penilaian proyek...'),
(99, 'Kursus Web Development', 'pengembangan-web-siswa', 'Kursus Pengembangan Website untuk Pelajar', 'Belajar membuat website dari nol hingga deploy', 'Teknologi web development...'),
(100, 'Platform Career Development', 'pengembangan-karir-siswa', 'Platform Pengembangan Karir Siswa', 'Persiapan karir dan dunia kerja untuk lulusan', 'Program pengembangan karir...'),
(101, 'Sistem Digital Badge', 'sistem-digital-badge', 'Sistem Digital Badge dan Sertifikat', 'Penghargaan digital untuk pencapaian belajar', 'Jenis digital badge...'),
(102, 'Kursus Game Development', 'pengembangan-game-siswa', 'Kursus Pengembangan Game untuk Pelajar', 'Belajar membuat game sederhana dari dasar', 'Engine game development...'),
(103, 'Platform Student Blogging', 'platform-blog-siswa', 'Platform Blogging untuk Siswa', 'Wadah menulis dan berbagi pengetahuan siswa', 'Fitur blogging platform...'),
(104, 'E-Learning', 'differentiated-instruction', 'Platform Differentiated Instruction', 'Pembelajaran yang disesuaikan dengan gaya belajar', 'Gaya belajar yang didukung...'),
(105, 'Kursus Data Visualization', 'visualisasi-data-siswa', 'Kursus Visualisasi Data untuk Pelajar', 'Belajar menyajikan data dalam bentuk visual menarik', 'Tools visualisasi data...'),
(106, 'Platform Mock Interview', 'wawancara-palsu-siswa', 'Platform Mock Interview untuk Siswa', 'Simulasi wawancara untuk persiapan karir', 'Jenis mock interview...'),
(107, 'Sistem Learning Path', 'jalur-belajar-terstruktur', 'Sistem Jalur Belajar Terstruktur', 'Rencana belajar bertahap dengan milestones jelas', 'Struktur learning path...'),
(108, 'Kursus Digital Citizenship', 'kewarganegaraan-digital', 'Kursus Kewarganegaraan Digital', 'Etika dan tanggung jawab dalam dunia digital', 'Materi digital citizenship...'),
(109, 'Platform Student Exchange', 'pertukaran-pelajar-virtual', 'Platform Pertukaran Pelajar Virtual', 'Program pertukaran pelajar secara online', 'Sekolah partner pertukaran...'),
(110, 'E-Learning', 'competency-tracking', 'Platform Pelacakan Kompetensi', 'Monitoring penguasaan kompetensi setiap siswa', 'Sistem tracking kompetensi...'),
(111, 'Kursus Mobile App Development', 'pengembangan-aplikasi-mobile', 'Kursus Pengembangan Aplikasi Mobile', 'Belajar membuat aplikasi untuk smartphone', 'Platform mobile development...'),
(112, 'Platform Research Collaboration', 'kolaborasi-riset-siswa', 'Platform Kolaborasi Riset Siswa', 'Kerja sama penelitian antar siswa dan sekolah', 'Proyek kolaborasi riset...'),
(113, 'Sistem Peer Assessment', 'penilaian-teman-sebaya', 'Sistem Penilaian oleh Teman Sebaya', 'Evaluasi pembelajaran melalui penilaian sesama siswa', 'Rubrik peer assessment...'),
(114, 'Kursus Critical Thinking', 'berpikir-kritis-siswa', 'Kursus Berpikir Kritis untuk Pelajar', 'Melatih kemampuan analisis dan evaluasi informasi', 'Teknik berpikir kritis...');

-- --------------------------------------------------------

--
-- Struktur dari tabel `grades`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `news`
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
-- Dumping data untuk tabel `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `category`, `thumbnail`, `author`, `created_at`) VALUES
(1, 'Anxiety', 'Aku juga tidak tahu apa yang sebenarnya terjadi.', 'Ilmu Pengetahuan', 'uploads/news/1758115541_pixai-1913456473539726748-2.png', 'arch', '2025-09-17 08:24:00'),
(2, 'Ngaco', 'afasdfasdf', 'Ngaco juga', 'uploads/news/1758116736_pixai-1919583134662944356-3.png', 'arch', '2025-09-17 08:45:00'),
(3, 'asdfas', 'asdfasdfqwawrfew', 'Ngaco juga', 'uploads/news/1758196050_pixai-1917422792962279431-3.png', 'arch', '2025-09-18 06:47:00'),
(4, 'fasdf', 'asdfasghgf', 'Kedokteran', 'uploads/news/1758203815_pixai-1915213834087094099-3.png', 'arch', '2025-09-18 08:56:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedule`
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
-- Struktur dari tabel `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `description`) VALUES
(1, 'Feline Krausher', 'Ini khusus untuk PPLG AJAH!!!!!!');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tasks_completed`
--

CREATE TABLE `tasks_completed` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `deadline` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `class_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `task_submissions`
--

CREATE TABLE `task_submissions` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('submitted','graded') DEFAULT 'submitted',
  `grade` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'asd', 'asd@gmail.com', '$2y$10$Qz1HAxW8YlKupbYtHQCgduMna2SxPQGnxMYkvvfIgShXPkU7OczIu', 'user', 'Ran', '345634', 'Jl. Cimahi', '2025-09-10', NULL, 'uploads/avatars/1761178979_2c518eb270d6.jpeg', 'Aku adalah siswa', 0, 0, 0, 'N/A', 'uploads/banners/1761178979_banner_8ad8544b64e2.jpg'),
(4, 'rdet', 'rdethei05@gmail.com', '$2y$10$kWNXnq7jzBLW0MRupqrzIuza/eU2xttNuIx1lWNiIP1oNpz5Z4chC', 'user', NULL, '', '', '2025-09-10', '', 'uploads/avatars/1757857397_bdbd449028d7.jpg', NULL, 0, 0, 0, 'N/A', NULL),
(5, 'arch', 'arch@gmail.com', '$2y$10$lfvoNZv8rAK/euyfo0peeeKRMakVtj4ju8PgUL2743yMC52vcnSIC', 'admin', 'Arch', '203495820394', 'Jl. Cipempek', '2025-09-10', 'XI-PPLG', 'uploads/avatars/1761178999_7819d7a0822f.jpg', 'Aku adalah admin dari Stuarz.', 10, 10, 10, 'N/A', 'uploads/banners/1761178999_banner_2e1b06bd0d9f.jpg'),
(6, 'afs', 'aff@gmail.com', '$2y$10$3jFG/zbEyc4zI62wYZE45.nXvaOzsHGFYg1J8U8s0MlKJrRiZn0xm', 'user', NULL, '', '', '2025-09-11', '', 'uploads/avatars/1757849576_e40e06245f3f.png', NULL, 0, 0, 0, 'N/A', 'uploads/banners/1757906728_banner_37ccb9789f2e.png'),
(16, 'sas', 'sad@gmail.com', '$2y$10$uM8FKwdRfPrJZFpiybmi/e.TExdCjWDrTX8.3YoWMyJfdpMpzSFHq', 'user', NULL, NULL, NULL, '2025-09-14', NULL, 'uploads/avatars/1757852338_3c1656f519c1.png', NULL, 0, 0, 0, 'N/A', 'uploads/banners/1757930361_banner_ce551ff6404d.png'),
(20, 'ag', 'ag@gmail.com', '$2y$10$x3kdRh6FhrRC.1gGo6Wd/.o4EID9U1M8zLr41kfVzRmqdUhwJ3w8S', 'user', NULL, NULL, NULL, '2025-09-15', NULL, 'uploads/avatars/1757932462_8673d5f36559.png', NULL, 0, 0, 0, 'N/A', 'uploads/banners/1757932462_banner_cbd29e5c51f0.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `announcement_comments`
--
ALTER TABLE `announcement_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcement_id` (`announcement_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `archives`
--
ALTER TABLE `archives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`class_id`,`user_id`,`date`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `average_grade`
--
ALTER TABLE `average_grade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `class_members`
--
ALTER TABLE `class_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `documentation`
--
ALTER TABLE `documentation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tasks_completed`
--
ALTER TABLE `tasks_completed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `task_submissions`
--
ALTER TABLE `task_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

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
-- AUTO_INCREMENT untuk tabel `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `announcement_comments`
--
ALTER TABLE `announcement_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `archives`
--
ALTER TABLE `archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `average_grade`
--
ALTER TABLE `average_grade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `class_members`
--
ALTER TABLE `class_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `documentation`
--
ALTER TABLE `documentation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tasks_completed`
--
ALTER TABLE `tasks_completed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `task_submissions`
--
ALTER TABLE `task_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `announcement_comments`
--
ALTER TABLE `announcement_comments`
  ADD CONSTRAINT `announcement_comments_ibfk_1` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcement_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `archives`
--
ALTER TABLE `archives`
  ADD CONSTRAINT `archives_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `archives_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `average_grade`
--
ALTER TABLE `average_grade`
  ADD CONSTRAINT `average_grade_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `class_members`
--
ALTER TABLE `class_members`
  ADD CONSTRAINT `class_members_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`task_id`) REFERENCES `tasks_completed` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `grades_ibfk_4` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tasks_completed`
--
ALTER TABLE `tasks_completed`
  ADD CONSTRAINT `tasks_completed_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_completed_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `task_submissions`
--
ALTER TABLE `task_submissions`
  ADD CONSTRAINT `task_submissions_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks_completed` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_submissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_submissions_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
