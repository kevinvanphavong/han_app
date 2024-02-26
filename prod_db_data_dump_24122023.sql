-- MySQL dump 10.13  Distrib 8.2.0, for macos14.0 (x86_64)
--
-- Host: oliadkuxrl9xdugh.chr7pe7iynqr.eu-west-1.rds.amazonaws.com    Database: tz4w1v0blblzzp3n
-- ------------------------------------------------------
-- Server version	8.0.33

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '';

--
-- Table structure for table `budget`
--

DROP TABLE IF EXISTS `budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `budget` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  `is_salary` boolean DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_73F2F77BA76ED395` (`user_id`),
  CONSTRAINT `FK_73F2F77BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget`
--

LOCK TABLES `budget` WRITE;
/*!40000 ALTER TABLE `budget` DISABLE KEYS */;
INSERT INTO `budget` VALUES (1,1,'Logement',400, null),(2,1,'Voiture',150, null),(3,1,'Sport',35, null),(4,1,'Developpement web',20, null),(5,1,'Manger',150, null),(6,1,'Plaisirs',150, null),(7,1,'Streaming',15, null),(8,1,'Internet/Mobile',20, null),(9,1,'Transports/Déplacements',30, null),(10,1,'Salaire',1600, null),(11,6,'Gastos',1000, null),(12,1,'Maman',200, null),(13,1,'Assurance/Santé',100, null),(14,1,'Nettoyage',20, null),(15,7,'NailGasm',150, null),(16,7,'courses',100, null),(17,7,'courses',100, null),(18,7,'sorties, food, shopping',100, null),(19,7,'courses food',100, null);
/*!40000 ALTER TABLE `budget` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `month`
--

DROP TABLE IF EXISTS `month`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `month` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `balance` double DEFAULT NULL,
  `total_amount_spent` double DEFAULT NULL,
  `total_amount_earned` double DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8EB61006A76ED395` (`user_id`),
  CONSTRAINT `FK_8EB61006A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `month`
--

LOCK TABLES `month` WRITE;
/*!40000 ALTER TABLE `month` DISABLE KEYS */;
INSERT INTO `month` VALUES (1,1,64.39,2646.78,2711.17,'2023-11-01'),(2,6,NULL,NULL,NULL,'2023-11-01'),(3,7,NULL,0,896,'2023-07-01'),(5,7,NULL,0,847,'2023-08-01'),(6,7,NULL,0,370,'2023-09-01'),(7,7,NULL,NULL,NULL,'2023-09-01'),(8,7,NULL,0,830,'2023-10-01'),(9,7,NULL,0,510,'2023-11-01'),(15,7,NULL,NULL,NULL,'2023-12-01'),(16,1,264.54,1971.16,2235.7,'2023-12-01'),(17,1,NULL,NULL,NULL,'2024-01-01');
/*!40000 ALTER TABLE `month` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `month_budget`
--

DROP TABLE IF EXISTS `month_budget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `month_budget` (
  `month_id` int NOT NULL,
  `budget_id` int NOT NULL,
  PRIMARY KEY (`month_id`,`budget_id`),
  KEY `IDX_78C61BE5A0CBDE4` (`month_id`),
  KEY `IDX_78C61BE536ABA6B8` (`budget_id`),
  CONSTRAINT `FK_78C61BE536ABA6B8` FOREIGN KEY (`budget_id`) REFERENCES `budget` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_78C61BE5A0CBDE4` FOREIGN KEY (`month_id`) REFERENCES `month` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `month_budget`
--

LOCK TABLES `month_budget` WRITE;
/*!40000 ALTER TABLE `month_budget` DISABLE KEYS */;
INSERT INTO `month_budget` VALUES (17,1),(17,2),(17,3),(17,4),(17,5),(17,6),(17,7),(17,8),(17,9),(17,10),(17,12),(17,13),(17,14);
/*!40000 ALTER TABLE `month_budget` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_id` int DEFAULT NULL,
  `budget_category_id` int DEFAULT NULL,
  `month_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_723705D1C54C8C93` (`type_id`),
  KEY `IDX_723705D1644CDBBD` (`budget_category_id`),
  KEY `IDX_723705D1A0CBDE4` (`month_id`),
  KEY `IDX_723705D1A76ED395` (`user_id`),
  CONSTRAINT `FK_723705D1644CDBBD` FOREIGN KEY (`budget_category_id`) REFERENCES `budget` (`id`),
  CONSTRAINT `FK_723705D1A0CBDE4` FOREIGN KEY (`month_id`) REFERENCES `month` (`id`),
  CONSTRAINT `FK_723705D1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_723705D1C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `transaction_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction`
--

LOCK TABLES `transaction` WRITE;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
INSERT INTO `transaction` VALUES (1,1,5,1,1,'2023-11-01 00:00:00','Restau sushi tassin',17.9),(2,1,5,1,1,'2023-11-01 00:00:00','Restau carnet gourmand',25.9),(3,1,6,1,1,'2023-11-01 00:00:00','Virement n26',150),(4,1,5,1,1,'2023-11-01 00:00:00','Virement boursorama',150),(5,2,10,1,1,'2023-11-02 00:00:00','Salaire Webqam',1620),(6,1,9,1,1,'2023-11-02 00:00:00','Trajet trottinette Dott',1),(7,1,2,1,1,'2023-11-01 00:00:00','Stationnement payant',4),(8,2,10,1,1,'2023-11-02 00:00:00','Remboursement de Hanane (trajet voiture GOLF7)',20),(9,1,5,1,1,'2023-11-02 00:00:00','Restau carnet gourmand',25.94),(10,1,5,1,1,'2023-11-02 00:00:00','Sushi shop à Tassin',17.9),(11,1,9,1,1,'2023-11-03 00:00:00','Dott trajet trott',2.38),(12,1,3,1,1,'2023-11-03 00:00:00','Abonnement basic fit',32),(13,2,10,1,1,'2023-11-03 00:00:00','Virement Louise.F - concert luidji Barcelone',60),(14,1,2,1,1,'2023-11-06 00:00:00','Essence plein',69.4),(15,1,12,1,1,'2023-11-06 00:00:00','Virement N26',100),(16,1,12,1,1,'2023-11-06 00:00:00','Virement Boursorama Kevin',100),(17,1,1,1,1,'2023-11-06 00:00:00','Loyer cardinal campus',398),(18,1,2,1,1,'2023-11-06 00:00:00','Parking en garage',60),(19,1,9,1,1,'2023-11-06 00:00:00','trajet trott dott',0.95),(20,1,9,1,1,'2023-11-06 00:00:00','Ticket métro TCL',2),(21,1,4,1,1,'2023-11-06 00:00:00','Abonnement github copilot',11.53),(22,1,9,1,1,'2023-11-07 00:00:00','trajet trott dott',3.46),(23,2,10,1,1,'2023-11-08 00:00:00','Remboursement Eric Ikanda',25),(24,1,4,1,1,'2023-11-08 00:00:00','Abonnement heroku app',5.89),(25,1,9,1,1,'2023-11-10 00:00:00','Trajet trottinette Dott',2),(26,2,10,1,1,'2023-11-10 00:00:00','Virement Famille - Remboursement crédit étudiant LCL',450),(27,1,9,1,1,'2023-11-13 00:00:00','Trajet trottinette Dott',2),(28,1,7,1,1,'2023-11-13 00:00:00','Abonnement crunchyroll',7),(29,1,7,1,1,'2023-11-01 00:00:00','Amazon - Abonnement OCS',13),(30,1,6,1,1,'2023-11-14 00:00:00','Abonnement annuel LinkedIn Premium (error)',237.9),(31,1,7,1,1,'2023-11-15 00:00:00','Abonnement crunchyroll bis',4.99),(32,1,10,1,1,'2023-11-15 00:00:00','Remboursement crédit LCL',435.72),(33,1,13,1,1,'2023-11-15 00:00:00','Cotisation mutuelle allianz',55.52),(34,1,6,1,1,'2023-11-15 00:00:00','Airbnb Barcelone Unite Hostel',69),(35,2,6,1,1,'2023-11-16 00:00:00','Remboursement abonnement linkedin',237.9),(36,2,7,1,1,'2023-11-16 00:00:00','Remboursement amazon',12.99),(37,1,13,1,1,'2023-11-16 00:00:00','Consultation Dr.Bernede Allergologue',76.8),(38,1,9,1,1,'2023-11-20 00:00:00','Trajet dott',2),(39,1,6,1,1,'2023-11-20 00:00:00','Abonnement broke and abroad',5),(40,2,6,1,1,'2023-11-20 00:00:00','Remboursement commande ASOS',48.74),(41,1,6,1,1,'2023-11-20 00:00:00','Billets train Lyon Barcelone aller retour',120),(42,2,13,1,1,'2023-11-21 00:00:00','Remboursement consultation allergologue',47.76),(43,1,8,1,1,'2023-11-22 00:00:00','Forfait téléphone SFR',12),(44,1,7,1,1,'2023-11-22 00:00:00','Location film amazon prime',5),(45,2,10,1,1,'2023-11-23 00:00:00','Prime activité CAF',165.74),(46,1,6,1,1,'2023-11-24 00:00:00','Dépenses séjour Barcelone',280),(47,1,5,1,1,'2023-11-28 00:00:00','Pho 69',13.9),(48,1,9,1,1,'2023-11-28 00:00:00','Trajet trottinette Dott',3),(49,1,14,1,1,'2023-11-30 00:00:00','Machine à laver/sécher',8),(50,1,10,1,1,'2023-11-30 00:00:00','Lettre recommandé avec AR pour Inetum',10),(51,2,13,1,1,'2023-11-01 00:00:00','Remboursement mutuelle allergologue',23.04),(52,2,15,3,7,'2023-07-01 00:00:00','NailGasm',896),(53,2,15,5,7,'2023-08-01 00:00:00','NailGasm',847),(54,2,15,6,7,'2023-09-01 00:00:00','NailGasm',370),(55,2,15,8,7,'2023-10-01 00:00:00','NailGasm',830),(56,2,15,9,7,'2023-11-01 00:00:00','NailGasm',510),(57,1,6,16,1,'2023-12-01 00:00:00','Boisson au transbordeur - concert TTK',5),(58,1,5,16,1,'2023-12-01 00:00:00','courses auchan',2),(59,1,6,16,1,'2023-12-01 00:00:00','retrait argent weed',60),(60,1,3,16,1,'2023-12-01 00:00:00','Abonnement basic fit',32.5),(61,1,14,16,1,'2023-12-01 00:00:00','machine à laver',4.5),(62,2,10,16,1,'2023-12-01 00:00:00','Salaire Webqam',1586),(63,1,14,16,1,'2023-12-01 00:00:00','machine à sécher',3.5),(64,1,5,1,1,'2023-11-01 00:00:00','ben\'s food',6),(65,1,9,1,1,'2023-11-01 00:00:00','Ticket TCL Lyon',2),(66,1,6,16,1,'2023-12-04 00:00:00','Virement N26',150),(67,1,12,16,1,'2023-12-04 00:00:00','Virement Mams',200),(68,1,5,16,1,'2023-12-04 00:00:00','Virement Boursorama Kevin',150),(69,1,9,16,1,'2023-12-04 00:00:00','Abonnement TCL',69.4),(70,1,4,16,1,'2023-12-04 00:00:00','Githup copilot',11.26),(71,1,1,16,1,'2023-12-05 00:00:00','Loyer cardinal campus',398),(72,1,2,16,1,'2023-12-05 00:00:00','Location parking Cardinal',60),(73,1,5,1,1,'2023-11-05 00:00:00','courses Casino',7.54),(74,1,9,1,1,'2023-11-04 00:00:00','Trajet trottinette Dott',1.16),(75,2,10,16,1,'2023-12-05 00:00:00','Virement CAF du Rhone',165),(76,1,7,16,1,'2023-12-05 00:00:00','Abonnement disney +',9),(77,2,9,16,1,'2023-12-06 00:00:00','Remboursement Webqam x TCL',34.7),(78,1,4,16,1,'2023-12-08 00:00:00','Heroku dyno serveur',13),(79,1,7,16,1,'2023-12-09 00:00:00','Location film amazon prime (harry potter 5)',3),(80,1,14,16,1,'2023-12-11 00:00:00','Laver linge Washin',8),(81,2,10,16,1,'2023-12-11 00:00:00','Virement Parent - Remboursement crédit LCL',450),(82,1,2,1,1,'2023-11-12 00:00:00','Essence plein',81),(83,1,5,16,1,'2023-12-11 00:00:00','Courses carrefour',3.5),(84,1,2,16,1,'2023-12-12 00:00:00','Franchises carrosserie voiture - SERENICAR',350),(85,1,2,16,1,'2023-12-12 00:00:00','Essence plein',81),(86,1,9,16,1,'2023-12-13 00:00:00','Dott trajet trott',3.5),(87,1,6,16,1,'2023-12-13 00:00:00','Citadium - santa scret',30),(88,1,9,16,1,'2023-12-13 00:00:00','Train sncf Lyon Tours aller retour',115),(89,1,13,16,1,'2023-12-15 00:00:00','Crème visage + corps pharmacie',30),(90,1,6,16,1,'2023-12-15 00:00:00','Ticket franchise expo Paris 2024',14),(91,1,13,16,1,'2023-12-15 00:00:00','Mensualité Mutuelle Allianz',55),(92,1,10,16,1,'2023-12-15 00:00:00','Retrait 60€',60),(93,1,14,16,1,'2023-12-18 00:00:00','Laver linge Washin',13),(94,1,9,16,1,'2023-12-18 00:00:00','Train Lyon Paris aller retour',32),(95,1,6,16,1,'2023-12-19 00:00:00','Abonnement broke and abroad',5);
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_type`
--

DROP TABLE IF EXISTS `transaction_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `associated_number` smallint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_type`
--

LOCK TABLES `transaction_type` WRITE;
/*!40000 ALTER TABLE `transaction_type` DISABLE KEYS */;
INSERT INTO `transaction_type` VALUES (1,'Spent',0),(2,'Collected',1);
/*!40000 ALTER TABLE `transaction_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'kevin45van@hotmail.fr','[\"ROLE_ADMIN\"]','$2y$13$tQFVnNHjz9MQ9QGnR4rBROACcarooKotTkdX8qPa5Ph3ycK4CYKAO','Tsehao'),(2,'hanahpersonnel@gmail.com','[\"ROLE_ADMIN\"]','$2y$13$neJMgF1j2pX3yqqFISKfdeLhQ0clA5821aGQLwUiastYWEotTvynO','Han <3'),(3,'yorgoaoun5@hotmail.com','[\"ROLE_USER\"]','$2y$13$hevDMQb84NsSmjoqDS7X7ewRjNHWKWMHQh9mV8V.KV6tEdwDSJ5ti','Yorjoooooo'),(4,'jenniferkhanji@gmail.com','[\"ROLE_USER\"]','$2y$13$2FlJWPv/mLp4IWCpuAqUA.9o63HV75KFqE63EqUaKH/5L4DX9jccG','Jenn'),(5,'mathieu.rako97@gmail.com','[\"ROLE_USER\"]','$2y$13$iRuEFdmpUay/1MlpbekbE.tfPSGJXz/YlIcsWkNVqibOFRW0F/I2W','Thieu'),(6,'anny_barrero@hotmail.com','[\"ROLE_USER\"]','$2y$13$UdgxwUAgiTOjv3RHGn3ChOU9B4WMohoU0sWram9PfiNCjX1oM8BJe','Anny'),(7,'mimievan@gmail.com','[\"ROLE_USER\"]','$2y$13$X4hGYGToFWIEzssHc/XySO5VfJXvHQfrrnDlQgh2CeyThpUUsz93e','Mims');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-12-24 17:24:39
