-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 10:47 AM
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
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` varchar(255) NOT NULL,
  `title` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `task_list` longtext DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `priority` varchar(255) DEFAULT NULL,
  `is_recurring` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `frequency_duration` longtext DEFAULT NULL,
  `reminders` varchar(255) DEFAULT NULL,
  `links` longtext DEFAULT NULL,
  `assign_to` varchar(255) DEFAULT NULL,
  `assign_by` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `final_status` varchar(255) DEFAULT NULL,
  `remarks` longtext DEFAULT NULL,
  `ratings` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_id`, `title`, `description`, `task_list`, `department`, `priority`, `is_recurring`, `frequency`, `frequency_duration`, `reminders`, `links`, `assign_to`, `assign_by`, `status`, `final_status`, `remarks`, `ratings`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 'TASK-20250623-JHFU', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'IT', 'high', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://todo.isbmerp.co.in/\",\"https://todo.isbmerp.co.in/uploads\"]', '2', '1', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-22 23:50:30', '2025-06-22 23:50:30'),
(2, 'TASK-20250623-2LVR', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'IT', 'high', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://todo.isbmerp.co.in/\",\"https://todo.isbmerp.co.in/uploads\"]', '2', '1', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-22 23:52:33', '2025-06-22 23:52:33'),
(3, 'TASK-20250623-2TTA', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'IT', 'high', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://todo.isbmerp.co.in/\",\"https://todo.isbmerp.co.in/uploads\"]', '2', '1', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 00:26:11', '2025-06-23 00:26:11'),
(4, 'TASK-20250623-KQU6', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 01:43:39', '2025-06-23 01:43:39'),
(5, 'TASK-20250623-IKHG', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 01:51:02', '2025-06-23 01:51:02'),
(6, 'TASK-20250623-MDSF', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 01:56:59', '2025-06-23 01:56:59'),
(7, 'TASK-20250623-FLEO', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:02:24', '2025-06-23 02:02:24'),
(8, 'TASK-20250623-D1NR', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:03:37', '2025-06-23 02:03:37'),
(9, 'TASK-20250623-XA4S', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:04:00', '2025-06-23 02:04:00'),
(10, 'TASK-20250623-BPL8', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:04:36', '2025-06-23 02:04:36'),
(11, 'TASK-20250623-ANWU', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:05:08', '2025-06-23 02:05:08'),
(12, 'TASK-20250623-IMTC', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:05:25', '2025-06-23 02:05:25'),
(13, 'TASK-20250623-QMBL', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:05:59', '2025-06-23 02:05:59'),
(14, 'TASK-20250623-BURI', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:06:45', '2025-06-23 02:06:45'),
(15, 'TASK-20250623-YRBK', 'new task', 'Task Information', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:22:17', '2025-06-23 02:22:17'),
(16, 'TASK-20250623-N8EX', 'new task', 'Task Information\r\nnew task', '[\"Task 1\",\"Assignment 1\"]', 'HR', 'medium', '0', 'Weekly', '[\"Sunday\",\"Tuesday\",\"Thursday\",\"Saturday\"]', '[\"Email\",\"WhatsApp\",\"Telegram\"]', '[\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146-b4a35f44bf0d\",\"https://chat.deepseek.com/a/chat/s/dd2c57c8-16a7-4943-b146\"]', '1', 'IN-001*TEST', 'Pending', 'Pending', NULL, NULL, NULL, '2025-06-23 02:33:09', '2025-06-23 02:33:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
