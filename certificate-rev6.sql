-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 13, 2024 at 05:16 PM
-- Server version: 11.1.2-MariaDB-1:11.1.2+maria~ubu2204
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `certificate`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `certificate_code` varchar(100) NOT NULL,
  `issued_at` timestamp NULL DEFAULT current_timestamp(),
  `certificate_template_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `download_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_fields`
--

CREATE TABLE `certificate_fields` (
  `id` int(11) NOT NULL,
  `certificate_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_templates`
--

CREATE TABLE `certificate_templates` (
  `id` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `font_name` varchar(255) NOT NULL,
  `font_file` varchar(255) NOT NULL,
  `template_name` varchar(255) NOT NULL,
  `template_desc` text NOT NULL,
  `uploader_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_templates`
--

INSERT INTO `certificate_templates` (`id`, `file_name`, `font_name`, `font_file`, `template_name`, `template_desc`, `uploader_id`, `created_at`) VALUES
('anajsydguiadjasfnn-adkj', 'anajsydguiadjasfnn-adkj.png', 'calibri', 'calibri-bold-italic.ttf', 'Anajsydguiadjasfnn adkj', 'dejhkj', 1, '2024-11-06 08:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `organizer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `event_name`, `event_description`, `event_date`, `organizer`, `created_at`) VALUES
(10, 'Belajar Golang Dasar anjay', 'cascadc', '2024-11-22', 'w23x', '2024-11-13 17:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_activity` enum('create','delete','update','login','logout','download') NOT NULL,
  `info` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `type_activity`, `info`, `created_at`) VALUES
(3, 1, 'login', 'Create new session with id: 1', '2024-11-05 09:20:36'),
(4, 1, 'login', 'Create new session with id: 1', '2024-11-05 09:26:26'),
(5, 1, 'logout', 'Success logout with id: 1', '2024-11-05 09:28:45'),
(6, 1, 'login', 'Create new session with id: 1', '2024-11-05 09:30:53'),
(7, 1, 'delete', 'Success delete template with id: biru-emas-template-sertifikat-rev-1', '2024-11-05 09:31:05'),
(8, 1, 'delete', 'Success delete certificate with id: 26', '2024-11-05 09:35:00'),
(9, 1, 'create', 'Success create new certificate with id: f7hL7KiL0u-2024', '2024-11-05 09:35:13'),
(10, 1, 'delete', 'Success delete user with id: 10', '2024-11-05 09:35:35'),
(11, 1, 'logout', 'Success logout with id: 1', '2024-11-05 09:36:02'),
(12, 7, 'login', 'Create new session with id: 7', '2024-11-05 09:36:10'),
(13, 7, 'delete', 'Success delete certificate with id: 29', '2024-11-05 09:36:36'),
(14, 7, 'create', 'Success create new certificate with id: cgqzuj2MdC-2024', '2024-11-05 09:37:33'),
(15, 7, 'delete', 'Success delete certificate with id: 28', '2024-11-05 09:43:58'),
(16, 1, 'login', 'Create new session with id: 1', '2024-11-05 14:36:05'),
(17, 1, 'update', 'Success edit certificate with id: 30', '2024-11-05 14:53:28'),
(18, 1, 'logout', 'Success logout with id: 1', '2024-11-05 15:24:53'),
(19, 1, 'login', 'Create new session with id: 1', '2024-11-05 15:25:00'),
(20, 1, 'create', 'Success create course with name: sjdhja', '2024-11-05 15:35:07'),
(21, 1, 'update', 'Success edit certificate with id: 30', '2024-11-05 15:37:49'),
(22, 1, 'delete', 'Success delete certificate with id: 27', '2024-11-05 16:35:57'),
(23, 1, 'delete', 'Success delete certificate with id: 30', '2024-11-05 16:36:00'),
(24, 1, 'delete', 'Success delete certificate with id: 31', '2024-11-05 16:36:03'),
(25, 1, 'create', 'Success create new certificate with id: Baezhxug5Z-2024', '2024-11-05 16:38:16'),
(26, 1, 'delete', 'Success delete certificate with id: 32', '2024-11-05 16:40:02'),
(27, 1, 'create', 'Success create new certificate with id: NQGCifCeza-2024', '2024-11-05 16:55:21'),
(28, 1, 'update', 'Success edit certificate with id: 35', '2024-11-05 16:55:38'),
(29, 1, 'update', 'Success edit certificate with id: 35', '2024-11-05 16:56:34'),
(30, 1, 'create', 'Success create new template', '2024-11-05 17:28:42'),
(31, 1, 'delete', 'Success delete template with id: biru-emas', '2024-11-05 17:32:23'),
(32, 1, 'create', 'Success create new certificate with id: 0L48UflYZZ-2024', '2024-11-05 17:32:40'),
(33, 1, 'update', 'Success edit template with id: biru-ajenfju-e883', '2024-11-05 17:53:37'),
(34, 1, 'delete', 'Success delete template with id: biru-ajenfju-e883, file_name = ', '2024-11-05 18:08:47'),
(35, 1, 'create', 'Success create new template', '2024-11-05 18:09:08'),
(36, 1, 'update', 'Success edit template with id: damda', '2024-11-05 18:12:35'),
(37, 1, 'update', 'Success edit template with id: damda', '2024-11-05 18:12:49'),
(38, 1, 'create', 'Success create new certificate with id: 7gVm9aL0i4-2024', '2024-11-05 18:18:20'),
(39, 1, 'logout', 'Success logout with id: 1', '2024-11-05 18:19:27'),
(40, 5, 'login', 'Create new session with id: 5', '2024-11-05 18:19:32'),
(41, 5, 'logout', 'Success logout with id: 5', '2024-11-05 18:47:44'),
(42, 1, 'login', 'Create new session with id: 1', '2024-11-05 18:47:53'),
(43, 1, 'login', 'Create new session with id: 1', '2024-11-06 00:14:17'),
(44, 1, 'login', 'Create new session with id: 1', '2024-11-06 01:09:54'),
(45, 1, 'logout', 'Success logout with id: 1', '2024-11-06 01:09:59'),
(46, 1, 'login', 'Create new session with id: 1', '2024-11-06 07:23:37'),
(47, 1, 'login', 'Create new session with id: 1', '2024-11-06 08:21:26'),
(48, 1, 'delete', 'Success delete template with id: damda', '2024-11-06 08:34:50'),
(49, 1, 'delete', 'Success delete course with id: 9', '2024-11-06 08:35:01'),
(50, 1, 'create', 'Success create new template', '2024-11-06 08:35:49'),
(51, 1, 'login', 'Create new session with id: 1', '2024-11-06 14:55:57'),
(52, 1, 'create', 'Success create new certificate with id: 4orHC2rwi6-2024', '2024-11-06 15:46:01'),
(53, 1, 'download', 'Success download certificate with code: 4orHC2rwi6-2024 with user id: 1', '2024-11-06 16:41:50'),
(54, 1, 'login', 'Create new session with id: 1', '2024-11-13 16:38:11'),
(55, 1, 'logout', 'Success logout with id: 1', '2024-11-13 16:46:05'),
(56, 5, 'login', 'Create new session with id: 5', '2024-11-13 16:46:12'),
(57, 5, 'logout', 'Success logout with id: 5', '2024-11-13 17:07:17'),
(58, 5, 'login', 'Create new session with id: 5', '2024-11-13 17:07:24'),
(59, 5, 'logout', 'Success logout with id: 5', '2024-11-13 17:10:51'),
(60, 1, 'login', 'Create new session with id: 1', '2024-11-13 17:10:59'),
(61, 1, 'delete', 'Success delete course with id: 4', '2024-11-13 17:11:06'),
(62, 1, 'delete', 'Success delete course with id: 6', '2024-11-13 17:11:10'),
(63, 1, 'delete', 'Success delete course with id: 7', '2024-11-13 17:11:13'),
(64, 1, 'delete', 'Success delete course with id: 8', '2024-11-13 17:11:18'),
(65, 1, 'logout', 'Success logout with id: 1', '2024-11-13 17:11:26'),
(66, 1, 'login', 'Create new session with id: 1', '2024-11-13 17:12:42'),
(67, 1, 'create', 'Success create course with name: Belajar Golang Dasar anjay', '2024-11-13 17:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reset_password`
--

INSERT INTO `reset_password` (`id`, `reset_token`, `email`) VALUES
(1, 'ba8c58e099ca2e43121e3fb08a097fa4a252a2cb675790c7094178ed54448347de7c8f1ef174bf1d', 'ibnu235729@gmail.com'),
(2, '1572cc0192e0856d8206bb7f679ec5d7508b1c11962c9900fc139e7d3e5ab97e6144d9f5801a92b4', 'ibnu235729@gmail.com'),
(3, 'a5ebd9604a7bf942b62a4589ac0cdeda67a9197123d30ede6395708622c7e88bfa9c25a72218fafe', 'ibnu235729@gmail.com'),
(4, '09652b3d5ec284be47c3fc082ad871af1369f57b6d1b065c173fd7978888fa0f554eff14eb7dfe98', 'ibnu235729@gmail.com'),
(5, 'deab6b9902f4e13d9a48d2a90eb84608a85a22ce5e2713cb293ea57494e32b1657e708fa8db69c6d', 'ibnu235729@gmail.com'),
(6, '31b9c9c5a1ddf9646ee93fe980c991983c7555ee2befb7e86cfb7e1021c4bbf7c9dc4ac3c85650f6', 'ibnu235729@gmail.com'),
(7, 'a61e213675e366b8d797654702be0f562d0ea374e0b9f0caca01dbe5acbd5a3a9c49d188da307da5', 'ibnu235729@gmail.com'),
(8, '0bd62017a14e6fd4869903136da540225006577bccaf4c95efa37e96b39b7819245922cb81d450ac', 'ibnu235729@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('participant','admin') DEFAULT 'participant',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `full_name`, `email`, `phone_number`, `password`, `role`, `created_at`) VALUES
(1, '3175062406070009', 'Chaeza Ibnu Akbar', 'ibnu235729@gmail.com', '0859102628529', '6b8c7e9305699d0837f82c27711625c1;f8b418755f1d6e9061a6ecf15c5f462eb0720ba1040ec37e3268c103d044bed6', 'admin', '2024-10-30 15:23:06'),
(4, '3198472355328', 'Rennard Adityatama', 'rennard@gmail.com', '08123556789', '291ddc90c349b060b5b08c6436a6e1a8;e6b24da9856458ee2f9014b8173fd96155e765395117bddc18a780e58ba240fc', 'participant', '2024-11-02 17:06:59'),
(5, '2192734124439', 'Danish Sulistyo', 'danish123@gmail.com', '086789283424', '88fac62a5fabf0a52618f681121308a7;73af6d1e34023a923cb8f9dc069924332a48564ab0058c9359be1556f4d70208', 'participant', '2024-11-02 17:07:51'),
(6, '2178461948713', 'Ayu Syafira', 'ayu@smkn71jakarta.sch.id', '082163731343', 'f3f99a207ac209fad93556adab125a91;9b8fba96617acd5efaaba7dd06af46d4b0a7522e40fd9389dbc0650c5695b2e7', 'participant', '2024-11-02 17:08:22'),
(7, '21847134698542', 'Laisa Almani', 'laisa@admin.com', '082184687443', 'fdb05dcfeb41d2afaa15e67630065fbb;053282259204fcb97e80744521f34f7cc861e94241f048c52165ddd2d9ed519d', 'admin', '2024-11-02 17:09:00'),
(8, '2748135235275', 'Reyhan Saputra', 'rey@reayhan.blog', '0832994242452', 'e1f758d27a4295866354fe1ffdce5532;3865b46dd69f722b7a503822b6bedc5470b27cdd1a0eb338dfa4c195ede5201d', 'participant', '2024-11-02 17:09:37'),
(9, '31349713492', 'Ilmi tantrum', 'ilmi@6727.com', '00349023948', '592c4c1d4de08ff569f18340d3a50521;8b2c4432730d58bee31a59bc937402604df91d3e0854b2f5501caa0dd77b642c', 'participant', '2024-11-03 13:29:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_code` (`certificate_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `certificate_template_id` (`certificate_template_id`);

--
-- Indexes for table `certificate_fields`
--
ALTER TABLE `certificate_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificate_id` (`certificate_id`);

--
-- Indexes for table `certificate_templates`
--
ALTER TABLE `certificate_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploader_id` (`uploader_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `certificate_fields`
--
ALTER TABLE `certificate_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `certificates_ibfk_3` FOREIGN KEY (`certificate_template_id`) REFERENCES `certificate_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `certificate_fields`
--
ALTER TABLE `certificate_fields`
  ADD CONSTRAINT `certificate_fields_ibfk_1` FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `certificate_templates`
--
ALTER TABLE `certificate_templates`
  ADD CONSTRAINT `certificate_templates_ibfk_1` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
