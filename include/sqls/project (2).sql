-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2026 at 11:53 AM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_guests`
--

CREATE TABLE `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `active_guests`
--

INSERT INTO `active_guests` (`ip`, `timestamp`) VALUES
('::1', 1784022769);

-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banned_users`
--

CREATE TABLE `banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` int(11) NOT NULL,
  `enquiry_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `call_type` enum('Incoming','Outgoing') NOT NULL,
  `call_outcome` enum('Connected','No Answer','Busy','Voicemail','Wrong Number','Not Interested','Interested','Callback Requested') DEFAULT NULL,
  `call_duration` varchar(20) DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `next_follow_up` date DEFAULT NULL,
  `next_follow_up_time` varchar(30) NOT NULL,
  `previous_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `counsellor` varchar(100) DEFAULT NULL,
  `communication_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `call_logs`
--

INSERT INTO `call_logs` (`id`, `enquiry_id`, `name`, `phone`, `call_type`, `call_outcome`, `call_duration`, `duration_seconds`, `description`, `note`, `next_follow_up`, `next_follow_up_time`, `previous_status`, `new_status`, `date`, `created_at`, `counsellor`, `communication_type`) VALUES
(10, 50, 'Khizar Ahmad', '0394944949', 'Outgoing', 'Connected', '0:03', 3, NULL, '', '2026-01-05', '', 'Interested', 'Interested', '2026-01-01', '2026-01-01 13:07:01', 'Khizar ', ''),
(11, 40, 'ABDUl SAMI KHAN ', '03075125947', 'Outgoing', 'Connected', '0:02', 2, NULL, '', '2026-01-05', '', 'Not Interested', 'Interested', '2026-01-01', '2026-01-01 13:09:10', 'Khizar ', ''),
(12, 36, 'Salar Ahmed', '03169177881', 'Outgoing', 'Connected', '0:06', 6, NULL, '', '2026-01-05', '', 'Not Interested', 'Interested', '2026-01-01', '2026-01-01 13:09:39', 'Khizar ', ''),
(13, 62, 'asad', '03125576242', 'Outgoing', 'Connected', '0:13', 13, NULL, 'muhatasondn dfasoidfju', '2026-01-10', '', 'New', 'Follow-up Required', '2026-01-02', '2026-01-02 16:14:45', 'Khizar ', ''),
(14, 62, 'asad', '03125576242', 'Outgoing', 'Busy', '1:00', 60, NULL, 'not intieore fics sdfsjdopifj', NULL, '', '', 'Not Interested', '2026-01-02', '2026-01-02 16:16:51', 'Khizar ', ''),
(15, 63, 'Adass', '086445667', 'Outgoing', 'Connected', '0:42', 42, NULL, 'He is ffdom asad reference ', '2026-01-06', '', 'New', 'Interested', '2026-01-03', '2026-01-03 09:21:20', 'Khizar ', ''),
(16, 63, 'Adass', '086445667', 'Outgoing', 'Connected', '0:04', 4, NULL, 'Nothgf', NULL, '', 'Interested', 'Not Interested', '2026-01-03', '2026-01-03 09:22:08', 'Khizar ', ''),
(17, 63, 'Adass', '086445667', 'Outgoing', 'Connected', '0:02', 2, NULL, 'Hgfff', NULL, '', 'Not Interested', 'Enrolled', '2026-01-03', '2026-01-03 09:23:42', 'Khizar ', ''),
(18, 25, 'Rafiq Ahmad ', '03441089266', 'Outgoing', 'Connected', '0:16', 16, NULL, ' vcxcx', '2026-01-06', '', 'New', 'Follow-up Required', '2026-01-05', '2026-01-05 14:28:57', 'Khizar ', ''),
(19, 25, 'Rafiq Ahmad ', '03441089266', 'Outgoing', 'Connected', '1:00', 60, NULL, 'rgfgfd', NULL, '', '', 'Not Interested', '2026-01-05', '2026-01-05 14:29:33', 'Khizar ', ''),
(20, 64, 'Syed Fakhir Ali Shah', '03105390627', 'Outgoing', 'Connected', '0:03', 3, NULL, 'hfuyjgy', NULL, '', 'New', 'Enrolled', '2026-01-07', '2026-01-07 11:39:27', 'Khizar ', ''),
(21, 57, 'Muhammed Zakria', '03489828312', 'Outgoing', 'Connected', '0:03', 3, NULL, 'fdsjhfgjhdsf', '2026-01-09', '', 'New', 'Follow-up Required', '2026-01-08', '2026-01-08 15:37:43', 'Khizar ', ''),
(22, 64, 'Syed Fakhir Ali Shah', '03105390627', 'Outgoing', 'Connected', '2:30', 150, NULL, '', NULL, '', 'Enrolled', 'Interested', '2026-01-19', '2026-01-19 14:59:56', 'Admin', ''),
(23, 25, 'Rafiq Ahmad ', '03441089266', 'Outgoing', 'Interested', '0:00', 0, NULL, '', NULL, '', 'Not Interested', 'Interested', '2026-01-19', '2026-01-19 17:10:29', 'Khizar ', ''),
(24, 77, 'Azeem Shah', '03369851344', 'Outgoing', 'Connected', '0:00', 0, NULL, '', NULL, '', 'New', 'Enrolled', '2026-01-20', '2026-01-20 11:12:23', 'Khizar ', ''),
(25, 74, 'Muhammad Azeem Tahir', '03147446059', 'Incoming', 'Connected', '0:00', 0, NULL, 'First student', NULL, '', 'New', 'Enrolled', '2026-01-20', '2026-01-20 11:30:31', 'Khizar ', ''),
(26, 78, 'Fazal ur Rehman', '03298717148', 'Outgoing', 'Connected', '0:08', 8, NULL, '', '2026-01-22', '', 'New', 'Interested', '2026-01-21', '2026-01-21 13:05:47', 'Khizar ', ''),
(27, 76, 'Aneela Khurshid', '03235275198', 'Outgoing', 'Connected', '0:05', 5, NULL, 'fghjkl;lkjhgfghjkl;\'', '2026-01-22', '', 'New', 'Interested', '2026-01-22', '2026-01-22 11:51:08', 'Khizar ', ''),
(28, 76, 'Aneela Khurshid', '03235275198', 'Outgoing', 'Connected', '0:00', 0, NULL, 'call again', '2026-01-23', '', 'Interested', 'Interested', '2026-01-22', '2026-01-22 17:52:32', 'Khizar ', ''),
(29, 85, 'Ashar Jabbar Khan', '03101562338', 'Outgoing', 'Connected', '0:00', 0, NULL, 'asad reference', '2026-01-26', '', 'New', 'Interested', '2026-01-23', '2026-01-23 16:04:05', 'Khizar ', ''),
(31, 86, 'Hamid Khan', '03117771321', '', '', '0:00', 0, NULL, 'He is in sialkot, will back on 9 feb', '2026-02-08', '', 'New', 'Interested', '2026-01-24', '2026-01-24 13:16:55', 'Admin', 'whatsapp'),
(32, 89, 'Syed Ali Shah', '03165540227', '', '', '0:00', 0, NULL, 'cal again to confirm visit', '2026-01-26', '', 'New', 'Interested', '2026-01-24', '2026-01-24 13:31:34', 'Admin', 'whatsapp'),
(33, 87, 'Ahsan saleem', '03484414955', '', '', '0:00', 0, NULL, 'phone turned off', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 13:40:23', 'Admin', 'whatsapp'),
(34, 84, 'Akasha zaib', '03195024959', 'Outgoing', 'Busy', '0:00', 0, NULL, 'phone busy. cal one last time', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 13:41:27', 'Admin', 'call'),
(35, 83, 'Ikram Ul Haq', '03361476636', 'Outgoing', 'Connected', '2:00', 120, NULL, '30 jan , 3 pm  vist confirmed', '2026-01-25', '', 'New', 'Interested', '2026-01-24', '2026-01-24 13:45:14', 'Admin', 'call'),
(36, 81, 'Sufian ali khan', '03183049707', '', '', '0:00', 0, NULL, 'call and confirm visit ', '2026-01-29', '', 'New', 'Interested', '2026-01-24', '2026-01-24 14:02:32', 'Admin', 'whatsapp'),
(37, 78, 'Fazal ur Rehman', '03298717148', '', '', '0:00', 0, NULL, 'fee issue', NULL, '', 'Interested', 'Not Interested', '2026-01-24', '2026-01-24 14:04:56', 'Admin', 'whatsapp'),
(38, 66, 'Fahad zeb', '03209886214', '', '', '0:00', 0, NULL, 'no solid response yet', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 14:08:47', 'Admin', 'whatsapp'),
(39, 76, 'Aneela Khurshid', '03235275198', '', '', '0:00', 0, NULL, 'call again for visit and demo', '2026-01-25', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 14:14:27', 'Admin', 'whatsapp'),
(40, 35, 'Attiqa Rehman ', '0348 7359266 ', '', '', '0:00', 0, NULL, 'cal for visit', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 14:17:50', 'Admin', 'whatsapp'),
(41, 64, 'Syed Fakhir Ali Shah', '03105390627', 'Outgoing', 'No Answer', '0:00', 0, NULL, 'cal to get enrolled urgently', NULL, '', 'Interested', NULL, '2026-01-24', '2026-01-24 14:24:37', 'Admin', 'call'),
(42, 57, 'Muhammed Zakria', '03489828312', '', '', '0:00', 0, NULL, 'call to confirm visit', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 14:26:45', 'Admin', 'whatsapp'),
(43, 56, 'Maaz Ihsan', '03104784777', '', '', '0:00', 0, NULL, 'call for visit and interest conformation\r\n', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 14:28:31', 'Admin', 'whatsapp'),
(44, 34, 'Muhammad Jarrar Jadoon', '03135372477', '', '', '0:00', 0, NULL, 'not interested', NULL, '', 'New', 'Not Interested', '2026-01-24', '2026-01-24 14:31:14', 'Admin', 'whatsapp'),
(45, 64, 'Syed Fakhir Ali Shah', '03105390627', '', '', '0:00', 0, NULL, '', '2026-01-25', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 14:33:25', 'Admin', 'whatsapp'),
(46, 74, 'Muhammad Azeem Tahir', '03147446059', 'Outgoing', 'No Answer', '0:00', 0, NULL, '', '2026-01-26', '16:00', 'Enrolled', 'Follow-up Required', '2026-01-24', '2026-01-24 14:37:19', 'Admin', 'call'),
(47, 23, 'Aqsa Eman', '03192859886', '', '', '0:00', 0, NULL, 'no response', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 14:45:39', 'Admin', 'whatsapp'),
(48, 22, 'Wahid Ali', '03255769270', '', '', '0:00', 0, NULL, '', '2026-01-25', '', 'New', 'Follow-up Required', '2026-01-24', '2026-01-24 14:52:38', 'Admin', 'whatsapp'),
(49, 89, 'Syed Ali Shah', '03165540227', 'Outgoing', '', '0:00', 0, NULL, 'cal again to confirm visit', '2026-01-25', '17:00', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 14:54:19', 'Admin', 'whatsapp'),
(50, 86, 'Hamid Khan', '03117771321', '', '', '0:00', 0, NULL, 'He is in sialkot, will back on 9 feb', '2026-01-25', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 14:55:42', 'Admin', 'whatsapp'),
(51, 85, 'Ashar Jabbar Khan', '03101562338', 'Outgoing', 'Connected', '0:00', 0, NULL, 'fee and visit .asad reference', '2026-01-25', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 14:56:46', 'Admin', 'call'),
(52, 83, 'Ikram Ul Haq', '03361476636', 'Outgoing', 'Connected', '2:00', 120, NULL, '30 jan , 3 pm vist confirmed', '2026-01-29', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 14:58:29', 'Admin', 'call'),
(53, 40, 'ABDUl SAMI KHAN ', '03075125947', '', '', '0:00', 0, NULL, 'confirm the interst and visit', '2026-01-25', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 15:03:29', 'Admin', 'whatsapp'),
(54, 36, 'Salar Ahmed', '03169177881', '', '', '0:00', 0, NULL, '', NULL, '', 'Interested', 'Not Interested', '2026-01-24', '2026-01-24 15:04:18', 'Admin', 'whatsapp'),
(55, 25, 'Rafiq Ahmad ', '03441089266', '', '', '0:00', 0, NULL, '', '2026-01-25', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 15:05:35', 'Admin', 'whatsapp'),
(56, 81, 'Sufian ali khan', '03183049707', '', '', '0:00', 0, NULL, 'call and confirm visit', '2026-01-29', '', 'Interested', 'Follow-up Required', '2026-01-24', '2026-01-24 15:08:31', 'Admin', 'whatsapp'),
(57, 85, 'Ashar Jabbar Khan', '03101562338', '', '', '0:00', 0, NULL, 'enrolled in Evening session', NULL, '', 'Follow-up Required', 'Enrolled', '2026-01-26', '2026-01-26 13:56:21', 'Khizar ', 'whatsapp'),
(58, 92, 'Imtinan mir', '03462116477', '', '', '0:00', 0, NULL, 'Enrolled in frontend, evening 2, fellow ashar jabbar khan', NULL, '', 'New', 'Enrolled', '2026-01-27', '2026-01-27 06:42:28', 'Admin', 'whatsapp'),
(59, 93, 'Muzammal rasheed', '03334605428', '', '', '0:00', 0, NULL, 'student msg i cant afford it', NULL, '', 'New', NULL, '2026-01-28', '2026-01-28 09:43:50', 'Admin', 'whatsapp'),
(60, 95, 'Abid Hussain', '03447878942', 'Outgoing', 'Connected', '3:00', 180, NULL, 'Asad Reference, No admission fee', NULL, '', 'New', 'Enrolled', '2026-01-29', '2026-01-29 08:43:32', 'Khizar ', 'call'),
(61, 96, 'Muhammad Usaman Ali', '03141959072', 'Outgoing', 'Connected', '3:00', 180, NULL, 'farman reference', NULL, '', 'New', 'Enrolled', '2026-01-29', '2026-01-29 15:51:52', 'Khizar ', 'call'),
(62, 98, 'Hamza Asad', '03295616013', 'Outgoing', 'Connected', '2:00', 120, NULL, 'Need confirmation from home', NULL, '', 'New', 'Interested', '2026-02-12', '2026-02-12 13:15:04', 'Khizar ', 'call'),
(63, 35, 'Attiqa Rehman ', '0348 7359266 ', '', '', '0:00', 0, NULL, 'not answering', NULL, '', 'Follow-up Required', 'Not Interested', '2026-02-17', '2026-02-17 13:36:20', 'Khizar ', 'whatsapp'),
(64, 98, 'Hamza Asad', '03295616013', 'Outgoing', 'Connected', '2:00', 120, NULL, 'not interested', NULL, '', 'Interested', 'Not Interested', '2026-02-19', '2026-02-19 07:48:37', 'Khizar ', 'call'),
(65, 103, 'muhammad faisal', '03312835064', 'Outgoing', 'Connected', '1:00', 60, NULL, 'cal before, confirmation', '2026-03-28', '', 'New', 'Interested', '2026-02-28', '2026-02-28 11:48:45', 'Khizar ', 'call'),
(66, 102, 'Ahsan Khalid', '03480244484', 'Outgoing', 'Connected', '0:13', 13, NULL, 'he is so int erer', NULL, '', 'New', NULL, '2026-04-03', '2026-04-03 17:09:24', 'Khizar ', 'call'),
(67, 102, 'Ahsan Khalid', '03480244484', '', '', '0:00', 0, NULL, 'sdfsdf', NULL, '', 'New', NULL, '2026-04-03', '2026-04-03 17:09:40', 'Khizar ', 'whatsapp');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `timestamp`) VALUES
(1, 'Education', '2025-11-15 11:27:09'),
(2, 'Technology', '2025-12-05 09:08:41'),
(4, 'Sports', '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `name`, `email`, `comment`, `status`, `created_at`) VALUES
(1, 2, 'Shahbaz Khan', 'khizarahmad222111@gmail.com', 'Work smarter, not harder — AI automation is doing the heavy lifting now.', 'Published', '2026-04-25 12:08:47');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `author` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'Draft',
  `featured_image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `is_featured` tinyint(4) DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `content`, `excerpt`, `category_id`, `author_id`, `author`, `status`, `featured_image`, `meta_title`, `meta_description`, `tags`, `views`, `is_featured`, `published_at`, `created_at`, `updated_at`, `is_deleted`) VALUES
(1, 'Lorem Ipsum', 'lorem-ipsum', '\r\n                                        \r\n                                        \r\n                                        \r\n                                        \r\n                                        \r\n                                        <h2 style=\"margin: 0px 0px 10px; padding: 0px; font-weight: 400; font-family: DauphinPlain; font-size: 24px; line-height: 24px; color: rgb(0, 0, 0);\">What is Lorem Ipsum?</h2><p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\"><strong style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br></p><h2 style=\"margin: 0px 0px 10px; padding: 0px; font-weight: 400; font-family: DauphinPlain; font-size: 24px; line-height: 24px; color: rgb(0, 0, 0);\">What is Lorem Ipsum?</h2><p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\"><strong style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br></p><h2 style=\"margin: 0px 0px 10px; padding: 0px; font-weight: 400; font-family: DauphinPlain; font-size: 24px; line-height: 24px; color: rgb(0, 0, 0);\">What is Lorem Ipsum?</h2><p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\"><strong style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.<br></p><h2 style=\"margin: 0px 0px 10px; padding: 0px; font-weight: 400; font-family: DauphinPlain; font-size: 24px; line-height: 24px; color: rgb(0, 0, 0);\">What is Lorem Ipsum?</h2><p style=\"margin: 0px 0px 15px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px;\"><strong style=\"margin: 0px; padding: 0px;\">Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n                                                                                                                                                                                                                        ', 'What is Lorem Ipsum?', 1, NULL, 'FN-admin', 'Published', '1777117227_lorem.jfif', 'What is Lorem Ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum', 'Lorem Ipsum,dummy text,dumy', 14, 0, NULL, '2026-04-25 11:22:56', '2026-07-14 09:52:32', 0),
(2, 'How AI Automation is Transforming Modern Businesses in 2026', 'how-ai-automation-is-transforming-modern-businesses-in-2026', '\r\n                                        <p data-start=\"615\" data-end=\"907\">Artificial Intelligence (AI) and automation are no longer futuristic concepts—they are actively reshaping how businesses operate in 2026. From small startups to large enterprises, organizations are adopting AI-powered solutions to streamline processes, reduce costs, and improve productivity.</p><p data-start=\"909\" data-end=\"1024\">In today’s fast-paced digital world, companies that fail to adopt automation risk falling behind their competitors.</p><hr data-start=\"1026\" data-end=\"1029\"><h2 data-section-id=\"x4a905\" data-start=\"1031\" data-end=\"1059\">🤖 What is AI Automation?</h2><p data-start=\"1061\" data-end=\"1213\">AI automation refers to the use of artificial intelligence technologies to perform tasks that typically require human intelligence. These tasks include:</p><ul data-start=\"1215\" data-end=\"1296\">\r\n<li data-section-id=\"rj78q2\" data-start=\"1215\" data-end=\"1232\">\r\nData analysis\r\n</li>\r\n<li data-section-id=\"1gxud7n\" data-start=\"1233\" data-end=\"1253\">\r\nCustomer support\r\n</li>\r\n<li data-section-id=\"kpycnb\" data-start=\"1254\" data-end=\"1276\">\r\nContent generation\r\n</li>\r\n<li data-section-id=\"uz1xle\" data-start=\"1277\" data-end=\"1296\">\r\nDecision-making\r\n</li>\r\n</ul><p data-start=\"1298\" data-end=\"1415\">Unlike traditional automation, AI systems can <strong data-start=\"1344\" data-end=\"1383\">learn, adapt, and improve over time</strong>, making them far more powerful.</p><hr data-start=\"1417\" data-end=\"1420\"><h2 data-section-id=\"n4qm3t\" data-start=\"1422\" data-end=\"1457\">💼 Key Benefits of AI Automation</h2><h3 data-section-id=\"789j3i\" data-start=\"1459\" data-end=\"1486\">1. Increased Efficiency</h3><p data-start=\"1487\" data-end=\"1631\">AI systems can perform repetitive tasks faster and more accurately than humans. This allows businesses to save time and focus on strategic work.</p><h3 data-section-id=\"1g9d8lk\" data-start=\"1633\" data-end=\"1654\">2. Cost Reduction</h3><p data-start=\"1655\" data-end=\"1766\">By automating manual processes, companies can significantly reduce operational costs and improve profitability.</p><h3 data-section-id=\"11luxek\" data-start=\"1768\" data-end=\"1797\">3. Better Decision Making</h3><p data-start=\"1798\" data-end=\"1900\">AI analyzes large amounts of data quickly, helping businesses make informed and data-driven decisions.</p><h3 data-section-id=\"11bauvn\" data-start=\"1902\" data-end=\"1926\">4. 24/7 Availability</h3><p data-start=\"1927\" data-end=\"2016\">AI-powered systems, such as chatbots, can operate around the clock without interruptions.</p><hr data-start=\"2018\" data-end=\"2021\"><h2 data-section-id=\"1d3xnja\" data-start=\"2023\" data-end=\"2056\">🔧 Popular AI Automation Tools</h2><p data-start=\"2058\" data-end=\"2092\">Some widely used AI tools include:</p><ul data-start=\"2094\" data-end=\"2218\">\r\n<li data-section-id=\"ysjkek\" data-start=\"2094\" data-end=\"2127\">\r\nChatbots for customer support\r\n</li>\r\n<li data-section-id=\"1igv4w9\" data-start=\"2128\" data-end=\"2153\">\r\nAI content generators\r\n</li>\r\n<li data-section-id=\"px4unc\" data-start=\"2154\" data-end=\"2187\">\r\nWorkflow automation platforms\r\n</li>\r\n<li data-section-id=\"14d4dzm\" data-start=\"2188\" data-end=\"2218\">\r\nPredictive analytics tools\r\n</li>\r\n</ul><p data-start=\"2220\" data-end=\"2304\">These tools are helping businesses automate everything from marketing to operations.</p><hr data-start=\"2306\" data-end=\"2309\"><h2 data-section-id=\"s3r2rl\" data-start=\"2311\" data-end=\"2337\">📈 Real-World Use Cases</h2><h3 data-section-id=\"ldgx98\" data-start=\"2339\" data-end=\"2356\">🛒 E-commerce</h3><p data-start=\"2357\" data-end=\"2438\">AI automates product recommendations, inventory management, and customer service.</p><h3 data-section-id=\"3lsto6\" data-start=\"2440\" data-end=\"2457\">🏥 Healthcare</h3><p data-start=\"2458\" data-end=\"2547\">Automation helps in patient data analysis, diagnosis support, and appointment scheduling.</p><h3 data-section-id=\"qtt7zp\" data-start=\"2549\" data-end=\"2565\">📊 Marketing</h3><p data-start=\"2566\" data-end=\"2633\">AI tools analyze user behavior and automate personalized campaigns.</p><hr data-start=\"2635\" data-end=\"2638\"><h2 data-section-id=\"a3ujno\" data-start=\"2640\" data-end=\"2673\">⚠️ Challenges of AI Automation</h2><p data-start=\"2675\" data-end=\"2735\">Despite its advantages, AI automation comes with challenges:</p><ul data-start=\"2737\" data-end=\"2854\">\r\n<li data-section-id=\"xuadsk\" data-start=\"2737\" data-end=\"2764\">\r\nHigh initial setup cost\r\n</li>\r\n<li data-section-id=\"v2cthx\" data-start=\"2765\" data-end=\"2790\">\r\nData privacy concerns\r\n</li>\r\n<li data-section-id=\"1g2kjz0\" data-start=\"2791\" data-end=\"2819\">\r\nDependence on technology\r\n</li>\r\n<li data-section-id=\"771dzl\" data-start=\"2820\" data-end=\"2854\">\r\nNeed for skilled professionals\r\n</li>\r\n</ul><p data-start=\"2856\" data-end=\"2935\">Businesses must carefully plan their automation strategy to avoid these issues.</p><hr data-start=\"2937\" data-end=\"2940\"><h2 data-section-id=\"1wjiblg\" data-start=\"2942\" data-end=\"2975\">🔮 The Future of AI Automation</h2><p data-start=\"2977\" data-end=\"3149\">The future of AI automation is incredibly promising. With advancements in machine learning and data processing, automation will become even more intelligent and accessible.</p><p data-start=\"3151\" data-end=\"3165\">We can expect:</p><ul data-start=\"3166\" data-end=\"3272\">\r\n<li data-section-id=\"syyr34\" data-start=\"3166\" data-end=\"3196\">\r\nSmarter virtual assistants\r\n</li>\r\n<li data-section-id=\"v3rt8y\" data-start=\"3197\" data-end=\"3235\">\r\nFully automated business workflows\r\n</li>\r\n<li data-section-id=\"135lbml\" data-start=\"3236\" data-end=\"3272\">\r\nIncreased human-AI collaboration\r\n</li>\r\n</ul><hr data-start=\"3274\" data-end=\"3277\"><h2 data-section-id=\"j3x6g\" data-start=\"3279\" data-end=\"3295\">🧠 Conclusion</h2><p data-start=\"3297\" data-end=\"3488\">AI automation is no longer optional—it’s a necessity for businesses aiming to stay competitive. By embracing automation, companies can unlock new levels of efficiency, innovation, and growth.</p><p>\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p data-start=\"3490\" data-end=\"3588\">Now is the perfect time to start integrating AI into your workflow and future-proof your business.</p>\r\n                                    ', 'AI automation is revolutionizing industries by reducing manual work, improving efficiency, and enabling smarter decision-making. Discover how businesses are leveraging AI tools to scale faster in 2026.', 2, NULL, 'AUTH-1024', 'Published', '1777118372_AI.jfif', 'AI Automation in 2026: How Businesses Are Transforming with AI', 'Discover how AI automation is transforming modern businesses in 2026. Learn key benefits, tools, and real-world use cases to boost productivity and growth.', 'AI,Automation,Technology,Business,Machine Learning,Future Tech,Digital Transformation', 35, 1, '2026-04-25 16:59:32', '2026-04-25 11:59:32', '2026-04-27 11:16:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `registration_no` varchar(20) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) UNSIGNED NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL,
  `parent_directory` varchar(30) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default_avatar.png',
  `bio` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `registration_no`, `username`, `password`, `userid`, `userlevel`, `email`, `timestamp`, `parent_directory`, `display_name`, `phone`, `created_at`, `profile_image`, `bio`, `last_login`) VALUES
(1, 'FN-admin', 'admin', '$2y$10$LTd0zZSFvLMaiEJU33ONAu5G/mQKWSOspvHPENLsHwVQn6eVbpxj.', '1330e7a44dced483386d939bd6122ab4', 4, 'admin@gmail.com', 1784022729, 'images.jpeg', 'Admin', '', '2025-11-18 11:18:46', 'default_avatar.png', NULL, '2026-07-14 14:51:43'),
(1025, 'AUTH-1024', 'khizar.ahmad', '$2y$10$zxskh3RL7gU2kXAudPFfYOG/YjEwtwpJzFJgx61GSNWMYiqKgHhXS', '40d99af210ee53cac8a1c7bc0676bf62', 1, 'khizarahmad0920@gmail.com', 1777124855, '', 'KHIZAR AHMAD', '03328912706', '', 'default_avatar.png', NULL, '2026-04-25 18:47:11'),
(1026, 'AUTH-1026', 'azam.fazal', '$2y$10$nvbft8azDLeXqAzsbxAhPuWF8tryrgTbPNAqd.4KSh5Lt1QSqrQLi', '8adca35f76e5b12c2f0d68c9730f6965', 1, 'azamfazal@gmail.com', 1777115737, '', 'Azam Fazal', '03328912706', '', 'default_avatar.png', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `active_guests`
--
ALTER TABLE `active_guests`
  ADD PRIMARY KEY (`ip`);

--
-- Indexes for table `active_users`
--
ALTER TABLE `active_users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `banned_users`
--
ALTER TABLE `banned_users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_enquiry` (`enquiry_id`),
  ADD KEY `idx_date` (`date`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1027;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
