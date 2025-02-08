-- MySQL dump 10.13  Distrib 9.2.0, for macos15.2 (arm64)
--
-- Host: localhost    Database: yoga_app
-- ------------------------------------------------------
-- Server version	9.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `class_id` int NOT NULL,
  `booked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`class_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (82,5,4,'2025-01-22 22:02:42'),(87,5,5,'2025-02-01 07:31:24'),(92,2,5,'2025-02-02 17:13:18'),(93,2,4,'2025-02-02 17:13:18');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `max_participants` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (4,'Yoga Basics','Basic yoga class for beginners','2025-01-20 10:00:00',10,'2025-01-17 19:36:56'),(5,'Advanced Yoga','Advanced techniques for experienced practitioners','2025-01-21 15:00:00',5,'2025-01-17 19:36:56');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment_likes`
--

DROP TABLE IF EXISTS `comment_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment_likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `comment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_comment_user` (`comment_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment_likes`
--

LOCK TABLES `comment_likes` WRITE;
/*!40000 ALTER TABLE `comment_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (13,1,5,'rrrr','2025-02-02 01:23:08');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (33,1,'You have successfully booked the class: 4','2025-01-17 19:54:53'),(34,1,'You have canceled your booking for the class: 4','2025-01-17 19:55:05'),(35,1,'You have successfully booked the class: 4','2025-01-17 19:55:13'),(36,1,'You have canceled your booking for the class: 4','2025-01-17 20:22:16'),(37,1,'You have successfully booked the class: 4','2025-01-17 20:24:15'),(38,1,'You have canceled your booking for the class: 4','2025-01-17 20:24:16'),(39,2,'You have successfully booked the class: 4','2025-01-17 22:38:50'),(40,2,'You have canceled your booking for the class: 4','2025-01-17 22:38:56'),(41,2,'You have successfully booked the class: 4','2025-01-17 22:38:57'),(42,2,'You have canceled your booking for the class: 4','2025-01-17 22:39:00'),(43,2,'You have successfully booked the class: 4','2025-01-17 22:39:00'),(44,2,'You have successfully booked the class: 4','2025-01-22 13:45:35'),(45,2,'You have successfully booked the class: 4','2025-01-22 13:53:26'),(46,2,'You have successfully booked the class: 4','2025-01-22 13:53:31'),(47,2,'You have successfully booked the class: 5','2025-01-22 13:53:42'),(48,2,'You have successfully booked the class: 4','2025-01-22 13:58:24'),(49,2,'You have successfully booked the class: 4','2025-01-22 14:00:54'),(50,2,'You have successfully booked the class: 4','2025-01-22 14:01:18'),(51,2,'You have successfully booked the class: 4','2025-01-22 14:01:24'),(52,2,'You have successfully booked the class: 4','2025-01-22 14:02:07'),(53,2,'You have successfully booked the class: 4','2025-01-22 14:10:17'),(54,2,'You have successfully booked the class: 4','2025-01-22 14:10:22'),(55,2,'You have successfully booked the class: 4','2025-01-22 14:10:27'),(56,2,'You have successfully booked the class: 4','2025-01-22 14:15:25'),(57,2,'You have successfully booked the class: 5','2025-01-22 14:15:38'),(58,2,'You have successfully booked the class: 4','2025-01-22 14:19:08'),(59,2,'You have successfully booked the class: 4','2025-01-22 14:19:11'),(60,2,'You have successfully booked the class: 5','2025-01-22 14:19:22'),(61,2,'You have successfully booked the class: 4','2025-01-22 14:22:31'),(62,2,'You have successfully booked the class: 4','2025-01-22 17:49:00'),(63,2,'You have successfully booked the class: 4','2025-01-22 18:55:31'),(64,2,'You have successfully booked the class: 5','2025-01-22 18:55:35'),(65,2,'You have successfully booked the class: 4','2025-01-22 19:21:09'),(66,2,'You have successfully booked the class: 5','2025-01-22 19:21:13'),(67,2,'You have successfully booked the class: 4','2025-01-22 21:22:11'),(68,5,'You have successfully booked the class: 4','2025-01-22 21:58:24'),(69,5,'You have successfully booked the class: 4','2025-01-22 22:02:42'),(70,2,'You have successfully booked the class: 4','2025-01-26 20:55:26'),(71,2,'You have successfully booked the class: 4','2025-01-26 20:55:50'),(72,2,'You have successfully booked the class: 5','2025-01-31 01:51:26'),(73,2,'You have successfully booked the class: 5','2025-01-31 01:51:40'),(74,5,'You have successfully booked the class: 5','2025-02-01 07:31:24'),(75,6,'You have successfully booked the class: 4','2025-02-02 14:45:53'),(76,6,'You have successfully booked the class: 4','2025-02-02 17:06:58'),(77,2,'You have successfully booked the class: 4','2025-02-02 17:13:04'),(78,2,'You have successfully booked the class: 5','2025-02-02 17:13:09'),(79,2,'You have successfully booked the class: 5','2025-02-02 17:13:18'),(80,2,'You have successfully booked the class: 4','2025-02-02 17:13:18');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_likes`
--

DROP TABLE IF EXISTS `post_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_post_user` (`post_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_likes`
--

LOCK TABLES `post_likes` WRITE;
/*!40000 ALTER TABLE `post_likes` DISABLE KEYS */;
INSERT INTO `post_likes` VALUES (9,42,5,'2025-02-02 00:58:08'),(12,36,2,'2025-02-03 17:55:56'),(13,28,2,'2025-02-03 17:55:57'),(25,42,2,'2025-02-04 21:04:59');
/*!40000 ALTER TABLE `post_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'Questo è il primo post di esempio','2025-01-26 21:21:09'),(2,1,'Secondo post di esempio per testare la bacheca','2025-01-26 21:21:09'),(3,1,'Questo è un nuovo post aggiunto dal frontend','2025-01-26 21:28:39'),(4,1,'Test post','2025-01-27 13:45:01'),(14,5,'aa','2025-01-30 13:26:45'),(15,5,'dddd','2025-01-30 23:37:43'),(16,5,'dd','2025-01-31 00:02:53'),(21,5,'sss','2025-01-31 22:22:33'),(22,5,'wwwwww','2025-01-31 23:28:09'),(23,5,'pin8','2025-01-31 23:43:00'),(24,5,'beppiii','2025-01-31 23:48:06'),(25,5,'aaaa','2025-01-31 23:51:21'),(26,5,'1234','2025-02-01 00:23:39'),(27,5,'prov<','2025-02-01 00:31:14'),(28,5,'provalo','2025-02-01 00:32:30'),(36,5,'porco','2025-02-01 08:26:13'),(42,5,'sssss','2025-02-02 00:34:48');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reactions`
--

DROP TABLE IF EXISTS `reactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `reaction_type` enum('like','dislike') NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reactions_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reactions`
--

LOCK TABLES `reactions` WRITE;
/*!40000 ALTER TABLE `reactions` DISABLE KEYS */;
INSERT INTO `reactions` VALUES (6,2,2,'like','2025-01-29 13:18:58'),(21,1,2,'like','2025-01-31 14:10:26'),(22,14,2,'like','2025-01-31 14:10:30');
/*!40000 ALTER TABLE `reactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (2,3,'a039388dcae644245a996ed8f1470bfbe668bab1d5ad0338fa842112272623d2','2025-01-16 17:30:38'),(3,2,'786817cf90c7769b7fdf03c8c0fe7cee585388b4f07400bd77a9b58bfd824673','2025-01-16 17:42:45'),(4,2,'dfce92232fcd3a1280b01af8753b7289ea554173fda89bcb68e38bcc7c03d427','2025-01-16 17:43:13'),(5,2,'2d8dd186045985f33569b6ccd6b08c73f28b2c74c74f5fde852ed90af92fdb5b','2025-01-16 23:25:07'),(6,2,'c44030f4771114e76989b13899010b696e6079f3d6a1bab792a10fee501d9e4f','2025-01-16 23:25:21'),(7,2,'2bb30fdda6f78b641141692f5ac2490e177d185dfafa6c2f9d18be4c5b083c7b','2025-01-16 23:28:52'),(8,2,'71c061fa088826bf345c68ed00ba3c0eafa60139a0f53a76f5c1a7eda8f0ee34','2025-01-16 23:31:57'),(9,2,'4154190e4ce4f232e89bbc9a613048b1714ba24a63c3a36874743751176eb133','2025-01-16 23:32:36'),(10,2,'4273cbc4ea4df73feed950d7d27a5880a20042bad23f6531453f98bab6465731','2025-01-16 23:33:02'),(11,2,'725dd6f38688c525cfd84ceec68157704b79123f8109861b41a059b747a50824','2025-01-16 23:52:00'),(12,2,'686cdf0a8aebd3eb47f19e0aa9b55988a574974ba17cfac5399b2a67add21fe1','2025-01-16 23:55:14'),(13,2,'80717527eb2c7cf4aafe36b6436e577e5e67472521ca379d58b18cc051ab7624','2025-01-17 00:12:43'),(14,2,'52d99fa42ecc082cda494cf4fa1ae42d9e636b1bfd96699cd4c22c93f162a05d','2025-01-17 00:13:02'),(15,2,'abdfdc274b21fa2e6a0d2b87d258b3169be6bb5750b3aa1ecbe1c42fa7238617','2025-01-17 00:25:08'),(16,2,'b1f723f533c8cc8f347254d4344dd9355cafe46673556fcef56e1b213d634e33','2025-01-17 00:31:41'),(17,2,'43c2e1e02638b712bfb9d964c94561467863689edac8ce8943818ed28f2de0e0','2025-01-17 17:37:55'),(18,2,'e88abbe38b88de5144be3dee181e2f55d09a1baed58252f03291b7e5702da60d','2025-01-17 18:07:25'),(19,2,'119e56d8d8c9d3c3ae2fcf6a79c334a11a622acdc7dffdaa13ede5c31c3eef61','2025-01-17 18:09:59'),(20,2,'38faee8e3b4437e38c571c2aba361f9afbe7fca0d57bc3ba488a585842813370','2025-01-17 18:10:39'),(21,2,'3fc54b0f7a0ec488e54a3593c9e1879e37a0bcf532ce4295fbb8d7e3992db6d9','2025-01-17 18:15:22'),(22,2,'f23db7d38f41fb8b643738125d63fae8a4fa455e3a8c583dda345e29a7593af1','2025-01-17 18:26:40'),(23,2,'7e570280a91e382930adb93a559d1bec353de8f5ccb71df5d132371e638ec3cc','2025-01-17 18:26:55'),(24,2,'0187fa36f438a70cc98145cb2f061154e97c003a91170d9be49d4732c53bc7f0','2025-01-17 18:34:50'),(25,4,'20745e05937beeb4df24450757230ae03eca3e459ef15bca49dde2eaad15c758','2025-01-17 18:55:19');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Mario Rossi','mario.rossi@example.com','$2y$10$PLVXY4lr0l8DhJVhR.laGuZV/pSipdr7AphbkVQO.L772eg9AY2Hi','2025-01-15 21:52:24'),(2,'Ales','ales1@prova.it','$2y$10$FOxmKSED4bI1egRDORFKFeMDGUMy4qIXjAUcEqmmz18nGiQJGV9p6','2025-01-16 14:15:52'),(3,'pinetto','pinetto@pinetto.it','$2y$10$GHEKIkTqaInUIegpAa/tCOgiVuMIY5DEq2lHE4YRqVidlS/n2PsSC','2025-01-16 17:30:14'),(4,'rana','rana@rana.it','$2y$10$uBX3PFjz6wjZtKUUHBgLnO6YZLnI.hpTplKTKwaOe6WwxmUUj/AFa','2025-01-17 18:52:54'),(5,'Silvi','silvia@hotm.it','$2y$10$/BCo3lq4Zgex02lvK8W3UufMAjY0HLtO5xKfiRM9gv4SIFdiwFUia','2025-01-22 21:57:58'),(6,'Silvia','silvia.silvi@jjjj.it','$2y$10$/T1iBmu3RdXaI3MAnNSJ0O0QZCFlAtQQBtcrCRN.xxZ6I3bwD7hCu','2025-02-02 14:44:54');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-02-08 16:34:42
