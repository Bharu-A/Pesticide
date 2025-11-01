-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+00:00';
/*!40101 SET NAMES utf8mb4 */;

-- Database: `pesticide_locator`

-- --------------------------------------------------------

CREATE TABLE `pesticides` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `crop` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` enum('Branded','Non-Branded') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `map_link` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `stores`
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('Sl No.', 'Shop name', 'Address', 'Phone number');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('1', 'Sri ananth agro agencies', 'Jevargi - Chamarajanagar Rd, P.W.D. Camp, Sindhanur, Karnataka 584128', '099801 55505');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('2', 'SRI SIDDIGANAPATI ENTERPRISES', 'Kunnatagi Camp, SINDHANUR, Karnataka 584128', '099726 84140');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('3', 'Jai Kisan Engineering', 'Opp. Dollars Colony, P.W.D. Camp, Sindhanur, Karnataka 584128', '098453 45233');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('4', 'Sreedhar Agro Agencies', 'Shop no.55, New APMC Yard, Kushtagi Rd, Sindhanur, Karnataka 584128', '094481 23310');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('5', 'Kartikeya Traders', '44, hedigibal camp, kolbal post, Sindhanur, SINDHANUR, Karnataka 584128', '7483509603');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('6', 'SRI DODDABASAVESHWARA TRADERS', 'QP8Q+92J, Kushtagi Rd, Sindhanur, Karnataka 584128', '085352 22677');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('7', 'SRI VASANTHM TRADERS', 'R.G. Road, JAWALAGERA-584143 Tq:Sindhanur, 584143', '8217369981');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('8', 'DSR Corporation', 'QP9W+FX6, Sindhanur, Karnataka 584128', '085352 23388');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('9', 'Srishaila Mallekarjuna Agro Agences', 'Shop No 3, near by Nataraj Colony, Nataraj Colony, Raichur, Sindhanur, Karnataka 584128', '098808 73765');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('10', 'Sri Raghavendra Agro Mart', 'Basavana Bhavi Chowk, 12-1-81/4, Gunj Rd, Jalal Nagar, Raichur, Karnataka 584102', '090084 90059');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('11', 'SAGAR ENTERPRISES', 'Basavana, Jodi Bhavi Road, Raichur, Karnataka 584102', 'nan');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('12', 'NAMMA GROMOR RAICHUR', 'Haji Colony, Raichur, Karnataka 584102', '097428 22792');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('13', 'Sri Swamy Agro Agencies', 'Sri Swamy Agro Agencies, Basavan Bavi Circle, Karnataka 584102', '074062 82835');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('14', 'Indian Agro Centre', 'Shop No: Poornima, Thaetre Complex, Gunj Main Rd, Jalal Nagar, Raichur, Karnataka 584102', '079754 60974');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('15', 'VEENA ENTEPRISES', ' Rajendra Gunj, Raichur, Karnataka 584102', '094480 22655');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('16', 'Pruthvi Agro Seeds', '6965+RH9, Gunj Main Rd, Jalal Nagar, Basavanabhavi, Raichur, Karnataka 584102', '098864 52174');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('17', 'Agroes Services Pvt Ltd,', 'Shop No.3, 9-20-59, Gadwal dharur road, Ganjipet Colony, Raichur, Karnataka 584102', '099000 92020');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('18', 'Sri Channabasaveshwara Agro Agency', 'Karadakal, main road, Lingsugur, Karnataka 584122', '097390 73989');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('19', 'A One Agritech', 'Lingsugur, Karnataka 584122', '090086 67844');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('20', 'Raitamitra Agro Traders', '5G4C+HFG, Lingsugur, Karnataka 584122', 'nan');
INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES ('21', 'SHREE SANGAMESHWARA KRUSHI KENDRA KALAPUR', 'Road, opposite Bus Stand, Lingsugur, Kalapur, Karnataka 584122', '099020 57390');
COMMIT;