-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2025 at 12:47 PM
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
-- Database: `kesongputi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`) VALUES
(1, 'lalaine conde', 'lalaineconde23@gmail.com', '$2y$10$9Dy/b9srwpwLGt6.BEbVGOZdWo4vY5SeUjaXuMTQ8Os09KOcHl7My'),
(3, 'lara danielle', 'larafremista21@gmail.com', '$2y$10$79rq0jOVXhyYsyvodZ7cO.iFhO5dTwadoMy97Bo6RiOsCr3PnWQFi'),
(4, 'JB Alico', 'jaironbartalico@gmail.com', '$2y$10$bGTmtBqUpUxb/2.Zzh7CceGEHC4xYNWBxAPGpFW6NmyFvBLNU4A5q'),
(5, 'arlene macalinao', 'lalaineconde22@gmail.com', '$2y$10$JZSDoE4983EQnbDab3jy5uvdUrzFfd5Krqhd854VxXHFxrWHucExe');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` enum('admin','superadmin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `c.id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_messages`
--

CREATE TABLE `inbox_messages` (
  `inbox_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `recipient` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inbox_messages`
--

INSERT INTO `inbox_messages` (`inbox_id`, `name`, `email`, `contact`, `message`, `created_at`, `recipient`) VALUES
(8, 'Lalaine Conde', 'lalaineconde11@gmail.com', '09397956661', 'Hello, this is just a sample message!', '2025-08-20 07:18:35', 'super_1'),
(9, 'Lara Danielle', 'larafremista21@gmail.com', '09123456789', 'SARAP!!!', '2025-08-20 07:22:46', 'admin_3'),
(10, 'Lalaine Conde', 'lalaineconde11@gmail.com', '09397956661', 'SHHHHH HAHAHHAHAHAH', '2025-08-20 08:06:02', 'admin_3'),
(11, 'Lalaine Conde', 'lalaineconde11@gmail.com', '09397956661', 'Hello, this is just a sample message!', '2025-08-20 08:06:39', 'admin_3');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_qty` int(11) DEFAULT 0,
  `category` varchar(100) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` enum('admin','superadmin') NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('available','out-of-stock') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_qty`, `category`, `product_image`, `category_id`, `owner_id`, `owner_type`, `date_added`, `updated_at`, `status`) VALUES
(9, 'Kesong Puti', 'Cheese wrapped in banana leaf', 150.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 1, 'admin', '2025-08-21 23:52:50', '2025-08-22 10:16:19', 'available'),
(17, 'Kesorbetes', 'ice cream', 115.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 1, 'admin', '2025-08-22 04:37:29', '2025-08-22 04:37:29', 'available'),
(19, 'Kesorbetes (1 Gallon)', 'ice cream', 650.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 1, 'admin', '2025-08-22 04:42:13', '2025-08-22 04:42:13', 'available'),
(20, 'Kesorbetes (1 Tub)', 'ice creamm', 550.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 1, 'admin', '2025-08-22 10:19:46', '2025-08-22 10:19:46', 'available'),
(21, 'Kesong Puti', 'cheese', 110.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, 'admin', '2025-08-22 10:20:16', '2025-08-22 10:27:12', 'available'),
(22, 'Kesorbetes (Small)', 'ice cream', 50.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 3, 'admin', '2025-08-22 10:31:19', '2025-08-22 10:31:19', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `super_admin`
--

CREATE TABLE `super_admin` (
  `super_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `super_admin`
--

INSERT INTO `super_admin` (`super_id`, `username`, `email`, `password`) VALUES
(1, 'arlene macalinao', 'lalaineconde22@gmail.com', '$2y$10$qirbMehtP/lX3wHhZbPBbOekAx0Vq/S09tBWDlzXavFsZsIZA22oy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`c.id`);

--
-- Indexes for table `inbox_messages`
--
ALTER TABLE `inbox_messages`
  ADD PRIMARY KEY (`inbox_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`super_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `c.id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox_messages`
--
ALTER TABLE `inbox_messages`
  MODIFY `inbox_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `super_admin`
--
ALTER TABLE `super_admin`
  MODIFY `super_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
