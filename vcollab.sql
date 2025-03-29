-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2025 at 10:35 AM
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
-- Database: `vcollab`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE `active_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `room_id` varchar(50) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `active_users`
--

INSERT INTO `active_users` (`id`, `username`, `room_id`, `last_active`) VALUES
(1, 'Parth Patil', '67e73a45a43f4', '2025-03-29 00:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `join_form`
--

CREATE TABLE `join_form` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `join_form`
--

INSERT INTO `join_form` (`id`, `username`, `email`, `room_id`, `joined_at`, `role`) VALUES
(1, 'Parth Patil', 'parthpatil28.8.2007@gmail.com', 'CELB4U', '2025-03-29 02:31:32', NULL),
(2, 'Parth Patil', 'parthpatil28.8.2007@gmail.com', 'BF52TJ', '2025-03-29 02:34:17', NULL),
(3, 'Omkar Temgire', 'omkartemgire00@gmail.com', 'BF52TJ', '2025-03-29 02:45:31', NULL),
(4, 'sanskruti', 'sans@gmail.com', 'BF52TJ', '2025-03-29 02:57:23', NULL),
(0, 'Omya', 'omya@gmail.com', 'BF52TJ', '2025-03-29 03:42:25', NULL),
(0, 'vishu', 'vishu@gmail.com', 'BF52TJ', '2025-03-29 03:46:52', NULL),
(0, 'pov', 'pov@gmail.com', 'BF52TJ', '2025-03-29 04:15:53', NULL),
(0, 'Hitman', 'rohit@gmail.com', 'BF52TJ', '2025-03-29 04:20:45', NULL),
(0, 'hakjb', 'jba@gmail.com', 'BF52TJ', '2025-03-29 04:31:27', NULL),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'S9WPH1', '2025-03-29 08:01:33', 'creator'),
(0, 'Omya', 'omkartemgire00@gmail.com', 'S9WPH1', '2025-03-29 08:03:08', NULL),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'B8C76U', '2025-03-29 08:06:57', 'creator'),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'F89JMV', '2025-03-29 08:16:24', 'creator'),
(0, 'Parth patil', 'parthpatil28.8.2007@gmail.com', '1DBA0J', '2025-03-29 08:16:44', 'creator'),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'TQJVMF', '2025-03-29 08:50:28', 'creator'),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'FA1NR6', '2025-03-29 08:51:43', 'creator'),
(0, 'Omya', 'pov@gmail.com', 'PZTXJO', '2025-03-29 08:55:40', 'creator'),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'D10FU4', '2025-03-29 09:04:13', 'creator'),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'MFECWB', '2025-03-29 09:13:10', 'creator'),
(0, 'Omya', 'omkartemgire00@gmail.com', 'MFECWB', '2025-03-29 09:14:45', NULL),
(0, 'Parth', 'parthpatil28.8.2007@gmail.com', 'W28V6Z', '2025-03-29 09:16:45', 'creator'),
(0, 'Omya', 'omkartemgire00@gmail.com', 'W28V6Z', '2025-03-29 09:17:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_id`, `created_at`) VALUES
(1, 'S2HORT', '2025-03-29 01:18:42'),
(2, 'R71ZB6', '2025-03-29 01:23:36'),
(3, 'UVE1DQ', '2025-03-29 01:31:07'),
(4, 'IAUR81', '2025-03-29 01:37:11'),
(5, 'YC7QSJ', '2025-03-29 01:46:34'),
(6, 'ADH870', '2025-03-29 01:50:30'),
(7, 'AU1MVG', '2025-03-29 01:55:05'),
(8, 'W9FZHI', '2025-03-29 01:57:44'),
(9, 'NGKLVW', '2025-03-29 02:06:29'),
(10, '73641V', '2025-03-29 02:21:16'),
(11, 'CELB4U', '2025-03-29 02:31:12'),
(12, 'BF52TJ', '2025-03-29 02:33:41'),
(0, 'UI6CXA', '2025-03-29 04:02:56'),
(0, '3BJ5TM', '2025-03-29 04:03:39'),
(0, 'PO8FR5', '2025-03-29 04:03:44'),
(0, '7958GW', '2025-03-29 04:15:23'),
(0, 'QHB9L2', '2025-03-29 04:22:31'),
(0, '17I0XH', '2025-03-29 05:24:57'),
(0, '032LXB', '2025-03-29 06:57:48'),
(0, 'IKFYNW', '2025-03-29 07:57:24'),
(0, 'S9WPH1', '2025-03-29 08:01:33'),
(0, 'B8C76U', '2025-03-29 08:06:57'),
(0, 'F89JMV', '2025-03-29 08:16:24'),
(0, '1DBA0J', '2025-03-29 08:16:44'),
(0, 'TQJVMF', '2025-03-29 08:50:28'),
(0, 'FA1NR6', '2025-03-29 08:51:43'),
(0, 'PZTXJO', '2025-03-29 08:55:40'),
(0, 'D10FU4', '2025-03-29 09:04:13'),
(0, 'MFECWB', '2025-03-29 09:13:10'),
(0, 'W28V6Z', '2025-03-29 09:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `room_users`
--

CREATE TABLE `room_users` (
  `id` int(11) NOT NULL,
  `room_code` varchar(6) NOT NULL,
  `username` varchar(50) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_img` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `file_path`, `created_at`, `profile_img`) VALUES
(6, 'Parth', 'parthpatil28.8.2007@gmail.com', '8252800701', '$2y$10$Na7hZjR.afuWi/kY.h4tUOBe6T4asd3WSR1cFtWrlcAvKb.yviN0a', '', '2025-03-28 08:54:46', 'uploads/profile_pics/67e663d6462b7_Screenshot 2025-03-27 143729.png'),
(7, 'pov', 'pov@gmail.com', '8252800701', '$2y$10$rhcahUYZNrrVM1x.XCv5q.ebRWMJ7WjkCfjLv0BUHQ.kJdhfGrpUC', '', '2025-03-29 04:14:50', 'uploads/profile_pics/67e773ba9259f_photo.jpg'),
(9, 'Omya', 'omya@gmail.com', '8252800701', '$2y$10$ZJSxtqkUj0nIscNhsia3n.zAqp/4ri7K31rHQVG2Yn2gV5CejB/62', '', '2025-03-29 08:54:41', 'uploads/profile_pics/67e7b5519874a_Screenshot 2025-03-27 143729.png'),
(10, 'darshana', 'jba@gmail.com', '8252800701', '$2y$10$hXa/JfBx4OWalTBHFyxrauMWE6yVLxgTbZtFJBpa2Kh/q9v4LLKEe', '', '2025-03-29 09:13:59', 'uploads/profile_pics/67e7b9d76d95b_Screenshot 2025-03-27 143729.png');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
