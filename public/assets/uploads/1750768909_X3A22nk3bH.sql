-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 02:33 PM
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
-- Database: `todouser_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `task_logs`
--

CREATE TABLE `task_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` varchar(255) NOT NULL,
  `log_description` longtext DEFAULT NULL,
  `added_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_logs`
--

INSERT INTO `task_logs` (`id`, `task_id`, `log_description`, `added_by`, `created_at`, `updated_at`) VALUES
(9, 'TASK-20250623-AJSS', 'Task created', 'IN-001*Bindu M Agarwal', '2025-06-23 07:27:58', '2025-06-23 07:27:58'),
(10, 'TASK-20250623-AJSS', 'Document uploaded: 1750683478_JnisKj3feZ.sql', 'IN-001*Bindu M Agarwal', '2025-06-23 07:27:58', '2025-06-23 07:27:58'),
(11, 'TASK-20250623-AJSS', 'Document uploaded: 1750683478_qGCtUmC8s1.jpeg', 'IN-001*Bindu M Agarwal', '2025-06-23 07:27:58', '2025-06-23 07:27:58'),
(12, 'TASK-20250623-8PKP', 'Task created', 'IN-001*Bindu M Agarwal', '2025-06-23 07:28:51', '2025-06-23 07:28:51'),
(13, 'TASK-20250623-8PKP', 'Document uploaded: 1750683531_TT3oj9CFX1.csv', 'IN-001*Bindu M Agarwal', '2025-06-23 07:28:51', '2025-06-23 07:28:51'),
(14, 'TASK-20250623-8PKP', 'Document uploaded: 1750683531_rLE61chJnd.jpeg', 'IN-001*Bindu M Agarwal', '2025-06-23 07:28:51', '2025-06-23 07:28:51'),
(15, 'TASK-20250623-YLYW', 'Document uploaded: media 17.jpeg', 'IN-001*Bindu M Agarwal', '2025-06-24 05:46:56', '2025-06-24 05:46:56'),
(16, 'TASK-20250623-B0QD', 'Document uploaded: media 17.webp', 'IN-001*Bindu M Agarwal', '2025-06-24 05:56:53', '2025-06-24 05:56:53'),
(17, 'TASK-20250623-B0QD', 'Document uploaded: Samurai VS Dragon.mp4', 'IN-001*Bindu M Agarwal', '2025-06-24 06:01:18', '2025-06-24 06:01:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `task_logs`
--
ALTER TABLE `task_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `task_logs`
--
ALTER TABLE `task_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
