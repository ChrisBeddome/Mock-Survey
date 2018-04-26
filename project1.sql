-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2017 at 08:04 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project1orig`
--
CREATE DATABASE IF NOT EXISTS `project1` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `project1`;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`) VALUES
(1, 'Phone'),
(2, 'Smart TV'),
(3, 'Laptop'),
(4, 'Tablet'),
(5, 'Home Theater');

-- --------------------------------------------------------

--
-- Table structure for table `purchasemethods`
--

CREATE TABLE `purchasemethods` (
  `id` int(11) NOT NULL,
  `method` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchasemethods`
--

INSERT INTO `purchasemethods` (`id`, `method`) VALUES
(1, 'Online'),
(2, 'Phone'),
(3, 'Store'),
(4, 'Mobile');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `purchaseMethod` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `recommend` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `studentstatus`
--

CREATE TABLE `studentstatus` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `studentstatus`
--

INSERT INTO `studentstatus` (`id`, `status`) VALUES
(1, 'Full Time'),
(2, 'Part Time'),
(3, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `student` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchasemethods`
--
ALTER TABLE `purchasemethods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `product` (`product`),
  ADD KEY `purchaseMethod` (`purchaseMethod`);

--
-- Indexes for table `studentstatus`
--
ALTER TABLE `studentstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student` (`student`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `purchasemethods`
--
ALTER TABLE `purchasemethods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `studentstatus`
--
ALTER TABLE `studentstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews.product_fk` FOREIGN KEY (`product`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews.purchaseMethod_fk` FOREIGN KEY (`purchaseMethod`) REFERENCES `purchasemethods` (`id`),
  ADD CONSTRAINT `reviews.user_fk` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `studentStatus_fk` FOREIGN KEY (`student`) REFERENCES `studentstatus` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*CREATE VIEW*/
CREATE VIEW `submissions`
AS SELECT users.fullname AS 'name', users.age AS 'age', studentstatus.status AS 'status', products.name AS 'product name', purchasemethods.method AS 'purchase method', reviews.rating AS 'product rating', reviews.recommend AS 'would recommend?'
FROM reviews
LEFT JOIN products 
ON products.id = reviews.product
LEFT JOIN users 
ON reviews.user = users.id
LEFT JOIN studentstatus 
ON users.student = studentstatus.id
LEFT JOIN purchasemethods
ON purchasemethods.id = reviews.purchaseMethod
ORDER BY name;

/*ADD USER DATA*/
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, RELOAD, REFERENCES, INDEX, ALTER ON *.* TO 'project1User'@'Localhost' IDENTIFIED BY 'proj1';