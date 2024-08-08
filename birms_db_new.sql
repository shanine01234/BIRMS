-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2024 at 09:21 PM
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
-- Database: `birms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'shanine', '$2y$10$neANQkffXqDTdmwTP2r.tebqkszn1TeT4sqZ6sVVzb7HOgLFBml7e');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `owner_id` varchar(50) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `owner_id`, `branch`, `location`) VALUES
(2, '00322', 'Santa Fe', 'Ilustrisimo street, Poblacion'),
(5, '00322', 'Bantayan', 'skina sa Baod'),
(7, '40613', 'Bantayan', 'Triplin, Santa Cruz Chapel'),
(8, '40613', 'Santa Fe', 'Ilustrisimo street, Poblacion'),
(9, '85494', '3', 'ATOP-ATOP');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `menu_id`, `user_id`, `quantity`, `date`) VALUES
(1, 13, 1, 6, '2024-07-30 15:39:14'),
(2, 15, 1, 4, '2024-07-30 15:39:28'),
(3, 12, 1, 29, '2024-07-30 15:51:14'),
(4, 14, 1, 11, '2024-07-30 15:58:15'),
(5, 21, 1, 2, '2024-07-30 15:58:19'),
(6, 22, 1, 1, '2024-07-30 16:10:14'),
(7, 27, 1, 4, '2024-07-30 17:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `owner_id` varchar(50) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_type` varchar(100) NOT NULL,
  `price` varchar(50) NOT NULL,
  `product_photo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `owner_id`, `product_name`, `product_type`, `price`, `product_photo`) VALUES
(3, '00322', 'Egg & Bacon with Potato', 'Food', '120', 'how-to-fry-an-egg-horizontal-642dc4ed76da2.jpg'),
(4, '00322', 'Spaghetti', 'Dessert', '250', 'Easyspaghettiwithtomatosauce_11715_DDMFS_1x2_2425-c67720e4ea884f22a852f0bb84a87a80.jpg'),
(5, '00322', 'Bacon', 'Food', '80', 'how-to-fry-an-egg-horizontal-642dc4ed76da2.jpg'),
(7, '40613', 'Spaghetti', 'Food', '98', 'Easyspaghettiwithtomatosauce_11715_DDMFS_1x2_2425-c67720e4ea884f22a852f0bb84a87a80.jpg'),
(8, '40613', 'Fontana', 'Drinks', '599', 'wineeee.jpg'),
(10, '40613', 'Sprite', 'Drinks', '30', 'd6cade337fde7128768721601964bb9d.jpg'),
(12, '85494', 'JESSIEFOOD', 'Food', '21', 'f1.jpeg'),
(13, '85494', 'Adobo', 'Food', '23', 'f2.jpg'),
(14, '85494', 'YEEH', 'Food', '45', 'f3.jpg'),
(15, '85494', 'REEEE', 'Food', '45', 'f4.jpg'),
(16, '85494', 'FFF', 'Food', '34', 'f5.jpg'),
(17, '85494', 'fff', 'Food', '23', 'f4.jpg'),
(18, '85494', 'ff', 'Food', '232', 'f1.jpeg'),
(19, '85494', 'coke', 'Drinks', '21', 'd1.jpg'),
(20, '85494', 'Royal', 'Drinks', '21', 'd2.jpg'),
(21, '85494', 'Lemon', 'Drinks', '111', 'd4.jpg'),
(22, '85494', 'Juice', 'Drinks', '1111', 'd5.jpg'),
(23, '53589', 'aasa', 'Food', 'qsqs', 'd1.jpg'),
(24, '53589', 'aaaz', 'Drinks', 'qsqsq', 'd1.jpg'),
(25, '53589', 'asa', 'Drinks', 'qsqsq', 'd5.jpg'),
(26, '53589', 'qwsq', 'Drinks', 'ww', 'd2.jpg'),
(27, '53589', 'www', 'Food', '22w', 'd4.jpg'),
(28, '53589', 'aax', 'Drinks', 'sq', 'd4.jpg'),
(29, '53589', 'qsqsq', 'Drinks', 'qqq', 'd5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '1=pending,2=confirmed,3=finished'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `created_at`, `status`) VALUES
(1, 6, 10087, '2024-07-30 18:54:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders1`
--

CREATE TABLE `orders1` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending',
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders2`
--

CREATE TABLE `orders2` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `menu_id`, `quantity`, `unit_price`) VALUES
(1, 1, 27, 4, 22.00),
(2, 1, 26, 5, 0.00),
(3, 1, 22, 9, 1111.00);

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `owner_id` varchar(50) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `contact_num` varchar(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `profile` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id`, `owner_id`, `firstname`, `middlename`, `lastname`, `email`, `password`, `contact_num`, `address`, `profile`, `status`) VALUES
(19, '85494', 'jessie', 'rayco', 'santillan', 'jessiesantillan@gmail.com', '$2y$10$EpOyfB7m4c1gWbCyT/y6fuR3/N7NdusspWUURguQqIT9ATLd/EfPW', '09292751823', 'Mojon Bantayan Cebu', NULL, 1),
(20, '53589', 'Gogoy', 'Gwapo', 'Santillan', 'santillanjessie@gmail.com', '$2y$10$dVdnX0kWZQh4NSn1IZwH4ukUvJkwvjOdD2aAesxRz2a6fCwxFvDvu', '09187931246', 'JessieRestobar', NULL, 1),
(21, '73500', 'Jorgen', 'Rayco', 'Santillamn', 'jorgem@gmail.com', '$2y$10$GZTD6eeXOqfmnNo6fPRnVuWgq3tTVzRpvLaSs9518MUFk3V0pqoKG', '09187531565', 'oboob', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `restobar`
--

CREATE TABLE `restobar` (
  `resto_id` int(11) NOT NULL,
  `owner_id` varchar(50) NOT NULL,
  `resto_name` varchar(100) NOT NULL,
  `resto_photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restobar`
--

INSERT INTO `restobar` (`resto_id`, `owner_id`, `resto_name`, `resto_photo`) VALUES
(8, '62578', 'banrnae', '242534303_4106499022795670_1728727078114636428_n.jpg'),
(9, '72990', 'JESSIE ', 'images.png'),
(10, '85494', 'Bantayan ', 'images.png'),
(11, '53589', 'JeesieRestobar', 'logo.jpg'),
(12, '73500', 'jorgen', 'logo2.png');

-- --------------------------------------------------------

--
-- Table structure for table `restobar_details`
--

CREATE TABLE `restobar_details` (
  `id` int(11) NOT NULL,
  `owner_id` varchar(50) NOT NULL,
  `details` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restobar_details`
--

INSERT INTO `restobar_details` (`id`, `owner_id`, `details`) VALUES
(3, '40613', 'Live Band @7pm - 10pm'),
(4, '40613', 'Open Every Weedays @8am - 10pm'),
(5, '85494', 'JESSIE IS GWAPO'),
(7, '53589', 'A restaurant is a business that prepares and serves A restaurant is a business that prepares and serves food and drinks to customers. Meals are generally served and eaten on the premises,A restaurant ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'jessie', 'jessie@gmail.com', '$2y$10$VK1eBrDXg2OGRjirij29w.yQC3RYSvsdkp3HpIn0pjLxKhzoFFUjO', '2024-07-26 10:11:11'),
(2, 'jessie23', 'earljustinesierra@gmail.com', '$2y$10$yjkRm64/r6f7xDQnUjtmw.9EKRFo7qES1dF6uXgytjGbZ3grUxxeO', '2024-07-26 10:12:59'),
(6, 'rogine', 'shin@gmail.com', '$2y$10$pdLjrpKaV1vAd8zDoIKo0eMUt1CVSLRw/1jKxZNim1.47VG/H4gzq', '2024-07-30 17:25:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders1`
--
ALTER TABLE `orders1`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders2`
--
ALTER TABLE `orders2`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restobar`
--
ALTER TABLE `restobar`
  ADD PRIMARY KEY (`resto_id`);

--
-- Indexes for table `restobar_details`
--
ALTER TABLE `restobar_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders1`
--
ALTER TABLE `orders1`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders2`
--
ALTER TABLE `orders2`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `restobar`
--
ALTER TABLE `restobar`
  MODIFY `resto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `restobar_details`
--
ALTER TABLE `restobar_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders1`
--
ALTER TABLE `orders1`
  ADD CONSTRAINT `orders1_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders2`
--
ALTER TABLE `orders2`
  ADD CONSTRAINT `orders2_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders2_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
