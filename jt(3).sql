-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 12, 2026 at 07:40 PM
-- Server version: 10.11.14-MariaDB-0+deb12u2
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jt`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_old_sessions` ()   DELETE FROM sessions WHERE ADDTIME(sessions.created_at, "06:00:00") < CURRENT_TIMESTAMP()$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'Lietotaja ID',
  `pid` int(11) NOT NULL COMMENT 'Post ID',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Ierakstu Likes Tabula';

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'Lietotaja ID',
  `title` varchar(255) NOT NULL COMMENT 'Ieraksta Nosaukums',
  `content` varchar(255) NOT NULL COMMENT 'Ieraksta Saturs',
  `likes` int(11) DEFAULT 0 COMMENT 'Saistitu likes Ierakstu skaits',
  `image` varchar(255) DEFAULT NULL COMMENT 'Ieraksta Bildes Path',
  `post_image` varchar(255) DEFAULT NULL COMMENT 'Ieraksta Otras Bildes Path',
  `post_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Ierakstu Tabula';

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `cookie` varchar(255) NOT NULL COMMENT 'Sesijas Cookie',
  `uid` int(11) NOT NULL COMMENT 'Lietotaja ID',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Time when the session was created.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lietotaja Sesijas Tabula';

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `cookie`, `uid`, `created_at`) VALUES
(15, 'fdc0aa63a1d8265a7d43d19e96bc9f9b41fbf8364f102683eb4d7c76dac7dc04', 12, '2026-03-13 17:31:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL COMMENT 'Lietotaja Nosaukums',
  `email` varchar(255) DEFAULT NULL COMMENT 'Lietotaja E-Pasts',
  `password` varchar(500) NOT NULL COMMENT 'Lietotaja Parole',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lietotaju Tabula';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(12, 'Testuser', 'test@test.test', '$2y$10$ZxsMFPxGaAoa3ovD3/RxBOi1Goi3KIpDbdrTYSuWP8uTAgJ2YmL0K', '2026-02-26 02:10:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `clear_old_sesions_sometimes` ON SCHEDULE EVERY 3 HOUR STARTS '2026-02-21 18:48:07' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM sessions WHERE ADDTIME(sessions.created_at, "06:00:00") < CURRENT_TIMESTAMP()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
