-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 03, 2025 at 03:37 PM
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
-- Database: `it_ticket_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(3, 'Sikutwa', '$2y$10$Q1nx8HEutr.biePi3EPYhuG61VytjadZVcLB14VaT7r5/1aiQ0pYa', '2025-10-21 23:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_log`
--

CREATE TABLE `password_reset_log` (
  `id` int(11) NOT NULL,
  `regno` varchar(50) NOT NULL,
  `reset_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_log`
--

INSERT INTO `password_reset_log` (`id`, `regno`, `reset_time`) VALUES
(1, 'EB1-2343-2025', '2025-11-02 18:42:28'),
(2, 'EB1-2343-2025', '2025-11-02 18:46:40'),
(3, 'EB1-2343-2025', '2025-11-02 18:48:16'),
(4, 'EB3/61607/22', '2025-11-02 19:26:25');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staffno` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_code` varchar(10) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staffno`, `fullname`, `email`, `department`, `specialization`, `role`, `phone`, `password`, `created_at`, `reset_code`, `reset_expires`) VALUES
(1, 'GPD457', 'EDNA TOO', 'edna@gmail.com', 'COMPUTER SCIENCE', 'machine lerning', 'Lecturer', '0746854250', '$2y$10$LYd8RGVSe.yTbY.6HiDyq.DtTJssIxrwgHeCjw.sBfGVgYzrLkFDm', '2025-10-24 13:02:29', NULL, NULL),
(2, 'GPD548', 'Folix', 'jey@gmail.com', 'BUSINESS', 'machine lerning', 'Administrator', '0701603497', '$2y$10$Mg6hhpmVfz.XmLeajwuRz.4rNW0pofRSuwQr5ksyoIsOtSZoV1DPW', '2025-10-24 13:05:17', NULL, NULL),
(3, 'GPD112', 'Gakii Emily', 'emily@gmail.com', 'COMPUTER SCIENCE', 'machine lerning', 'Lecturer', '0723456567', '$2y$10$oiDuEqgsjkWxGkjwllo1Oe2JQ8GDVbFD.P/JtyBngQl2dewqed6ci', '2025-10-29 10:47:42', NULL, NULL),
(4, 'GPD453', 'Otula Kero', 'otula@gmail.com', 'COMPUTER SCIENCE', 'Networking', 'Lecturer', '0787654563', '$2y$10$umixKp1jIaLgx6FBae6ppOD3j5o0N4YqHYSDutzmYvxX.fOuHYREC', '2025-10-29 10:57:17', NULL, NULL),
(5, 'GPD65M', 'Mr. Wandera', 'wandera@gmail.com', 'BUSINESS', 'Finance', 'Administrator', '0723456732', '$2y$10$e102t76dnFuEdTzGY/wnm.y442d0bC/R3noLtX/xXQXyOOD4iANjK', '2025-10-29 11:03:44', NULL, NULL),
(6, 'GPD111', 'Jey Folix', 'jeysikutwa@gmail.com', 'COMPUTER SCIENCE', 'machine lerning', 'Administrator', '0701603497', '$2y$10$KUlHnmx.wTXhureHKwaIm.nEddF71.gebvBS1XEuqUM19rR.bcK6.', '2025-10-31 18:41:24', NULL, NULL),
(7, 'GPD113', 'Brian Njeru', 'brianmwenda05@gmail.com', 'COMPUTER SCIENCE', 'machine lerning', 'Lecturer', '0701603498', '$2y$10$GZJEQ03FX0oFceRMarUTWO7un1pPhds3MT6W1NQ7HKLc07TIUCIgi', '2025-10-31 18:54:37', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `regno` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `faculty` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `reset_code` varchar(10) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `fullname`, `email`, `regno`, `phone`, `faculty`, `department`, `course`, `password_hash`, `reset_code`, `reset_expires`, `created_at`) VALUES
(1, 'Folix Sikutwa Nyongesa', 'sikutwafolix22@gmail.com', 'EB1-2343-2025', '0115663270', 'Faculty of Science & Technology', 'ict', 'IT', '$2y$10$5i1nMEHLvlbB4p4hwJUaeufn2pMseMTQ4uXFfYax9ATcO6YR5gos.', '101527', '2025-11-02 16:58:35', '2025-10-15 06:04:32'),
(2, 'Brian Mwenda', 'brian@gmail.com', 'EB3-61542-22', '0727563453', 'Faculty of Science & Technology', 'COMPUTER SCIENCE', 'APPLIED COMPUTER SCIENCE', '$2y$10$yeuvM3yW8L5KtgKT4ICVLuuSUT/76gpSD/XRQUx1MaCbHUungAlMu', NULL, NULL, '2025-10-15 06:22:24'),
(3, 'mikel brian mutuso', 'mikel@gmail.com', 'EB3/61547/22', '0115663270', 'Faculty of Education & Arts', 'COMPUTER SCIENCE', 'APPLIED COMPUTER SCIENCE', '$2y$10$CToCU/9oRehkF4fu3g6xMO737lMqypksmnSGgQSey71zi04XhhaK2', NULL, NULL, '2025-10-15 09:10:29'),
(4, 'Barbra Nanjala', 'barbra@gmail.come', 'EB3/61587/22', '0701603698', 'Faculty of Science & Technology', 'ict', 'APPLIED COMPUTER SCIENCE', '$2y$10$5RyUmkbTX/kWQiVD5MMGa..EWhDowyM9O2YRmQBWcJkJ4b9gYa9KO', NULL, NULL, '2025-10-17 13:25:13'),
(6, 'Aron Keya', 'keya@gmail.com', 'EB3/61542/22', '0703456734', 'Faculty of Business', 'BUSINESS', 'BCOM', '$2y$10$Vm5GkUn8VbbBUTkg6YZ27OsE/ZwANwLp5QRibEXl4RVBqijua09g.', NULL, NULL, '2025-10-20 09:41:06'),
(7, 'Barnado webo', 'benardwebo298@gmail.com', 'HB5/62462/22', '0100945233', 'Faculty of Science & Technology', 'NURSING', 'NUTRITION', '$2y$10$dKgXRA6irvXLT0guV2nP6evi/c3bGuA5a1DWDacHBKweAcOxT37Aa', '903439', '2025-11-02 18:00:26', '2025-10-25 10:37:29'),
(8, 'Shiringa Wayne', 'wayne@gmail.com', 'EB3/61601/22', '0700000000', 'Faculty of Science & Technology', 'COMPUTER SCIENCE', 'APPLIED COMPUTER SCIENCE', '$2y$10$yh7HNDFZbrKDvO950pKSuOlZFvvqowCR6HeQkZXOojNbE9sUmPIv.', NULL, NULL, '2025-10-27 08:09:01'),
(9, 'Mutiso Mikel', 'mutiso@gmail.com', 'EB3/61603/22', '0700000001', 'Faculty of Education & Arts', 'NURSING', 'NUTRITION', '$2y$10$B4LMqbxa4ZCBo/0bJcFqkOSsZISlil.vnkN2yz.EiaZ7AnUoNWLjm', NULL, NULL, '2025-10-27 08:13:08'),
(10, 'Karit Loynold', 'karit@gmail.com', 'EB3/61604/22', '0115663270', 'Faculty of Science & Technology', 'NURSING', 'NUTRITION', '$2y$10$V5vIYZFWS5S2bJHw8Dd0iuHDBhP6AVOa6kjW.45pvvlJ0NObkmuqe', NULL, NULL, '2025-10-29 08:34:50'),
(11, 'John Nyongesa', 'john@gmail.com', 'EB3/61605/22', '254701603497', 'Faculty of Education & Arts', 'NURSING', 'IT', '$2y$10$QAhUE/LVxuQofQ5G7vUOeengFTl6tWMJ5BGTvk.N8r./aADj2oNei', '788525', '2025-11-01 15:43:31', '2025-11-01 12:54:45'),
(12, 'Milcent Nancy', 'jeysikutwa@gmail.com', 'EB3/61606/22', '254776546363', 'Faculty of Education & Arts', 'BUSINESS', 'NUTRITION', '$2y$10$A0R6WDxUUW2d6SppPn4oMeJ6uhw9H1GUrWKgVAqFg4zCR8P9jE8nq', NULL, NULL, '2025-11-01 14:53:01'),
(13, 'Wilberforce', 'wilbarforcemangoli22@gmail.com', 'EB3/61607/22', '254115462345', 'Faculty of Education & Arts', 'Education', 'Education science', '$2y$10$m/Wl6eygRzBPSObxieYy6.mt7p2TSCYVJ3VPCLSEtks94WxbpOn9K', NULL, NULL, '2025-11-02 16:19:54');

-- --------------------------------------------------------

--
-- Table structure for table `student_ticket`
--

CREATE TABLE `student_ticket` (
  `id` int(11) NOT NULL,
  `regno` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `priority` enum('Low','Medium','High') DEFAULT 'Medium',
  `status` enum('pending','assigned','solved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ticket_number` varchar(50) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_ticket`
--

INSERT INTO `student_ticket` (`id`, `regno`, `fullname`, `email`, `phone`, `category`, `title`, `description`, `attachment`, `priority`, `status`, `created_at`, `ticket_number`, `assigned_to`, `assigned_at`) VALUES
(6, 'EB3/61587/22', 'Barbra Nanjala', 'barbra@gmail.come', '0701603698', 'Accommodation', 'hostel', '.lew', '', 'Low', 'solved', '2025-10-17 14:58:43', '23', 1, '2025-10-24 15:33:20'),
(7, 'EB1/61602/22', 'Dennis Peter', 'brianmwenda05@gmail.com', '0712566935', 'Fee Statement request', '41', 'daada', '', 'Medium', 'solved', '2025-10-19 13:35:00', '23', 2, '2025-10-24 15:46:22'),
(8, 'EB3/61542/22', 'Aron Keya', 'keya@gmail.com', '0727563453', 'Fee Statement request', 'fees stractuture', 'i need to downloard fee stracture', '', 'High', 'pending', '2025-10-20 09:42:41', NULL, NULL, NULL),
(9, 'EB3/61542/22', 'Aron Keya', 'keya@gmail.com', '0727563453', 'course registration', 'registration of course', 'am triying to register my course but failes', '', 'High', 'solved', '2025-10-20 09:43:58', '26', 1, '2025-10-25 16:45:50'),
(10, 'HB5/62462/22', 'Barnado webo', 'benard@gmail.com', '0100945233', 'Gate Pass Picking', 'no fee areas', 'i what a gatepass', '', 'Medium', 'pending', '2025-10-25 10:39:06', '24', 2, '2025-10-25 10:43:09'),
(11, 'EB3/61601/22', 'Shiringa Wayne', 'wayne@gmail.com', '0700000000', 'Scholarship', 'fee reflection', 'my scholarship fee is not updated', '', 'High', 'assigned', '2025-10-27 08:10:47', '29', 1, '2025-10-27 08:22:44'),
(12, 'EB3/61603/22', 'Mutiso mikel', 'mutiso@gmail.com', '0700000001', 'Gate Pass Picking', 'i neeed a gatepass', 'to access university environment', '', 'High', 'assigned', '2025-10-27 08:15:33', '45', 1, '2025-10-29 08:05:50'),
(13, 'EB3/61604/22', 'Karit Loynold', 'karit@gmail.com', '0115663270', 'Scholarship', 'my fee', 'i need fee reflection', '', 'High', 'assigned', '2025-10-29 08:36:19', '67', 5, '2025-10-29 11:05:35'),
(14, 'EB3/61603/22', 'Mutiso Mikel', 'mutiso@gmail.com', '0100945233', 'Fee Statement request', 'fee statement', 'i was required to submit my fee statement to my dad', '', 'High', 'pending', '2025-10-29 10:31:53', NULL, NULL, NULL),
(15, 'EB3/61542/22', 'Aron Keya', 'keya@gmail.com', '0701603497', 'missing marks', '40', 'I have missing mark', '', 'Medium', 'assigned', '2025-10-31 11:56:43', '45', 4, '2025-10-31 13:28:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `password_reset_log`
--
ALTER TABLE `password_reset_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staffno` (`staffno`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `regno` (`regno`);

--
-- Indexes for table `student_ticket`
--
ALTER TABLE `student_ticket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assigned_to` (`assigned_to`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `password_reset_log`
--
ALTER TABLE `password_reset_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `student_ticket`
--
ALTER TABLE `student_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `student_ticket`
--
ALTER TABLE `student_ticket`
  ADD CONSTRAINT `fk_assigned_to` FOREIGN KEY (`assigned_to`) REFERENCES `staff` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
