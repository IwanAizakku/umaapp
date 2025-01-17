-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql305.infinityfree.com
-- Generation Time: Jan 17, 2025 at 03:29 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37900427_umaapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `price`, `added_at`) VALUES
(11, 1, 49, 1, '8.00', '2025-01-15 07:00:33'),
(12, 1, 41, 1, '1500.00', '2025-01-15 07:00:56'),
(13, 1, 44, 1, '120.00', '2025-01-15 07:01:04');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(1, 'Stationery', 'School stuffs'),
(2, 'Electronics', 'Devices and gadgets for academic and personal use'),
(3, 'Books', 'Textbooks, reference materials, and leisure reading for students');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(34, 28, 46, 5, '15.00'),
(36, 30, 41, 1, '1500.00'),
(37, 31, 43, 1, '30.00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `status` enum('received','processing','ready','completed','cancelled') DEFAULT 'received',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `delivery_address`, `status`, `created_at`, `updated_at`) VALUES
(28, 8, '75.00', 'Taman Penampang, 89500, Penampang, Sabah, Malaysia', 'received', '2025-01-17 06:06:22', '2025-01-17 06:06:22'),
(30, 8, '1500.00', 'Taman Penampang, 89500, Penampang, Sabah, Malaysia', 'received', '2025-01-17 06:17:55', '2025-01-17 06:17:55'),
(31, 23, '30.00', 'JA9152, JALAN NURI, TAMAN KOPERASI, SERKAM DARAT, 77300, MERLIMAU, state, Malaysia', 'received', '2025-01-17 08:25:07', '2025-01-17 08:25:07');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('fpx','card','cod') NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_method`, `payment_status`, `transaction_date`) VALUES
(28, 28, 'fpx', 'completed', '2025-01-17 06:06:22'),
(30, 30, 'fpx', 'completed', '2025-01-17 06:18:08'),
(31, 31, 'fpx', 'completed', '2025-01-17 08:25:10');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `views` int(11) DEFAULT 0,
  `added_to_cart` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `retailer_id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `image_url`, `created_at`, `updated_at`, `views`, `added_to_cart`) VALUES
(2, 1, 1, 'Notebook', 'A 100-page notebook', '25.50', 50, '../Images/notebook.jpg', '2025-01-03 20:29:42', '2025-01-17 06:09:39', 0, 0),
(41, 8, 2, 'Laptop', 'High-performance laptop ideal for programming and research', '1500.00', 25, '../Images/laptop.jpg', '2025-01-04 02:00:00', '2025-01-17 06:09:39', 0, 0),
(42, 8, 2, 'Noise-Cancelling Headphones', 'Headphones for focused studying in noisy environments', '250.00', 40, '../Images/headphones.jpg', '2025-01-04 02:05:00', '2025-01-17 06:09:39', 0, 0),
(43, 1, 2, 'Wireless Mouse', 'Ergonomic mouse for comfortable usage during long study sessions', '30.00', 60, '../Images/mouse.jpg', '2025-01-04 02:10:00', '2025-01-17 06:09:39', 0, 0),
(44, 1, 3, 'Introduction to Algorithms', 'Comprehensive guide to algorithms used in software development', '120.00', 50, '../Images/book1.jpg', '2025-01-04 02:15:00', '2025-01-17 06:09:39', 0, 0),
(45, 1, 3, 'Advanced Physics Textbook', 'Detailed physics textbook for engineering students', '80.00', 40, '../Images/book2.jpg', '2025-01-04 02:20:00', '2025-01-17 06:09:39', 0, 0),
(46, 1, 3, 'Notebooks Pack', 'Set of 5 ruled notebooks for class notes', '15.00', 150, '../Images/notebookset.jpg', '2025-01-04 02:30:00', '2025-01-17 06:09:39', 0, 0),
(47, 1, 1, 'Scientific Calculator', 'Essential tool for math and engineering courses', '50.00', 120, '../Images/cal.jpg', '2025-01-04 02:35:00', '2025-01-17 06:09:39', 0, 0),
(49, 1, 1, 'Highlighter Set', 'Set of 5 colorful highlighters for marking important notes', '8.00', 100, '../Images/highlighter.jpg', '2025-01-04 02:45:00', '2025-01-17 06:09:39', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('student','retailer','admin') NOT NULL,
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `postcode` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `profile_picture_url`, `address`, `created_at`, `postcode`, `city`, `state`, `country`) VALUES
(1, 'retailer', 'retailer@example.com', '$2y$10$WvL.amL8LHubi2MEikP7ZeKcBOe9UjMIvwQPv.mDSXV4kyb344fn6', 'retailer', 'NULL', '123 Campus Street', '2025-01-03 20:26:13', NULL, NULL, NULL, NULL),
(8, 'Ivan', 'ivan@gmail.com', '$2y$10$1dj8V07P4/8GNMZXlzlEaOUHC2glLEnL6jqV7sto9hqwVyTdjLWBO', 'student', NULL, 'Taman Penampang', '2025-01-04 20:42:08', '89500', 'Penampang', 'Sabah', 'Malaysia'),
(13, 'Isaac', 'isaac20@gmail.com', '$2y$10$Zz0YbojKKJLZYukYY/d4WeejJv1CtPMysMH9PpAcxEujW3sBh1z5u', 'student', NULL, 'Address', '2025-01-13 15:07:39', '89500', 'City', 'State', 'Country'),
(14, 'amnan', 'amnan1803@gmail.com', '$2y$10$TrVzxYoK5dDDAWxTXpkY9.gsU8Uk0/ZsXi/GXE.0JbgBwax7u.Bg6', 'admin', NULL, 'JA9152, JALAN NURI, TAMAN KOPERASI, SERKAM DARAT', '2025-01-14 16:12:21', '77300', 'MERLIMAU', 'asdasd', 'Malaysia'),
(17, 'amir', 'amir@gmail.com', '$2y$10$RGBhOvj.J7Nix5WqainHZeraq3ONSmU9z6NmjlS2HOLGsqGrNgOT6', 'student', NULL, 'asd', '2025-01-15 16:23:32', '123', 'asd', 'asd', 'asd'),
(18, '82773', 'annnicole235@gmail.com', '$2y$10$fpwjva6YDT7cTpraRlbXOOQdTmiIJRv9GtiuKgmmdwukLS4zUn.by', 'student', NULL, 'D/A Siena Anak Bakir, Bahagian Siasatan Jenayah Narkotik, Ibu Pejabat Polis Daerah', '2025-01-16 02:21:40', '98000', 'Miri', 'SARAWAK', 'Malaysia'),
(19, '82555', '82555@siswa.unimas.my', '$2y$10$TxJ/3f3H3ZzELgsv5kIAieZts2OsklCs9uihJx5zraReYXxa1Wy.i', 'student', NULL, 'Kolej Allamanda', '2025-01-16 02:46:30', '94300', 'Kota Samarahan', 'Sarawak', 'Malaysia'),
(21, 'Zack', 'zack@gmail.com', '$2y$10$YjsnZ0xBRM4BzckNuGZeUe9S/3UGF1ZuNin/JSGrSXfIiFwHZPYVm', 'retailer', NULL, 'address', '2025-01-16 06:53:12', '89500', 'city', 'state', 'country'),
(23, '82713', 'khirkardi@gmail.com', '$2y$10$xkprmQEMgpp1jk.krm9lgOirTokvfFspBLT/5M7o9crVst9R7TFUi', 'student', NULL, 'JA9152, JALAN NURI, TAMAN KOPERASI, SERKAM DARAT', '2025-01-17 08:05:26', '77300', 'MERLIMAU', 'state', 'Malaysia'),
(25, 'ByteTech', 'ann@gmail.com', '$2y$10$FGIKNWXXZsJNUx4JvwdmzulvU74wfKUhUEDNNmBM7.hm8aofRwD8u', 'retailer', NULL, 'D/A Siena Anak Bakir, Bahagian Siasatan Jenayah Narkotik, Ibu Pejabat Polis Daerah', '2025-01-17 22:02:44', '98000', 'Miri', 'SARAWAK', 'Malaysia'),
(27, 'BRC Mart', 'brcmart@gmail.com', '$2y$10$TFrvHDgfjs74.QVIn8413udGN8jQeg4FtI0spkUNQcsI/ryBI90Fa', 'retailer', NULL, 'Lot 3493, No. 13 2nd Floor, Jalan Piasau,', '2025-01-17 22:09:09', '98000', 'Miri', 'Sarawak', 'Malaysia'),
(28, 'allamanda', 'allamanda@gmail.com', '$2y$10$yZfD7vl1s4slX9APJG92SeGYLq8eQ2ssfdthSX5ndqJQjcdbMPOgS', 'retailer', NULL, 'Lorong Jati 2, Lot 5538, Jalan desa Senadin', '2025-01-17 22:17:02', '98000', 'Miri', 'Sarawak', 'Malaysia'),
(29, 'nicole', 'nicole@gmail.com', '$2y$10$4lk6IjkffXBOm9QDAsYfLO76I04rCAN9cu8jPP06FtSlfKpIt66rO', 'admin', NULL, 'Lot 10591, Block 10, Kuala Baram Land District, Pujut 7 Commercial Center,', '2025-01-17 22:45:26', '98000', 'Miri', 'Sarawak', 'Malaysia'),
(30, 'admin', 'admin@gmail.com', '$2y$10$difOb.EQYZSms4DUTPe3T.AKawTxONRNsnVojndIgi74bQ.0oyS6S', 'admin', NULL, 'Address', '2025-01-17 22:52:09', '89500', 'City', 'State', 'Country');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `retailer_id` (`retailer_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
