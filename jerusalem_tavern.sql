-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 05, 2026 at 12:24 AM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jerusalem_tavern`
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

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `uid`, `pid`, `created_at`) VALUES
(11, 14, 58, '2026-05-16 22:46:00'),
(12, 14, 57, '2026-05-16 22:46:03'),
(13, 14, 65, '2026-05-17 23:13:42'),
(14, 14, 66, '2026-05-17 23:32:32'),
(15, 15, 69, '2026-05-18 01:06:06'),
(16, 15, 68, '2026-05-18 01:06:09'),
(17, 15, 67, '2026-05-18 01:06:11'),
(18, 14, 71, '2026-05-18 01:13:40'),
(19, 15, 71, '2026-05-18 01:13:57');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'Lietotaja ID',
  `title` varchar(255) NOT NULL COMMENT 'Ieraksta Nosaukums',
  `content` text NOT NULL COMMENT 'Ieraksta Saturs',
  `likes` int(11) DEFAULT 0 COMMENT 'Saistitu likes Ierakstu skaits',
  `image` varchar(255) DEFAULT NULL COMMENT 'Ieraksta Bildes Path',
  `post_image` varchar(255) DEFAULT NULL COMMENT 'Ieraksta Otras Bildes Path',
  `post_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Ierakstu Tabula';

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `uid`, `title`, `content`, `likes`, `image`, `post_image`, `post_date`) VALUES
(67, 14, 'Testa Ieraksts', '&Scaron;is ir testa ieraksts lai pārbaudītu ka ieraksti darbojas.', 1, NULL, NULL, '2026-05-18 01:03:10'),
(68, 15, 'Testa Ieraksts no cita lietotāja.', '&Scaron;is ieraksts demonstrē ka ierakstus var veidot citi atsevi&scaron;ķi lietotāji!', 1, NULL, NULL, '2026-05-18 01:04:00'),
(69, 15, 'Ieraksts ar bildi.', '&Scaron;is ieraksts demonstrē ka ierakstam var būt pielikta klāt bilde! Bildes nerādīsies &quot;Top Posts&quot; sadaļā, bet uz galvenās lapas tās rādīsies.', 1, NULL, 'fa7aec4efb728534ef32c172197c9560097c6d0e4893fe6b20242a566ef033d1251-1.jpg', '2026-05-18 01:04:52'),
(70, 14, 'Informācija par DBPS!', 'Datubāzu pārvaldības sistēma (DBPS), arī datubāzu pārvaldības sistēma (angļu: Database management system (DBMS)), ir programma (vai programmu kopums), kas nodro&scaron;ina datubāzu pārvaldību. DBPS ļauj ievietot, nolasīt, modificēt un dzēst ierakstus, kā arī veic citas ar datu glabā&scaron;anu un apstrādi saistītas darbības datubāzē.', 0, NULL, NULL, '2026-05-18 01:11:34'),
(71, 14, 'Mājaslapas datu plūsma.', 'Mājaslapā ir vienkār&scaron;a datu plūsma. Ir interesanti zināt ka mājaslapa nelieto nekādu framework, tādēļ katrai funkcijai ir sava PHP lapa.', 2, NULL, '6bcaea9882504292b2f6ea37a84b215463e71ab73b824ee90ecdc10c8dde71eddatuplusmudiagramma.png', '2026-05-18 01:12:47'),
(72, 14, 'Alrgoritms', '&Scaron;ajā algoritmā attēlots kas notiek kad tu spied &quot;Like&quot; pogu.', 0, NULL, 'a5af16fb4a4856cc3f8530b5214830a85103fb5a515b39b93e652c0a142363eelikesAlgoritms.png', '2026-05-18 01:13:31'),
(73, 14, 'Test post', 'Test', 0, NULL, NULL, '2026-05-18 03:47:00'),
(74, 14, '', 'test', 0, NULL, NULL, '2026-05-18 03:47:03');

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
(25, 'b95bb9ef9edbe6bc9baa102fb3bfa65be593052b38b98e146f22d0b04e93ebab', 14, '2026-05-17 23:50:02'),
(31, '4c0dc160cbb4d3e84f08547d965aae8620d620e3fa659682771a47687ba49f46', 14, '2026-05-18 04:08:56'),
(32, 'ee284abace251e024f2473e8d15a87b184b76e5f235e560528b037a418d5c5af', 14, '2026-06-05 02:36:44');

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
(14, 'Testuser', '', '$2y$12$4g7TCSWnFhm0TQOVj9g.r.0ZPFcI9U/YPxZTRgnXGA2NvSlOJ21PC', '2026-04-12 22:59:20'),
(15, 'Testuser2', '', '$2y$12$6rZ0MhBtfg/rXEHto6Fl0uec1vghp4xRZN9Ru9nMOqarI5.IO8l8O', '2026-05-18 01:03:25');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
