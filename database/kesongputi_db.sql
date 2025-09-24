-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 02:42 PM
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

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `owner_id`, `owner_type`, `created_at`) VALUES
(1, 'Dairy Products', NULL, 1, 'admin', '2025-09-23 07:58:32'),
(10, 'Dairy Products', NULL, 1, 'admin', '2025-09-23 07:59:14'),
(30, 'Dairy Products', NULL, 1, 'admin', '2025-09-23 07:59:31'),
(51, 'cheese', NULL, 1, 'admin', '2025-09-23 08:00:24'),
(101, 'cheese', NULL, 1, 'admin', '2025-09-23 08:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `c_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`c_id`, `fullname`, `phone_number`, `email`, `address`) VALUES
(1, 'Lara Fremista', '09957432998', 'larafremista21@gmail.com', 'B-48 L-10, N/A, Dila, Santa Rosa, Laguna, 4026'),
(2, 'Lalaine Conde', '09123456789', 'lalaineconde@gmail.com', ''),
(3, 'Juan Dela Cruz', '09152345678', 'juandelacruz@gmail.com', ''),
(4, 'Stephen Strange', '09123456789', 'stephen@gmail.com', '');

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
-- Table structure for table `home_about_slider`
--

CREATE TABLE `home_about_slider` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_about_slider`
--

INSERT INTO `home_about_slider` (`id`, `image_path`, `position`) VALUES
(1, 'uploads/assets/default-team-1.png', 1),
(2, 'uploads/assets/default-team-2.png', 2),
(3, 'uploads/assets/default-team-3.png', 3),
(4, 'uploads/assets/default-team-4.png', 4),
(5, 'uploads/assets/default-team-5.png', 5);

-- --------------------------------------------------------

--
-- Table structure for table `home_delivery_pickup`
--

CREATE TABLE `home_delivery_pickup` (
  `id` int(11) NOT NULL,
  `title1` varchar(255) DEFAULT NULL,
  `title2` varchar(255) DEFAULT NULL,
  `title3` varchar(255) DEFAULT NULL,
  `title4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_delivery_pickup`
--

INSERT INTO `home_delivery_pickup` (`id`, `title1`, `title2`, `title3`, `title4`) VALUES
(1, 'THE', 'CHOICE', 'IS YOURS:', 'GO OR STAY');

-- --------------------------------------------------------

--
-- Table structure for table `home_featured`
--

CREATE TABLE `home_featured` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_featured`
--

INSERT INTO `home_featured` (`id`, `image_path`, `title`, `position`) VALUES
(1, 'uploads/assets/default-featured-1.png', 'Kesorbetes', 1),
(2, 'uploads/assets/default-featured-2.png', 'Kesong Puti', 2);

-- --------------------------------------------------------

--
-- Table structure for table `home_hero`
--

CREATE TABLE `home_hero` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_hero`
--

INSERT INTO `home_hero` (`id`, `image_path`, `subtitle`, `position`) VALUES
(1, 'uploads/assets/default-pic-1.png', 'SOFT CREAMY AND FRESH', 1),
(2, 'uploads/assets/default-pic-2.png', 'MORNING CLASSIC BREAKFAST', 2),
(3, 'uploads/assets/default-pic-3.png', 'FRESH WHITE CHEESE', 3),
(4, 'uploads/assets/default-pic-4.png', 'SNACKS AND APPETIZERS', 4);

-- --------------------------------------------------------

--
-- Table structure for table `home_reasons`
--

CREATE TABLE `home_reasons` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_reasons`
--

INSERT INTO `home_reasons` (`id`, `icon`, `title`, `subtitle`, `position`) VALUES
(1, 'fa-solid fa-cow', 'Freshness and Simple Production', 'Kesong puti is a good choice because it\'s a fresh cheese without preservatives, offering a pure, natural flavor.', 1),
(2, 'fa-solid fa-pills', 'Rich in Nutrients', 'It is a great source of essential nutrients, particularly protein and calcium, from the rich carabao\'s milk.', 2),
(3, 'fa-solid fa-cheese', 'Cultural and Culinary Importance', 'Kesong puti is a cultural staple in the Philippines, often enjoyed as a classic breakfast food.', 3),
(4, 'fa-solid fa-utensils', 'Versatility in the Kitchen', 'Its mild, slightly salty flavor makes it a versatile ingredient for both sweet and savory dishes.', 4);

-- --------------------------------------------------------

--
-- Table structure for table `home_settings`
--

CREATE TABLE `home_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_settings`
--

INSERT INTO `home_settings` (`id`, `setting_key`, `setting_value`) VALUES
(470, 'del_pick_image', ''),
(473, 'home_header_font_color_part1', '#0D8540'),
(474, 'home_header_font_color_part2', '#F4C40F'),
(475, 'home_header_font_color_part3', '#0D8540'),
(476, 'del_pick_font_color_title1', '#058240'),
(477, 'del_pick_font_color_title2', '#058240'),
(478, 'del_pick_font_color_title3', '#058240'),
(479, 'del_pick_font_color_title4', '#F4C40F'),
(480, 'home_featured_title', 'The Original & Classics'),
(481, 'home_reasons_heading', 'Why is it Good?'),
(482, 'about_heading', 'OUR FAMILY’S LEGACY OF KESONG PUTI');

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `o_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `handled_by` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'cash',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `delivery_address` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`o_id`, `c_id`, `handled_by`, `order_date`, `total_amount`, `payment_status`, `payment_method`, `proof_of_payment`, `order_status`, `delivery_address`, `owner_id`) VALUES
(1, 1, 0, '2025-09-19 13:19:01', 190.00, 'pending', 'cash', NULL, 'pending', 'B-48 L-10, N/A, Dila, Santa Rosa, Laguna, 4026', 1),
(2, 2, 0, '2025-09-19 17:32:55', 100.00, 'pending', 'cash', NULL, 'pending', '', 3),
(3, 3, 0, '2025-09-19 17:38:40', 650.00, 'pending', 'cash', NULL, 'pending', '', 3),
(4, 4, 0, '2025-09-24 02:20:19', 320.00, 'pending', 'gcash', 'proof_68d2e4e37cf5c.png', 'pending', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `o_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `o_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 34, 1, 190.00),
(2, 2, 22, 1, 100.00),
(3, 3, 31, 1, 650.00),
(4, 4, 20, 1, 320.00);

-- --------------------------------------------------------

--
-- Table structure for table `page_headers`
--

CREATE TABLE `page_headers` (
  `id` int(11) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `header_text` varchar(255) NOT NULL,
  `header_image` varchar(255) DEFAULT NULL,
  `part1` varchar(50) DEFAULT NULL,
  `part2` varchar(50) DEFAULT NULL,
  `part3` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_headers`
--

INSERT INTO `page_headers` (`id`, `page_name`, `header_text`, `header_image`, `part1`, `part2`, `part3`) VALUES
(1, 'home', 'WELCOME', NULL, 'PURE', 'CHEESE', 'BLISS'),
(2, 'about us', 'OUR STORY', NULL, NULL, NULL, NULL),
(3, 'products', 'OUR PRODUCTS', NULL, NULL, NULL, NULL),
(4, 'FAQ', 'GOT QUESTIONS?', NULL, NULL, NULL, NULL),
(5, 'contact', 'GET IN TOUCH', NULL, NULL, NULL, NULL),
(6, 'feedback', 'CUSTOMER FEEDBACK', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `method_id` int(11) NOT NULL,
  `method_name` varchar(100) NOT NULL,
  `method_type` varchar(100) DEFAULT NULL,
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

INSERT INTO `payment_methods` (`method_id`, `method_name`, `method_type`, `account_name`, `account_number`, `qr_code`, `status`, `created_at`, `updated_at`, `recipient`) VALUES
(4, 'bank', NULL, 'arlene macalinao', '09123456789', '1757398482_small tub.png', 'published', '2025-09-09 06:14:42', '2025-09-20 04:19:23', 'super_1'),
(5, 'bank', NULL, 'lara danielle', '09957432996', '1758277057_qr.jpg', 'published', '2025-09-19 10:17:37', '2025-09-19 10:17:37', 'admin_3'),
(9, 'e-wallet', 'gcash', 'arlene macalinao', '09123456789', '1758705385_528149698_2466653143708497_6422652806459652480_n.jpg', 'published', '2025-09-24 09:16:25', '2025-09-24 09:16:25', 'super_1'),
(11, 'e-wallet', 'paymaya', 'arlene macalinao', '09123456789', '1758706526_pint.png', 'published', '2025-09-24 09:35:26', '2025-09-24 09:35:26', 'super_1');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `variation_size` varchar(50) DEFAULT NULL,
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

INSERT INTO `products` (`product_id`, `product_name`, `variation_size`, `description`, `price`, `stock_qty`, `category`, `product_image`, `category_id`, `owner_id`, `store_id`, `owner_type`, `date_added`, `updated_at`, `status`) VALUES
(1, 'Kesong Puti', NULL, NULL, 120.00, 0, '', NULL, NULL, 0, NULL, 'admin', '2025-09-23 07:53:17', '2025-09-23 07:53:17', 'available'),
(2, 'Kesorbetes', NULL, NULL, 80.00, 0, '', NULL, NULL, 0, NULL, 'admin', '2025-09-23 07:53:17', '2025-09-23 07:53:17', 'available'),
(3, 'Kesong Puti', 'pint', 'Fresh white cheese made from carabao milk.', 120.00, 50, 'Dairy', 'kesong_puti.jpg', 1, 1, 1, 'admin', '2025-09-23 07:58:32', '2025-09-23 13:30:46', 'available'),
(4, 'Kesorbetes', 'tub', 'Homemade cheese ice cream.', 80.00, 30, 'Dessert', 'kesorbetes.jpg', 1, 1, 1, 'admin', '2025-09-23 07:58:32', '2025-09-23 13:30:56', 'available'),
(9, 'Kesong Puti', NULL, 'Cheese wrapped in banana leaf', 140.00, 15, 'Cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 1, NULL, 'admin', '2025-08-21 23:52:50', '2025-08-27 15:07:59', 'available'),
(17, 'Kesorbetes (Pint)', NULL, 'ice cream', 260.00, 15, 'ice-cream', '/assets/pint.png', NULL, 1, NULL, 'admin', '2025-08-22 04:37:29', '2025-09-05 22:50:49', 'available'),
(19, 'Kesorbetes (Med Tub)', NULL, 'ice cream', 160.00, 15, 'ice-cream', '/assets/med tub.png', NULL, 1, NULL, 'admin', '2025-08-22 04:42:13', '2025-09-05 22:50:58', 'available'),
(20, 'Kesorbetes (Liter)', NULL, 'ice cream', 320.00, 15, 'ice-cream', '/assets/liter.png', NULL, 1, NULL, 'admin', '2025-08-22 10:19:46', '2025-09-05 22:51:04', 'available'),
(21, 'Kesong Puti', NULL, 'cheese', 110.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, NULL, 'admin', '2025-08-22 10:20:16', '2025-08-22 10:27:12', 'available'),
(22, 'Kesorbetes (Small Tub)', NULL, 'ice cream', 100.00, 15, 'ice-cream', '/assets/small tub.png', NULL, 3, NULL, 'admin', '2025-08-22 10:31:19', '2025-09-05 22:52:39', 'available'),
(30, 'Kesorbetes (1 Gallon)', NULL, 'ice cream', 870.00, 15, 'ice-cream', '/assets/gallon.png', NULL, 3, NULL, 'admin', '2025-09-03 06:20:19', '2025-09-05 22:52:19', 'available'),
(31, 'Kesorbetes (Half Gallon)', NULL, 'ice cream', 650.00, 15, 'ice-cream', '/assets/half gallon.png', NULL, 3, NULL, 'admin', '2025-09-03 06:22:07', '2025-09-05 22:52:13', 'available'),
(34, 'Kesong Puti', NULL, 'cheese', 190.00, 15, 'Cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 1, NULL, 'admin', '2025-09-03 07:18:17', '2025-09-03 07:18:17', 'available'),
(35, 'Kesong Puti', NULL, 'cheese', 180.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, NULL, 'admin', '2025-09-03 07:23:18', '2025-09-03 07:23:18', 'available'),
(36, 'Kesong Puti', NULL, 'cheese', 200.00, 15, 'cheese', '/assets/528149698_2466653143708497_6422652806459652480_n.jpg', NULL, 3, NULL, 'admin', '2025-09-03 11:12:51', '2025-09-03 11:12:51', 'available'),
(101, 'Kesong Puti', 'pint', 'Fresh white cheese made from carabao milk.', 120.00, 50, 'Dairy', 'kesong_puti.jpg', 1, 1, 1, 'admin', '2025-09-23 08:00:59', '2025-09-23 13:22:17', 'available'),
(102, 'Kesorbetes', 'tub', 'Homemade cheese ice cream.', 80.00, 30, 'Dessert', 'kesorbetes.jpg', 1, 1, 1, 'admin', '2025-09-23 08:00:59', '2025-09-23 13:23:57', 'available');

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
  `recipient` varchar(100) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `media` text DEFAULT NULL,
  `order_item_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `name`, `email`, `rating`, `feedback`, `created_at`, `recipient`, `order_id`, `media`, `order_item_id`) VALUES
(8, 'Lalaine Conde', 'lalaineconde11@gmail.com', '4', 'Hello, this is just a sample feedback message!', '2025-08-27 07:46:48', 'super_1', NULL, NULL, NULL),
(10, 'Lara Fremista', 'larafremista21@gmail.com', '★★★★☆', 'Hello, this is just a sample feedback message!', '2025-08-27 08:26:54', 'admin_3', NULL, NULL, NULL),
(11, 'JB Alico', 'jaironbartalico@gmail.com', '★★★☆☆', 'Hello this is just a sample feedback message!!', '2025-08-27 08:27:40', 'admin_3', NULL, NULL, NULL),
(12, 'Russell Garcia', 'jonrussell@gmail.com', '★★★☆☆', 'Hello, sample feedback message!', '2025-08-27 18:22:14', 'admin_1', NULL, NULL, NULL),
(198, 'Lara Fremista', 'larafremista21@gmail.com', '5', 'keso', '2025-09-23 16:57:47', 'super_1', NULL, '[\"uploads\\/reviews\\/1758646667_0_0_528149698_2466653143708497_6422652806459652480_n.jpg\"]', 101),
(199, 'Lara Fremista', 'larafremista21@gmail.com', '3', 'sorbetes', '2025-09-23 16:57:47', 'super_1', NULL, '[\"uploads\\/reviews\\/1758646667_1_0_gallon.png\",\"uploads\\/reviews\\/1758646667_1_1_half gallon.png\",\"uploads\\/reviews\\/1758646667_1_2_liter.png\",\"uploads\\/reviews\\/1758646667_1_3_med tub.png\",\"uploads\\/reviews\\/1758646667_1_4_pint.png\",\"uploads\\/reviews\\/1758646667_1_5_small cup.png\",\"uploads\\/reviews\\/1758646667_1_6_small tub.png\"]', 102);

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
('home_header_text_color', ''),
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
('page_number_hover', '#F4C40F'),
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
  ADD PRIMARY KEY (`c_id`);

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
-- Indexes for table `home_about_slider`
--
ALTER TABLE `home_about_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_delivery_pickup`
--
ALTER TABLE `home_delivery_pickup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_featured`
--
ALTER TABLE `home_featured`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `position` (`position`);

--
-- Indexes for table `home_hero`
--
ALTER TABLE `home_hero`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_reasons`
--
ALTER TABLE `home_reasons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `position` (`position`);

--
-- Indexes for table `home_settings`
--
ALTER TABLE `home_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `inbox_messages`
--
ALTER TABLE `inbox_messages`
  ADD PRIMARY KEY (`inbox_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `fk_orders_customer` (`c_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_orderitems_order` (`o_id`);

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
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `home_about_slider`
--
ALTER TABLE `home_about_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `home_delivery_pickup`
--
ALTER TABLE `home_delivery_pickup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `home_featured`
--
ALTER TABLE `home_featured`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `home_hero`
--
ALTER TABLE `home_hero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `home_reasons`
--
ALTER TABLE `home_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `home_settings`
--
ALTER TABLE `home_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=483;

--
-- AUTO_INCREMENT for table `inbox_messages`
--
ALTER TABLE `inbox_messages`
  MODIFY `inbox_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `page_headers`
--
ALTER TABLE `page_headers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=421;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`c_id`) REFERENCES `customers` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_orderitems_order` FOREIGN KEY (`o_id`) REFERENCES `orders` (`o_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
