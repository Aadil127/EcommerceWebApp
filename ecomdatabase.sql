

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `cart` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UsersID` int(10) UNSIGNED NOT NULL,
  `ProductsID` int(10) UNSIGNED NOT NULL,
  `Quantity` int(10) UNSIGNED NOT NULL,
  `SubTotal` int(10) UNSIGNED NOT NULL,
  `CartID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `orders` (
  `ID` int(10) UNSIGNED NOT NULL,
  `UsersID` int(10) UNSIGNED NOT NULL,
  `Total` int(10) UNSIGNED NOT NULL,
  `Date` datetime DEFAULT current_timestamp(),
  `CartID` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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


CREATE TABLE `users` (
  `ID` int(10) UNSIGNED NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` int(10) UNSIGNED NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Address` varchar(300) NOT NULL,
  `RegDate` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `cart`
  ADD PRIMARY KEY (`ID`);


ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);


ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);


ALTER TABLE `cart`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;


ALTER TABLE `orders`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;


ALTER TABLE `products`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;


ALTER TABLE `users`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

COMMIT;