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
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_user_auth` int DEFAULT NULL,
  `id_account` int DEFAULT NULL,
  `id_address` int DEFAULT NULL,
  `id_profile` int DEFAULT NULL,
  `excluded` varchar(45) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_user_id_account_idx` (`id_account`),
  KEY `fk_user_id_address_idx` (`id_address`),
  KEY `fk_user_id_profile_idx` (`id_profile`),
  KEY `fk_user_id_user_auth_idx` (`id_user_auth`),
  CONSTRAINT `fk_user_id_account` FOREIGN KEY (`id_account`) REFERENCES `user_account` (`id`),
  CONSTRAINT `fk_user_id_address` FOREIGN KEY (`id_address`) REFERENCES `user_address` (`id`),
  CONSTRAINT `fk_user_id_profile` FOREIGN KEY (`id_profile`) REFERENCES `user_profile` (`id`),
  CONSTRAINT `fk_user_id_user_auth` FOREIGN KEY (`id_user_auth`) REFERENCES `user_auth` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (4,51,28,29,26,'0'),(5,52,29,30,27,'0'),(6,53,30,31,28,'0'),(7,54,31,32,29,'0'),(8,55,32,33,30,'0'),(9,56,33,34,31,'0'),(10,57,34,35,32,'0'),(11,58,35,36,33,'0'),(12,59,36,37,34,'0'),(13,60,37,38,35,'0'),(14,61,38,39,36,'0'),(15,62,39,40,37,'0'),(16,63,40,41,38,'0'),(17,64,41,42,39,'0'),(18,65,42,43,40,'0'),(19,66,43,44,41,'0'),(20,67,44,45,42,'0'),(21,68,45,46,43,'0'),(22,69,46,47,44,'0'),(23,70,47,48,45,'0');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
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
