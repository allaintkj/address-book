-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 14, 2021 at 04:58 PM
-- Server version: 5.7.31
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `address-book`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city` varchar(30) NOT NULL,
  `province` varchar(30) NOT NULL,
  `postal` varchar(6) NOT NULL,
  `birthday` date NOT NULL,
  `rep` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rep` (`rep`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `fname`, `surname`, `phone`, `email`, `address`, `city`, `province`, `postal`, `birthday`, `rep`) VALUES
(1, 'John', 'Doe', 1234567890, 'example@provider.com', '123 Somewhere Street', 'Halifax', 'Alberta', 'A1A1A1', '1999-09-09', 4),
(2, 'Jane', 'Doe', 1234567890, 'domain@example.com', '55 Nowhere Lane', 'Montreal', 'Quebec', 'B2N3M2', '1978-03-28', 1),
(3, 'Tresa', 'Laxen', 7523000016, 'tlaxen@mail.ru', '86454 Shopko Avenue', 'Cartier', 'Manitoba', 'R4K3A6', '1997-11-08', 2),
(4, 'Napoleon', 'Bellinger', 6375902403, 'nbellinger2@tinyurl.com', '28395 Goodland Crossing', 'Bradford', 'Ontario', 'L3Z0C4', '1962-11-18', 2),
(5, 'Nerta', 'Veryan', 2238690020, 'nveryan0@qq.com', '8 Springs Point', 'Quismapsis', 'New Brunswick', 'E2G0R3', '1969-07-09', 4),
(6, 'Ysabel', 'Mattedi', 4804414858, 'ymattedi1@dell.com', '54 Rockefeller Drive', 'Lavaltrie', 'Quebec', 'J5T0W0', '1973-09-29', 1),
(7, 'Klara', 'Culleford', 4262198894, 'kculleford3@exblog.jp', '5293 Warbler Place', 'Sturgeon Falls', 'Ontario', 'P2B0T3', '1962-07-23', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales_reps`
--

DROP TABLE IF EXISTS `sales_reps`;
CREATE TABLE IF NOT EXISTS `sales_reps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` text,
  `email` varchar(50) NOT NULL,
  `is_admin` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_reps`
--

INSERT INTO `sales_reps` (`id`, `fname`, `surname`, `username`, `password`, `email`, `is_admin`) VALUES
(1, 'Cortney', 'Lawty', NULL, NULL, 'clawty0@twitter.com', 0),
(2, 'Julissa', 'Legier', NULL, NULL, 'jlegier1@edublogs.org', 0),
(3, 'Jerrilee', 'Spink', 'spink', '$2y$10$ciN/pEDbBbCElYLtKmYIbOYehQUTh2JphWvVX7TGkg1ovSS3tvZUW', 'jspink2@vk.com', 0),
(4, 'Tom', 'Allain', 'tom', '$2y$10$uwKaDfv6WSgSwr.wnhN6/OSC1Sw/kvV216sMU0WR4Mh1oUa8yVZHG', 'tom.allain@protonmail.com', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`rep`) REFERENCES `sales_reps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
