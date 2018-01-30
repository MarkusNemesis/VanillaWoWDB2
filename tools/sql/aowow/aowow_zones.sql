-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: world_prog
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

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
-- Table structure for table `aowow_zones`
--

DROP TABLE IF EXISTS `aowow_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aowow_zones` (
  `mapID` int(11) DEFAULT NULL,
  `areatableID` int(11) DEFAULT NULL,
  `name_loc0` text,
  `x_min` double DEFAULT NULL,
  `y_min` double DEFAULT NULL,
  `x_max` double DEFAULT NULL,
  `y_max` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aowow_zones`
--

LOCK TABLES `aowow_zones` WRITE;
/*!40000 ALTER TABLE `aowow_zones` DISABLE KEYS */;
INSERT INTO `aowow_zones` VALUES (29,0,'CashTest',0,0,0,0),
(30,2597,'Alterac Valley',0,0,0,0),
(33,209,'Shadowfang Keep',0,0,0,0),
(34,717,'Stormwind Stockade',0,0,0,0),
(36,1581,'Deadmines',0,0,0,0),
(43,718,'Wailing Caverns',0,0,0,0),
(44,0,'<unused> Monastery',0,0,0,0),
(47,491,'Razorfen Kraul',0,0,0,0),
(48,719,'Blackfathom Deeps',0,0,0,0),
(70,1337,'Uldaman',0,0,0,0),
(90,721,'Gnomeregan',0,0,0,0),
(109,1477,'Sunken Temple',0,0,0,0),
(129,722,'Razorfen Downs',0,0,0,0),
(169,0,'Emerald Dream',0,0,0,0),
(189,796,'Scarlet Monastery',0,0,0,0),
(209,978,'Zul\'Farrak',0,0,0,0),
(229,1583,'Blackrock Spire',0,0,0,0),
(230,1584,'Blackrock Depths',0,0,0,0),
(249,2159,'Onyxia\'s Lair',0,0,0,0),
(269,0,'Opening of the Dark Portal',0,0,0,0),
(289,2057,'Scholomance',0,0,0,0),
(309,19,'Zul\'Gurub',0,0,0,0),
(329,2017,'Stratholme',0,0,0,0),
(349,2100,'Maraudon',0,0,0,0),
(389,2437,'Ragefire Chasm',0,0,0,0),
(409,2717,'Molten Core',0,0,0,0),
(429,2557,'Dire Maul',0,0,0,0),
(469,2677,'Blackwing Lair',0,0,0,0),
(489,3277,'Warsong Gulch',0,0,0,0),
(509,3429,'Ruins of Ahn\'Qiraj',0,0,0,0),
(529,3358,'Arathi Basin',0,0,0,0),
(531,3428,'Ahn\'Qiraj Temple',0,0,0,0),
(532,3457,'Karazhan',0,0,0,0),
(533,3456,'Naxxramas',0,0,0,0),
(1,14,'Durotar',-1716.67,-7250,1808.33,-1962.5),
(1,215,'Mulgore',-3697.92,-3089.58,-272.917,2047.92),
(1,17,'The Barrens',-5143.75,-7510.42,1612.5,2622.92),
(0,36,'Alterac Mountains',-366.667,-2016.67,1500,783.333),
(0,45,'Arathi Highlands',-2533.33,-4466.67,-133.333,-866.667),
(0,3,'Badlands',-7547.92,-4566.67,-5889.58,-2079.17),
(0,4,'Blasted Lands',-12800,-4591.67,-10566.7,-1241.67),
(0,85,'Tirisfal Glades',825,-1485.42,3837.5,3033.33),
(0,130,'Silverpine Forest',-1133.33,-750,1666.67,3450),
(0,28,'Western Plaguelands',500,-3883.33,3366.67,416.667),
(0,139,'Eastern Plaguelands',1218.75,-6056.25,3800,-2185.42),
(0,267,'Hillsbrad Foothills',-1733.33,-2133.33,400,1066.67),
(0,47,'The Hinterlands',-1100,-5425,1466.67,-1575),
(0,1,'Dun Morogh',-7160.42,-3122.92,-3877.08,1802.08),
(0,51,'Searing Gorge',-7587.5,-2554.17,-6100,-322.917),
(0,46,'Burning Steppes',-8983.33,-3195.83,-7031.25,-266.667),
(0,12,'Elwynn Forest',-10254.2,-1935.42,-7939.58,1535.42),
(0,41,'Deadwind Pass',-11533.3,-3333.33,-9866.67,-833.333),
(0,10,'Duskwood',-11516.7,-1866.67,-9716.67,833.333),
(0,38,'Loch Modan',-6327.08,-4752.08,-4487.5,-1993.75),
(0,44,'Redridge Mountains',-10022.9,-3741.67,-8575,-1570.83),
(0,33,'Stranglethorn Vale',-15422.9,-4160.42,-11168.8,2220.83),
(0,8,'Swamp of Sorrows',-11150,-4516.67,-9620.83,-2222.92),
(0,40,'Westfall',-11733.3,-483.333,-9400,3016.67),
(0,11,'Wetlands',-4904.17,-4525,-2147.92,-389.583),
(1,141,'Teldrassil',8437.5,-1277.08,11831.2,3814.58),
(1,148,'Darkshore',3966.67,-3608.33,8333.33,2941.67),
(1,331,'Ashenvale',829.167,-4066.67,4672.92,1700),
(1,400,'Thousand Needles',-6900,-4833.33,-3966.67,-433.333),
(1,406,'Stonetalon Mountains',-339.583,-1637.5,2916.67,3245.83),
(1,405,'Desolace',-2545.83,-262.5,452.083,4233.33),
(1,357,'Feralas',-7000,-1508.33,-2366.67,5441.67),
(1,15,'Dustwallow Marsh',-5533.33,-6225,-2033.33,-975),
(1,440,'Tanaris',-10475,-7118.75,-5875,-218.75),
(1,16,'Azshara',1960.42,-8347.92,5341.67,-3277.08),
(1,361,'Felwood',3300,-4108.33,7133.33,1641.67),
(1,490,'Un\'Goro Crater',-8433.33,-3166.67,-5966.67,533.333),
(1,493,'Moonglade',6952.08,-3689.58,8491.67,-1381.25),
(1,1377,'Silithus',-8281.25,-945.834,-5958.33,2537.5),
(1,618,'Winterspring',3800,-7416.67,8533.33,-316.667),
(0,1519,'Stormwind City',-9175.21,36.7006,-8278.85,1380.97),
(1,1637,'Orgrimmar',1338.46,-5083.21,2273.88,-3680.6),
(0,1537,'Ironforge',-5096.85,-1504.22,-4569.24,-713.591),
(1,1638,'Thunder Bluff',-1545.83,-527.083,-850,516.667),
(1,1657,'Darnassus',9532.59,1880.03,10238.3,2938.36),
(0,1497,'Undercity',1237.84,-86.1824,1877.95,873.193),
(30,2597,'Alterac Valley',-1739.58,-2456.25,1085.42,1781.25),
(489,3277,'Warsong Gulch',862.5,895.833,1627.08,2041.67),
(529,3358,'Arathi Basin',337.5,102.083,1508.33,1858.33);

/*!40000 ALTER TABLE `aowow_zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-30 13:23:42
