-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2025 at 06:36 AM
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
(7, 'JB Alico', 'jaironbartalico@gmail.com', '$2y$10$RTJTk4ja/R7Agmm3ZBgAC.EmEadBa7p/XoKhGgxx3CwfYAawpFxcq');

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
  `store_id` int(11) DEFAULT NULL,
  `owner_type` enum('admin','superadmin') NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('available','out-of-stock') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_qty`, `category`, `product_image`, `category_id`, `owner_id`, `store_id`, `owner_type`, `date_added`, `updated_at`, `status`) VALUES
(9, 'Kesong Puti', 'Cheese wrapped in banana leaf', 140.00, 15, 'Cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 1, NULL, 'admin', '2025-08-21 23:52:50', '2025-08-27 15:07:59', 'available'),
(17, 'Kesorbetes', 'ice cream', 115.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 1, NULL, 'admin', '2025-08-22 04:37:29', '2025-08-22 04:37:29', 'available'),
(19, 'Kesorbetes (1 Gallon)', 'ice cream', 650.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 1, NULL, 'admin', '2025-08-22 04:42:13', '2025-08-22 04:42:13', 'available'),
(20, 'Kesorbetes (1 Tub)', 'ice creamm', 550.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 1, NULL, 'admin', '2025-08-22 10:19:46', '2025-08-22 10:19:46', 'available'),
(21, 'Kesong Puti', 'cheese', 110.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, NULL, 'admin', '2025-08-22 10:20:16', '2025-08-22 10:27:12', 'available'),
(22, 'Kesorbetes (Small)', 'ice cream', 50.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 3, NULL, 'admin', '2025-08-22 10:31:19', '2025-08-22 10:31:19', 'available'),
(30, 'Kesorbetes (1 Gallon)', 'ice cream', 750.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 3, NULL, 'admin', '2025-09-03 06:20:19', '2025-09-03 06:20:19', 'available'),
(31, 'Kesorbetes (1 Tub)', 'ice cream', 550.00, 15, 'ice-cream', '/assets/522845871_740647665242049_262399641837452041_n.jpg', NULL, 3, NULL, 'admin', '2025-09-03 06:22:07', '2025-09-03 06:22:07', 'available'),
(34, 'Kesong Puti', 'cheese', 190.00, 15, 'Cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 1, NULL, 'admin', '2025-09-03 07:18:17', '2025-09-03 07:18:17', 'available'),
(35, 'Kesong Puti', 'cheese', 180.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, NULL, 'admin', '2025-09-03 07:23:18', '2025-09-03 07:23:18', 'available'),
(36, 'Kesong Puti', 'cheese', 200.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, NULL, 'admin', '2025-09-03 11:12:51', '2025-09-03 11:12:51', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rating` varchar(10) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `recipient` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `name`, `email`, `rating`, `feedback`, `created_at`, `recipient`) VALUES
(8, 'Lalaine Conde', 'lalaineconde11@gmail.com', '★★★★★', 'Hello, this is just a sample feedback message!', '2025-08-27 07:46:48', 'super_1'),
(10, 'Lara Fremista', 'larafremista21@gmail.com', '★★★★☆', 'Hello, this is just a sample feedback message!', '2025-08-27 08:26:54', 'admin_3'),
(11, 'JB Alico', 'jaironbartalico@gmail.com', '★★★☆☆', 'Hello this is just a sample feedback message!!', '2025-08-27 08:27:40', 'admin_3'),
(12, 'Russell Garcia', 'jonrussell@gmail.com', '★★★☆☆', 'Hello, sample feedback message!', '2025-08-27 18:22:14', 'admin_1'),
(13, 'Jaira Bautista', 'jairared@gmail.com', '★★★★☆', 'sample feedback!', '2025-08-27 18:31:34', 'super_1');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_id` int(11) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `recipient` varchar(100) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `super_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_id`, `store_name`, `recipient`, `owner_id`, `super_id`) VALUES
(1, 'Arlene Macalinao Kesong Puti', 'super_1', NULL, 1),
(2, 'Noel Lucena Kesong Puti', 'admin_1', 1, NULL),
(3, 'Garin Kesong Puti', 'admin_3', 3, NULL),
(4, 'Mommy Lodie Kesong Puti', 'admin_7', 7, NULL);

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
  ADD KEY `fk_category` (`category_id`),
  ADD KEY `fk_store` (`store_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `super_id` (`super_id`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_store` FOREIGN KEY (`store_id`) REFERENCES `store` (`store_id`) ON DELETE CASCADE;

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `store_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_ibfk_2` FOREIGN KEY (`super_id`) REFERENCES `super_admin` (`super_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
