/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: clothing_store
-- ------------------------------------------------------
-- Server version	12.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `categories` VALUES
(1,'Men','men','2026-01-29 23:36:02'),
(2,'Women','women','2026-01-29 23:36:02'),
(3,'Kids','kids','2026-01-29 23:36:02'),
(4,'Accessories','accessories','2026-01-29 23:36:02');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `order_items` VALUES
(1,1,9,2,55.00),
(2,2,1,5,299.00),
(3,3,1,1,299.00),
(4,4,11,1,325.00);
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `shipping_name` varchar(100) NOT NULL,
  `shipping_address` text NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_zip` varchar(20) NOT NULL,
  `shipping_phone` varchar(15) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `orders` VALUES
(1,2,120.00,'pending','rina','wgatebegr','kath','12312','1212121212','2026-01-30 00:39:26'),
(2,3,1495.00,'pending','Prajina','testadress','Kathmandu','0951234','0123456789','2026-01-31 20:22:18'),
(3,1,299.00,'pending','Admin','testadress','Kathmandu','0951234','1234567890','2026-02-02 20:08:10'),
(4,4,325.00,'pending','Prajina','Kathmandu','baniyatar','04567','0123456789','2026-02-03 03:16:19');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `products` VALUES
(1,1,'Classic Wool Blazer','Premium wool blazer with modern tailoring. Perfect for both formal and casual occasions.',299.00,'https://i.pinimg.com/1200x/0c/26/0a/0c260affbba025937b79b3a1fcbc9400.jpg',1,19,'2026-01-29 23:36:02'),
(2,1,'Slim Fit Chinos','Comfortable stretch chinos in a contemporary slim fit.',89.00,'https://i.pinimg.com/1200x/99/2a/76/992a760c8a84d572a4f667e0404830bf.jpg',1,50,'2026-01-29 23:36:02'),
(3,1,'Cotton Oxford Shirt','Crisp cotton oxford shirt with button-down collar.',75.00,'https://i.pinimg.com/1200x/a6/bb/de/a6bbdea8b393c5d7563a9d835ec14442.jpg',0,40,'2026-01-29 23:36:02'),
(4,2,'Cashmere Sweater','Luxuriously soft pure cashmere sweater in neutral tones.',245.00,'https://i.pinimg.com/736x/34/76/ab/3476abbbe9576b70f86aac421829255a.jpg',1,30,'2026-01-29 23:36:02'),
(5,2,'Wide Leg Trousers','Elegant wide-leg trousers with high waist design.',145.00,'https://i.pinimg.com/736x/cb/bb/73/cbbb73d72cc0be8ff741f087dcfd7c4f.jpg',1,35,'2026-01-29 23:36:02'),
(6,2,'Silk Blouse','Flowing silk blouse with delicate details.',165.00,'https://i.pinimg.com/1200x/c5/4d/4e/c54d4ec4ec31dfd9eee8cde01a0fde66.jpg',0,20,'2026-01-29 23:36:02'),
(7,3,'Kids Denim Jacket','Stylish denim jacket for the little fashionistas.',65.00,'https://i.pinimg.com/1200x/0c/90/d6/0c90d660783ba3ccb3038568a4cece4d.jpg',1,45,'2026-01-29 23:36:02'),
(8,3,'Cotton T-Shirt Set','Pack of 3 comfortable cotton t-shirts.',45.00,'https://i.pinimg.com/1200x/12/76/f2/1276f20fcdc8ad4b50b0db8b05928fc0.jpg',0,60,'2026-01-29 23:36:02'),
(9,4,'Leather Belt','Genuine leather belt with brushed metal buckle.',55.00,'https://i.pinimg.com/1200x/3a/91/14/3a9114fae5a0dee47cda8eb513076772.jpg',1,68,'2026-01-29 23:36:02'),
(10,4,'Wool Scarf','Warm wool scarf in classic patterns.',85.00,'https://i.pinimg.com/1200x/75/36/75/75367561ea34a0a37e0b7c03cbd841aa.jpg',0,40,'2026-01-29 23:36:02'),
(11,4,'Leather Handbag','Premium leather handbag with gold hardware.',325.00,'https://i.pinimg.com/736x/a4/42/eb/a442eb13e6bc7a658aabfaf226178286.jpg',1,14,'2026-01-29 23:36:02'),
(12,4,'Designer Sunglasses','Vintage-inspired acetate sunglasses.',195.00,'https://i.pinimg.com/1200x/07/48/d7/0748d77a127f8454b45813e363b06493.jpg',0,25,'2026-01-29 23:36:02');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Admin','admin@elegance.com','1234567890','$2y$12$wUaiA2A2KmzKIZ35q9i5zuxDWyJMR/I2iahifULploryz6Td9Xpn6','admin','2026-01-29 23:36:02'),
(2,'rina','rina@gmail.com','1212121212','$2y$12$PHV4aIeswqNBPOU9cFk35uwVD1YL1YEcdu5MWl3TKVnFXfJDSVThG','customer','2026-01-30 00:38:49'),
(3,'Prajina','prajina@gmail.com','0123456789','$2y$12$r5rPE9cUQf.pzQ394thaLeZVwU48.jrIs./JY44p8m1wMfmZ6Yw92','customer','2026-01-31 20:21:36'),
(4,'Prajina','prajj8@gmail.com','0123456789','$2y$12$rFWduZlwKinCEGu1osNmIO0M8oJHXuJjmA8fAahT1YYFCLr5MLQuW','customer','2026-02-03 03:15:09');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-02-03 13:12:19
