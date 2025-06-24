-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 11:20 AM
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
-- Table structure for table `personal_tasks`
--

CREATE TABLE `personal_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` varchar(255) NOT NULL,
  `title` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `priority` varchar(255) DEFAULT NULL,
  `is_recurring` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `frequency_duration` longtext DEFAULT NULL,
  `reminders` varchar(255) DEFAULT NULL,
  `assign_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `due_date` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_tasks`
--

INSERT INTO `personal_tasks` (`id`, `task_id`, `title`, `description`, `priority`, `is_recurring`, `frequency`, `frequency_duration`, `reminders`, `assign_by`, `status`, `notes`, `due_date`, `created_at`, `updated_at`) VALUES
(2, 'TASK-20250623-B0QD', 'this is monthly taskDSv', 'zxczczc', 'medium', '0', 'Weekly', NULL, '[\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\"]', '1', 'Pending', '[{\"timestamp\":\"2025-06-20 11:27:21\",\"content\":\"hiee\",\"id\":\"note_6855297934e217.78180909\"}]', NULL, '2025-06-23 05:36:16', '2025-06-23 05:36:16'),
(3, 'TASK-20250623-QHKK', 'this is monthly taskDSv', 'this is form desc', 'high', '0', 'Weekly', NULL, '[\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\"]', '1', 'Pending', NULL, NULL, '2025-06-23 05:52:38', '2025-06-23 05:52:38'),
(4, 'TASK-20250623-YLYW', 'this is monthly taskDSv', 'this is decriopion', 'medium', '0', 'Weekly', NULL, '[\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\"]', '1', 'Pending', NULL, NULL, '2025-06-23 06:02:06', '2025-06-23 06:02:06'),
(5, 'TASK-20250623-BBWC', 'this is monthly taskDSv', 'test', 'medium', '0', 'Weekly', NULL, '[\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\"]', '1', 'Pending', NULL, '', '2025-06-23 06:45:09', '2025-06-23 06:45:09'),
(6, 'TASK-20250623-6QIM', 'this is  task title', 'this is  task decription', 'medium', '0', 'Weekly', NULL, '[\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\"]', 'IN-001*Bindu M Agarwal', 'Pending', NULL, NULL, '2025-06-23 07:14:38', '2025-06-23 07:14:38'),
(7, 'TASK-20250623-AJSS', 'this is  task title', 'this is  task decription', 'medium', '0', 'Weekly', NULL, '[\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\"]', 'IN-001*Bindu M Agarwal', 'Pending', NULL, NULL, '2025-06-23 07:27:58', '2025-06-23 07:27:58'),
(8, 'TASK-20250623-8PKP', 'this is monthly taskDSv', 'csacsac', 'medium', '0', 'Weekly', NULL, '[\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\",\"Email\",\"WhatsApp\",\"Telegram\"]', 'IN-001*Bindu M Agarwal', 'Pending', NULL, NULL, '2025-06-23 07:28:51', '2025-06-23 07:28:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `personal_tasks`
--
ALTER TABLE `personal_tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `personal_tasks`
--
ALTER TABLE `personal_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
