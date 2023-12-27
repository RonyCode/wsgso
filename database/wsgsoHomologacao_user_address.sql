-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: localhost    Database: wsgsoHomologacao
-- ------------------------------------------------------
-- Server version	8.0.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `user_address`
--

DROP TABLE IF EXISTS `user_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_address` (
  `id` int NOT NULL AUTO_INCREMENT,
  `address` varchar(45) DEFAULT NULL,
  `number` varchar(20) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `complement` text,
  `district` varchar(220) DEFAULT NULL,
  `city` varchar(120) DEFAULT NULL,
  `state` varchar(120) DEFAULT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `excluded` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_address`
--

LOCK TABLES `user_address` WRITE;
/*!40000 ALTER TABLE `user_address` DISABLE KEYS */;
INSERT INTO `user_address` VALUES (1,'Avenida D','18','77060-046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(2,'Avenida D','18','77060-046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(3,'Avenida D','18','77060-046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(4,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(5,'Avenida D','18','77060-046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(6,'Avenida D','18','77060-046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(7,'Avenida D','18','77060-046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(8,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(9,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(10,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(11,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(12,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(13,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(14,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(15,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(16,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(17,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(18,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(19,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(20,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(21,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(29,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(30,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(31,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(32,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(33,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(34,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(35,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(36,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(37,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(38,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(39,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(40,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(41,'Avenida D','18','77060046','Avenida D','Jardim Aureny IV','Palmas','TO','TO',0),(42,'Avenida D','18','77060046','','Jardim Aureny IV','Palmas','TO','TO',0),(43,'Avenida D','25','77060046','','Jardim Aureny IV','Palmas','TO','TO',0),(44,'Avenida D','25','77060046','','Jardim Aureny IV','Palmas','TO','TO',0),(45,'Avenida D','55','77060046','','Jardim Aureny IV','Palmas','TO','TO',0),(46,'Avenida D','55','77060046','','Jardim Aureny IV','Palmas','TO','TO',0),(47,'Avenida D','18','77060046','AD 41 AVENIDA D','Jardim Aureny IV','Palmas','TO','TO',0),(48,'Rua 2','18','77060044','','Jardim Aureny IV','Palmas','TO','TO',0);
/*!40000 ALTER TABLE `user_address` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-21 18:35:52
