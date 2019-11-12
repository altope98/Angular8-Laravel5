-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2019 at 09:14 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api_rest_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ordenadores', '2019-08-23 00:00:00', '2019-08-24 00:00:00'),
(2, 'moviles y tablets', '2019-08-29 00:00:00', '2019-08-30 00:00:00'),
(3, 'Videojuegos3', '2019-08-22 15:46:45', '2019-08-22 15:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `category_id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `category_id`, `title`, `content`, `image`, `created_at`, `updated_at`) VALUES
(13, 6, 3, 'Entrada a 50', '<p>contenido de poleman</p>', '15669557301984-Peugeot-205-GTI-V8-1080.jpg', '2019-08-28 01:28:51', '2019-08-31 22:16:23'),
(14, 6, 2, 'Mitsubishi Lancer Evo', '<p>Club Evo RalliArT Baza Granada</p>', '1567132633_DSC0016.png', '2019-08-30 02:37:30', '2019-08-31 22:18:53'),
(15, 1, 2, 'Leke', 'dvsdvfbdfb', NULL, NULL, NULL),
(18, 6, 1, 'LUL', '<p>abcd</p>', '15672902849_Audi-Sport-Quattro-S1.jpg', '2019-08-31 22:19:29', '2019-08-31 22:24:45'),
(19, 6, 1, 'Audi Quattro', '<p><strong>con la leadsw :)</strong></p>', '1567299434wp2317652.jpg', '2019-09-01 00:57:20', '2019-09-01 00:57:20'),
(20, 6, 1, 'Ñoño está en Lugo Andalucía', '<p><strong>svdvfvfñññ :))</strong></p><p><strong><em>sdvvfsvfdb</em></strong></p><p><em>fbdbdfbb</em></p><p><u>fewfefwef</u></p><p>1.dvdvsv</p><p>2.dvsvfdvs</p>', '15673002453.jpg', '2019-09-01 01:10:47', '2019-09-01 01:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `password`, `role`, `description`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', 'admin', 'ROLE_ADMIN', 'descripcion admin', NULL, '2019-08-22 00:00:00', '2019-08-24 00:00:00', NULL),
(6, 'altopetin', 'racing', 'alvaro@alvaro.com', 'ee7d81103f122bb171ce1eb2b8da9b44403f2b2da7924b48b3fafe0ba36b5a81', 'ROLE_USER', '<p>i30N</p>', '15668678440-415534.jpg', '2019-08-21 14:37:59', '2019-08-27 01:04:08', NULL),
(7, 'lul', 'dvdsvdv', 'sdvcascasc@gmail.com', 'e091e0d0a395aed0209b22c6462dada3cd613db0a4e4311102a666ef9a1b235e', 'ROLE_USER', NULL, NULL, '2019-08-25 17:05:42', '2019-08-25 17:05:42', NULL),
(8, 'Alvaro', 'dvjjnvdvjv', 'asmkdsc@dgfgr.com', '72b597f8c2984c48377655fae8cdcad6c83c24752ce56cb2e8fdedb336a3efa4', 'ROLE_USER', NULL, NULL, '2019-08-25 17:12:39', '2019-08-25 17:12:39', NULL),
(9, 'dagfvdsvf', 'dvfgrgjreiog', 'thokrhkhr@fgbhkdg.com', '34eea71f2625e9879c15629575b43d3bb5bf74880efeb924ac9bd4fc997317ba', 'ROLE_USER', NULL, NULL, '2019-08-25 17:14:43', '2019-08-25 17:14:43', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_user` (`user_id`),
  ADD KEY `fk_post_category` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_post_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
