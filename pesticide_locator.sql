-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 06:02 PM
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
-- Database: `pesticide_locator`
--

-- --------------------------------------------------------

--
-- Table structure for table `pesticides`
--

CREATE TABLE `pesticides` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `crop` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` enum('Branded','Non-Branded') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesticides`
--

INSERT INTO `pesticides` (`id`, `name`, `crop`, `description`, `price`, `category`) VALUES
(1, 'Glyphosate', 'Weeds', 'Common herbicide used for weed control.', 650.00, 'Branded'),
(2, 'Chlorpyrifos', 'Rice', 'Insecticide for crop pest management.', 720.00, 'Non-Branded'),
(3, 'Carbaryl', 'Fruits', 'Broad-spectrum insecticide for fruits and vegetables.', 480.00, 'Branded'),
(4, 'Imidacloprid', 'Cotton', 'Used against sucking insects on crops.', 690.00, 'Branded'),
(5, 'Acephate', 'Vegetables', 'Systemic insecticide for vegetables and cotton.', 530.00, 'Branded'),
(6, 'Lambda Cyhalothrin', 'Cereals', 'Pyrethroid insecticide for crop protection.', 750.00, 'Non-Branded'),
(7, '2,4-D', 'Maize', 'Selective herbicide for broadleaf weed control.', 460.00, 'Non-Branded'),
(8, 'Atrazine', 'Sugarcane', 'Herbicide used for maize and sugarcane.', 600.00, 'Branded'),
(9, 'Mancozeb', 'Tomato', 'Fungicide used for fruits and vegetables.', 420.00, 'Branded'),
(10, 'Metalaxyl', 'Grapes', 'Fungicide for downy mildew control.', 680.00, 'Branded'),
(11, 'Captan', 'Apple', 'Fungicide for seed treatment and fruit crops.', 520.00, 'Non-Branded'),
(12, 'Copper Oxychloride', 'Banana', 'Fungicide for bacterial and fungal diseases.', 390.00, 'Non-Branded'),
(13, 'Sulphur Dust', 'Chili', 'Fungicide and miticide for horticultural crops.', 250.00, 'Non-Branded'),
(14, 'Neem Oil', 'Pulses', 'Organic pesticide from neem extract.', 320.00, 'Branded'),
(15, 'Spinosad', 'Cabbage', 'Biopesticide for caterpillars and thrips.', 450.00, 'Branded'),
(16, 'Thiamethoxam', 'Rice', 'Systemic insecticide for rice and pulses.', 690.00, 'Branded'),
(17, 'Paraquat', 'Weeds', 'Non-selective herbicide for weed control.', 560.00, 'Branded'),
(18, 'Pendimethalin', 'Wheat', 'Pre-emergence herbicide for field crops.', 610.00, 'Non-Branded'),
(19, 'Cypermethrin', 'Cotton', 'Insecticide for cotton and vegetables.', 580.00, 'Branded'),
(20, 'Deltamethrin', 'Vegetables', 'Insecticide for agricultural and household pests.', 600.00, 'Branded'),
(21, 'Fipronil', 'Rice', 'Insecticide for rice and sugarcane.', 730.00, 'Branded'),
(22, 'Malathion', 'Fruits', 'Insecticide for public health and crops.', 480.00, 'Branded'),
(23, 'Quinalphos', 'Cotton', 'Insecticide for rice, cotton, and pulses.', 530.00, 'Non-Branded'),
(24, 'Dimethoate', 'Wheat', 'Systemic insecticide for aphids and thrips.', 570.00, 'Non-Branded'),
(25, 'Carbendazim', 'Paddy', 'Systemic fungicide for multiple crops.', 510.00, 'Non-Branded');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `lat` decimal(10,6) DEFAULT NULL,
  `lng` decimal(10,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `address`, `lat`, `lng`) VALUES
(1, 'AgroMart Basavanagudi', '12 Bull Temple Rd, Basavanagudi, Bengaluru, Karnataka', 12.942100, 77.568500),
(2, 'GreenGrow Agro Koramangala', '80 Feet Rd, Koramangala, Bengaluru, Karnataka', 12.935200, 77.624500),
(3, 'Farmers Hub Jayanagar', '4th Block, Jayanagar, Bengaluru, Karnataka', 12.925100, 77.593500),
(4, 'Kaveri Agro Supplies Whitefield', 'Main Rd, Whitefield, Bengaluru, Karnataka', 12.969800, 77.749900),
(5, 'AgroZone Indiranagar', 'CMH Rd, Indiranagar, Bengaluru, Karnataka', 12.978400, 77.640800),
(6, 'RuralMart Yelahanka', 'Doddaballapur Rd, Yelahanka, Bengaluru, Karnataka', 13.099000, 77.596300),
(7, 'EcoFarm Supply HSR Layout', 'Sector 6, HSR Layout, Bengaluru, Karnataka', 12.910300, 77.638700),
(8, 'Growers Point Electronic City', 'Hosur Rd, Electronic City, Bengaluru, Karnataka', 12.841200, 77.678200),
(9, 'AgroCare Hebbal', 'Hebbal Flyover, Bengaluru, Karnataka', 13.035900, 77.597000),
(10, 'FarmFresh Agro Peenya', 'Industrial Area, Peenya, Bengaluru, Karnataka', 13.028200, 77.515200),
(11, 'Green Harvest Marathahalli', 'Outer Ring Rd, Marathahalli, Bengaluru, Karnataka', 12.956700, 77.701100),
(12, 'KrishiKendra Banashankari', 'Ring Rd, Banashankari, Bengaluru, Karnataka', 12.916600, 77.566900),
(13, 'Harvest House RT Nagar', 'RT Nagar Main Rd, Bengaluru, Karnataka', 13.021600, 77.594600),
(14, 'AgroVision BTM Layout', 'BTM 2nd Stage, Bengaluru, Karnataka', 12.916300, 77.610100),
(15, 'Farmers Choice Malleshwaram', '8th Cross Rd, Malleshwaram, Bengaluru, Karnataka', 13.003600, 77.569300);

-- --------------------------------------------------------

--
-- Table structure for table `store_pesticides`
--

CREATE TABLE `store_pesticides` (
  `id` int(11) NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `pesticide_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_pesticides`
--

INSERT INTO `store_pesticides` (`id`, `store_id`, `pesticide_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 14),
(8, 8, 14),
(9, 9, 14),
(10, 10, 14),
(11, 11, 14),
(12, 12, 14),
(13, 5, 9),
(14, 6, 9),
(15, 7, 9),
(16, 8, 9),
(17, 9, 9),
(18, 10, 9),
(19, 3, 4),
(20, 5, 4),
(21, 7, 4),
(22, 9, 4),
(23, 11, 4),
(24, 13, 4),
(25, 2, 19),
(26, 4, 19),
(27, 6, 19),
(28, 8, 19),
(29, 10, 19),
(30, 12, 19),
(31, 2, 2),
(32, 4, 2),
(33, 6, 2),
(34, 8, 2),
(35, 5, 3),
(36, 9, 3),
(37, 12, 3),
(38, 3, 5),
(39, 5, 5),
(40, 11, 5),
(41, 14, 5),
(42, 1, 8),
(43, 7, 8),
(44, 10, 8),
(45, 13, 8),
(46, 2, 11),
(47, 4, 11),
(48, 8, 11),
(49, 1, 12),
(50, 9, 12),
(51, 14, 12),
(52, 15, 12),
(53, 5, 16),
(54, 8, 16),
(55, 10, 16),
(56, 4, 17),
(57, 11, 17),
(58, 15, 17),
(59, 3, 18),
(60, 6, 18),
(61, 9, 18),
(62, 12, 18),
(63, 7, 20),
(64, 9, 20),
(65, 11, 20),
(66, 13, 20),
(67, 2, 21),
(68, 10, 21),
(69, 13, 21),
(70, 1, 22),
(71, 8, 22),
(72, 14, 22),
(73, 2, 23),
(74, 5, 23),
(75, 12, 23),
(76, 6, 24),
(77, 9, 24),
(78, 13, 24),
(79, 15, 24),
(80, 1, 25),
(81, 3, 25),
(82, 7, 25),
(83, 10, 25),
(84, 1, 1),
(85, 1, 2),
(86, 1, 6),
(87, 1, 8),
(88, 2, 3),
(89, 2, 4),
(90, 2, 7),
(91, 2, 9),
(92, 2, 17),
(93, 3, 5),
(94, 3, 8),
(95, 3, 9),
(96, 3, 10),
(97, 3, 15),
(98, 4, 1),
(99, 4, 3),
(100, 4, 10),
(101, 4, 11),
(102, 4, 12),
(103, 4, 16),
(104, 1, 1),
(105, 1, 2),
(106, 1, 6),
(107, 1, 8),
(108, 2, 3),
(109, 2, 4),
(110, 2, 7),
(111, 2, 9),
(112, 2, 17),
(113, 3, 5),
(114, 3, 8),
(115, 3, 9),
(116, 3, 10),
(117, 3, 15),
(118, 4, 1),
(119, 4, 3),
(120, 4, 10),
(121, 4, 11),
(122, 4, 12),
(123, 4, 16),
(124, 1, 1),
(125, 1, 2),
(126, 1, 6),
(127, 1, 8),
(128, 2, 3),
(129, 2, 4),
(130, 2, 7),
(131, 2, 9),
(132, 2, 17),
(133, 3, 5),
(134, 3, 8),
(135, 3, 9),
(136, 3, 10),
(137, 3, 15),
(138, 4, 1),
(139, 4, 3),
(140, 4, 10),
(141, 4, 11),
(142, 4, 12),
(143, 4, 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pesticides`
--
ALTER TABLE `pesticides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_pesticides`
--
ALTER TABLE `store_pesticides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_id` (`store_id`),
  ADD KEY `pesticide_id` (`pesticide_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pesticides`
--
ALTER TABLE `pesticides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `store_pesticides`
--
ALTER TABLE `store_pesticides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `store_pesticides`
--
ALTER TABLE `store_pesticides`
  ADD CONSTRAINT `store_pesticides_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `store_pesticides_ibfk_2` FOREIGN KEY (`pesticide_id`) REFERENCES `pesticides` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
