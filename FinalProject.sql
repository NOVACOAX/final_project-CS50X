-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 25, 2022 at 03:49 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `FinalProject`
--

-- --------------------------------------------------------

--
-- Table structure for table `editorRequest`
--

CREATE TABLE `editorRequest` (
  `id` int(255) NOT NULL,
  `user-id` int(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(111) NOT NULL,
  `rate` text DEFAULT NULL,
  `comment` text NOT NULL,
  `name` text DEFAULT NULL,
  `email` text NOT NULL,
  `replied` int(10) DEFAULT 0,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `rate`, `comment`, `name`, `email`, `replied`, `date`) VALUES
(1, 'neutral', 'Could be better!!', 'admin', 'hughherschell2018@gmail.com', 1, '2022-07-30 02:49:27'),
(2, 'neutral', 'Do better', 'admin', 'hughherschell2018@gmail.com', 1, '2022-07-30 02:51:07'),
(5, 'good', 'wow!!!', 'admin', 'hughherschell2018@gmail.com', 0, '2022-08-09 14:28:48'),
(6, 'good', 'some text here', 'hugh', 'hughherschell.ke@gmail.com', 1, '2022-08-18 02:46:29'),
(7, 'neutral', 'hello world', 'John Doe', 'hughherschell.ke@gmail.com', 0, '2022-08-18 02:47:10'),
(9, 'good', 'Hello World.', 'Johm Doe', 'test@test.com', 1, '2022-08-23 20:59:44'),
(10, 'good', 'Hello World!!', 'John Doe', 'test@test.com', 0, '2022-08-23 21:06:16');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(255) NOT NULL,
  `follower` int(255) NOT NULL,
  `following` int(255) NOT NULL,
  `datestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower`, `following`, `datestamp`) VALUES
(12, 21, 22, '2022-08-20 03:42:33'),
(16, 21, 20, '2022-08-20 03:45:21'),
(53, 20, 21, '2022-08-21 00:23:19'),
(54, 20, 22, '2022-08-21 00:23:29'),
(55, 22, 20, '2022-08-21 00:24:03'),
(57, 22, 21, '2022-08-21 00:24:31');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(111) NOT NULL,
  `image` text NOT NULL,
  `user-id` int(111) NOT NULL,
  `dimension` text NOT NULL,
  `upload-date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(255) NOT NULL,
  `user-id` int(255) NOT NULL,
  `pic-id` int(255) NOT NULL,
  `datestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(255) NOT NULL,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `image` text NOT NULL DEFAULT 'def.jpg',
  `about` text DEFAULT NULL,
  `twitter` text DEFAULT NULL,
  `instagram` text DEFAULT NULL,
  `facebook` text DEFAULT NULL,
  `tiktok` text DEFAULT NULL,
  `type` int(111) NOT NULL DEFAULT 111,
  `active` int(10) NOT NULL,
  `joined` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `image`, `about`, `twitter`, `instagram`, `facebook`, `tiktok`, `type`, `active`, `joined`) VALUES
(20, 'admin', 'admin@admin.com', '202cb962ac59075b964b07152d234b70', '2022.08.19-20-05.11.jpg', 'All about Black', 'https://twitter.com/twitter', 'https://www.instagram.com/instagram/', 'https://www.facebook.com/facebook', 'https://www.tiktok.com/@tiktok', 323, 1, '2022-07-27 00:00:00'),
(21, 'user', 'user@user.com', '202cb962ac59075b964b07152d234b70', '21-2022.08.05-02.36.jpg', '', 'https://twitter.com/#', 'https://instagram.com/instagram/', 'https://facebook.com/facebook', 'https://tiktok.com/@tiktok', 222, 1, '2022-07-27 00:00:00'),
(22, 'editor', 'editor@editor.com', '202cb962ac59075b964b07152d234b70', 'def.jpg', NULL, NULL, 'https://www.instagram.com/instagram/', NULL, NULL, 222, 1, '2022-08-15 18:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `user-settings`
--

CREATE TABLE `user-settings` (
  `id` int(255) NOT NULL,
  `user-id` int(255) NOT NULL,
  `privacy` int(5) NOT NULL DEFAULT 0,
  `EonChange` int(5) NOT NULL DEFAULT 1,
  `EonProducts` int(5) NOT NULL DEFAULT 1,
  `sensitiveC` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user-settings`
--

INSERT INTO `user-settings` (`id`, `user-id`, `privacy`, `EonChange`, `EonProducts`, `sensitiveC`) VALUES
(3, 21, 0, 0, 1, 0),
(7, 20, 0, 1, 0, 0),
(8, 22, 0, 0, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `editorRequest`
--
ALTER TABLE `editorRequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usr` (`user-id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ing` (`following`),
  ADD KEY `wer` (`follower`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user-id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pic` (`pic-id`),
  ADD KEY `ussr` (`user-id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user-settings`
--
ALTER TABLE `user-settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner` (`user-id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `editorRequest`
--
ALTER TABLE `editorRequest`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(111) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user-settings`
--
ALTER TABLE `user-settings`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `editorRequest`
--
ALTER TABLE `editorRequest`
  ADD CONSTRAINT `usr` FOREIGN KEY (`user-id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `ing` FOREIGN KEY (`following`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `wer` FOREIGN KEY (`follower`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `user` FOREIGN KEY (`user-id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `pic` FOREIGN KEY (`pic-id`) REFERENCES `gallery` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `ussr` FOREIGN KEY (`user-id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user-settings`
--
ALTER TABLE `user-settings`
  ADD CONSTRAINT `owner` FOREIGN KEY (`user-id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
