-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 06:01 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `javajam`
--
CREATE DATABASE IF NOT EXISTS `javajam` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `javajam`;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_by` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_options`
--

CREATE TABLE `product_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_type` varchar(255) NOT NULL COMMENT 'Single, Double',
  `price` decimal(10,2) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_by` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_line`
--

CREATE TABLE `order_line` (
  `order_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_by` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_by` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`order_id`, `option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `created_by`, `updated_by`) VALUES
(1, 'Just Java', 'Regular house Blend, decaffeinated coffee, or flavor of the day.', '2024-09-29 10:24:57', NULL),
(2, 'Cafe au Lait', 'House blended coffee infused into a smooth, steamed milk.', '2024-09-29 10:24:57', NULL),
(3, 'Iced Cappuccino', 'Sweetened espresso blended with icy-cold milk and served in a chilled glass.', '2024-09-29 10:25:33', NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `product_options`
--

INSERT INTO `product_options` (`option_id`, `option_type`, `price`, `product_id`, `created_by`, `updated_by`) VALUES
(1, 'Endless Cup', '2.00', 1, '2024-09-29 10:27:37', '2024-09-30 17:15:42'),
(2, 'Single', '2.00', 2, '2024-09-29 10:27:37', '2024-09-30 14:30:29'),
(3, 'Double', '3.00', 2, '2024-09-29 10:28:30', '2024-09-30 17:17:34'),
(4, 'Single', '4.75', 3, '2024-09-29 10:28:30', '2024-09-30 14:29:49'),
(5, 'Double', '5.75', 3, '2024-09-29 10:28:50', '2024-09-30 14:29:49');

-- --------------------------------------------------------

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `created_by`, `updated_by`) VALUES
(5, '2024-09-30 16:41:39', NULL),
(6, '2024-09-30 16:55:08', NULL),
(7, '2024-09-30 16:56:59', NULL),
(8, '2024-09-30 17:01:04', NULL),
(9, '2024-09-30 17:05:36', NULL),
(10, '2024-09-30 17:10:09', NULL),
(11, '2024-09-30 17:11:31', NULL);

-- --------------------------------------------------------

--
-- Dumping data for table `order_line`
--

INSERT INTO `order_line` (`order_id`, `option_id`, `qty`, `price`, `created_by`, `updated_by`) VALUES
(5, 1, 2, '2.00', '2024-09-30 16:41:39', NULL),
(5, 2, 3, '2.00', '2024-09-30 16:41:39', NULL),
(5, 5, 6, '5.75', '2024-09-30 16:41:39', NULL),
(6, 4, 2, '4.75', '2024-09-30 16:55:08', NULL),
(7, 2, 5, '2.00', '2024-09-30 16:56:59', NULL),
(8, 1, 2, '2.00', '2024-09-30 17:01:04', NULL),
(9, 1, 3, '2.00', '2024-09-30 17:05:36', NULL),
(10, 3, 3, '3.00', '2024-09-30 17:10:09', NULL),
(11, 3, 1, '3.00', '2024-09-30 17:11:31', NULL),
(11, 4, 1, '4.75', '2024-09-30 17:11:31', NULL);

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_line`
--
ALTER TABLE `order_line`
  ADD CONSTRAINT `fk_orderline_option` FOREIGN KEY (`option_id`) REFERENCES `product_options` (`option_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orderline_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_options`
--
ALTER TABLE `product_options`
  ADD CONSTRAINT `fk_options_products` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
