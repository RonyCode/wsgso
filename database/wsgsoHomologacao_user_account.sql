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
-- Table structure for table `user_account`
--

DROP TABLE IF EXISTS `user_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(220) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` text,
  `excluded` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_account`
--

LOCK TABLES `user_account` WRITE;
/*!40000 ALTER TABLE `user_account` DISABLE KEYS */;
INSERT INTO `user_account` VALUES (1,'rony','ronysedddaasdeedeeesdddadasddadpc@outlook.com','01680562169','11111111111',NULL,0),(2,'rony','ronysedddaasdeedeeesdddadasddadpc@outlook.com','01680562169','11111111111',NULL,0),(3,'rony','ronysddadedddaasdeedeeesdddadasddadpc@outlook.com','01680562169','11111111111',NULL,0),(4,'rony','ronysdadaddadedddaasdeedeeesdddadasddadpc@outlook.com','01680562169','11111111111',NULL,0),(5,'rony','sdasd@outlook.com','01680562169','11111111111',NULL,0),(6,'rony','sdadasd@outlook.com','01680562169','11111111111',NULL,0),(7,'rony','sdadddasd@outlook.com','01680562169','11111111111',NULL,0),(8,'rony','sdaddaaddasd@outlook.com','01680562169','11111111111',NULL,0),(9,'rony','sdaddadasdaaddasd@outlook.com','01680562169','11111111111',NULL,0),(10,'rony','sdaddadaddsdaaddasd@outlook.com','01680562169','11111111111',NULL,0),(11,'rony','dadas@outlook.com','01680562169','11111111111',NULL,0),(12,'rony','dadads@outlook.com','01680562169','11111111111',NULL,0),(13,'rony','dadasaads@outlook.com','01680562169','11111111111',NULL,0),(14,'rony','dadaseeaads@outlook.com','01680562169','11111111111',NULL,0),(15,'rony','aaaaddd@dadoutlook.com','01680562169','11111111111',NULL,0),(16,'rony','aaarweaddd@dadoutlook.com','01680562169','11111111111',NULL,0),(17,'rony','aaattrweaddd@dadoutlook.com','01680562169','11111111111',NULL,0),(18,'rony','aarerattrweaddd@dadoutlook.com','01680562169','11111111111',NULL,0),(19,'rony','blasdabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(20,'rony','blaesdabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(28,'rony','bldasaesdabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(29,'rony','bldasaesdddabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(30,'rony','bldaddsaesdddabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(31,'rony','bldaddddsaesdddabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(32,'rony','bldaddddddsaesdddabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(33,'rony','bldadddsddddsaesdddabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(34,'rony','bldadddddsddddsaesdddabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(35,'rony','bldaddsddddsddddsaesdddabeeela@dadoutlook.com','01680562169','11111111111',NULL,0),(36,'rony','tee@dadoutlook.com','01680562169','11111111111',NULL,0),(37,'rony','teste@dadoutlook.com','01680562169','11111111111',NULL,0),(38,'rony','testsde@dadoutlook.com','01680562169','11111111111',NULL,0),(39,'rony','testasde@dadoutlook.com','01680562169','11111111111',NULL,0),(40,'rony','afse@dadoutlook.com','01680562169','11111111111',NULL,0),(41,'rony','ronypc@outlodsok.com','01680562169','11111111111',NULL,0),(42,'asdasdasd','ronyanderssdasdasdonpc@gmail.com','01680562169','11111111111',NULL,0),(43,'asdasdasd','ronyanderssdsdaasdasdonpc@gmail.com','01680562169','11111111111',NULL,0),(44,'asdasdas','ronyandsdadasdersonpc@gmail.com','01680562169','11111111111',NULL,0),(45,'asdasdas','ronyandaaasdadasdersonpc@gmail.com','01680562169','11111111111',NULL,0),(46,'gisele','gisele_p_@hotmail.com','01680563130','63999279522',NULL,0),(47,'rony anderson ','ronyanderaaaasonpc@gmail.com','01680562169','63988888888',NULL,0);
/*!40000 ALTER TABLE `user_account` ENABLE KEYS */;
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
