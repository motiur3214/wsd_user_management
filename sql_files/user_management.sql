-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 07, 2024 at 10:26 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint NOT NULL COMMENT '1=admin, 2=user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$8yZNlmmoAB0MvasOEGNRHuBhIe4XGoSYvT7wpJMwGNfvDt.KzSBmq', 1),
(2, 'user1', 'user1@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(3, 'user2', 'user2@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(4, 'user4', 'user4@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(5, 'user5', 'user5@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(6, 'user6', 'user6@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(7, 'user7', 'user7@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(8, 'user8', 'user8@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(9, 'user9', 'user9@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2),
(10, 'user10', 'user10@gmail.com', '$2y$10$J726xVXvaBkgf7B/t6ClFOF5AUEjp64n/vOdEUjfQc1v93G7aCC/6', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
