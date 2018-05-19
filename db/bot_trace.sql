-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2018 at 12:52 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tg_hookup_bot`
--

-- --------------------------------------------------------

--
-- Table structure for table `bot_trace`
--

CREATE TABLE `bot_trace` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'Trace id',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'Id of the user talking to the bot',
  `last_bot_message_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Id of the last message the bot sent',
  `last_bot_message` text COMMENT 'Last message the bot sent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores traces of the bot''s messages with the user';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bot_trace`
--
ALTER TABLE `bot_trace`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bot_trace`
--
ALTER TABLE `bot_trace`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Trace id';COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
