-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 09:45 AM
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
('', 1777102973);

-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `active_users`
--

INSERT INTO `active_users` (`username`, `timestamp`) VALUES
('admin', 1777103093);

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE `admissions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `mobile_no` varchar(30) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `cnic` varchar(20) DEFAULT NULL,
  `current_grade` varchar(60) NOT NULL,
  `institute` varchar(60) NOT NULL,
  `field_education` varchar(60) NOT NULL,
  `field_interest` varchar(255) NOT NULL,
  `course_id` int(11) NOT NULL,
  `education_status` enum('Primary','Secondary','Intermediate','Undergraduate','Graduate','Other') DEFAULT NULL,
  `grade` varchar(30) NOT NULL,
  `current_semester` varchar(30) NOT NULL,
  `other_degree` varchar(255) DEFAULT NULL,
  `graduation_year` year(4) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relation` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(20) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `additional_info` varchar(500) NOT NULL,
  `slot` varchar(50) DEFAULT NULL,
  `visit_date` varchar(50) NOT NULL,
  `visit_time` varchar(50) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reference` varchar(30) NOT NULL,
  `enquiry_status` enum('New','Attempted Contact','Connected','Interested','Scheduled Visit','Visited','Enrolled','Waiting List','Not Interested','Follow-up Required') DEFAULT 'New',
  `enquiry_source` enum('website','walk-in','reference','campaign','other','portal') DEFAULT 'website',
  `priority` enum('Low','Medium','High','Urgent') DEFAULT 'Medium',
  `assigned_counsellor` varchar(100) DEFAULT NULL,
  `last_contact_date` datetime DEFAULT NULL,
  `next_follow_up` datetime DEFAULT NULL,
  `next_follow_up_time` varchar(30) NOT NULL,
  `assigned_to` varchar(100) DEFAULT NULL,
  `total_calls` int(11) DEFAULT 0,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`id`, `name`, `gender`, `mobile_no`, `email`, `dob`, `cnic`, `current_grade`, `institute`, `field_education`, `field_interest`, `course_id`, `education_status`, `grade`, `current_semester`, `other_degree`, `graduation_year`, `address`, `guardian_name`, `guardian_relation`, `guardian_phone`, `registration_date`, `additional_info`, `slot`, `visit_date`, `visit_time`, `timestamp`, `updated_at`, `reference`, `enquiry_status`, `enquiry_source`, `priority`, `assigned_counsellor`, `last_contact_date`, `next_follow_up`, `next_follow_up_time`, `assigned_to`, `total_calls`, `remarks`) VALUES
(22, 'Wahid Ali', 'Male', '03255769270', 'wahidtanoli28@gmail.com', NULL, NULL, '', 'Abbottabad university of science and technology ', 'Software engineering ', 'Web Development, Mobile App Development, AI / Machine Learning, Cyber Security', 10, 'Undergraduate', '', '7th', '', NULL, 'VILLAGE DARRA POST OFFICE PIND KARGU KHAN TAHSEEL LOWER TANA', NULL, NULL, NULL, NULL, '', NULL, '', '', '2025-11-29 12:50:40', '2026-01-25 11:06:25', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:52:38', '2026-01-25 00:00:00', '', NULL, 0, NULL),
(23, 'Aqsa Eman', 'Female', '03192859886', 'aqsaeman2000cs@gmail.com', NULL, NULL, '', 'Abbottabad University of Science And Technology ', 'Computer Science ', 'AI / Machine Learning', 10, 'Undergraduate', '', '1st', '', NULL, 'Abbottabad, Havelian, Near railway station, Mohalla Ehle e H', NULL, NULL, NULL, NULL, '', NULL, '', '', '2025-11-29 12:50:53', '2026-01-25 11:06:32', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:45:39', '2026-01-25 00:00:00', '', NULL, 0, '\n\n[24 Jan 2026 19:45 - Admin - WhatsApp]\nno response'),
(25, 'Rafiq Ahmad ', 'Male', '03441089266', 'llvariablefescue@gmail.com', NULL, NULL, '', 'AUST ', 'CS', 'Web Development, Cyber Security', 10, 'Undergraduate', '', '1', '', NULL, 'Gilgit baltistan bunji ', NULL, NULL, NULL, NULL, '', NULL, '', '', '2025-11-29 13:03:20', '2026-01-25 11:06:44', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 15:05:35', '2026-01-25 00:00:00', '', NULL, 3, '\n\n[05 Jan 2026 19:28 - Khizar ]\n vcxcx\n\n[05 Jan 2026 19:29 - Khizar ]\nrgfgfd'),
(34, 'Muhammad Jarrar Jadoon', 'Male', '03135372477', 'jarrarjadoon@gmail.com', NULL, NULL, '', 'Abbottabad university of science and technology ', 'Computer science ', 'Web Development, Graphic Designing, AI / Machine Learning, Cyber Security, Amazon eCommerce / Marketplace', 10, 'Undergraduate', '', '1st', '', NULL, 'Sheikh ul bandi Abbottabad', NULL, NULL, NULL, NULL, '', NULL, '', '', '2025-11-29 13:31:06', '2026-01-24 14:31:14', 'website', 'Not Interested', 'website', 'Medium', 'Admin', '2026-01-24 14:31:14', NULL, '', NULL, 0, '\n\n[24 Jan 2026 19:31 - Admin - WhatsApp]\nnot interested'),
(35, 'Attiqa Rehman ', 'Female', '0348 7359266 ', 'rehmanattiqa0@gmail.com', NULL, NULL, '', 'Abbottabad university of science and technology ', 'Computer science ', 'Web Development', 10, 'Undergraduate', '', '1st ', '', NULL, 'Banda phugwarian last stop ', NULL, NULL, NULL, NULL, '', NULL, '', '', '2025-11-29 14:16:03', '2026-02-17 13:36:20', 'website', 'Not Interested', 'website', 'Medium', 'Khizar ', '2026-02-17 13:36:20', '2026-01-25 00:00:00', '', NULL, 0, '\n\n[24 Jan 2026 19:17 - Admin - WhatsApp]\ncal for visit\n\n[17 Feb 2026 18:36 - Khizar  - WhatsApp]\nnot answering'),
(36, 'Salar Ahmed', 'Male', '03169177881', 'asalar510@gmail.com', NULL, NULL, '', 'Abbottabad university of science and technology ', 'Software engineering ', 'Web Development, Graphic Designing, Mobile App Development, AI / Machine Learning, Amazon Web Services (AWS), Amazon eCommerce / Marketplace', 10, 'Undergraduate', '', '7th', '', NULL, 'Bilal house c/o royal star filling station opposite New boar', NULL, NULL, NULL, NULL, '', NULL, '', '', '2025-11-29 14:19:33', '2026-01-24 15:04:18', 'website', 'Not Interested', 'website', 'Medium', 'Admin', '2026-01-24 15:04:18', '2026-01-05 00:00:00', '', NULL, 3, '\n\n[19 Dec 2025 19:45 - Admin]\nshahmir brothere\n\n[22 Dec 2025 19:36 - Admin]\nno interednts'),
(40, 'ABDUl SAMI KHAN ', 'Male', '03075125947', 'abdualsamikhan@gmail.com', NULL, NULL, '', 'AUST ', 'Software engineer ', 'Mobile App Development, AI / Machine Learning', 10, 'Undergraduate', '', '7', '', NULL, 'Havellian ', NULL, NULL, NULL, NULL, '.', NULL, '', '', '2025-11-30 07:29:44', '2026-01-25 11:06:53', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 15:03:29', '2026-01-25 00:00:00', '', NULL, 3, '\n\n[22 Dec 2025 16:03 - Admin]\nsdsf\n\n[22 Dec 2025 16:04 - Admin]\ncbcvbcv\n\n[24 Jan 2026 20:03 - Admin - WhatsApp]\nconfirm the interst and visit'),
(56, 'Maaz Ihsan', 'Male', '03104784777', 'maazihsan303@gmail.com', NULL, NULL, '', 'Abbottabad University ', 'Software Engineering ', 'Web Development', 0, 'Undergraduate', '', '3rd', '', '0000', 'Jinnahabad, Abbottabad ', NULL, NULL, NULL, NULL, 'No additional information ', NULL, '2026-01-01', '12:00 PM', '2025-12-31 04:32:35', '2026-01-25 11:06:57', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:28:31', '2026-01-25 00:00:00', '', NULL, 0, '\n\n[24 Jan 2026 19:28 - Admin - WhatsApp]\ncall for visit and interest conformation\r\n'),
(57, 'Muhammed Zakria', 'Male', '03489828312', 'zakriam663@gmail.com', NULL, NULL, '', 'Gpgs no1 abbottabad ', 'Computer science ', 'Web Development', 10, 'Intermediate', '12', '', '', '0000', 'Lower malik pura maira house no lm554 atd', NULL, NULL, NULL, NULL, '', NULL, '', '', '2025-12-31 05:49:15', '2026-01-25 11:07:01', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:26:45', '2026-01-25 00:00:00', '', NULL, 1, '\n\n[08 Jan 2026 20:37 - Khizar ]\nfdsjhfgjhdsf\n\n[24 Jan 2026 19:26 - Admin - WhatsApp]\ncall to confirm visit'),
(64, 'Syed Fakhir Ali Shah', 'Male', '03105390627', 'alisfakhir@gmail.com', NULL, NULL, '', 'Commmerce College, Jinahabad, Atd', 'BBA', 'Web Development, AI / Machine Learning', 11, 'Undergraduate', '', '7', '', '0000', 'banda nabi, public school atd', NULL, NULL, NULL, NULL, 'Reference from Asad', NULL, '2026-01-09', '03:00 PM', '2026-01-06 10:59:06', '2026-01-25 11:07:05', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:33:25', '2026-01-25 00:00:00', '', NULL, 3, '\n\n[07 Jan 2026 16:39 - Khizar ]\nhfuyjgy\n\n[24 Jan 2026 19:24 - Admin - Call]\ncal to get enrolled urgently'),
(66, 'Fahad zeb', 'Male', '03209886214', 'Fahadzeb1500@gmail.com', NULL, NULL, '', 'Comsats university Abbottabad campus', 'Software Engneering', 'Web Development, AI / Machine Learning', 7, 'Undergraduate', '', '6th', '', '0000', 'Dargai manga disst charsadda mohallah dagwal', NULL, NULL, NULL, NULL, '', NULL, '2026-01-19', '10:00 AM', '2026-01-17 16:56:18', '2026-01-25 11:07:10', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:08:47', '2026-01-25 00:00:00', '', NULL, 0, '\n\n[24 Jan 2026 19:08 - Admin - WhatsApp]\nno solid response yet'),
(74, 'Muhammad Azeem Tahir', 'Male', '03147446059', 'azeemyousafzai212@gmail.com', NULL, NULL, '', 'Allama Iqbal open university ', 'Sciences', 'Web Development', 7, 'Primary', '10', '', '', '0000', 'Mansehra road sethi colony opposite sethi masjid street 3 ', NULL, NULL, NULL, NULL, '', NULL, '2026-01-20', '04:00 PM', '2026-01-19 09:39:46', '2026-01-26 05:24:25', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:37:19', '2026-01-26 00:00:00', '16:00', NULL, 2, '\n\n[20 Jan 2026 16:30 - Khizar ]\nFirst student'),
(76, 'Aneela Khurshid', 'Female', '03235275198', 'aneelaaneela2604@gmail.com', NULL, NULL, '', 'Comsats Abbottabad', 'Software Engineering', 'Web Development, Mobile App Development, AI / Machine Learning, UI / UX Design', 7, 'Undergraduate', '', '4th', '', '0000', 'House#11 street#13 Bilal town', NULL, NULL, NULL, NULL, '', NULL, '2026-01-20', '04:00 PM', '2026-01-19 10:34:47', '2026-01-25 11:07:17', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:14:27', '2026-01-25 00:00:00', '', NULL, 2, '\n\n[22 Jan 2026 16:51 - Khizar ]\nfghjkl;lkjhgfghjkl;\'\n\n[22 Jan 2026 22:52 - Khizar ]\ncall again\n\n[24 Jan 2026 19:14 - Admin - WhatsApp]\ncall again for visit and demo'),
(78, 'Fazal ur Rehman', 'Male', '03298717148', 'fazal676869@gmail.com', NULL, NULL, '', 'Vision Islamic school ', 'Medical ', 'Web Development, Graphic Designing', 11, 'Primary', '1st year', '', '', '0000', 'Abbotabad ', NULL, NULL, NULL, NULL, '', NULL, '', '', '2026-01-20 07:36:17', '2026-01-24 14:04:56', 'website', 'Not Interested', 'website', 'Medium', 'Admin', '2026-01-24 14:04:56', '2026-01-22 00:00:00', '', NULL, 1, '\n\n[24 Jan 2026 19:04 - Admin - WhatsApp]\nfee issue'),
(81, 'Sufian ali khan', 'Male', '03183049707', 'sufiyanyy59@gmail.com', NULL, NULL, '', 'GOVT QAZI SULTAN HIGHER SEC SCHOOL KUNRI ', 'Computer Science ', 'Cyber Security', 10, 'Intermediate', 'A1', '', '', '0000', 'mohala amirabad town kamati kunri ward number 317', NULL, NULL, NULL, NULL, '', NULL, '2026-01-30', '03:00 PM', '2026-01-22 17:36:00', '2026-01-25 11:07:22', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 15:08:31', '2026-01-29 00:00:00', '', NULL, 0, '\n\n[24 Jan 2026 19:02 - Admin - WhatsApp]\ncall and confirm visit \n\n[24 Jan 2026 20:08 - Admin - WhatsApp]\ncall and confirm visit'),
(83, 'Ikram Ul Haq', 'Male', '03361476636', 'mehboobtnoli@gmail.com', NULL, NULL, '', 'Abbottabad University Of Science And Technology ', 'Software engineering', 'Graphic Designing, Mobile App Development, AI / Machine Learning, Data Science, Blockchain Development, DevOps & Cloud Engineering', 8, 'Undergraduate', '', '3', '', '0000', 'House no 14 Dhangri Rd Mansehra', NULL, NULL, NULL, NULL, '', NULL, '2026-01-28', '02:37 PM', '2026-01-22 18:37:34', '2026-01-25 11:07:26', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:58:29', '2026-01-29 00:00:00', '', NULL, 2, '\n\n[24 Jan 2026 18:45 - Admin - Call]\n30 jan , 3 pm  vist confirmed\n\n[24 Jan 2026 19:58 - Admin - Call]\n30 jan , 3 pm vist confirmed'),
(84, 'Akasha zaib', 'Male', '03195024959', 'akashazaib994@gmail.com', NULL, NULL, '', 'Aust Abbottabad ', 'Computer science ', 'Web Development, Mobile App Development, Digital Marketing', 10, 'Undergraduate', '', '6', '', '0000', 'Abbottabad ', NULL, NULL, NULL, NULL, '', NULL, '2026-02-01', '10:19 PM', '2026-01-22 18:55:54', '2026-01-25 11:07:30', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 13:41:27', '2026-01-25 00:00:00', '', NULL, 1, '\n\n[24 Jan 2026 18:41 - Admin - Call]\nphone busy. cal one last time'),
(85, 'Ashar Jabbar Khan', 'Male', '03101562338', 'asharjabbar76@gmail.com', NULL, NULL, '', 'Comsats University Islamabad , Abbottabad Campus ', 'Computer Science', '', 7, 'Graduate', '', '', '', '2026', 'House no 37 babu chowk Sector 1 kts Haripur', NULL, NULL, NULL, NULL, 'Have some questions , will discuss inshaAllah.', NULL, '2026-01-26', '05:00 PM', '2026-01-22 19:23:37', '2026-01-26 13:56:21', 'website', 'Enrolled', 'website', 'Medium', 'Khizar ', '2026-01-26 13:56:21', '2026-01-25 00:00:00', '', NULL, 2, '\n\n[23 Jan 2026 21:04 - Khizar ]\nasad reference\n\n[24 Jan 2026 19:56 - Admin - Call]\nfee and visit .asad reference\n\n[26 Jan 2026 18:56 - Khizar  - WhatsApp]\nenrolled in Evening session'),
(86, 'Hamid Khan', 'Other', '03117771321', 'hamidjadoon14@gmail.com', NULL, NULL, '', 'Gpgc no1 Atd', 'Medical ', 'Web Development', 10, 'Intermediate', '12', '', '', '0000', 'dhamtour thai near forest check post', NULL, NULL, NULL, NULL, '', NULL, '2026-02-09', '02:15 PM', '2026-01-23 18:15:12', '2026-01-25 11:07:49', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:55:42', '2026-01-25 00:00:00', '', NULL, 0, '\n\n[24 Jan 2026 18:16 - Admin - WhatsApp]\nHe is in sialkot, will back on 9 feb\n\n[24 Jan 2026 19:55 - Admin - WhatsApp]\nHe is in sialkot, will back on 9 feb'),
(87, 'Ahsan saleem', 'Male', '03484414955', 'sardarahsan123abc@gmail.com', NULL, NULL, '', 'Aust abbottabad', 'Computer science', 'Graphic Designing', 12, 'Undergraduate', '', '8', '', '0000', 'Moh mallach P/O Nathiagali Abbottabad.', NULL, NULL, NULL, NULL, '', NULL, '2026-03-01', '10:00 AM', '2026-01-24 06:14:42', '2026-01-25 11:07:40', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 13:40:23', '2026-01-25 00:00:00', '', NULL, 0, '\n\n[24 Jan 2026 18:40 - Admin - WhatsApp]\nphone turned off'),
(89, 'Syed Ali Shah', 'Male', '03165540227', 'sherazi12ali12@gmail.com', NULL, NULL, '', 'Degree no 2 college mandiya abbottabad ', 'Computer science ', 'AI / Machine Learning', 12, 'Intermediate', 'Fsc', '', '', '0000', 'Niyazi colony supply abbottabad', NULL, NULL, NULL, NULL, '', NULL, '2026-01-24', '03:53 PM', '2026-01-24 10:54:00', '2026-01-25 13:06:19', 'website', 'Follow-up Required', 'website', 'Medium', 'Admin', '2026-01-24 14:54:19', '2026-01-25 00:00:00', '17:00', NULL, 0, '\n\n[24 Jan 2026 18:31 - Admin - WhatsApp]\ncal again to confirm visit\n\n[24 Jan 2026 19:54 - Admin - WhatsApp]\ncal again to confirm visit'),
(92, 'Imtinan mir', 'Male', '03462116477', 'immmir71@gmail.com', NULL, NULL, '', 'COMSATS ', 'Computer Science ', 'AI / Machine Learning', 10, 'Graduate', '', '', '', '2025', 'Mir street, nawanshehr , Abbottabad ', NULL, NULL, NULL, NULL, '', NULL, '2026-01-26', '05:00 PM', '2026-01-26 06:00:31', '2026-01-27 06:42:28', 'website', 'Enrolled', 'website', 'Medium', 'Admin', '2026-01-27 06:42:28', NULL, '', NULL, 0, '\n\n[27 Jan 2026 11:42 - Admin - WhatsApp]\nEnrolled in frontend, evening 2, fellow ashar jabbar khan'),
(93, 'Muzammal rasheed', 'Male', '03334605428', 'muzmailkhan686@gmail.com', NULL, NULL, '', 'Gpgc no2 mandian abbottabad', 'Computer science', 'Web Development, Mobile App Development, AI / Machine Learning', 11, 'Intermediate', '1st', '', '', '0000', 'Pips college mandian abbottabad', NULL, NULL, NULL, NULL, '', NULL, '2026-01-27', '02:00 AM', '2026-01-26 20:43:27', '2026-01-28 09:43:50', 'website', 'New', 'website', 'Medium', 'Admin', '2026-01-28 09:43:50', NULL, '', NULL, 0, '\n\n[28 Jan 2026 14:43 - Admin - WhatsApp]\nstudent msg i cant afford it'),
(95, 'Abid Hussain', 'Male', '03447878942', 'abidbili1998@gmail.com', NULL, NULL, '', 'post graduate college, degree 1', 'Arts', 'Web Development, Freelancing', 7, 'Intermediate', '2nd year', '', '', '0000', 'siddique shopping centre mandian abbotttabad', NULL, NULL, NULL, NULL, 'asad reference , no admission fee', NULL, '2026-01-29', '01:38 PM', '2026-01-29 08:39:42', '2026-01-29 08:43:32', 'website', 'Enrolled', 'website', 'Medium', 'Khizar ', '2026-01-29 08:43:32', NULL, '', NULL, 1, '\n\n[29 Jan 2026 13:43 - Khizar  - Call]\nAsad Reference, No admission fee'),
(96, 'Muhammad Usman Ali', 'Male', '03141959072', 'usman@filenod.com', NULL, NULL, '', 'The Peace International', 'computer Science', 'Web Development, Freelancing', 7, 'Secondary', '8th', '', '', '0000', 'mian di seri, kalalpul , atd', NULL, NULL, NULL, NULL, 'Asad Reference\r\n', NULL, '2026-01-29', '03:55 PM', '2026-01-29 10:56:12', '2026-02-02 00:21:13', 'website', 'Enrolled', 'website', 'Medium', 'Khizar ', '2026-01-29 15:51:52', NULL, '', NULL, 1, '\n\n[29 Jan 2026 20:51 - Khizar  - Call]\nfarman reference'),
(97, 'Muhammad Moiz', 'Male', '03410757652', 'moiza708@gmail.com', NULL, NULL, '', 'hazara university', 'biochemistry', 'Web Development, Mobile App Development, Freelancing', 7, 'Undergraduate', '', '4 sem', '', '0000', 'Punjab chowk mansehra', NULL, NULL, NULL, NULL, 'haris swati', NULL, '2026-01-30', '05:35 PM', '2026-01-30 12:36:47', '2026-01-30 12:36:47', 'website', 'New', 'website', 'Medium', NULL, NULL, NULL, '', NULL, 0, NULL),
(98, 'Hamza Asad', 'Male', '03295616013', 'hmazaqureshibuisness@gmial.com', NULL, NULL, '', 'Peace School & College Abbottabad', 'Computer Science', 'Web Development, Freelancing, Python', 7, 'Intermediate', '12', '', '', '0000', 'street 5 , sir sayyad , 5 , Abbottabad', NULL, NULL, NULL, NULL, 'Interested . need confirmation from home', NULL, '2026-02-13', '03:00 PM', '2026-02-12 13:11:39', '2026-02-19 07:48:37', 'website', 'Not Interested', 'website', 'Medium', 'Khizar ', '2026-02-19 07:48:37', NULL, '', NULL, 2, '\n\n[12 Feb 2026 18:15 - Khizar  - Call]\nNeed confirmation from home\n\n[19 Feb 2026 12:48 - Khizar  - Call]\nnot interested'),
(99, 'Iqra riaz', 'Female', '03295832927', 'iqrariaz024@gmail.com', NULL, NULL, '', 'Allama Iqbal open uni', 'Bcom', 'Web Development', 11, 'Graduate', '', '', '', '2022', 'Abbottabad Phol ghulab road wali Muhammad khan manzil', NULL, NULL, NULL, NULL, '', NULL, '2026-02-19', '07:15 PM', '2026-02-18 18:41:00', '2026-02-18 18:41:00', 'website', 'New', 'website', 'Medium', NULL, NULL, NULL, '', NULL, 0, NULL),
(100, 'Wajahat Hameed', 'Male', '03146965099', 'wajahathameed198@gmail.com', NULL, NULL, '', 'University of haripur', 'Computer Science ', 'Web Development', 7, 'Undergraduate', '', '8th', '', '0000', 'mohallah zahidabad', NULL, NULL, NULL, NULL, '', NULL, '2026-02-23', '04:00 PM', '2026-02-18 18:45:52', '2026-02-18 18:45:52', 'website', 'New', 'website', 'Medium', NULL, NULL, NULL, '', NULL, 0, NULL),
(102, 'Ahsan Khalid', 'Male', '03480244484', 'ahsankhalid0968@gmail.com', NULL, NULL, '', 'COMSATS abbottabad', 'Computer science', 'AI / Machine Learning', 8, 'Undergraduate', '', '4th', '', '0000', 'Street no 5 sirysed colony mandia Abbottabad', NULL, NULL, NULL, NULL, '', NULL, '2026-04-01', '05:30 PM', '2026-02-28 00:50:46', '2026-04-03 17:09:40', 'website', 'New', 'website', 'Medium', 'Khizar ', '2026-04-03 17:09:40', NULL, '', NULL, 1, '\n\n[03 Apr 2026 22:09 - Khizar  - Call]\nhe is so int erer\n\n[03 Apr 2026 22:09 - Khizar  - WhatsApp]\nsdfsdf'),
(103, 'muhammad faisal', 'Male', '03312835064', 'mfaisalatd@gmail.com', NULL, NULL, '', 'federal, urdu university karachi', 'english literature', 'Web Development, Freelancing', 11, 'Graduate', '', '', '', '2016', 'phul gulab road mandian abbottabad', NULL, NULL, NULL, NULL, 'inquiring for himself and for someone else as welll. planning to join after Eid', NULL, '2026-02-28', '04:46 PM', '2026-02-28 11:47:15', '2026-02-28 11:48:45', 'website', 'Interested', 'website', 'Medium', 'Khizar ', '2026-02-28 11:48:45', NULL, '', NULL, 1, '\n\n[28 Feb 2026 16:48 - Khizar  - Call]\ncal before, confirmation');

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
-- Table structure for table `careers`
--

CREATE TABLE `careers` (
  `id` int(11) NOT NULL,
  `position` varchar(60) NOT NULL,
  `fullname` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` int(32) NOT NULL,
  `city` varchar(255) NOT NULL,
  `education` varchar(255) NOT NULL,
  `experience_years` varchar(11) NOT NULL,
  `linkedin` varchar(255) NOT NULL,
  `portfolio` varchar(255) NOT NULL,
  `skills` varchar(500) NOT NULL,
  `cover_letter` varchar(500) NOT NULL,
  `cv` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `careers`
--

INSERT INTO `careers` (`id`, `position`, `fullname`, `email`, `phone`, `city`, `education`, `experience_years`, `linkedin`, `portfolio`, `skills`, `cover_letter`, `cv`, `created_at`) VALUES
(1, 'Assistant Supervisor', 'Khizar Ahmad', 'khizarahmad0920@gmail.com', 2147483647, 'Abbottabad', 'Bachelor\'s', '0-1', 'https://www.linkedin.com/in/khizar-ahmad-41a56b258/', 'https://khizar0920.github.io/khizar/', 'HTML,CSS,JS,PHP,MYSQl', '', '1767873300_Student Profile - Khizar Ahmad.pdf', '2026-01-08 12:07:19'),
(2, 'Assistant Supervisor', 'Dawood Jan', 'dawoodjanedu22@gmail.com', 2147483647, 'Abbottabad', 'Bachelor\'s', '1-3', '', '', '', '', '', '2026-01-21 15:12:01');

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
(1, 'Web Development', '2025-11-15 11:27:09'),
(2, 'Adobe Photoshop', '2025-12-05 09:08:41'),
(3, 'Web Development', '2026-04-25 12:20:48');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `slots` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `description`, `slots`, `created_at`) VALUES
(5, 'Class 1', 'Abbottabad', 13, '2025-11-15 05:10:27');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `account_type` varchar(255) NOT NULL,
  `account_balance` varchar(255) NOT NULL,
  `currency_type` varchar(255) NOT NULL,
  `transaction_date` varchar(255) NOT NULL,
  `transaction_type` varchar(255) NOT NULL,
  `comment` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `fname`, `lname`, `address`, `phone`, `email`, `occupation`, `account_id`, `account_type`, `account_balance`, `currency_type`, `transaction_date`, `transaction_type`, `comment`) VALUES
(1, 'Khizar', 'Ahmad', 'Abbottabad', '03328912706', 'Khizarahmad0920@gmail.com', 'Dev', '28392793', 'saving', '89389', 'rup,', '2024-11-14', 'bank', 'register khizar');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `course_title` text NOT NULL,
  `duration` text DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `tasks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tasks`)),
  `timestamp` varchar(20) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `learning_hours` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `category_id`, `course_title`, `duration`, `fee`, `description`, `image`, `tasks`, `timestamp`, `created_at`, `learning_hours`) VALUES
(7, 1, 'Frontend', '2', 90000.00, 'Turn your ideas into stunning websites! Learn front-end development, make your web pages interactive, and impress users with smooth, modern designs.', 'frontend.jpg', NULL, '2025-12-05 10:06:51', '2025-12-05 10:06:51', 61),
(8, 1, 'Backend', '2', 90000.00, 'Power your websites from behind the scenes! Learn server-side programming, database management, and API development.', 'backend.png', NULL, '2025-12-05 10:51:35', '2025-12-05 10:51:35', 0),
(10, 1, 'Full Stack', '2', 90000.00, 'Learn to build complete web applications from front-end to back-end using modern technologies.', 'fullstack.jpg', NULL, '2026-01-01 15:14:51', '2026-01-01 15:14:51', 0),
(11, 1, 'CMS (Wordpress)', '2', 90000.00, 'Learn to create, customize, and manage websites using WordPress. Build professional sites with themes, plugins, and dynamic content.', 'wordpress.png', NULL, '2026-01-01 15:15:32', '2026-01-01 15:15:32', 0),
(12, 1, 'CMS (Shopify)', '2', 90000.00, 'Learn to build and manage online stores using Shopify. Set up products, payments, and themes easily.', 'shopify.png', NULL, '2026-01-01 15:19:02', '2026-01-01 15:19:02', 0),
(13, 1, 'CMS (Wix)', '2', 90000.00, 'Learn to create stunning websites with Wix. Drag, drop, and design professional sites without coding.', 'wix.png', NULL, '2026-01-01 15:19:21', '2026-01-01 15:19:21', 0);

-- --------------------------------------------------------

--
-- Table structure for table `expences`
--

CREATE TABLE `expences` (
  `id` int(11) NOT NULL,
  `date` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `vendor` varchar(255) NOT NULL,
  `recurring` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `project_account` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `month` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expences`
--

INSERT INTO `expences` (`id`, `date`, `category`, `client`, `payment_method`, `description`, `vendor`, `recurring`, `currency`, `attachment`, `project_account`, `timestamp`, `month`) VALUES
(6, '2024-11-16', 'Bus', '', 'bank', 'bank payment', 'bus for school', 'yes', 'Rupee', 'fineartmineral.jpg', '8453496', '2024-11-16 10:03:10', ''),
(7, '2024-11-17', 'Transport', 'Khizar', 'Bank', 'for transport', 'jdfhksjd skdjh fkjdhfj', 'yes', 'Dollars', 'fineartmineral.jpg', '8453496', '2024-11-17 13:18:45', ''),
(8, '2024-11-17', 'Office', 'Khizar', 'Bank', 'ofice rent paid', 'ghjkl', 'yes', 'Dollars', 'fineartmineral.jpg', '76786878', '2024-11-17 14:21:01', 'November');

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `type` varchar(60) NOT NULL,
  `monthly` varchar(11) NOT NULL,
  `amount` bigint(30) NOT NULL,
  `month` varchar(20) NOT NULL,
  `due_date` varchar(30) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` varchar(30) NOT NULL,
  `status` varchar(40) NOT NULL,
  `fee_month` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `course_id`, `type`, `monthly`, `amount`, `month`, `due_date`, `description`, `created_at`, `status`, `fee_month`) VALUES
(9, 7, 'Course Fees', '30000', 90000, '3', '', 'frontend course fee', '2025-12-05 10:10:21', '', ''),
(10, 8, 'Course Fees', '30000', 90000, '3', '', '3 months backend course fee', '2025-12-05 10:53:53', '', ''),
(11, 7, 'Course Fees', '30000', 90000, '3', '', '', '2025-12-05 22:03:34', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `inactive_users`
--

CREATE TABLE `inactive_users` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `timestamp` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inactive_users`
--

INSERT INTO `inactive_users` (`id`, `username`, `timestamp`) VALUES
(8, 'web-00034', '2026-01-26 14:03:14'),
(9, 'web-00036', '2026-01-28 17:09:31'),
(11, 'web-00037', '2026-01-30 12:45:36'),
(12, 'web-00038', '2026-02-16 09:17:25');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `warehouse` varchar(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `username`, `product_name`, `source`, `warehouse`, `picture`, `date`, `timestamp`, `status`) VALUES
(1, 'admin', 'salajit', 'minerals', 'Abbottabad', 'bookingform.jpeg', '2024-11-18', ' 2024-11-18 10:29:15', 'Sold');

-- --------------------------------------------------------

--
-- Table structure for table `investments`
--

CREATE TABLE `investments` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `payment_Date` varchar(255) NOT NULL,
  `amount_paid` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `inverstment_number_id` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investments`
--

INSERT INTO `investments` (`id`, `company_name`, `payment_Date`, `amount_paid`, `payment_method`, `location`, `inverstment_number_id`, `description`, `timestamp`, `month`) VALUES
(1, 'Filenod', '2024-11-16', '8000', 'Easypaisa', 'Abbottabad', '4789948', 'paid amount', ' 2024-11-16 20:19:01', '');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'Draft',
  `thumbnail` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `purchase_date` varchar(255) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `itemdescription` varchar(255) NOT NULL,
  `unitprice` varchar(255) NOT NULL,
  `totalamount` varchar(255) NOT NULL,
  `payment_duedate` varchar(255) NOT NULL,
  `paidamount` varchar(255) NOT NULL,
  `balanceamount` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `purchase_date`, `supplier_name`, `itemdescription`, `unitprice`, `totalamount`, `payment_duedate`, `paidamount`, `balanceamount`, `timestamp`, `month`) VALUES
(1, '2024-11-16', 'khizar', 'item for sale', '12000', '12000', '2024-11-20', '12000', '12000', '2024-11-16 19:56:18', '');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `date_sale` varchar(255) NOT NULL,
  `sale_person` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `product_id` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` varchar(255) NOT NULL,
  `discounts` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `shipping_info` varchar(255) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `comments` varchar(255) NOT NULL,
  `essential_features` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `date_sale`, `sale_person`, `customer_id`, `customer_name`, `product_id`, `product_name`, `unit_price`, `discounts`, `total_amount`, `payment_method`, `payment_status`, `shipping_info`, `shipping_address`, `comments`, `essential_features`, `timestamp`, `month`) VALUES
(1, '2024-11-16', 'khizar', '82738', 'hamza', '3945734895', 'salajit', '12000', '10', '12100', 'Bank transfer', 'paid', 'Afg kabul', 'kabul kuri road', 'jkffj befhek jkhkf hefjekl', 'Expensive', '2024-11-16 15:54:13', '');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `class_id` bigint(11) NOT NULL,
  `category_id` bigint(11) NOT NULL,
  `course_id` varchar(60) NOT NULL,
  `teacher_id` varchar(11) NOT NULL,
  `start_time` varchar(30) NOT NULL,
  `end_time` varchar(30) NOT NULL,
  `slots` bigint(20) NOT NULL,
  `booked_slots` varchar(30) NOT NULL,
  `available_slots` varchar(30) NOT NULL,
  `created_at` varchar(30) NOT NULL,
  `course_duration_weeks` int(2) DEFAULT 8,
  `session_start_date` date DEFAULT NULL,
  `class_days` varchar(100) DEFAULT 'Monday,Tuesday,Wednesday,Thursday,Friday'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `title`, `class_id`, `category_id`, `course_id`, `teacher_id`, `start_time`, `end_time`, `slots`, `booked_slots`, `available_slots`, `created_at`, `course_duration_weeks`, `session_start_date`, `class_days`) VALUES
(13, 'Evening 2', 5, 1, '7', '7', '04:00 PM', '', 11, '4', '', '2026-02-09 11:13:54', 8, '2026-02-02', 'Monday,Tuesday,Wednesday,Thursday,Friday');

-- --------------------------------------------------------

--
-- Table structure for table `session_classes`
--

CREATE TABLE `session_classes` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `week_number` int(2) NOT NULL,
  `day_of_week` varchar(10) NOT NULL,
  `class_date` date NOT NULL,
  `start_time` varchar(30) DEFAULT NULL,
  `end_time` varchar(30) DEFAULT NULL,
  `duration_hours` decimal(3,1) NOT NULL,
  `status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session_classes`
--

INSERT INTO `session_classes` (`id`, `session_id`, `week_number`, `day_of_week`, `class_date`, `start_time`, `end_time`, `duration_hours`, `status`, `created_at`) VALUES
(41, 13, 1, 'Monday', '2026-02-02', '04:00 PM', '06:00 PM', 2.0, 'scheduled', '2026-02-09 11:13:54'),
(42, 13, 1, 'Tuesday', '2026-02-03', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(43, 13, 1, 'Wednesday', '2026-02-04', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(44, 13, 1, 'Thursday', '2026-02-05', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(45, 13, 1, 'Friday', '2026-02-06', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(46, 13, 2, 'Monday', '2026-02-09', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(47, 13, 2, 'Tuesday', '2026-02-10', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(48, 13, 2, 'Wednesday', '2026-02-11', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(49, 13, 2, 'Thursday', '2026-02-12', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(50, 13, 2, 'Friday', '2026-02-13', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(51, 13, 3, 'Monday', '2026-02-16', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(52, 13, 3, 'Tuesday', '2026-02-17', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(53, 13, 3, 'Wednesday', '2026-02-18', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(54, 13, 3, 'Thursday', '2026-02-19', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(55, 13, 3, 'Friday', '2026-02-20', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(56, 13, 4, 'Monday', '2026-02-23', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(57, 13, 4, 'Tuesday', '2026-02-24', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(58, 13, 4, 'Wednesday', '2026-02-25', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(59, 13, 4, 'Thursday', '2026-02-26', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(60, 13, 4, 'Friday', '2026-02-27', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(61, 13, 5, 'Monday', '2026-03-02', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(62, 13, 5, 'Tuesday', '2026-03-03', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(63, 13, 5, 'Wednesday', '2026-03-04', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(64, 13, 5, 'Thursday', '2026-03-05', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(65, 13, 5, 'Friday', '2026-03-06', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(66, 13, 6, 'Monday', '2026-03-09', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(67, 13, 6, 'Tuesday', '2026-03-10', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(68, 13, 6, 'Wednesday', '2026-03-11', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(69, 13, 6, 'Thursday', '2026-03-12', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(70, 13, 6, 'Friday', '2026-03-13', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(71, 13, 7, 'Monday', '2026-03-16', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(72, 13, 7, 'Tuesday', '2026-03-17', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(73, 13, 7, 'Wednesday', '2026-03-18', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(74, 13, 7, 'Thursday', '2026-03-19', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(75, 13, 7, 'Friday', '2026-03-20', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(76, 13, 8, 'Monday', '2026-03-23', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(77, 13, 8, 'Tuesday', '2026-03-24', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(78, 13, 8, 'Wednesday', '2026-03-25', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(79, 13, 8, 'Thursday', '2026-03-26', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54'),
(80, 13, 8, 'Friday', '2026-03-27', '04:00 PM', '05:30 PM', 1.5, 'scheduled', '2026-02-09 11:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(20) NOT NULL,
  `admission_no` varchar(20) NOT NULL,
  `registration_no` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `cnic` varchar(30) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `current_grade` varchar(50) DEFAULT NULL,
  `institute` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `session_id` int(11) NOT NULL,
  `slot` int(11) NOT NULL,
  `guardian_name` varchar(40) NOT NULL,
  `guardian_relation` varchar(30) NOT NULL,
  `guardian_phone` bigint(20) NOT NULL,
  `registration_date` varchar(30) NOT NULL,
  `status` varchar(11) NOT NULL,
  `months` varchar(20) NOT NULL,
  `fee_status` varchar(20) NOT NULL,
  `paid_amount` varchar(30) NOT NULL,
  `reference` varchar(20) NOT NULL,
  `field_education` varchar(60) NOT NULL,
  `education_status` varchar(30) NOT NULL,
  `current_semester` varchar(30) NOT NULL,
  `other_degree` varchar(500) NOT NULL,
  `graduation_year` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `admission_no`, `registration_no`, `name`, `gender`, `dob`, `mobile_no`, `cnic`, `email`, `current_grade`, `institute`, `address`, `category_id`, `course_id`, `session_id`, `slot`, `guardian_name`, `guardian_relation`, `guardian_phone`, `registration_date`, `status`, `months`, `fee_status`, `paid_amount`, `reference`, `field_education`, `education_status`, `current_semester`, `other_degree`, `graduation_year`) VALUES
(34, '000034', 'web-00034', 'Muhammad Azeem Tahir', 'male', '2007-01-15', '03147446059', ' 161018015199', 'azeemyousafzai212@gmail.com', '10', 'Allama Iqbal open university ', 'Mansehra road sethi colony opposite sethi masjid street 3 ', 1, 7, 10, 1, 'Muhammad Tahir', 'Father', 3147446059, '2026-01-20 11:32:43', 'Inactive', '2', 'Unpaid', '', 'khizar4562', 'Science', 'Secondary', '', '', '0000'),
(35, '000035', 'web-00035', 'Ashar Jabbar Khan', 'male', '2002-11-01', '03101562338', '1330285952669', 'asharjabbar76@gmail.com', '', 'Comsats University Islamabad , Abbottabad Campus ', 'House no 37 babu chowk Sector 1 kts Haripur', 1, 7, 13, 1, 'Tariq Mehmood', 'Father', 3005127383, '2026-01-23 16:15:43', 'Active', '2', 'Unpaid', '', 'khizar4562', 'Computer Science', 'Undergraduate', '8th', '', '2026'),
(38, '000038', 'web-00038', 'Muhammad Usman Ali', 'male', '2009-08-08', '03141959072', '3410332980125', 'hasntsaith751@gmail.com', '8th', 'The Peace International', 'mian di seri, kalalpul , atd', 1, 7, 13, 3, 'Muhammad Maqbool', 'Father', 3095322466, '2026-01-29 11:05:46', 'Inactive', '2', 'Unpaid', '', 'khizar4562', 'computer Science', 'Secondary', '', '', '0000'),
(39, '000039', 'web-00039', 'Wahid Ali', 'male', '2026-02-08', '03255769270', '1350320851879', 'khizarahmad222111@gmail.com', '', 'Abbottabad university of science and technology ', 'VILLAGE DARRA POST OFFICE PIND KARGU KHAN TAHSEEL LOWER TANA', 1, 7, 12, 1, 'cvcbcv', 'vnbv', 6757657657, '2026-02-08 16:48:42', 'Inactive', '2', 'Unpaid', '', 'Portal', 'Software engineering ', 'Undergraduate', '7th', '', ''),
(40, '000040', 'web-00040', 'Sufiyan Khan', 'male', '2003-06-06', '03181830567', '1310123705651', 'sufiyankhanjadoon8@gmail.com', '12', 'Iqra Public School & College', 'mirpur klan , muhallah musa zai', 1, 7, 13, 2, 'Fareedoon Khan Jadoon', 'Father', 3145025405, '2026-02-10 13:29:21', 'Active', '2', 'Unpaid', '', 'khizar4562', 'Computer Science', 'Intermediate', '', '', ''),
(41, '000041', 'web-00041', 'Umar Atta', 'male', '2008-08-05', '03151617420', '135034948438', 'umaratta355@gmail.com', '12', 'tameer-i-wattan', 'SOS Youth Home Abbottabad', 1, 7, 13, 4, 'Ghous Ali Shah', 'uncle', 3488488448, '2026-03-13 10:17:05', 'Active', '2', 'Unpaid', '', 'khizar4562', 'medical', 'Intermediate', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `id` int(11) NOT NULL,
  `student_id` bigint(30) NOT NULL,
  `session_id` bigint(30) NOT NULL,
  `attendance` varchar(30) NOT NULL,
  `entry_time` varchar(30) NOT NULL,
  `exit_time` varchar(30) NOT NULL,
  `note` varchar(255) NOT NULL,
  `created_at` varchar(40) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `duration_hours` decimal(3,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_attendance`
--

INSERT INTO `student_attendance` (`id`, `student_id`, `session_id`, `attendance`, `entry_time`, `exit_time`, `note`, `created_at`, `class_id`, `duration_hours`) VALUES
(27, 35, 13, 'Present', '', '', 'Sharing the timetable and providing a portal orientation', '2026-02-02 12:00:00', NULL, 0.0),
(29, 38, 13, 'Present', '', '', 'Sharing the timetable and providing a portal orientation', '2026-02-02 12:00:00', NULL, 0.0),
(30, 35, 13, 'Present', '16:30', '18:30', '', '2026-02-03 12:00:00', NULL, 0.0),
(31, 36, 13, 'Not marked', '', '', '', '2026-02-03 12:00:00', NULL, 0.0),
(32, 38, 13, 'Present', '17:30', '18:30', 'Late', '2026-02-03 12:00:00', NULL, 0.0),
(33, 35, 13, 'Present', '16:30', '18:30', '', '2026-02-04 12:00:00', NULL, 0.0),
(34, 36, 13, 'Not marked', '', '', '', '2026-02-04 12:00:00', NULL, 0.0),
(35, 38, 13, 'Present', '16:30', '18:30', '', '2026-02-04 12:00:00', NULL, 0.0),
(36, 35, 13, 'Leave', '', '', '', '2026-02-05 12:00:00', NULL, 0.0),
(38, 38, 13, 'Present', '16:00', '17:30', '', '2026-02-05 12:00:00', NULL, 0.0),
(42, 35, 13, 'Leave', '', '', 'On leave', '2026-02-06 12:00:00', 45, 1.5),
(43, 36, 13, 'Not marked', '', '', '', '2026-02-06 12:00:00', 45, 1.5),
(44, 38, 13, 'Present', '16:00', '17:30', '', '2026-02-06 12:00:00', 45, 1.5),
(45, 35, 13, 'Absent', '', '', '', '2026-02-09 12:00:00', 46, 1.5),
(46, 36, 13, 'Not marked', '', '', '', '2026-02-09 12:00:00', 46, 1.5),
(47, 38, 13, 'Present', '16:00', '17:30', '', '2026-02-09 12:00:00', 46, 1.5),
(48, 35, 13, 'Present', '16:00', '16:30', '', '2026-02-10 12:00:00', 47, 1.5),
(49, 38, 13, 'Present', '16:00', '17:30', '', '2026-02-10 12:00:00', 47, 1.5),
(50, 40, 13, 'Present', '', '', '', '2026-02-10 12:00:00', 47, 1.5),
(51, 35, 13, 'Present', '', '', '', '2026-02-11 12:00:00', 48, 1.5),
(52, 38, 13, 'Leave', '', '', '', '2026-02-11 12:00:00', 48, 1.5),
(53, 40, 13, 'Present', '', '', '', '2026-02-11 12:00:00', 48, 1.5),
(54, 35, 13, 'Present', '11:30', '13:00', '', '2026-02-12 12:00:00', 49, 1.5),
(55, 38, 13, 'Leave', '', '', '', '2026-02-12 12:00:00', 49, 1.5),
(56, 40, 13, 'Present', '04:00', '18:00', '', '2026-02-12 12:00:00', 49, 1.5),
(57, 35, 13, 'Leave', '', '', '', '2026-02-13 12:00:00', 50, 1.5),
(58, 38, 13, 'Present', '16:17', '17:30', '[Late] ', '2026-02-13 12:00:00', 50, 1.5),
(59, 40, 13, 'Absent', '', '', '', '2026-02-13 12:00:00', 50, 1.5),
(60, 35, 13, 'Absent', '', '', '', '2026-02-16 12:00:00', 51, 1.5),
(61, 38, 13, 'Not marked', '', '', '', '2026-02-16 12:00:00', 51, 1.5),
(62, 40, 13, 'Present', '', '', '', '2026-02-16 12:00:00', 51, 1.5),
(63, 35, 13, 'Absent', '', '', '', '2026-02-17 12:00:00', 52, 1.5),
(64, 38, 13, 'Not marked', '', '', '', '2026-02-17 12:00:00', 52, 1.5),
(65, 40, 13, 'Present', '16:57', '', '[Late] ', '2026-02-17 12:00:00', 52, 1.5),
(66, 35, 13, 'Absent', '', '', '', '2026-02-18 12:00:00', 53, 1.5),
(67, 38, 13, 'Not marked', '', '', '', '2026-02-18 12:00:00', 53, 1.5),
(68, 40, 13, 'Present', '', '', '', '2026-02-18 12:00:00', 53, 1.5),
(69, 35, 13, 'Absent', '', '', '', '2026-02-19 12:00:00', 54, 1.5),
(70, 38, 13, 'Not marked', '', '', '', '2026-02-19 12:00:00', 54, 1.5),
(71, 40, 13, 'Present', '11:30', '13:00', '', '2026-02-19 12:00:00', 54, 1.5),
(72, 35, 13, 'Absent', '', '', '', '2026-02-20 12:00:00', 55, 1.5),
(73, 38, 13, 'Not marked', '', '', '', '2026-02-20 12:00:00', 55, 1.5),
(74, 40, 13, 'Absent', '', '', '', '2026-02-20 12:00:00', 55, 1.5),
(75, 35, 13, 'Present', '11:30', '13:00', '', '2026-02-23 12:00:00', 56, 1.5),
(76, 38, 13, 'Not marked', '', '', '', '2026-02-23 12:00:00', 56, 1.5),
(77, 40, 13, 'Present', '11:30', '13:00', '', '2026-02-23 12:00:00', 56, 1.5),
(78, 35, 13, 'Present', '', '', '', '2026-02-24 12:00:00', 57, 1.5),
(79, 38, 13, 'Not marked', '', '', '', '2026-02-24 12:00:00', 57, 1.5),
(80, 40, 13, 'Present', '', '', '', '2026-02-24 12:00:00', 57, 1.5),
(81, 35, 13, 'Leave', '', '', '', '2026-02-25 12:00:00', 58, 1.5),
(82, 38, 13, 'Not marked', '', '', '', '2026-02-25 12:00:00', 58, 1.5),
(83, 40, 13, 'Present', '', '', '', '2026-02-25 12:00:00', 58, 1.5),
(84, 35, 13, 'Leave', '', '', '', '2026-02-26 12:00:00', 59, 1.5),
(85, 38, 13, 'Not marked', '', '', '', '2026-02-26 12:00:00', 59, 1.5),
(86, 40, 13, 'Present', '', '', '', '2026-02-26 12:00:00', 59, 1.5),
(87, 35, 13, 'Leave', '', '', '', '2026-02-27 12:00:00', 60, 1.5),
(88, 38, 13, 'Not marked', '', '', '', '2026-02-27 12:00:00', 60, 1.5),
(89, 40, 13, 'Leave', '', '', '', '2026-02-27 12:00:00', 60, 1.5);

-- --------------------------------------------------------

--
-- Table structure for table `timetable_overrides`
--

CREATE TABLE `timetable_overrides` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `new_start_time` varchar(30) DEFAULT NULL,
  `new_end_time` varchar(30) DEFAULT NULL,
  `new_duration` decimal(5,2) DEFAULT NULL,
  `status` enum('Active','Cancelled') DEFAULT 'Active',
  `reason` text DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `timetable_overrides`
--

INSERT INTO `timetable_overrides` (`id`, `class_id`, `new_start_time`, `new_end_time`, `new_duration`, `status`, `reason`, `updated_by`, `updated_at`) VALUES
(1, 54, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 06:14:38'),
(2, 55, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 06:15:38'),
(3, 56, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 06:28:58'),
(4, 57, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:07:01'),
(5, 58, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:07:01'),
(6, 59, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:07:01'),
(7, 60, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:07:01'),
(8, 61, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(9, 62, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(10, 63, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(11, 64, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(12, 65, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(13, 66, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(14, 67, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(15, 68, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(16, 69, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(17, 70, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:09:19'),
(18, 71, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:10:04'),
(19, 72, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:10:04'),
(20, 73, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:10:04'),
(21, 74, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:10:04'),
(22, 75, '11:30 AM', '01:00 PM', 1.50, 'Active', 'Ramzan', 'khizar4562', '2026-02-19 08:10:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
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
  `created_at` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`registration_no`, `username`, `password`, `userid`, `userlevel`, `email`, `timestamp`, `parent_directory`, `display_name`, `phone`, `created_at`) VALUES
('FN-admin', 'admin', '$2y$10$LTd0zZSFvLMaiEJU33ONAu5G/mQKWSOspvHPENLsHwVQn6eVbpxj.', '8b6792bb50c7055a85bd30fabb6b4682', 4, 'j.anderson@blogadmin.com', 1777103093, 'images.jpeg', 'Jonathan Anderson', '', '2025-11-18 11:18:46'),
('FN-Khizar-93', 'khizar4562', '$2y$10$lTI1LbIsv6/Uq2sdVds.u.CgOBkkQi2LAWLHtcIWeVMonbniVf3SS', '873a2af0fe50b11a1109df810b7ebf16', 1, 'khizarahmad222111@gmail.com', 1775767606, '', 'Khizar ', '03328912706', '2025-12-19 00:09:00');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `mobile_no` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `current_grade` varchar(255) NOT NULL,
  `institute` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `created_at` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `category_id`, `course_id`, `name`, `gender`, `mobile_no`, `email`, `current_grade`, `institute`, `address`, `created_at`) VALUES
(3, 0, 0, 'Najam Shah', 'male', '03249850953', 'nhs79809@gmail.com', '..', '...', 'Sadiq center 3rd flr', '2026-01-23 17:14:58'),
(4, 0, 0, 'wajahat hameed', 'male', '03146965099', 'wajahathameed198@gmail.com', '8 th semester', 'university of haripur', 'zahid abad mandian abbottabad', '2026-02-23 14:38:57'),
(5, 1, 7, 'syed shaheer haider', 'male', '03229247731', 'shahshery57@gmail.com', 'bs 6 semester', 'Comsats', 'house no. cb 11 supply Atd', '2026-02-25 14:56:37');

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
-- Indexes for table `admissions`
--
ALTER TABLE `admissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`enquiry_status`),
  ADD KEY `idx_follow_up` (`next_follow_up`),
  ADD KEY `idx_source` (`enquiry_source`),
  ADD KEY `idx_mobile` (`mobile_no`);

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
-- Indexes for table `careers`
--
ALTER TABLE `careers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `expences`
--
ALTER TABLE `expences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inactive_users`
--
ALTER TABLE `inactive_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investments`
--
ALTER TABLE `investments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session_classes`
--
ALTER TABLE `session_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`),
  ADD KEY `class_date` (`class_date`),
  ADD KEY `week_number` (`week_number`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_no` (`registration_no`),
  ADD UNIQUE KEY `registration_no_2` (`registration_no`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetable_overrides`
--
ALTER TABLE `timetable_overrides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admissions`
--
ALTER TABLE `admissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `careers`
--
ALTER TABLE `careers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `expences`
--
ALTER TABLE `expences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `inactive_users`
--
ALTER TABLE `inactive_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `investments`
--
ALTER TABLE `investments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `session_classes`
--
ALTER TABLE `session_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `timetable_overrides`
--
ALTER TABLE `timetable_overrides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `session_classes`
--
ALTER TABLE `session_classes`
  ADD CONSTRAINT `fk_session_classes_session` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `timetable_overrides`
--
ALTER TABLE `timetable_overrides`
  ADD CONSTRAINT `timetable_overrides_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `session_classes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
