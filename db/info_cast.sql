-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2023 at 09:59 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `course`
--
DROP TABLE IF EXISTS `course`;
CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `acronym` varchar(32) NOT NULL,
  `status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `avatar` varchar(250) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `isNew` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `mname`, `lname`, `course_id`, `year`, `section`, `sy`, `email`, `contact`, `role`, `created_at`, `avatar`, `password`, `isNew`) VALUES
(1, 'Admin', NULL, 'Admin', NULL, NULL, NULL, NULL, 'admin@admin.com', NULL, 'admin', '2023-07-04 23:10:02', NULL, '$argon2i$v=19$m=65536,t=4,p=1$RUZKYi8vdEZPZTIzRGJlSQ$nu99vTpWpkgmgcqbXBHo9g07+JvQLrCuupNq0t3Kkw4', 0),
(4, 'Test', 'Test', 'Montemar', NULL, NULL, NULL, NULL, 'montemar@gmail.com', '098653', 'admin', '2023-07-06 00:19:41', NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
