-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 09:38 PM
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
  `store_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `store_name`, `email`, `password`) VALUES
(1, 'lalaine conde', 'Noel Lucena Kesong Puti', 'lalaineconde23@gmail.com', '$2y$10$9Dy/b9srwpwLGt6.BEbVGOZdWo4vY5SeUjaXuMTQ8Os09KOcHl7My'),
(3, 'lara danielle', 'Garin Kesong Puti', 'larafremista21@gmail.com', '$2y$10$79rq0jOVXhyYsyvodZ7cO.iFhO5dTwadoMy97Bo6RiOsCr3PnWQFi'),
(7, 'JB Alico', 'Mommy Lodie Kesong Puti', 'jaironbartalico@gmail.com', '$2y$10$RTJTk4ja/R7Agmm3ZBgAC.EmEadBa7p/XoKhGgxx3CwfYAawpFxcq');

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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`) VALUES
(3, 'What is Kesong Puti?', 'Kesong Puti is a traditional Filipino white cheese made from carabaos milk with a delicate creamy flavor.', '2025-09-07 10:44:58'),
(17, 'sample questions', 'sample answer', '2025-09-13 03:13:18');

-- --------------------------------------------------------

--
-- Table structure for table `footer_settings`
--

CREATE TABLE `footer_settings` (
  `id` int(11) NOT NULL,
  `logo` varchar(255) DEFAULT '../../assets/logo.png',
  `description` text DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `quick_links` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bottom_text` varchar(255) NOT NULL DEFAULT 'Kesong Puti © 2025 All Rights Reserved',
  `background_image` varchar(255) DEFAULT NULL,
  `mon_hours` varchar(50) NOT NULL DEFAULT '07:00AM - 05:00PM',
  `tue_hours` varchar(50) NOT NULL DEFAULT '07:00AM - 05:00PM',
  `wed_hours` varchar(50) NOT NULL DEFAULT '07:00AM - 05:00PM',
  `thu_hours` varchar(50) NOT NULL DEFAULT '07:00AM - 05:00PM',
  `fri_hours` varchar(50) NOT NULL DEFAULT '07:00AM - 05:00PM',
  `sat_hours` varchar(50) NOT NULL DEFAULT '07:00AM - 05:00PM',
  `sun_hours` varchar(50) NOT NULL DEFAULT '07:00AM - 05:00PM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `footer_settings`
--

INSERT INTO `footer_settings` (`id`, `logo`, `description`, `facebook_link`, `instagram_link`, `email`, `phone`, `address`, `quick_links`, `updated_at`, `bottom_text`, `background_image`, `mon_hours`, `tue_hours`, `wed_hours`, `thu_hours`, `fri_hours`, `sat_hours`, `sun_hours`) VALUES
(1, 'logo.png', 'Kesong Puti is your go-to online shop for fresh, authentic Filipino cottage cheese. We take pride in delivering locally made, high-quality products straight to your doorstep, preserving the rich tradition of our hometown delicacy.', 'https://www.facebook.com/AlohaKesorbetes', 'https://www.instagram.com/arlene_macalinao_kesongputi/', 'hernandezshy00@gmail.com', '+63 999 715 9226', '4883 Sitio 3 Brgy. Bagumbayan, Santa Cruz, Philippines, 4009', '[{\"name\":\"Home\",\"url\":\"index.php\"},{\"name\":\"Products\",\"url\":\"products.php\"},{\"name\":\"About Us\",\"url\":\"about.php\"},{\"name\":\"Feedback\",\"url\":\"feedback.php\"},\r\n{\"name\":\"FAQ\",\"url\":\"FAQ.php\"},{\"name\":\"Contact Us\",\"url\":\"contact.php\"},\r\n{\"name\":\"Terms and Condition\",\"url\":\"terms-condition.php\"}]', '2025-09-18 19:25:03', 'Kesong Puti © 2026 All Rights Reserved', '1758223503_leave.png', '07:00AM - 05:00PM', '07:00AM - 05:00PM', '07:00AM - 05:00PM', '07:00AM - 05:00PM', '07:00AM - 05:00PM', '07:00AM - 05:00PM', '07:00AM - 05:00PM');

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
(11, 'Lalaine Conde', 'lalaineconde11@gmail.com', '09397956661', 'Hello, this is just a sample message!', '2025-08-20 08:06:39', 'admin_3'),
(12, 'sample', 'sample@sample.com', '09123456611', 'sample message!', '2025-09-09 05:29:46', 'super_1'),
(13, 'Lalaine Conde', 'lalaineconde11@gmail.com', '09397956661', 'hello', '2025-09-11 06:45:00', 'super_1'),
(14, 'Russell Garcia', 'sample@sample.com', '09123456611', 'sample sample', '2025-09-18 09:45:54', 'super_1');

-- --------------------------------------------------------

--
-- Table structure for table `page_headers`
--

CREATE TABLE `page_headers` (
  `id` int(11) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `header_text` varchar(255) NOT NULL,
  `header_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_headers`
--

INSERT INTO `page_headers` (`id`, `page_name`, `header_text`, `header_image`) VALUES
(1, 'home', 'WELCOME', NULL),
(2, 'products', 'OUR PRODUCTS', NULL),
(3, 'FAQ', 'GOT QUESTIONS?', NULL),
(4, 'contact', 'GET IN TOUCH', NULL),
(5, 'feedback', 'CUSTOMER FEEDBACK', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `method_id` int(11) NOT NULL,
  `method_name` varchar(100) NOT NULL,
  `account_name` varchar(150) DEFAULT NULL,
  `account_number` varchar(150) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `status` enum('published','unpublished') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `recipient` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`method_id`, `method_name`, `account_name`, `account_number`, `qr_code`, `status`, `created_at`, `updated_at`, `recipient`) VALUES
(4, 'bank', 'arlene macalinao', '09123456789', '1757398482_small tub.png', 'unpublished', '2025-09-09 06:14:42', '2025-09-09 06:15:05', 'superadmin_1');

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
(17, 'Kesorbetes (Pint)', 'ice cream', 260.00, 15, 'ice-cream', '/assets/pint.png', NULL, 1, NULL, 'admin', '2025-08-22 04:37:29', '2025-09-05 22:50:49', 'available'),
(19, 'Kesorbetes (Med Tub)', 'ice cream', 160.00, 15, 'ice-cream', '/assets/med tub.png', NULL, 1, NULL, 'admin', '2025-08-22 04:42:13', '2025-09-05 22:50:58', 'available'),
(20, 'Kesorbetes (Liter)', 'ice cream', 320.00, 15, 'ice-cream', '/assets/liter.png', NULL, 1, NULL, 'admin', '2025-08-22 10:19:46', '2025-09-05 22:51:04', 'available'),
(21, 'Kesong Puti', 'cheese', 110.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, NULL, 'admin', '2025-08-22 10:20:16', '2025-08-22 10:27:12', 'available'),
(22, 'Kesorbetes (Small Tub)', 'ice cream', 100.00, 15, 'ice-cream', '/assets/small tub.png', NULL, 3, NULL, 'admin', '2025-08-22 10:31:19', '2025-09-05 22:52:39', 'available'),
(30, 'Kesorbetes (1 Gallon)', 'ice cream', 870.00, 15, 'ice-cream', '/assets/gallon.png', NULL, 3, NULL, 'admin', '2025-09-03 06:20:19', '2025-09-05 22:52:19', 'available'),
(31, 'Kesorbetes (Half Gallon)', 'ice cream', 650.00, 15, 'ice-cream', '/assets/half gallon.png', NULL, 3, NULL, 'admin', '2025-09-03 06:22:07', '2025-09-05 22:52:13', 'available'),
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
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('background_color', '#FDF5E6'),
('body_font_color', '#000000'),
('button1_color', '#0D8540'),
('button1_font_color', '#0D8540'),
('button1_hover', '#FFFFFF'),
('button1_hover_color', '#F4C40F'),
('button1_hover_font', '#0D8540'),
('button2_color', '#FFFFFF'),
('button2_font_color', '#FFFFFF'),
('button2_hover', '#0D8540'),
('button2_hover_color', '#0D8540'),
('button2_hover_font', '#FFFFFF'),
('button3_color', '#F4C40F'),
('button3_font_color', '#000000'),
('button_bg_color', '#000000'),
('button_font_color', '#24be19'),
('button_hover_color', '#3d5538'),
('checkout_button_color', '#F4C40F'),
('checkout_button_hover', '#0D8540'),
('description_color', '#000000'),
('description_font_color', '#09430d'),
('faq_answer_bg', '#87B86B'),
('faq_answer_font_color', '#000000'),
('faq_button_bg', '#0D8540'),
('faq_question_font_color', '#FFFFFF'),
('font_family', 'Lilita One'),
('footer_bg', '#FBF1D7'),
('footer_color', '#FAF3DD'),
('headers_bg', '#FBF1D7'),
('header_color', '#0D8540'),
('header_font_color', '#ffffff'),
('heading_font_color', '#0D8540'),
('icon_color', '#0D8540'),
('navbar_color', '#82b658'),
('navbar_font_color', '#ffffff'),
('navbar_font_family', 'Fredoka'),
('navbar_hover_color', '#87B86B'),
('page_bg', '#FEFAF6'),
('page_header_font', 'Lilita One'),
('page_header_font_color', '#ffffff'),
('page_header_font_family', 'Lilita One'),
('page_numbers_font_color', '#ff9500'),
('page_number_active', '#0D8540'),
('page_number_hover', '#0D8540'),
('price_color', '#F4C40F'),
('price_font_color', '#e63946'),
('primary_color', '#50a838'),
('primary_font', 'Poppins'),
('products_font_family', 'Fredoka'),
('product_font_color', '#000000'),
('product_name_font_color', '#a54545'),
('product_name_font_family', 'Fredoka'),
('product_page_number_bg', '#F4C40F'),
('secondary_color', '#ffff00'),
('secondary_font', 'Lilita One'),
('second_heading_font_color', '#FFFFFF'),
('site_title', 'Kesong Puti Stores'),
('store_name_font_color', '#000000'),
('store_name_font_family', 'Fredoka'),
('subtitle_color', '#ff00e1'),
('subtitle_font_color', '#0D8540'),
('title_color', '#ff0000'),
('wave_background', '#058240');

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
-- Table structure for table `store_contacts`
--

CREATE TABLE `store_contacts` (
  `id` int(11) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_contacts`
--

INSERT INTO `store_contacts` (`id`, `store_name`, `email`, `phone`, `address`, `latitude`, `longitude`) VALUES
(1, 'Mommy Lodie Kesong Puti', 'lodie@gmail.com', '091233455678', '123 sdjfhakjs fsdhafj', NULL, NULL),
(5, 'sample store', 'lalaineconde11@gmail.com', '09123432874', '123 sample address', NULL, NULL),
(8, 'sample store', 'lalaineconde11@gmail.com', '09123432874', 'sample address', NULL, NULL),
(9, 'sample storesss', 'lalaineconde23@gmail.com', '09123432874', '4883 Bagumbayan Road, Santa Cruz, 4009 Laguna', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `super_admin`
--

CREATE TABLE `super_admin` (
  `super_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `super_admin`
--

INSERT INTO `super_admin` (`super_id`, `username`, `store_name`, `email`, `password`) VALUES
(1, 'arlene macalinao', 'Arlene Macalinao Kesong Puti', 'lalaineconde22@gmail.com', '$2y$10$qirbMehtP/lX3wHhZbPBbOekAx0Vq/S09tBWDlzXavFsZsIZA22oy');

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
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer_settings`
--
ALTER TABLE `footer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbox_messages`
--
ALTER TABLE `inbox_messages`
  ADD PRIMARY KEY (`inbox_id`);

--
-- Indexes for table `page_headers`
--
ALTER TABLE `page_headers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page_name` (`page_name`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`method_id`);

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
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `super_id` (`super_id`);

--
-- Indexes for table `store_contacts`
--
ALTER TABLE `store_contacts`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `footer_settings`
--
ALTER TABLE `footer_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inbox_messages`
--
ALTER TABLE `inbox_messages`
  MODIFY `inbox_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `page_headers`
--
ALTER TABLE `page_headers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=416;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `store_contacts`
--
ALTER TABLE `store_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
