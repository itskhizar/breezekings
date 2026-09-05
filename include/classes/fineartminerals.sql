-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2024 at 10:12 AM
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
-- Database: `fineartminerals`
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
('::1', 1731948888);

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `timestamp` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `timestamp`) VALUES
(1, 'Office bill', '2024-11-17 12:50:21'),
(2, 'Rent', '2024-11-17 12:51:15'),
(3, 'Laptops', '2024-11-17 12:51:49'),
(4, 'Chairs', '2024-11-17 12:52:35'),
(6, 'Transport', '2024-11-17 12:54:31');

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) UNSIGNED NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `timestamp` int(11) UNSIGNED NOT NULL,
  `parent_directory` varchar(30) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `userid`, `userlevel`, `email`, `timestamp`, `parent_directory`, `display_name`, `phone`) VALUES
('admin', '123456789', '3a5780f4476587778604f6f6ffbd5eff', 1, 'Khizarahmad0920@gmail.com', 1731948888, 'profile.jpeg', 'Admin', '03328912706'),
('master1', 'd5802d05bbf0881de2fd823c9560619e', 'a516e723c2eb32fc93402206d8327ad2', 8, 'master1@3g.com', 1442974264, 'admin', '', ''),
('master1agent1', 'bc6a6d13b10264fa960eddb401342243', '208413dbac8039b518be9f4cb40452af', 1, 'master1agent1@3g.com', 1442974298, 'master1', '', ''),
('master1agent1member1', '77e8ca40094f38029e99f8e9b6b6edf7', 'ad1988fbb51db1a4dd1310284e1f8954', 2, 'master1agent1member1@3g.com', 1442912022, 'master1agent1', '', ''),
('master1agent1member2', '6cea8991d4a932fce929f81299d73070', '0', 2, 'master1agent1member2@3g.com', 1442911991, 'master1agent1', '', ''),
('zohaib', '12345', '5d1b2e7274533211315c6b0d14837bfd', 0, 'zohaibahmad@gmail.com', 1731814337, '', '', '');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expences`
--
ALTER TABLE `expences`
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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expences`
--
ALTER TABLE `expences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
