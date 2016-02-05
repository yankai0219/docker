-- MySQL dump 10.13  Distrib 5.1.73, for unknown-linux-gnu (x86_64)
--
-- Host: localhost    Database: chong
-- ------------------------------------------------------
-- Server version	5.5.37-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `order_user`
--

DROP TABLE IF EXISTS `order_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `tel` varchar(11) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `addr` varchar(200) DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_user`
--

LOCK TABLES `order_user` WRITE;
/*!40000 ALTER TABLE `order_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_user_id` int(11) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT '0' COMMENT '1. 寄养\n2. 代遛\n3. 训练\n4. 接送',
  `begindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `enddate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_spec_info` varchar(500) DEFAULT NULL COMMENT '订单中用户的附加信息\n',
  `pet_spec_info` varchar(500) DEFAULT NULL COMMENT '订单中宠物的附加信息\n',
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) DEFAULT NULL COMMENT '0 无效订单\n1  新提交订单\n2 得到确认\n3 订单完成',
  `sitter_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderuser`
--

DROP TABLE IF EXISTS `orderuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `tel` varchar(11) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `addr` varchar(200) DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderuser`
--

LOCK TABLES `orderuser` WRITE;
/*!40000 ALTER TABLE `orderuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `orderuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pet`
--

DROP TABLE IF EXISTS `pet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `age` varchar(45) DEFAULT NULL,
  `breed` varchar(45) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL COMMENT '1 female\n2 male',
  `weight` varchar(45) DEFAULT NULL,
  `other` varchar(45) DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pet`
--

LOCK TABLES `pet` WRITE;
/*!40000 ALTER TABLE `pet` DISABLE KEYS */;
/*!40000 ALTER TABLE `pet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pictures`
--

DROP TABLE IF EXISTS `pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `imageable_type` varchar(20) DEFAULT NULL,
  `token` varchar(120) DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pictures`
--

LOCK TABLES `pictures` WRITE;
/*!40000 ALTER TABLE `pictures` DISABLE KEYS */;
/*!40000 ALTER TABLE `pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `relationships`
--

DROP TABLE IF EXISTS `relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `relationships`
--

LOCK TABLES `relationships` WRITE;
/*!40000 ALTER TABLE `relationships` DISABLE KEYS */;
/*!40000 ALTER TABLE `relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `replies`
--

DROP TABLE IF EXISTS `replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body` text,
  `user_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `token` varchar(120) DEFAULT NULL,
  `refer_id` int(11) DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `replies`
--

LOCK TABLES `replies` WRITE;
/*!40000 ALTER TABLE `replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sitters`
--

DROP TABLE IF EXISTS `sitters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sitters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `tel` varchar(11) NOT NULL,
  `gender` int(11) DEFAULT NULL COMMENT '1 female 2 male',
  `address` varchar(500) DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sitters`
--

LOCK TABLES `sitters` WRITE;
/*!40000 ALTER TABLE `sitters` DISABLE KEYS */;
/*!40000 ALTER TABLE `sitters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) DEFAULT NULL,
  `body` text,
  `replies_count` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `token` varchar(120) DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `nickname` varchar(45) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `gender` int(11) DEFAULT NULL COMMENT '1 female\n2 male',
  `openid` varchar(100) DEFAULT NULL,
  `opentype` int(11) DEFAULT NULL COMMENT '1 qqid\n2 wechatid\n3 sinaid',
  `open_access_token` varchar(100) DEFAULT NULL,
  `tel` varchar(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 普通username（邮箱/手机号码）+password\n2 qq登录\n3 微信登录\n4 新浪登录',
  `addr_province` varchar(45) DEFAULT NULL,
  `addr_detail` varchar(45) DEFAULT NULL,
  `one_sentence` varchar(200) DEFAULT NULL,
  `thumb` varchar(45) DEFAULT NULL,
  `employ` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `contact_email` varchar(45) DEFAULT NULL,
  `contact_tel` int(11) DEFAULT NULL,
  `birth` timestamp NULL DEFAULT NULL,
  `createline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updateline` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
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

-- Dump completed on 2015-05-03 17:37:00
