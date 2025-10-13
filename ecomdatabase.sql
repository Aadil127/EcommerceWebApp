-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2025 at 06:20 AM
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
-- Database: `ecomdata`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UsersID` int(10) UNSIGNED NOT NULL,
  `ProductsID` int(10) UNSIGNED NOT NULL,
  `Quantity` int(10) UNSIGNED NOT NULL,
  `SubTotal` int(10) UNSIGNED NOT NULL,
  `CartID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`ID`, `UsersID`, `ProductsID`, `Quantity`, `SubTotal`, `CartID`) VALUES
(1, 3, 1, 5, 50, 'cart_68ec759f71caf'),
(2, 3, 6, 1, 10, 'cart_68ec759f71caf'),
(3, 2, 10, 1, 40, 'cart_68ec770880c2c'),
(4, 2, 2, 5, 50, 'cart_68ec770880c2c'),
(5, 4, 1, 5, 50, 'cart_68ec7c08483ca'),
(6, 4, 6, 1, 10, 'cart_68ec7c08483ca');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UsersID` int(10) UNSIGNED NOT NULL,
  `Total` int(10) UNSIGNED NOT NULL,
  `Date` datetime DEFAULT current_timestamp(),
  `CartID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `UsersID`, `Total`, `Date`, `CartID`) VALUES
(1, 3, 60, '2025-10-13 05:44:31', 'cart_68ec759f71caf'),
(2, 2, 90, '2025-10-13 05:50:32', 'cart_68ec770880c2c'),
(3, 4, 60, '2025-10-13 06:11:52', 'cart_68ec7c08483ca');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Price` int(10) UNSIGNED NOT NULL,
  `ImgPath` varchar(300) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Category` varchar(50) NOT NULL,
  `PurchasedPrice` int(10) UNSIGNED NOT NULL,
  `StartingQuantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `Name`, `Price`, `ImgPath`, `Quantity`, `Category`, `PurchasedPrice`, `StartingQuantity`) VALUES
(1, 'Cocal cola', 10, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 1000, 'drinks', 0, 100),
(2, '7UP', 10, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 995, 'drinks', 0, 1000),
(3, 'C', 15, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 1000, 'drinks', 0, 100),
(4, 'D', 20, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 100, 'drinks', 0, 100),
(5, 'E', 10, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 1000, 'drinks', 0, 100),
(6, 'Chips A', 10, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 499, 'snacks', 0, 100),
(7, 'Chips B', 15, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 500, 'snacks', 0, 100),
(8, 'Chips C', 30, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 400, 'snacks', 0, 100),
(9, 'F', 15, 'images/thomas-le-pRJhn4MbsMM-unsplash.jpg', 1000, 'drinks', 10, 100),
(10, 'Red Bull', 40, 'uploads/img_68ec76a35c8fe9.99912411.jpg', 199, 'drinks', 30, 200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` int(10) UNSIGNED NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Address` varchar(300) NOT NULL,
  `RegDate` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Phone`, `Password`, `Address`, `RegDate`) VALUES
(1, 'A', 1, '$2y$10$iv6/wwJQB/UGPzravktnfeIYLJFoPzW.3MqnPEQ752izV3Fcm9ZSi', 'a', '2025-10-13'),
(2, 'B', 2, '$2y$10$vYAhWM/wJwGvh4SDnuQkEe.vWUb75iGuqhSe0OLypkmIPLa/r8PBG', 'abcd 1234', '2025-10-13'),
(3, 'C', 3, '$2y$10$Lj4S9NAxXitiHb.UW1dr2.G1rjms.L88NtoEUwfP4SqN90L5tk5z2', 'abcd 1234', '2025-10-13'),
(4, 'D', 4, '$2y$10$19bwxYPtKzopL5jiszyUb.dsfzOz81JQmiqstp3YZSQJ6whxdPdry', 'abcd 1234', '2025-10-13'),
(5, 'E', 5, '$2y$10$0OGB2riiadMS6KPXdL3w/epa.lax1HTGowlueF4Yjd6bYABROkV.G', 'abcd 1234', '2025-10-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
