-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 06:59 PM
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
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `dispositions`
--

CREATE TABLE `dispositions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_disposed` int(11) NOT NULL,
  `date_disposed` datetime DEFAULT current_timestamp(),
  `disposition_reason` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dispositions`
--

INSERT INTO `dispositions` (`id`, `product_id`, `quantity_disposed`, `date_disposed`, `disposition_reason`, `user_id`) VALUES
(20, 18, 2, '2024-10-30 17:10:42', 'pinjam', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `order_date`, `created_at`) VALUES
(17, 3, 18, 10, '2024-10-28 20:12:20', '2024-10-28 20:12:20');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `disposition_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `quantity`, `description`, `image`, `price`, `category`, `location`, `disposition_reason`) VALUES
(4, 'baju 2', 2, 'bagus', 'uploads/Screenshot 2024-08-28 130451.png', 10000.00, 'baju', NULL, NULL),
(8, 'celana 1', 3, 'bagus', 'uploads/Screenshot 2024-08-28 130009.png', 7000.00, 'celana', NULL, NULL),
(18, 'sepatu', 45, 'bagus', 'uploads/Screenshot 2024-08-28 121533.png', 3000.00, 'sepatu', NULL, NULL),
(19, 'topi', 6, 'bagus', 'uploads/Screenshot 2024-08-28 101425.png', 3000.00, 'topi', NULL, NULL),
(20, 'kaos kaki', 2, 'bagus', 'uploads/Screenshot 2024-10-09 084349.png', 25000.00, 'kaos kaki', NULL, NULL),
(21, 'tas', 15, 'bagus', 'uploads/Screenshot 2024-08-29 102925.png', 5000.00, 'tas', NULL, NULL),
(22, 'kalung', 20, 'bagus', 'uploads/Screenshot 2024-09-01 211216.png', 10000.00, 'kalung', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$a8Wm2TXsgFyRIR7hAuSJnexipE3yAtBl.xGFlKTh/TSFDhQNt9nOu', 'admin@tes.com', 'admin'),
(3, 'bryan', '$2y$10$J6itDYovXFlO3c1591MLN.0l4VSnyZrxvi8TX/sta8.tmT.DNecFG', 'bryanwaluyan@gmail.com', 'user'),
(9, 'tes', '$2y$10$Q.AGioq2X67Z7JSgtl3x..USoUwU5bXKCcqMbILwxqyTd2NnHw7mq', 'tes@gmail.com', 'user'),
(10, 'marvel', '$2y$10$P5R8.XfLcW7vqReAFHDnOuiJdv7YniuLYfxKmMFkzAclt76zKEYpq', 'marvel@gmail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dispositions`
--
ALTER TABLE `dispositions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dispositions`
--
ALTER TABLE `dispositions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dispositions`
--
ALTER TABLE `dispositions`
  ADD CONSTRAINT `dispositions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
