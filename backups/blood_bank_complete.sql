-- ============================================================================
-- Blood Bank Management System - Complete Database Schema
-- ============================================================================
-- Version: 2.0
-- Date: November 12, 2025
-- Description: Complete database with all tables, data, and latest updates
-- Includes: donor_id field, notifications system, and all integrity fixes
-- ============================================================================

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================================================
-- Database Creation
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `blood` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `blood`;

-- ============================================================================
-- Table Structure: admins
-- ============================================================================

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sample data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'TITO', 'tito@gmail.com', '$2y$10$NPzCyAX7C6r9Lvr7Hqbvk.vN.fr/mgnxiFPPD8KqgG/Acs0F873JK', 'admin', '2025-09-01 14:02:42'),
(2, 'TITO', 'tito123@gmail.com', '$2y$10$vUKuLduLsuskhlIRQ3n1a.VHI/hnAOjjPJ3C3P/onbq2lm4dGRHFq', 'admin', '2025-09-01 14:23:55'),
(3, 'arun', 'arun123@gmail.com', '$2y$10$RmncNWY08bmvxgAn9BMeueegf1T40xQHoNS63ylyVTFx1lspqL23y', 'admin', '2025-09-02 06:24:45'),
(4, 'testadmin', 'testadmin@gmail.com', '$2y$10$MuixCJNavM.EA4iqUGsISubjmUJpQ0utijxtZRP97hMbWUMBTDvMS', 'admin', '2025-09-10 17:16:24');

-- ============================================================================
-- Table Structure: users
-- ============================================================================

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','hospital','user') NOT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `blood_group` varchar(3) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `district` varchar(100) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `emergency_contact` varchar(15) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sample data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `location`, `password`, `role`, `hospital_id`, `phone`, `blood_group`, `created_at`, `status`, `district`, `blood_type`, `latitude`, `longitude`, `dob`, `gender`, `emergency_contact`, `notes`) VALUES
(1, 'Super Admin', 'admin@bloodbank.com', 'Jane Smith', '{PASSWORD_HASH}', 'admin', NULL, '9999999999', NULL, '2025-09-01 09:30:11', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'testh', 'testh@gmail.com', 'thiruvcallas', '$2y$10$1FCE.1J8j0knoIRaOpJQL.YdR4GLVpAR3yBjr8bnYNBue2x66Xx2a', 'hospital', NULL, '1234656789', NULL, '2025-09-01 10:59:12', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'jerin', 'jerin@gmail.com', NULL, '$2y$10$WTDaraxFjGvxD9goqK7rJ.47expIaaol41NfMA43.mHZ251mi.bc2', 'user', NULL, '8138953814', 'A+', '2025-09-02 14:05:51', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'hospital', 'hospital@gmail.com', NULL, '$2y$10$AE4696HfpkXHe.VrYj7UCuR2zVqwI5JqbJbBHKZkp.j2nTkkrMj9u', 'hospital', NULL, '8138953814', NULL, '2025-09-02 14:42:43', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'testfinal ', 'testfinal@gmail.com', 'thiruvalla', '$2y$10$N95VmJguG6dvpvaooONHtunSyG1Kh7vIOdIjmx/gV3vhxchixVYt.', 'user', NULL, '8138953814', 'O+', '2025-09-10 16:29:45', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'testuseranother ', 'testuseranother@gmail.com', 'thiruvalla', '$2y$10$TT38rhebLF8s5G8YNklz/OXxH7/1unpvYuKbcGUIiWdC72YONc00i', 'user', NULL, '8138953814', 'AB-', '2025-09-10 16:43:35', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'testhospital', 'testhospital@gmail.com', 'thiruvalla', '$2y$10$yP4pHp/SdU/XL1079HoHIOMqhyTPfZPC1Q.2DPX/DupVrin5mhZuK', 'hospital', NULL, '8138953814', NULL, '2025-09-10 16:46:01', 'approved', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'TMM', 'tmm@gmail.com', 'stgdfgsdfg', '$2y$10$MWBq5zGy30RrJz7aUKHQ/ua1zwKjlV3dTwTivSOKSzyreB.RrEyCe', 'hospital', 564, '8134234534', NULL, '2025-09-22 17:53:44', 'approved', '', NULL, 9.28230840, 76.82002606, '0000-00-00', 'Female', '254786746', 'sfdhsdh'),
(19, 'gma ', 'gma@gmail.com', 'egy', '$2y$10$5LD5TuzDEeN3mLwgZBjnuOMUvCdNfAp0C6NDNhn5OmqFJRIkue9ZW', 'user', NULL, '8135958747', 'AB-', '2025-10-05 14:45:20', 'pending', 'Kollam', NULL, NULL, NULL, '2022-06-06', 'Male', '', ''),
(20, 'tito', 'arun45@gmail.com', 'kunhfgriurgiu', '$2y$10$LPr3amlKE13xMfPV4WK/6OS0OJTUS4XCPuPP7RWO5qVxWBwhc32lC', 'user', NULL, '8448484522', 'O-', '2025-10-05 14:47:02', 'pending', 'Kollam', NULL, NULL, NULL, '2025-04-09', 'Male', '', ''),
(21, 'jerin', 'jerin12@gmail.com', 'gbhrb j', '$2y$10$FXdmSeLJHcebEvE/yWIAa.vONu.ErHxRK0E7CuxKZrBsaGTE.0IKa', 'user', NULL, '9148585565', 'O-', '2025-10-05 15:17:37', 'pending', 'Thiruvananthapuram', NULL, NULL, NULL, '0204-08-07', 'Male', '', '');

-- ============================================================================
-- Table Structure: blood_stock
-- ============================================================================

DROP TABLE IF EXISTS `blood_stock`;
CREATE TABLE `blood_stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `hospital_id` int(11) NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`stock_id`),
  KEY `hospital_id` (`hospital_id`),
  CONSTRAINT `blood_stock_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sample data for table `blood_stock`
--

INSERT INTO `blood_stock` (`stock_id`, `hospital_id`, `blood_group`, `quantity`, `updated_at`) VALUES
(5, 5, 'B+', 4, '2025-09-01 13:31:24'),
(6, 13, 'A+', 1, '2025-09-10 16:53:27'),
(7, 13, 'A-', 2, '2025-09-10 16:53:33'),
(8, 13, 'AB+', 2, '2025-09-10 16:53:42'),
(14, 18, 'A+', 23, '2025-09-22 17:54:37');

-- ============================================================================
-- Table Structure: notifications
-- ============================================================================

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `message` varchar(255) NOT NULL,
  `type` enum('user','hospital','donation','request','system') DEFAULT 'system',
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `idx_status` (`status`),
  KEY `idx_type` (`type`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================================
-- Table Structure: requests (UPDATED with donor_id field)
-- ============================================================================

DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `donor_id` int(11) DEFAULT NULL COMMENT 'User-to-user blood requests',
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`request_id`),
  KEY `user_id` (`user_id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `donor_id` (`donor_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_requests_donor` FOREIGN KEY (`donor_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sample data for table `requests`
-- Note: Empty status values have been fixed to 'pending'
--

INSERT INTO `requests` (`request_id`, `user_id`, `hospital_id`, `donor_id`, `blood_group`, `quantity`, `status`, `created_at`) VALUES
(5, 11, 5, NULL, 'B+', 5, 'pending', '2025-09-10 16:40:37'),
(6, 11, 5, NULL, 'B+', 5, 'pending', '2025-09-10 16:40:45'),
(22, 20, 18, NULL, 'O+', 1, 'pending', '2025-10-05 14:49:13'),
(23, 20, 13, NULL, 'O+', 1, 'pending', '2025-10-05 14:49:21'),
(24, 20, 13, NULL, 'O+', 1, 'pending', '2025-10-05 14:52:47'),
(25, 20, 18, NULL, 'A+', 2, 'approved', '2025-10-05 14:53:00'),
(26, 20, NULL, NULL, 'A+', 0, 'pending', '2025-10-05 15:16:17'),
(27, 21, NULL, NULL, 'O-', 0, 'pending', '2025-10-05 15:18:12'),
(28, 20, NULL, NULL, 'O-', 0, 'pending', '2025-10-05 15:19:32'),
(29, 20, NULL, NULL, 'A+', 0, 'pending', '2025-10-05 15:20:27');

-- ============================================================================
-- Table Structure: donations
-- ============================================================================

DROP TABLE IF EXISTS `donations`;
CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`donation_id`),
  KEY `user_id` (`user_id`),
  KEY `hospital_id` (`hospital_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `donations_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sample data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `user_id`, `hospital_id`, `blood_group`, `quantity`, `status`, `created_at`) VALUES
(6, 20, 18, 'O-', 1, 'approved', '2025-10-05 15:21:01');

-- ============================================================================
-- AUTO_INCREMENT Settings
-- ============================================================================

ALTER TABLE `admins` AUTO_INCREMENT=5;
ALTER TABLE `blood_stock` AUTO_INCREMENT=15;
ALTER TABLE `donations` AUTO_INCREMENT=7;
ALTER TABLE `notifications` AUTO_INCREMENT=1;
ALTER TABLE `requests` AUTO_INCREMENT=30;
ALTER TABLE `users` AUTO_INCREMENT=22;

-- ============================================================================
-- Additional Indexes for Performance
-- ============================================================================

-- Already created in table definitions above

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================================================
-- INSTALLATION NOTES
-- ============================================================================
-- 
-- This SQL file includes all the latest updates for the Blood Bank System:
-- 
-- 1. donor_id field in requests table for user-to-user blood requests
-- 2. All necessary foreign key constraints
-- 3. Performance indexes on frequently queried columns
-- 4. Fixed empty status values (all set to 'pending')
-- 5. Complete notifications system structure
-- 6. Sample data for testing
-- 
-- To install this database:
-- 1. Create a new database or drop existing one
-- 2. Import this file using phpMyAdmin or command line:
--    mysql -u root -p < blood_bank_complete.sql
-- 3. Update your database connection settings in includes/config.php
-- 4. Test the system
-- 
-- For migration from old database:
-- 1. Backup your existing database first
-- 2. Export your current data
-- 3. Import this schema
-- 4. Import your data back
-- 
-- Version: 2.0
-- Last Updated: November 12, 2025
-- Compatible with: MySQL 5.7+, MariaDB 10.4+
-- 
-- ============================================================================
