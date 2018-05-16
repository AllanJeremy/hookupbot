-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2018 at 11:56 AM
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
-- Table structure for table `hookups`
--

CREATE TABLE `hookups` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_id` int(10) UNSIGNED NOT NULL COMMENT 'Id of the hookup request',
  `payment_received` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If the payment was received for this hookup ~ generally after the hookup accepts request',
  `payment_id` int(10) UNSIGNED NOT NULL,
  `payment_confirmed` varchar(64) DEFAULT NULL COMMENT 'Confirmation code for payment',
  `date_hooked_up` timestamp NULL DEFAULT NULL COMMENT 'Date the hookup was finalized'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contains a list of hookups (Requests and confirmations)';

-- --------------------------------------------------------

--
-- Table structure for table `hookup_pool`
--

CREATE TABLE `hookup_pool` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'Id of the hookup pool entry ~ will be used for hookups',
  `hookup_user_id` int(10) UNSIGNED NOT NULL COMMENT 'Id of the user in the hookup pool',
  `is_taken` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the user is taken or not',
  `date_joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date this user joined the hookup pool'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Hookup pool ~ contains a list of users looking for hookups';

-- --------------------------------------------------------

--
-- Table structure for table `hookup_requests`
--

CREATE TABLE `hookup_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `hookup_id` int(10) UNSIGNED NOT NULL,
  `is_accepted` tinyint(1) NOT NULL DEFAULT '0',
  `date_requested` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contains a list of hookup requests';

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `pay_id` int(10) UNSIGNED NOT NULL COMMENT 'Payment id',
  `payer_id` int(10) UNSIGNED NOT NULL COMMENT 'Id of the user that made the payment',
  `payment_method` varchar(64) NOT NULL DEFAULT 'mpesa' COMMENT 'The method of payment used to make this payment',
  `amount` int(10) UNSIGNED NOT NULL COMMENT 'Amount the user paid',
  `currency_iso` char(3) NOT NULL COMMENT 'Currency iso for the user that paid',
  `amount_due` int(10) UNSIGNED NOT NULL COMMENT 'Amount that is due for the payment. Only when a person pays less than is expected',
  `confirmation_code` varchar(128) DEFAULT NULL COMMENT 'Confirmation code confirming the payment',
  `is_completed` tinyint(1) DEFAULT NULL COMMENT 'Whether this payment was completed or not',
  `date_made` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date the payment was made'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `method_name` varchar(64) NOT NULL COMMENT 'The unique name of the payment method',
  `payment_type` enum('mobile','card','online','crypto') NOT NULL DEFAULT 'mobile' COMMENT 'The category/type of payment method it is',
  `confirmation_type` enum('email','text','call') NOT NULL COMMENT 'The confirmation method used to confirm payments through this payment method'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Contains a list of payment methods';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'User id',
  `is_bot` tinyint(1) NOT NULL COMMENT 'If the user is a bot',
  `first_name` varchar(64) NOT NULL COMMENT 'First name',
  `last_name` varchar(64) DEFAULT NULL COMMENT 'Last name',
  `username` varchar(32) NOT NULL COMMENT 'Username',
  `language_code` varchar(32) DEFAULT NULL COMMENT 'IETF language tag of the user''s language',
  `age` int(10) UNSIGNED NOT NULL COMMENT 'User age',
  `gender` enum('Male','Female') NOT NULL COMMENT 'User gender',
  `phone` varchar(15) NOT NULL COMMENT 'Phone number of the user',
  `latitude` float DEFAULT NULL COMMENT 'User location latitude',
  `longitude` float DEFAULT NULL COMMENT 'User location longitude',
  `location_title` varchar(160) NOT NULL COMMENT 'Name of the venue',
  `location_address` varchar(160) NOT NULL COMMENT 'Address of the venue',
  `gender_preference` enum('Male','Female') DEFAULT NULL COMMENT 'Preferred hookup gender',
  `min_age` int(10) UNSIGNED DEFAULT '18' COMMENT 'Preferred minimum age of hookup',
  `max_age` int(10) UNSIGNED DEFAULT NULL COMMENT 'Preferred maximum age of hookup',
  `needs_appreciation` tinyint(1) DEFAULT NULL COMMENT 'If user needs appreciation',
  `providing_appreciation` tinyint(1) DEFAULT NULL COMMENT 'If user is providing appreciation to hookups',
  `details` text COMMENT 'Extra details about the user',
  `is_banned` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not the user is banned from the bot. This will determine if a user can use the bot or not',
  `date_joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date the user joined'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores information about the various users';

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE `user_images` (
  `file_id` varchar(512) NOT NULL COMMENT 'Id of the file',
  `file_size` int(10) UNSIGNED DEFAULT NULL COMMENT 'File size',
  `file_path` text NOT NULL COMMENT 'Path to the file',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'Id of the user who the file belongs to'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores a list of user images';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hookups`
--
ALTER TABLE `hookups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hookup_pool`
--
ALTER TABLE `hookup_pool`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hookup_requests`
--
ALTER TABLE `hookup_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`method_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hookups`
--
ALTER TABLE `hookups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hookup_pool`
--
ALTER TABLE `hookup_pool`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id of the hookup pool entry ~ will be used for hookups';
--
-- AUTO_INCREMENT for table `hookup_requests`
--
ALTER TABLE `hookup_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pay_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Payment id';
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'User id';COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
