-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2023 at 08:21 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `info_cast`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activity_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activity_id`, `user_id`, `action`, `created_at`) VALUES
(9, 1, '<strong>Turned OFF</strong> Teacher registration.', '2023-07-20 14:26:48'),
(10, 1, '<strong>Turned ON</strong> Teacher registration.', '2023-07-20 14:27:00'),
(11, 1, '<strong>New Course:</strong> \'(dwdw) Awd\' <strong>Status:</strong> Active.', '2023-07-20 14:27:13'),
(13, 1, '<strong>Edited Course: </strong> \'(awdawd) Aawdd\' to \'(awd) Aawddd\' <strong>Status:</strong> \'Active\' to \'Active\'', '2023-07-20 14:32:45'),
(14, 1, '<strong>Edited Course: </strong> \'(awd) Aawddd\' to \'(awd) Aawddd\' <strong>Status:</strong> \'Active\' to \'Inactive\'', '2023-07-20 14:34:30'),
(15, 1, '<strong>Edited Course: </strong> \'(awd) Aawddd\' to \'(awd) Aawddd\' <strong>Status:</strong> \'Inactive\' to \'Active\'', '2023-07-20 14:34:46'),
(16, 1, '<strong>Added new admin:</strong> \'Awd Awd Awd\'.', '2023-07-20 14:36:04'),
(17, 1, '<strong>Deleted course:</strong> \'(awd) Aawddd\'.', '2023-07-20 14:37:23'),
(18, 1, '<strong>Deleted course:</strong> \'(awdawd) Awd\'.', '2023-07-20 14:37:48'),
(19, 1, '<strong>Deleted course:</strong> \'(dwdw) Awd\'.', '2023-07-20 14:38:04'),
(20, 1, '\'Admin Admin\' <strong>Logged out</strong>.', '2023-07-20 14:39:26'),
(21, 10, '\'Awd Awd Awd\' <strong>Logged in</strong>.', '2023-07-20 14:39:51'),
(22, 1, '\'Admin Admin\' <strong>Logged out</strong>.', '2023-07-20 23:09:30'),
(23, 1, '\'Admin Admin\' <strong>Logged in</strong>.', '2023-07-20 23:13:56'),
(24, 1, '<strong>Approve</strong> \'Test Test Tst\' verification.', '2023-07-21 06:39:49'),
(25, 1, '\'Admin Admin\' <strong>Logged out</strong>.', '2023-07-21 13:25:49'),
(26, 1, '\'Admin Admin\' <strong>Logged in</strong>.', '2023-07-21 13:39:33'),
(27, 1, '\'Admin Admin\' <strong>Logged out</strong>.', '2023-07-22 11:04:19'),
(28, 1, '\'Admin Admin\' <strong>Logged in</strong>.', '2023-07-22 11:25:20'),
(29, 1, '\'Admin Admin\' <strong>Logged in</strong>.', '2023-07-22 11:54:36'),
(30, 1, '\'Admin Admin\' <strong>Logged in</strong>.', '2023-07-23 03:57:30'),
(31, 1, '<strong>Decline</strong> \'Test Test Tst\' verification.', '2023-07-23 03:57:43'),
(32, 1, '<strong>Decline</strong> \'Test Test Tst\' verification.', '2023-07-23 04:03:38'),
(33, 1, '<strong>Decline</strong> \'Test Test Tst\' verification.', '2023-07-23 04:08:48'),
(34, 1, '<strong>Decline</strong> \'Test Test Tst\' verification.', '2023-07-23 04:20:31'),
(35, 11, '\'Test Test Tst\' <strong>Logged out</strong>.', '2023-07-23 06:18:43'),
(36, 1, '\'Admin Admin\' <strong>Logged in</strong>.', '2023-07-23 06:18:52'),
(37, 1, '<strong>Turned OFF</strong> Teacher registration.', '2023-07-23 06:19:21'),
(38, 1, '<strong>Turned ON</strong> Teacher registration.', '2023-07-23 06:19:32');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `acronym` varchar(32) NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `name`, `acronym`, `status`) VALUES
(1, 'Bachelor Of Science In Information Technology', 'BSIT', 'active'),
(2, 'Bachelor Of Science In Hospitality Management', 'BSHM', 'active'),
(3, 'Bachelor In Elementary Education', 'BEED', 'active'),
(4, 'Bachelor Of Secondary Education', 'BSEd', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `created_by`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 11, 'Your account verification was declined by the Admin.', '2023-07-23 04:08:48'),
(2, 1, 11, 'Your account verification was declined by the Admin.', '2023-07-23 04:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `teacher_reg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `teacher_reg`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(250) NOT NULL,
  `mname` varchar(250) DEFAULT NULL,
  `lname` varchar(250) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `year` int(32) DEFAULT NULL,
  `section` varchar(32) DEFAULT NULL,
  `sy` varchar(32) DEFAULT NULL,
  `email` varchar(250) NOT NULL,
  `contact` varchar(32) DEFAULT NULL,
  `role` enum('student','teacher','admin','alumni') NOT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `isNew` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `set_graduate_at` date DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT NULL COMMENT '0 = non-verified \r\n1 = pending\r\n2 = approved\r\n3 = declined',
  `verification_img` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `mname`, `lname`, `course_id`, `year`, `section`, `sy`, `email`, `contact`, `role`, `avatar`, `password`, `isNew`, `created_at`, `set_graduate_at`, `is_verified`, `verification_img`) VALUES
(1, 'Admin', NULL, 'Admin', NULL, NULL, NULL, NULL, 'admin@admin.com', '09876543', 'admin', NULL, '$argon2i$v=19$m=65536,t=4,p=1$RUZKYi8vdEZPZTIzRGJlSQ$nu99vTpWpkgmgcqbXBHo9g07+JvQLrCuupNq0t3Kkw4', 0, '2023-07-22 10:50:30', NULL, NULL, NULL),
(6, 'Awd', 'Awd', 'Awd', 1, 4, 'A', '2022-23', 'test@email.com', '0987654321', 'alumni', NULL, '$argon2i$v=19$m=65536,t=4,p=1$a0NtSkRjYzJkRHVOY3I4Yw$+a7zA6nspFN8UYUhL8IyYN254aqwbjpLBF4AiY6/9wY', 0, '2023-07-21 03:39:22', '2023-07-07', 2, NULL),
(8, 'Test', 'Test', 'Test', NULL, NULL, NULL, NULL, 'montemar@gmail.com', '098765432', 'admin', '07072023-011404_5996c14479318.jpg', '$argon2i$v=19$m=65536,t=4,p=1$RWhEQkxmTDhZaDFvWXJ3cg$MJT7M5t9H4zntYiiUXP1Aqw3UkFQd/+qnyzrzw6U91Q', 0, '2023-07-20 00:30:53', NULL, NULL, NULL),
(9, 'Test', 'Test', 'Test', 1, NULL, NULL, NULL, 'test@test.com', '0987654321', 'teacher', NULL, '$argon2i$v=19$m=65536,t=4,p=1$TlRPUi5Cak8ySjdDaUtBWQ$x3cB6DE/qqJ+QGwx0Deh74orvuubsb37CuK6Rt8nzAA', 0, '2023-07-19 23:56:50', NULL, NULL, NULL),
(10, 'Awd', 'Awd', 'Awd', NULL, NULL, NULL, NULL, 'test1@email.com', '0987654321', 'admin', NULL, '$argon2i$v=19$m=65536,t=4,p=1$TWlkTmw3QVRSUndFeWhjWQ$at98xOL9kPyQV4Opqhi0K/nQjURQKG5Nfsf3iDIpQJo', 0, '2023-07-20 14:40:19', NULL, NULL, NULL),
(11, 'Test', 'Test', 'Tst', 1, 4, 'B', '2022-23', 'test_student@gmail.com', '0987654321', 'student', NULL, '$argon2i$v=19$m=65536,t=4,p=1$RWlqQmhkZGUzRTVwOTRKTw$JY8Pw8dS+xexa16m6zcE/3f6qroRPM65csW2MN2XJCg', 0, '2023-07-23 04:20:31', NULL, 3, '07232023-122012_lockscreen.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
