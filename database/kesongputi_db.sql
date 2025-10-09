-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 08:52 AM
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
-- Table structure for table `about_images`
--

CREATE TABLE `about_images` (
  `id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_images`
--

INSERT INTO `about_images` (`id`, `section_name`, `image_path`, `position`) VALUES
(915, 'beginning', 'uploads/assets/default-team-1.png', 1),
(916, 'beginning', 'uploads/assets/default-team-2.png', 2),
(917, 'tradition', 'uploads/assets/default-team-3.png', 1),
(918, 'tradition', 'uploads/assets/default-team-4.png', 2),
(919, 'business', 'uploads/assets/default-team-5.png', 1),
(920, 'business', 'uploads/assets/default-team-6.png', 2),
(921, 'support_farmers', 'uploads/assets/default-team-1.png', 1),
(922, 'support_farmers', 'uploads/assets/default-team-2.png', 2),
(923, 'present', 'uploads/assets/default-team-3.png', 1),
(924, 'present', 'uploads/assets/default-team-4.png', 2);

-- --------------------------------------------------------

--
-- Table structure for table `about_sections`
--

CREATE TABLE `about_sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `quote` text DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_sections`
--

INSERT INTO `about_sections` (`id`, `section_name`, `quote`, `content`) VALUES
(561, 'beginning', 'It all began in our kitchen, where kesong puti was more than food — it was family, tradition, and togetherness.', 'Our story starts in the heart of our home, where the aroma of freshly made kesong puti would fill the air every morning. Long before this became a business, kesong puti was part of our family’s daily life — soft, creamy, and always served with warm pan de sal and a hot cup of coffee.\r\n\r\nIt wasn’t just food; it was tradition. Each batch of cheese brought us together around the breakfast table, a reminder of simpler times when meals were about love and connection. For us, kesong puti was more than just nourishment — it was a way of keeping our family’s bond strong, generation after generation.'),
(562, 'tradition', 'A recipe lovingly passed down through generations...', 'Our kesong puti is not just cheese — it’s a family treasure. The recipe has been passed down from our grandparents, who learned the art of making it the traditional way: heating fresh carabao’s milk, curdling it naturally, and carefully shaping it by hand. The final touch was always wrapping it in banana leaves, a symbol of authenticity and respect for our Filipino roots.\r\n\r\nOver time, this recipe became more than just instructions — it became a symbol of our heritage. Every member of the family had a role to play, whether it was preparing the milk, stirring the curds, or helping pack the cheese. Through these small yet meaningful moments, we learned not only how to make kesong puti, but also how to value patience, craftsmanship, and love for tradition.'),
(563, 'business', 'From our table to our neighbors’...', 'At first, we made kesong puti only for ourselves. But as neighbors, friends, and even relatives from far away tasted it, they began to request more. What started as small gifts shared during gatherings and fiestas soon turned into regular orders.\r\n\r\nEncouraged by the joy on people’s faces, our family decided to take the leap — to transform our homemade recipe into a small business. We carried with us a promise: no shortcuts, no compromises. Just the same freshness, authenticity, and love that began in our home.'),
(564, 'support_farmers', 'Behind every piece of our kesong puti...', 'Our business grew hand in hand with the local farming community. By sourcing directly from small-scale dairy farmers, we not only ensure the freshness of our kesong puti but also support the livelihoods of hardworking families like ours.\r\n\r\nThis partnership is more than business — it’s family helping family. It reflects our belief that when farmers thrive, traditions live on, and when traditions are preserved, communities grow stronger together.'),
(565, 'present', 'Though we’ve grown, our heart remains the same...', 'Today, our family business has reached more homes and more hearts, but our values remain unchanged. Every piece of kesong puti we make still carries the same authenticity, warmth, and care that started it all.\r\n\r\nWith each bite, we hope you experience not just cheese, but a story — of tradition, of community, and of the love that binds families together across generations.');

-- --------------------------------------------------------

--
-- Table structure for table `about_team`
--

CREATE TABLE `about_team` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT 'OUR KESONG PUTI FAMILY',
  `image_path` varchar(255) DEFAULT '',
  `position` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_team`
--

INSERT INTO `about_team` (`id`, `title`, `image_path`, `position`, `created_at`) VALUES
(160, 'OUR KESONG PUTI FAMILY', 'uploads/assets/default-team-1.png', 1, '2025-09-25 04:37:48'),
(161, 'OUR KESONG PUTI FAMILY', 'uploads/assets/default-team-2.png', 2, '2025-09-25 04:37:48'),
(162, 'OUR KESONG PUTI FAMILY', 'uploads/assets/default-team-3.png', 3, '2025-09-25 04:37:48'),
(163, 'OUR KESONG PUTI FAMILY', 'uploads/assets/default-team-4.png', 4, '2025-09-25 04:37:48'),
(164, 'OUR KESONG PUTI FAMILY', 'uploads/assets/default-team-5.png', 5, '2025-09-25 04:37:48'),
(165, 'OUR KESONG PUTI FAMILY', 'uploads/assets/default-team-6.png', 6, '2025-09-25 04:37:48');

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
(1, 'lalaine conde', 'Noel Lucena Kesong Puti', 'lalainecondes23@gmail.com', '$2y$10$9Dy/b9srwpwLGt6.BEbVGOZdWo4vY5SeUjaXuMTQ8Os09KOcHl7My'),
(3, 'lara danielle', 'Garin Kesong Puti', 'larafremista21@gmail.com', '$2y$10$79rq0jOVXhyYsyvodZ7cO.iFhO5dTwadoMy97Bo6RiOsCr3PnWQFi'),
(7, 'JB Alico', 'Mommy Lodie Kesong Puti', 'jaironbartalico@gmail.com', '$2y$10$RTJTk4ja/R7Agmm3ZBgAC.EmEadBa7p/XoKhGgxx3CwfYAawpFxcq');

-- --------------------------------------------------------

--
-- Table structure for table `branding_sections_products`
--

CREATE TABLE `branding_sections_products` (
  `id` int(11) NOT NULL,
  `icon_class` varchar(100) NOT NULL,
  `icon_color` varchar(20) DEFAULT '#000000',
  `heading` varchar(255) NOT NULL,
  `paragraph` text NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branding_sections_products`
--

INSERT INTO `branding_sections_products` (`id`, `icon_class`, `icon_color`, `heading`, `paragraph`, `position`) VALUES
(1, 'fa-solid fa-heart', '#0D8540', 'Authentic Filipino Tradition', 'Taste a Piece of Filipino Heritage. Our kesong puti is crafted with a time-honored recipe passed down through generations', 1),
(2, 'fa-solid fa-leaf', '#0D8540', 'Freshness from Local Farms', 'Farm-Fresh Goodness. We use carabao’s milk sourced daily from local farmers to ensure maximum freshness and flavor.', 2),
(3, 'fa-solid fa-cheese', '#0D8540', 'Simple, Pure Ingredients', 'Pure and Simple. Absolutely Delicious. Made only with fresh carabao’s milk, salt, and rennet, our cheese has no preservatives—just natural flavor.', 3);

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
-- Table structure for table `cta_sections`
--

CREATE TABLE `cta_sections` (
  `id` int(11) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `paragraph` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cta_sections`
--

INSERT INTO `cta_sections` (`id`, `heading`, `paragraph`, `updated_at`) VALUES
(1, 'Experience Authentic Filipino Flavor', 'Discover the creamy, wholesome taste of our kesong puti — a true Filipino delicacy made with love and tradition. Browse our shop today and bring home a piece of heritage that will delight every table.', '2025-09-25 13:11:17');

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
(4, 'Stephen Strange', '09123456789', 'stephen@gmail.com', ''),
(5, 'Jaira Bautista', '09154852394', 'jaira@example.com', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(6, 'Jaira Bautista', '09154587632', 'jaira@example.com', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(7, 'Jaira Bautista', '09154587632', 'jaira@example.com', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(8, 'Jaira Bautista', '09154587632', 'jaira@example.com', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(9, 'Andrea Flores', '09551874453', 'andrea@example.com', 'B-48 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(10, 'Jaira Bautista', '09154587632', 'jaira@example.com', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(11, 'Russell Garcia', '09234751220', 'russell@example.com', 'B-8 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(12, 'JB Alico', '09551223482', 'jb@example.com', 'B- 20 L-10, N/A, Anos, Los Baños, Laguna, 4030'),
(20, 'Trisha Lopez', '09164457812', 'trisha@example.com', 'Pickup on 2025-10-11 at 16:30'),
(22, 'sample sample', '09445488523', 'sample@sample.com', 'Pickup on 2025-10-11 at 12:30'),
(23, 'sample sample', '09234751220', 'sample@sample.com', 'Pickup on 2025-10-11 at 15:40'),
(24, 'sample sample', '09234751220', 'sample@sample.com', 'Pickup on 2025-10-11 at 15:41'),
(25, 'sample sample', '09445488523', 'sample@sample.com', 'Pickup on 2025-10-11 at 16:45'),
(26, 'sample sample', '09445488523', 'sample@sample.com', 'Pickup on 2025-10-11 at 16:55'),
(27, 'sample sample', '09445488523', 'sample@sample.com', 'Pickup on 2025-10-11 at 16:00'),
(28, 'sample sample', '09123456789', 'sample@sample.com', 'Pickup on 2025-10-11 at 08:03'),
(29, 'sample sample', '09415582331', 'sample@sample.com', '104, Gold, Anos, Los Baños, Laguna 4030'),
(30, 'sample sample', '09445488523', 'sample@sample.com', '104, Gold, Anos, Los Baños, Laguna 4030'),
(31, 'sample sample', '09415582331', 'sample@sample.com', '104, Gold, Anos, Los Baños, Laguna 4030');

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
(1, 'fa-solid fa-cow', 'Freshness and Simple Production', 'Kesong puti is a fresh cheese without preservatives, offering a pure, natural flavor.', 1),
(2, 'fa-solid fa-pills', 'Rich in Nutrients', 'It is a great source of essential nutrients, particularly protein and calcium, from carabaos milk.', 2),
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
(1133, 'home_header_font_color_part1', '#0D8540'),
(1134, 'home_header_font_color_part2', '#F4C40F'),
(1135, 'home_header_font_color_part3', '#0D8540'),
(1136, 'del_pick_font_color_title1', '#058240'),
(1137, 'del_pick_font_color_title2', '#058240'),
(1138, 'del_pick_font_color_title3', '#058240'),
(1139, 'del_pick_font_color_title4', '#F4C40F'),
(1140, 'del_pick_image', ''),
(1141, 'home_featured_title', 'The Original & Classics'),
(1142, 'home_reasons_heading', 'Why is it Good?'),
(1143, 'about_heading', 'OUR FAMILY’S LEGACY OF KESONG PUTI');

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
  `payment_method` varchar(50) DEFAULT 'cash',
  `proof_of_payment` varchar(255) DEFAULT NULL,
  `order_status` enum('pending','verified','payment-failed','processing','ready-to-pick-up','out-for-delivery','completed','declined-area','cancelled','returned') DEFAULT 'pending',
  `delivery_address` varchar(255) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `order_type` enum('delivery','pickup') DEFAULT 'delivery',
  `reference_number` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`o_id`, `c_id`, `handled_by`, `order_date`, `total_amount`, `payment_method`, `proof_of_payment`, `order_status`, `delivery_address`, `owner_id`, `order_type`, `reference_number`) VALUES
(1, 1, 0, '2025-09-19 13:19:01', 190.00, 'cash', NULL, '', 'B-48 L-10, N/A, Dila, Santa Rosa, Laguna, 4026', 1, 'delivery', 'REF16991'),
(2, 2, 0, '2025-09-19 17:32:55', 100.00, 'cash', NULL, 'pending', '', 3, 'delivery', 'REF26776'),
(3, 3, 0, '2025-09-19 17:38:40', 650.00, 'cash', NULL, 'pending', '', 3, 'delivery', 'REF32908'),
(4, 4, 0, '2025-09-24 02:20:19', 320.00, 'gcash', 'proof_68d2e4e37cf5c.png', 'pending', '', 1, 'delivery', 'REF44212'),
(10, 7, 0, '2025-10-01 01:14:59', 190.00, 'bank', '1759252499_522845871_740647665242049_262399641837452041_n.jpg', 'pending', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030', 0, 'delivery', 'REF102339'),
(11, 8, 0, '2025-10-01 01:30:01', 180.00, 'bank', '1759253401_522845871_740647665242049_262399641837452041_n.jpg', 'pending', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030', 0, 'delivery', 'REF119057'),
(12, 9, 0, '2025-10-01 02:18:08', 190.00, 'bank', NULL, 'pending', 'B-48 L-10, N/A, Anos, Los Baños, Laguna, 4030', 1, 'delivery', 'REF128273'),
(13, 10, 0, '2025-10-01 02:22:48', 160.00, 'ewallet', NULL, 'pending', 'B-4 L-10, N/A, Anos, Los Baños, Laguna, 4030', 1, 'delivery', 'REF134195'),
(14, 11, 0, '2025-10-01 02:36:16', 100.00, 'bank', '1759257376_68dc2320750cf.png', 'pending', 'B-8 L-10, N/A, Anos, Los Baños, Laguna, 4030', 3, 'delivery', 'REF146153'),
(15, 12, 0, '2025-10-02 00:52:56', 320.00, 'ewallet', '1759337576_68dd5c68c541a.jpg', 'pending', 'B- 20 L-10, N/A, Anos, Los Baños, Laguna, 4030', 1, 'delivery', 'REF158183'),
(27, 26, 0, '2025-10-02 16:54:15', 180.00, 'bank', '1759395255_68de3db70e37d.jpg', 'pending', 'Pickup on 2025-10-11 at 16:55', 3, 'pickup', 'REF272460'),
(32, 31, 0, '2025-10-03 01:23:59', 190.00, 'ewallet', '1759425839_ewallet_68deb52fb7d89.jpg', 'pending', '104, Gold, Anos, Los Baños, Laguna 4030', 1, 'delivery', 'REF327747');

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
(4, 4, 20, 1, 320.00),
(207, 10, 34, 1, 190.00),
(208, 11, 35, 1, 180.00),
(209, 12, 34, 1, 190.00),
(210, 13, 19, 1, 160.00),
(211, 14, 22, 1, 100.00),
(212, 15, 20, 1, 320.00),
(224, 27, 35, 1, 180.00),
(229, 32, 34, 1, 190.00);

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
(5, 'bank', NULL, 'lara danielle', '09957432996', '1758277057_qr.jpg', 'published', '2025-09-19 10:17:37', '2025-09-19 10:17:37', 'admin_3'),
(14, 'e-wallet', 'gcash', 'arlene macalinao', '09123456789', '1759153031_528149698_2466653143708497_6422652806459652480_n.jpg', 'published', '2025-09-29 13:37:11', '2025-09-29 13:37:11', 'super_1'),
(15, 'bank', 'bdo', 'arlene macalinao', '09123456789', '1759153045_528149698_2466653143708497_6422652806459652480_n.jpg', 'published', '2025-09-29 13:37:25', '2025-09-29 13:37:25', 'super_1'),
(18, 'e-wallet', 'paymaya', 'arlene macalinao', '09123456789', '1759258565_banana-leaf.png', 'published', '2025-09-30 18:56:05', '2025-09-30 18:56:05', 'super_1');

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
(1, 'Arlene Macalinao Kesong Puti', 'super_1', 1, 1),
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
  `owner` varchar(100) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_contacts`
--

INSERT INTO `store_contacts` (`id`, `store_name`, `owner`, `email`, `phone`, `address`, `facebook`, `twitter`, `instagram`, `latitude`, `longitude`) VALUES
(1, 'Mommy Lodie Kesong Puti', NULL, 'lodie@gmail.com', '091233455678', '123 sdjfhakjs fsdhafj', NULL, NULL, NULL, NULL, NULL),
(5, 'sample store', NULL, 'lalaineconde11@gmail.com', '09123432874', '123 sample address', NULL, NULL, NULL, NULL, NULL),
(8, 'sample store', NULL, 'lalaineconde11@gmail.com', '09123432874', 'sample address', NULL, NULL, NULL, NULL, NULL),
(9, 'sample storessssss', 'secret', 'lalaineconde23@gmail.com', '09123432874', '4883 Bagumbayan Road, Santa Cruz, 4009 Laguna', 'https://www.facebook.com/lalaine.conde19', 'https://www.facebook.com/lalaine.conde19', 'https://www.facebook.com/lalaine.conde19', NULL, NULL),
(10, 'keso store', 'lalaine conde', 'lalaineconde23@gmail.com', '09123432874', '321', 'https://www.facebook.com/lalaine.conde19', '', '', NULL, NULL);

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
(1, 'arlene macalinao', 'Arlene Macalinao Kesong Puti', 'arlene.sample@gmail.com', '$2y$10$qirbMehtP/lX3wHhZbPBbOekAx0Vq/S09tBWDlzXavFsZsIZA22oy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_images`
--
ALTER TABLE `about_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_section_position` (`section_name`,`position`);

--
-- Indexes for table `about_sections`
--
ALTER TABLE `about_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section_name` (`section_name`);

--
-- Indexes for table `about_team`
--
ALTER TABLE `about_team`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_position` (`position`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `branding_sections_products`
--
ALTER TABLE `branding_sections_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `cta_sections`
--
ALTER TABLE `cta_sections`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `reference_number` (`reference_number`),
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
-- AUTO_INCREMENT for table `about_images`
--
ALTER TABLE `about_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=925;

--
-- AUTO_INCREMENT for table `about_sections`
--
ALTER TABLE `about_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=566;

--
-- AUTO_INCREMENT for table `about_team`
--
ALTER TABLE `about_team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=682;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `branding_sections_products`
--
ALTER TABLE `branding_sections_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `cta_sections`
--
ALTER TABLE `cta_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1145;

--
-- AUTO_INCREMENT for table `inbox_messages`
--
ALTER TABLE `inbox_messages`
  MODIFY `inbox_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `page_headers`
--
ALTER TABLE `page_headers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=426;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
