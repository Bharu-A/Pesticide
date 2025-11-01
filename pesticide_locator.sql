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

INSERT INTO `stores` (`name`, `address`, `phone_number`, `map_link`) VALUES
('Sri ananth agro agencies', 'Jevargi - Chamarajanagar Rd, P.W.D. Camp, Sindhanur, Karnataka 584128', '099801 55505', 'https://share.google/w28PjgyFXOOHCYT5k'),
('SRI SIDDIGANAPATI ENTERPRISES', 'Kunnatagi Camp, SINDHANUR, Karnataka 584128', '099726 84140', 'https://share.google/tRqkECmqgWKmywIsF'),
('Jai Kisan Engineering', 'Opp. Dollars Colony, P.W.D. Camp, Sindhanur, Karnataka 584128', '098453 45233', 'https://share.google/RNInJnGD0UfmMWe5n'),
('Sreedhar Agro Agencies', 'Shop no.55, New APMC Yard, Kushtagi Rd, Sindhanur, Karnataka 584128', '094481 23310', 'https://share.google/YF8P99S3CAJn0JcZe'),
('Kartikeya Traders', '44, hedigibal camp, kolbal post, Sindhanur, SINDHANUR, Karnataka 584128', '7483509603', 'https://share.google/kizdGJQN17sxeFQDg'),
('SRI DODDABASAVESHWARA TRADERS', 'QP8Q+92J, Kushtagi Rd, Sindhanur, Karnataka 584128', '085352 22677', 'https://share.google/KsaGEHv2CE9WpMW3g'),
('SRI VASANTHM TRADERS', 'R.G. Road, JAWALAGERA-584143 Tq:Sindhanur, 584143', '8217369981', 'https://share.google/G95HRr7ueNCIcDTkC'),
('DSR Corporation', 'QP9W+FX6, Sindhanur, Karnataka 584128', '085352 23388', 'https://share.google/vOlXBz7sZOo4kn2NS'),
('Srishaila Mallekarjuna Agro Agences', 'Shop No 3, near by Nataraj Colony, Nataraj Colony, Raichur, Sindhanur, Karnataka 584128', '098808 73765', 'https://share.google/9vHBzfMULTuaR1fWF'),
('Sri Raghavendra Agro Mart', 'Basavana Bhavi Chowk, 12-1-81/4, Gunj Rd, Jalal Nagar, Raichur, Karnataka 584102', '090084 90059', 'https://share.google/W2u9IkfDKgoYjpVKG'),
('SAGAR ENTERPRISES', 'Basavana, Jodi Bhavi Road, Raichur, Karnataka 584102', NULL, 'https://share.google/uxq4nLxNg1btQ33RW'),
('NAMMA GROMOR RAICHUR', 'Haji Colony, Raichur, Karnataka 584102', '097428 22792', 'https://share.google/IlBdUzIXVtep4ENF3'),
('Sri Swamy Agro Agencies', 'Sri Swamy Agro Agencies, Basavan Bavi Circle, Karnataka 584102', '074062 82835', 'https://share.google/OyoWk7rYFr0BfThYd'),
('Indian Agro Centre', 'Shop No: Poornima, Thaetre Complex, Gunj Main Rd, Jalal Nagar, Raichur, Karnataka 584102', '079754 60974', 'https://share.google/YkP2D3sQHPnX9szC4'),
('VEENA ENTEPRISES', 'Rajendra Gunj, Raichur, Karnataka 584102', '094480 22655', 'https://share.google/8hyEZeRq2IRmqwzN1'),
('Pruthvi Agro Seeds', '6965+RH9, Gunj Main Rd, Jalal Nagar, Basavanabhavi, Raichur, Karnataka 584102', '098864 52174', 'https://share.google/FpGkcM8V3VVQTmXBi'),
('Agroes Services Pvt Ltd', 'Shop No.3, 9-20-59, Gadwal dharur road, Ganjipet Colony, Raichur, Karnataka 584102', '099000 92020', 'https://share.google/wgoDZ5isrEeVlvHLi'),
('Sri Channabasaveshwara Agro Agency', 'Karadakal, main road, Lingsugur, Karnataka 584122', '097390 73989', 'https://share.google/8TadMRgdG5NBiGlKr'),
('A One Agritech', 'Lingsugur, Karnataka 584122', '090086 67844', 'https://share.google/k15Y4RP1WmViefG4D'),
('Raitamitra Agro Traders', '5G4C+HFG, Lingsugur, Karnataka 584122', NULL, 'https://share.google/as8JkuQttz5vh2WDq'),
('SHREE SANGAMESHWARA KRUSHI KENDRA KALAPUR', 'Road, opposite Bus Stand, Lingsugur, Kalapur, Karnataka 584122', '099020 57390', 'https://share.google/3jrOa5jfX7OAL4kLy');
COMMIT;