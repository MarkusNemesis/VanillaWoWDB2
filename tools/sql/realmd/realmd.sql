-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: realmd
-- ------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@SESSION.TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `db_version`
--

DROP TABLE IF EXISTS `db_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_version` (
  `version` int(3) NOT NULL,
  `structure` int(3) NOT NULL,
  `content` int(3) NOT NULL,
  `description` varchar(30) NOT NULL DEFAULT '',
  `comment` varchar(150) DEFAULT '',
  PRIMARY KEY (`version`,`structure`,`content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Used DB version notes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db_version`
--

LOCK TABLES `db_version` WRITE;
/*!40000 ALTER TABLE `db_version` DISABLE KEYS */;
INSERT  INTO `db_version`(`version`,`structure`,`content`,`description`,`comment`) VALUES (21,1,3,'Remove field from dbDocs','Base Database from 20150409 to Rel21_1_3');
/*!40000 ALTER TABLE `db_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Account identifier',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT 'User name',
  `sha_pass_hash` varchar(40) NOT NULL DEFAULT '' COMMENT 'SHA1 password Hash',
  `gmlevel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sessionkey` longtext,
  `v` longtext,
  `s` longtext COMMENT 'password salt',
  `email` text COMMENT 'Email address',
  `joindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Account Created Date',
  `last_ip` varchar(30) NOT NULL DEFAULT '0.0.0.0',
  `failed_logins` int(11) unsigned NOT NULL DEFAULT '0',
  `locked` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `active_realm_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'last realm id',
  `expansion` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'max game expansion',
  `mutetime` bigint(40) unsigned NOT NULL DEFAULT '0',
  `locale` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `os` varchar(3) DEFAULT '' COMMENT 'Client OS Version',
  `playerBot` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Whether the account is a playerbot account',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_username` (`username`),
  KEY `idx_gmlevel` (`gmlevel`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Account System';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account`
--

LOCK TABLES `account` WRITE;
/*!40000 ALTER TABLE `account` DISABLE KEYS */;
insert  into `account`(`id`,`username`,`sha_pass_hash`,`gmlevel`,`sessionkey`,`v`,`s`,`email`,`joindate`,`last_ip`,`failed_logins`,`locked`,`last_login`,`active_realm_id`,`expansion`,`mutetime`,`locale`,`os`,`playerBot`) values (1,'ADMINISTRATOR','a34b29541b87b7e4823683ce6c7bf6ae68beaaac',3,'','0','0','','2006-04-25 13:18:56','127.0.0.1',0,0,'0000-00-00 00:00:00',0,0,0,0,'','\0');
insert  into `account`(`id`,`username`,`sha_pass_hash`,`gmlevel`,`sessionkey`,`v`,`s`,`email`,`joindate`,`last_ip`,`failed_logins`,`locked`,`last_login`,`active_realm_id`,`expansion`,`mutetime`,`locale`,`os`,`playerBot`) values (2,'GAMEMASTER','7841e21831d7c6bc0b57fbe7151eb82bd65ea1f9',2,'','0','0','','2006-04-25 13:18:56','127.0.0.1',0,0,'0000-00-00 00:00:00',0,0,0,0,'','\0');
insert  into `account`(`id`,`username`,`sha_pass_hash`,`gmlevel`,`sessionkey`,`v`,`s`,`email`,`joindate`,`last_ip`,`failed_logins`,`locked`,`last_login`,`active_realm_id`,`expansion`,`mutetime`,`locale`,`os`,`playerBot`) values (3,'MODERATOR','a7f5fbff0b4eec2d6b6e78e38e8312e64d700008',1,'','0','0','','2006-04-25 13:19:35','127.0.0.1',0,0,'0000-00-00 00:00:00',0,0,0,0,'','\0');
insert  into `account`(`id`,`username`,`sha_pass_hash`,`gmlevel`,`sessionkey`,`v`,`s`,`email`,`joindate`,`last_ip`,`failed_logins`,`locked`,`last_login`,`active_realm_id`,`expansion`,`mutetime`,`locale`,`os`,`playerBot`) values (4,'PLAYER','3ce8a96d17c5ae88a30681024e86279f1a38c041',0,'','0','0','','2006-04-25 13:19:35','127.0.0.1',0,0,'0000-00-00 00:00:00',0,0,0,0,'','\0');

/*!40000 ALTER TABLE `account` ENABLE KEYS */;
UNLOCK TABLES;
--
-- Table structure for table `account_banned`
--

DROP TABLE IF EXISTS `account_banned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_banned` (
  `id` int(11) unsigned NOT NULL COMMENT 'Account identifier',
  `bandate` bigint(40) NOT NULL DEFAULT '0',
  `unbandate` bigint(40) NOT NULL DEFAULT '0',
  `bannedby` varchar(50) NOT NULL,
  `banreason` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`bandate`),
  CONSTRAINT `account_banned_ibfk_1` FOREIGN KEY (`id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Ban List';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_banned`
--

LOCK TABLES `account_banned` WRITE;
/*!40000 ALTER TABLE `account_banned` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_banned` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dbdocsfields` 
--

DROP TABLE IF EXISTS `dbdocsfields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbdocsfields` (
  `fieldId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Id for this field',
  `languageId` int(11) NOT NULL DEFAULT '0' COMMENT 'dbdocsLanguageId to link to. (Normallu 0 = English)',
  `tableName` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the table to link to',
  `fieldName` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the field to link the note to',
  `fieldComment` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Main field Note',
  `fieldNotes` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Additional Field Notes',
  PRIMARY KEY (`fieldId`,`languageId`),
  KEY `fieldId` (`fieldId`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Data for the table `dbdocsfields`
--

LOCK TABLES `dbdocsfields` WRITE;
/*!40000 ALTER TABLE `dbdocsfields` DISABLE KEYS */;
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (1,0,'account','active_realm_id','Unique ID of the realm server','This references the realmlist tables unique ID of the realm server on which the account is currently active. This will allow the game client to connect to the realm the account was active on at the last login.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (2,0,'account','email','The e-mail address associated with this account.','The e-mail address associated with this account.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (3,0,'account','expansion','Which expansion\'s content a user has access to.','The field controls to which expansion\'s content a user has access. By default this is set to 0, allowing access to vanilla WoW content. In mangos-zero, other values can be ignored, since there is no expansion.<br />\r\n¬subtable:2¬\r\n<br />\r\n* Base Game Version of World of Warcraft, otherwise known as Vanilla.\r\n<br />\r\nThe world server will block access to accounts with 0 in this field from accessing the TBC and WotLK areas in-game.\r\n<br />\r\nThe world server will block access to accounts with 1 in this field from accessing the WotLK areas in-game and so on.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (4,0,'account','failed_logins','The number of failed logins attempted on the account.','The number of failed logins attempted on the account. Monitoring this field may help spotting users who try to gain access to accounts which are not their own, or who have forgotten their passwords.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (5,0,'account','gmlevel','The account security level.','The account security level. Different levels have access to different commands. The individual level required for a command is defined in the Mangos command table. Valid values are:<br />\r\n¬subtable:1¬');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (6,0,'account','id','The unique account ID.','The unique account ID.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (7,0,'account','joindate','The date when the account was created.','The date when the account was created.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (8,0,'account','last_ip','The last IP used by the person who last logged into the account.','The last IP used by the person who last logged into the account.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (9,0,'account','last_login','The date when the account was last logged into.','The date when the account was last logged into.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (10,0,'account','locale','The locale used by the client logged into this account.','The locale used by the client logged into this account. If multiple locale data has been configured and added to the world servers, the world servers will return the proper locale strings to the client. \r\n¬subtable:3¬\r\n');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (11,0,'account','locked','Indicates whether the account has been locked or not.','Boolean 0 or 1 controlling if the account has been locked or not.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (12,0,'account','mutetime','The time, in Unix time, when the account will be unmuted.','The time, in Unix time, when the account will be unmuted.<br/>\r\n<pre>SELECT `username`, FROM_UNIXTIME(`mutetime`) FROM `account`;</pre>\r\n');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (13,0,'account','s','Session \'Salt\' Value.','Session \'Salt\' Value. Initially a randomly generated value, which when applied against the SHA1 hash of the username / password combo (See account.sha_pass_hash) produces the validation hash (See account.v).\r\n');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (14,0,'account','sessionkey','The Session Key.','The SessionKey - this is the validated result of the login negotiation. This allows disconnected clients to reconnection to their previous session.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (15,0,'account','sha_pass_hash','This field contains the encrypted password.','This field contains the encrypted password. The encryption is SHA1 and is in the following format: username:password. The SQL to create the password (or to compare with the current hash) is:\r\n<pre>SELECT SHA1(CONCAT(UPPER(`username`), \':\', UPPER({pass})));</pre>');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (16,0,'account','username','The account user name.','The account user name.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (17,0,'account','v','The validated Hash Value.','The validated Hash Value, this is created by using the \'Salt\' value (See account.s) applied to the username/password SHA1 hash (See account.sha_pass_hash).');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (18,0,'account_banned','active','Is the ban is currently active or not.','This is a Boolean field controlling if the ban is currently active or not.<br />\r\n¬subtable:4¬\r\n');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (19,0,'account_banned','bandate','The date when the account was banned, in Unix time.','The date when the account was banned, in Unix time.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (20,0,'account_banned','bannedby','The character that banned the account.','The character with the rights to the .ban command that banned the account.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (21,0,'account_banned','banreason','The reason for the ban.','The reason for the ban.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (22,0,'account_banned','id','The account ID (See account.id).','The account ID (See account.id).');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (23,0,'account_banned','unbandate','The date when the account will be automatically unbanned.','The date when the account will be automatically unbanned, in Unix time. A value less than the current date means, in effect, a permanent ban.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (24,0,'dbdocsfields','fieldComment','Main field Note','Main field Note');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (25,0,'dbdocsfields','fieldName','The fieldname in the table to link the note to.','The fieldname in the table to link the note to.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (26,0,'dbdocsfields','fieldNotes','The Field Note text','The Field Note text');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (27,0,'dbdocsfields','tableName','The table name to link the note to.','The table name to link the note to.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (28,0,'dbdocssubtables','subTableContent','The Sub Table Content','The Sub Table Content');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (29,0,'dbdocssubtables','subTableId','The Lookup Id of the subTable','This is the Lookup Id of the subTable');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (30,0,'dbdocssubtables','subTableName','The Name of the SubTable','The Name of the SubTable');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (31,0,'dbdocssubtables','subTableTemplate','The SubTable Template','The SubTable Template, Used to create the SubTableContent field content');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (32,0,'dbdocstable','tableName','The table name to link the note to.','The table name to link the note to.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (33,0,'dbdocstable','tableNotes','The table note text.','The table note text.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (34,0,'ip_banned','bandate','The date when the IP was first banned, in Unix time.','The date when the IP was first banned, in Unix time.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (35,0,'ip_banned','bannedby','The name of the character that banned the IP.','The name of the character that banned the IP. The character should belong to an account with the rights to the .ban command in-game.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (36,0,'ip_banned','banreason','The reason given for the IP ban.','The reason given for the IP ban.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (37,0,'ip_banned','ip','The IP address that is banned.','The IP address that is banned.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (38,0,'ip_banned','unbandate','The date when the IP will be unbanned in Unix time.','The date when the IP will be unbanned in Unix time. Any date that is set lower than the current date basically classifies as a permanent ban as it will never auto expire.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (39,0,'realmcharacters','acctid','The account ID (See account.id).','The account ID (See account.id).');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (40,0,'realmcharacters','numchars','The number of characters the account has on the realm.','The number of characters the account has on the realm.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (41,0,'realmcharacters','realmid','The ID of the realm (See realmlist.id).','The ID of the realm (See realmlist.id).');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (42,0,'realmd_db_version','required_20140607_Realm_Resync','zzzz','zzzz');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (43,0,'realmd_db_version','required_z2426_01_realmd_relations','The Version of the Realm Database','The Version of the Realm Database');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (44,0,'realmlist','address','The public IP address of the world server.','The public IP address of the world server. 127.0.0.1 works for LAN play only.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (45,0,'realmlist','allowedSecurityLevel','Minimum account (see account.gmlevel) required for accounts to log in.','The minimum account (see account.gmlevel) required for accounts to log in to this realm. Normally 0 - that means normal players allowed on this realm. Just useful if you make a GM-only developement realm.<br />\nChanging this value will automatically update the visible in-game realm list, but the realm server must be restarted for it to take effect.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (46,0,'realmlist','icon','The icon of the realm.','The icon of the realm.<br />\r\n¬subtable:5¬');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (47,0,'realmlist','id','The realm ID.','The realm ID. This number is unique for every realm and it MUST correlate with the RealmID configuration value in mangosd.conf.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (48,0,'realmlist','name','The name of the realm.','The name of the realm. This will appear in the realm selection list as well as in the character selection screen.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (49,0,'realmlist','population','Show the current population.','This field shows the current population and is automatically updated at regular intervals and will . The formula to calculate the value in this field is:<br />\r\n<pre>playerCount / maxPlayerCount * 2</pre><br />\r\n¬subtable:8¬\r\n\r\n');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (50,0,'realmlist','port','The port that the world server is running on.','The port that the world server is running on. Default is 8085. If you will run more than one world server on the same machine, e.g. develop and production, they will all need to use a different port.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (51,0,'realmlist','realmbuilds','The accepted client builds that the realm will accept.','The accepted client builds that the realm will accept. (You can see this version at the clients left lower corner when starting.)<br />\r\nThe format is version No. {space} version No. (i.e. space separated) <pre>xxxx xxxx xxxx</pre><br />\r\nAcceptable values are:\r\n¬subtable:9¬\r\n');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (52,0,'realmlist','realmflags','Supported masks for the realm, based on the table below.','Supported masks for the realm, based on the table below.<br />\r\n¬subtable:6¬');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (53,0,'realmlist','timezone','The realm timezone.','The realm timezone, it will be displayed in the tabs of the realmlist.<br />\r\n¬subtable:7¬\r\n');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (54,0,'uptime','maxplayers','The maximum number of players connected','The maximum number of players connected');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (55,0,'uptime','realmid','The realm id (See realmlist.id).','The realm id (See realmlist.id).');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (56,0,'uptime','startstring','The time when the server started, formated as a readable string.','The time when the server started, formated as a readable string.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (57,0,'uptime','starttime','The time when the server was started, in Unix time.','The time when the server was started, in Unix time.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (58,0,'uptime','uptime','The uptime of the server, in seconds.','The uptime of the server, in seconds.');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (61,0,'account','os','The Operating System of the connect client','The Operating System of the connect client. This is used by the Warden Module');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (63,0,'account','os','The Operating System of the connect client','The Operating System of the connect client. This is used by the Warden Module');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (64,0,'account','os','The Operating System of the connect client','The Operating System of the connect client. This is used by the Warden Module');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (65,0,'account','playerBot','Determines whether the account is a User or a PlayerBot','Determines whether the account is a User or a PlayerBot.<br /><br />¬subtable:11¬');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (66,0,'account','os','The Operating System of the connect client','The Operating System of the connect client. This is used by the Warden Module');
insert  into `dbdocsfields`(`fieldId`,`languageId`,`tableName`,`fieldName`,`fieldComment`,`fieldNotes`) values (67,0,'account','playerBot','Determines whether the account is a User or a PlayerBot','Notes: LanguageId for this Language<br />¬subtable:3¬');
/*!40000 ALTER TABLE `dbdocsfields` ENABLE KEYS */;
UNLOCK TABLES;

/*Table structure for table `dbdocsfields_localised` */

DROP TABLE IF EXISTS `dbdocsfields_localised`;

CREATE TABLE `dbdocsfields_localised` (
  `fieldId` int(11) NOT NULL COMMENT 'dbdocsfields.fieldId to link to.',
  `languageId` int(11) NOT NULL COMMENT 'dbdocsLanguageId to link to. (Normallu 0 = English)',
  `fieldNotes` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Additional Field Notes',
  `fieldComment` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Main field Note',
  PRIMARY KEY (`fieldId`,`languageId`),
  KEY `fieldId` (`fieldId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `dbdocsfields_localised` */

LOCK TABLES `dbdocsfields_localised` WRITE;
/*!40000 ALTER TABLE `dbdocsfields_localised` DISABLE KEYS */;

insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (1,2,'Référence vers le royaume pour lequel ce compte est actuellement actif. Permet au client de se reconnecter directement au royaume lors de la connexion.','Identifiant unique du royaume');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (2,2,'L\'adress e-mail associée au compte.','L\'adresse e-mail associée au compte.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (3,2,'Ce champ contrôle l\'extension à laquelle le joueur a accès. Par défaut, la valeur est 0, représentant WoW vanilla. Dans MangosZero, les autres valeurs peuvent être ignorées. <br />\r\n¬subtable:2¬\r\n<br />\r\n* Base Game Version of World of Warcraft, aussi appelé Vanilla.\r\n<br />\r\nLe serveur monde bloquera l\'accès aux zones de BC et WOLK pour les comptes ayant un 0 dans ce champ.\r\n<br />\r\nLe serveur monde bloquera l\'accès aux zones de WOLK pour les comptes ayant un 1 dans ce champ, etc..','Indique l\'extension à laquelle le joueur a droit.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (4,2,'Le nombre de connexion échouée pour ce compte. Surveiller ce champ peut aider à la détection des utilisateurs tentant d\'usurper le compte d\'un autre joueur ou, plus simplement, qui ont oublié leur mot de passe.','Le nombre de connexion échouée pour ce compte.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (5,2,'Niveau de sécurité du compte. Différents niveaux de sécurité existent, la correspondance commandes/niveau est définie dans la table des commandes Mangos. Les valeurs acceptées sont:<br />\r\n¬subtable:1¬','Niveau de sécurité du compte.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (6,2,'L\'identifiant unique du compte.','L\'identifiant unique du compte.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (7,2,'Date à laquelle le compte a été créé.','Date à laquelle le compte a été créé.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (8,2,'Dernière adresse IP ayant été utilisée pour se connecter avec ce compte.','Dernière adresse IP ayant été utilisée pour se connecter avec ce compte.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (9,2,'Dernière date de connexion.','Dernière date de connexion.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (10,2,'Si plusieurs configurations linguistiques sont configurées, le serveur retournera les informations dans la langue du client. \r\n¬subtable:3¬\r\n','Détermine les paramètres linguistiques du client de ce compte.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (11,2,'0 = Compte actif, 1 = Compte bloqué.','Indique si le compte est bloqué ou non.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (12,2,'Le moment, en format Unix, lors duquel ce compte ne sera plus réduit au silence.<br/>\r\n<pre>SELECT `username`, FROM_UNIXTIME(`mutetime`) FROM `account`;</pre>\r\n','Le moment, en format Unix, lors duquel ce compte ne sera plus réduit au silence.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (13,2,'Variable de session (Salt). Initialement générée aléatoirement. Utilisée avec le hash SHA1 du couple nom d\'utilisateur / mot de passe (voir account.sha_pass_hash) produit le hash de validation (voir account.v).\r\n','Variable de session (Salt).');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (14,2,'Clé de session - Résultat validé lors de la négociatation d\'authentificationt. Permet à des clients déconnectés de se reconnecter à leur session précédente.','Clé de session.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (15,2,'Mot de passe encrypté. L\'algorithme d\'encryption est SHA1 et produit le format suivant: username:password. L\'instruction SQL pour créér ou comparer le mot de passe est:\r\n<pre>SELECT SHA1(CONCAT(UPPER(`username`), \':\', UPPER({pass})));</pre>','Mot de passe encrypté.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (16,2,'Le nom du compte.','Le nom du compte.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (17,2,'La valeur hash validée est créée en utilisant la valeur \'s\' (Salt) appliquée sur le hash sha_pass_hash (voir account.sha_pass_hash).','La valeur hash validée.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (18,2,'Indique si le compte est actuellement banni.<br />\r\n¬subtable:4¬\r\n','Indique si le compte est actuellement banni.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (19,2,'Date de bannissement du compte, en format Unix.','Date de bannissement du compte, en format Unix.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (20,2,'Nom du bannisseur. (Doit avoir le droit d\'exécuter la commande .ban).','Nom du bannisseur.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (21,2,'Raison du bannissement.','Raison du bannissement.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (22,2,'Identifiant du compte (Voir account.id).','Identifiant du compte (Voir account.id).');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (23,2,'Date de suppression automatique du bannissement, en format Unix. Une valeur inférieure à la date actuelle indique un bannissement permanent.','Date de suppression automatique du bannissement.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (24,2,'zzzz','zzzz');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (25,2,'Le nom du champ à laquelle lier la note.','Le nom du champ à laquelle lier la note.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (26,2,'Le texte de la note.','Le texte de la note.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (27,2,'Le nom de la table à laquelle lier la note.','Le nom de la table à laquelle lier la note.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (28,2,'Le texte de la note.','Le texte de la note.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (29,2,'Le nom de la table à laquelle lier la note.','Le nom de la table à laquelle lier la note.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (30,2,'zzzz','zzzz');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (31,2,'L\'identifiant à rechercher dans la table imbriquée.','L\'identifiant à rechercher dans la table imbriquée.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (32,2,'zzzz','zzzz');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (33,2,'zzzz','zzzz');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (34,2,'L\'identifiant à rechercher dans la table imbriquée.','L\'identifiant à rechercher dans la table imbriquée.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (35,2,'zzzz','zzzz');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (36,2,'Le nom de la table à laquelle lier la note.','Le nom de la table à laquelle lier la note.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (37,2,'Le texte de la note de la table.','Le texte de la note de la table.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (38,2,'Le nom de la table à laquelle lier la note.','Le nom de la table à laquelle lier la note.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (39,2,'Le texte de la note de la table.','Le texte de la note de la table.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (40,2,'La première date à laquelle l\'IP a été bannie, en format Unix.','La première date à laquelle l\'IP a été bannie, en format Unix.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (41,2,'Le nom du personnage qui a banni cette IP (doit avoir le droit d\'exécuter la commande .ban).','Le nom du personnage qui a banni cette IP.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (42,2,'La raison du bannissement de l\'IP.','La raison du bannissement de l\'IP.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (43,2,'L\'adresse IP bannie.','L\'adresse IP bannie.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (44,2,'Date à laquelle l\'IP sera débannie automatiquement, en format Unix. Une valeur inférieure à la date actuelle indique un bannissement permanent.','Date à laquelle l\'IP sera débannie automatiquement, en format Unix.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (45,2,'Identifiant du compte (voir account.id).','Identifiant du compte (voir account.id).');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (46,2,'Nombre de personnages pour ce compte sur le royaume.','Nombre de personnages pour ce compte sur le royaume.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (47,2,'Identifiant du royaume. (voir realmlist.id).','Identifiant du royaume. (voir realmlist.id).');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (48,2,'Version de la base de données \'Realm\'.','Version de la base de données \'Realm\'.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (49,2,'Version de la base de données \'Realm\'.','Version de la base de données \'Realm\'.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (50,2,'Adresse IP publique du serveur monde. 127.0.0.1 fonctionne uniquement en LAN.','Adresse IP publique du serveur monde.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (51,2,'Niveau de sécurité minimum requis pour authentifier la connexion (voir account.gmlevel). Par défaut 0, indiquant que chaque joueur normal est autorisé à jouer sur ce royaume. Uniquement utile pour construire un serveur de développement limité aux MJ.<br />\nChanger cette valeur aura un effet immédiat en jeu sur la liste des royaumes. Toutefois, afin de prendre effet, le serveur du royaume doit être redémarré.','Niveau de sécurité minimum requis pour authentifier la connexion (voir account.g');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (52,2,'L\'icône du royaume.<br />\r\n¬subtable:5¬','L\'icône du royaume.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (53,2,'Identifiant du royaume. Cette valeur est unique pour chaque royaume et DOIT être égale à la valeur RealmID du fichier de configuration mangosd.conf.','Identifiant du royaume.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (54,2,'Le nom du royaume. Apparait dans la liste de sélection du royaume ainsi que dans la fenêtre de sélection des personnages.','Le nom du royaume.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (55,2,'Ce champ indique la population actuelle et est mis à jour automatiquement. La formule pour calculer cette valeur est:<br />\r\n<pre>playerCount / maxPlayerCount * 2</pre><br />\r\n¬subtable:8¬\r\n\r\n','Indique la population actuelle.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (56,2,'Port de communication du royaume (default: 8085). Si plusieurs royaumes doivent tourner sur la même machine et sur la même adresse IP, cette valeur doit être différente pour chaque royaume.','Le port de communication du royaume.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (57,2,'Les versions de clients acceptées par ce royaume. (Vous pouvez voir la version du client dans le coin inférieur gauche)<br />\r\nLe format est Version No. {space} Version No. <pre>xxxx xxxx xxxx</pre><br />\r\nLes valeurs acceptées sont:\r\n¬subtable:9¬\r\n','Les versions de clients acceptées par ce royaume.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (58,2,'Masques supportés pour le royaume, basé sur la table ci-dessous.<br />\r\n¬subtable:6¬','Masques supportés pour le royaume, basés sur la table ci-dessous.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (59,2,'Le fuseau horaire du royaume, sera affiché dans l\'onglet de la liste des royaumes.<br />\r\n¬subtable:7¬\r\n','Le fuseau horaire du royaume.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (60,2,'Le nombre maximum de joueurs connectés.','Le nombre maximum de joueurs connectés.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (61,2,'Identifiant du royaume (voir realmlist.id).','Identifiant du royaume (voir realmlist.id).');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (62,2,'Date de démarrage du serveur dans un format lisible.','Date de démarrage du serveur dans un format lisible.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (63,2,'Date de démarrage du serveur, en format Unix.','Date de démarrage du serveur, en format Unix.');
insert  into `dbdocsfields_localised`(`fieldId`,`languageId`,`fieldNotes`,`fieldComment`) values (64,2,'Temps d\'activité du serveur, en secondes.','Temps d\'activité du serveur, en secondes.');
/*!40000 ALTER TABLE `dbdocsfields_localised` ENABLE KEYS */;
UNLOCK TABLES;

/*Table structure for table `dbdocslanguage` */

DROP TABLE IF EXISTS `dbdocslanguage`;

CREATE TABLE `dbdocslanguage` (
  `LanguageId` int(11) NOT NULL COMMENT 'LanguageId for this Language',
  `LanguageName` varchar(30) NOT NULL COMMENT 'The Language Name',
  PRIMARY KEY (`LanguageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `dbdocslanguage` */

LOCK TABLES `dbdocslanguage` WRITE;
/*!40000 ALTER TABLE `dbdocslanguage` DISABLE KEYS */;

insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (0,'English');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (1,'Korean');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (2,'French');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (3,'German');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (4,'Chinese');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (5,'Taiwanese');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (6,'Spanish (Spain)');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (7,'Spanish (Latin America)');
insert  into `dbdocslanguage`(`LanguageId`,`LanguageName`) values (8,'Russian');
/*!40000 ALTER TABLE `dbdocslanguage` ENABLE KEYS */;
UNLOCK TABLES;

/*Table structure for table `dbdocssubtables` */

DROP TABLE IF EXISTS `dbdocssubtables`;

CREATE TABLE `dbdocssubtables` (
  `subTableId` int(11) NOT NULL COMMENT 'Unique Lookup Id',
  `languageId` int(11) NOT NULL DEFAULT '0' COMMENT 'dbdocsLanguageId to link to. (Normally 0 = English)',
  `subTableName` varchar(80) DEFAULT NULL COMMENT 'Description of Content',
  `subTableTemplate` text NOT NULL COMMENT 'The Sub Table Template',
  PRIMARY KEY (`subTableId`,`languageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `dbdocssubtables` */

LOCK TABLES `dbdocssubtables` WRITE;
/*!40000 ALTER TABLE `dbdocssubtables` DISABLE KEYS */;

insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (1,0,'GM Levels','Value|<Description\r\n0|Player\r\n1|Moderator\r\n2|Gamemaster\r\n3|Administrator\r\n4|Access control within the world server console only');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (2,0,'Expansions','Value|<Expansion\r\n0|World of Warcraft *\r\n1|Burning Crusade \r\n2|Wrath of the Lich King \r\n3|Cataclysm \r\n4|Mists of Pandaria\r\n5|Warlords of Draenor');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (3,0,'Languages','Value|<Language\r\n0|English \r\n1|Korean \r\n2|French \r\n3|German \r\n4|Chinese \r\n5|Taiwanese \r\n6|Spanish Spain \r\n7|Spanish Latin America \r\n8|Russian');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (4,0,'Ban Status','Value|<Meaning\r\n0|Ban Active\r\n1|Ban Inactive');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (5,0,'Realm Type/Icon','Icon|<Meaning\r\n0|Normal\r\n1|PvP\r\n4|Normal\r\n6|RP\r\n8|RP PvP');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (6,0,'Realm Flags','Value|<Meaning\r\n0x1|Invalid - Do Not show in Realm List\r\n0x2|Offline - Set by mangosd\r\n0x4|Show version and build in Realm List\r\n0x20|New Players - New Players Only\r\n0x40|Recommended - sets the recommended option');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (7,0,'Timezones','TimezoneId|<Displayed Name\r\n1|Development\r\n2|United States\r\n3|Oceanic\r\n4|Latin America\r\n5|Tournament\r\n6|Korea\r\n7|Tournament\r\n8|English\r\n9|German\r\n10|French\r\n11|Spanish\r\n12|Russian\r\n13|Tournament\r\n14|Taiwan\r\n15|Tournament\r\n16|China\r\n17|CN1\r\n18|CN2\r\n19|CN3\r\n20|CN4\r\n21|CN5\r\n22|CN6\r\n23|CN7\r\n24|CN8\r\n25|Tournament\r\n26|Test Server\r\n27|Tournament\r\n28|QA Server\r\n29|CN9');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (8,0,'Population Level','Population|<Displayed Population Level\r\n0.5|Low\r\n1.0|Medium\r\n2.0|High');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (9,0,'Realm Builds','Core|<Build Number(s)|<Version Number\r\nMangosZero|5875 6005 6141|Vanila 1.12.x\r\nMangosOne|8606|TBC 2.4.3\r\nMangosTwo|12340|WOTLK 3.3.5a\r\nMangosThree|15595|Cataclysm 4.3.4\r\nMangosFour|18414|Mists of Pandaria 5.4.8');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (10,0,'Class Stats Levels','Value|Name|<Description\r\n1|Warrior|Has increased health and no mana\r\n2|Paladin|Has increased health and low mana\r\n4|Rogue|Has increased damage, but lower armor\r\n8|Mage|Has low health, but increased mana');
insert  into `dbdocssubtables`(`subTableId`,`languageId`,`subTableName`,`subTableTemplate`) values (11,0,'Account Types','Value|<Account Type\n0|Normal User\n1|Playerbot account');
/*!40000 ALTER TABLE `dbdocssubtables` ENABLE KEYS */;
UNLOCK TABLES;

/*Table structure for table `dbdocssubtables_localised` */

DROP TABLE IF EXISTS `dbdocssubtables_localised`;

CREATE TABLE `dbdocssubtables_localised` (
  `subTableId` int(11) NOT NULL COMMENT 'dbdocsSubtableId to link to',
  `languageId` int(11) NOT NULL DEFAULT '2' COMMENT 'dbdocsLanguageId to link to.',
  `subTableTemplate` text NOT NULL COMMENT 'The Sub Table Template',
  PRIMARY KEY (`subTableId`,`languageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `dbdocssubtables_localised` */

LOCK TABLES `dbdocssubtables_localised` WRITE;
/*!40000 ALTER TABLE `dbdocssubtables_localised` DISABLE KEYS */;

insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (1,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (2,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (3,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (4,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (5,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (6,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (7,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (8,2,'');
insert  into `dbdocssubtables_localised`(`subTableId`,`languageId`,`subTableTemplate`) values (9,2,'');
/*!40000 ALTER TABLE `dbdocssubtables_localised` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dbdocstable`
--

DROP TABLE IF EXISTS `dbdocstable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbdocstable` (
  `tableId` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique ID of this entry',
  `languageId` int(11) NOT NULL DEFAULT '0' COMMENT 'The Language Id for the Notes (Normally 0 for English)',
  `tableName` varchar(80) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Name of the table to add additional notes to',
  `tableNotes` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'The additional note to be added to the table',
  PRIMARY KEY (`tableId`,`languageId`,`tableName`),
  KEY `tableId` (`tableId`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Data for the table `dbdocstable`
--

LOCK TABLES `dbdocstable` WRITE;
/*!40000 ALTER TABLE `dbdocstable` DISABLE KEYS */;

insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (1,0,'account','This table holds information on all available accounts.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (2,0,'account_banned','This table lists all of the accounts that have been banned along with the date when (or if) the ban will expire.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (3,0,'dbdocsfields','This table is part of the implementation of the \'Mangos Database Documentation\' (MDD) Project.\r\n\r\nAn entry in this table provides a link to the table and field to allow additional notes to describe the field in the Wiki.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (5,0,'dbdocssubtables','This table is part of the implementation of the \'Mangos Database Documentation\' (MDD) Project.\r\n\r\nAn entry in this table provides a table which dirctly replaces the link in the fieldnotes.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (7,0,'dbdocstable','This table is part of the implementation of the \'Mangos Database Documentation\' (MDD) Project.\r\n\r\nAn entry in this table provides a additional notes field to describe the database in the Wiki.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (9,0,'ip_banned','This table contains all of the banned IPs and the date when (or if) the ban will expire.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (10,0,'realmcharacters','This table holds information on the number of characters each account has for each realm.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (11,0,'realmd_db_version','This table contains the latest version of the database.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (12,0,'realmlist','This table holds information for the realms.');
insert  into `dbdocstable`(`tableId`,`languageId`,`tableName`,`tableNotes`) values (13,0,'uptime','This table holds the uptime of all realms. Each realm will automatically update it\'s latest entry\'s value until it crashes and a new record is added. Doing so also some statistical/historical information is collected - about the availability of your machine.');
/*!40000 ALTER TABLE `dbdocstable` ENABLE KEYS */;
UNLOCK TABLES;

/*Table structure for table `dbdocstable_localised` */

DROP TABLE IF EXISTS `dbdocstable_localised`;

CREATE TABLE `dbdocstable_localised` (
  `tableId` int(11) NOT NULL COMMENT 'The dbdocsTableId to link to',
  `languageId` int(11) NOT NULL DEFAULT '2' COMMENT 'The dbdocsLanguageId to link to',
  `tableNotes` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'The additional note to be added to the table',
  PRIMARY KEY (`tableId`,`languageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `dbdocstable_localised` */

LOCK TABLES `dbdocstable_localised` WRITE;
/*!40000 ALTER TABLE `dbdocstable_localised` DISABLE KEYS */;

insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (1,2,'Cette table contient toutes les informations relatives aux comptes.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (2,2,'Cette table contient toutes les informations relatives aux comptes bannis ainsi que la durée du bannissement.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (3,2,'Cette table est une partie de l\'implémentation du projet MDD: \'Mangos Database Documentation\'. \r\n\r\nUne entrée dans cette table fournit un lien vers la table et un champ permet l\'ajout de notes additionnelles pour leur description dans le Wiki.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (4,2,'Cette table est une partie de l\'implémentation du projet MDD: \'Mangos Database Documentation\'. \r\n\r\nUne entrée dans cette table fournit un lien vers la table et un champ permet l\'ajout de notes additionnelles pour leur description dans le Wiki.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (5,2,'Cette table est une partie de l\'implémentation du projet MDD: \'Mangos Database Documentation\'.\r\n\r\nUne entrée dans cette table fournit une table qui remplace directement le lien dans la description du champ.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (6,2,'Cette table est une partie de l\'implémentation du projet MDD: \'Mangos Database Documentation\'.\r\n\r\nUne entrée dans cette table fournit une table qui remplace directement le lien dans la description du champ.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (7,2,'Cette table est une partie de l\'implémentation du projet MDD: \'Mangos Database Documentation\'.\r\n\r\nAn entry in this table provides a additional notes field to describe the database in the Wiki.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (8,2,'Cette table est une partie de l\'implémentation du projet MDD: \'Mangos Database Documentation\'.\r\n\r\nAn entry in this table provides a additional notes field to describe the database in the Wiki.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (9,2,'Cette table contient toutes les IP bannies ainsi que la durée du bannissement.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (10,2,'Cette table contient les informations relatives au nombre de personnages pour chaque compte et chaque royaume.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (11,2,'Cette table contient la version de la base de données.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (12,2,'Cette table contient les informations sur les royaumes.');
insert  into `dbdocstable_localised`(`tableId`,`languageId`,`tableNotes`) values (13,2,'Cette table contient l\'information de disponibilité de chaque royaume. Chaque royaume met à jour automatiquement sa dernière entrée jusqu\'à son arrêt. Permet également la collecte de statistiques relative à l\'activité du royaume.');
/*!40000 ALTER TABLE `dbdocstable_localised` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ip_banned`
--

DROP TABLE IF EXISTS `ip_banned`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_banned` (
  `ip` varchar(32) NOT NULL DEFAULT '0.0.0.0',
  `bandate` bigint(40) NOT NULL,
  `unbandate` bigint(40) NOT NULL,
  `bannedby` varchar(50) NOT NULL DEFAULT '[Console]',
  `banreason` varchar(255) NOT NULL DEFAULT 'no reason',
  PRIMARY KEY (`ip`,`bandate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Banned IPs';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ip_banned`
--

LOCK TABLES `ip_banned` WRITE;
/*!40000 ALTER TABLE `ip_banned` DISABLE KEYS */;
/*!40000 ALTER TABLE `ip_banned` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `realmcharacters`
--

DROP TABLE IF EXISTS `realmcharacters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `realmcharacters` (
  `realmid` int(11) unsigned NOT NULL COMMENT 'Realm identifier',
  `acctid` int(11) unsigned NOT NULL COMMENT 'Account identifier',
  `numchars` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`realmid`,`acctid`),
  KEY `acctid` (`acctid`),
  CONSTRAINT `realmcharacters_ibfk_1` FOREIGN KEY (`realmid`) REFERENCES `realmlist` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Realm Character Tracker';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `realmcharacters`
--

LOCK TABLES `realmcharacters` WRITE;
/*!40000 ALTER TABLE `realmcharacters` DISABLE KEYS */;
/*!40000 ALTER TABLE `realmcharacters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `realmlist`
--

DROP TABLE IF EXISTS `realmlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `realmlist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Realm identifier',
  `name` varchar(32) NOT NULL DEFAULT '',
  `address` varchar(32) NOT NULL DEFAULT '127.0.0.1',
  `localAddress` varchar(255) NOT NULL DEFAULT '127.0.0.1',
  `localSubnetMask` varchar(255) NOT NULL DEFAULT '255.255.255.0',
  `port` int(11) NOT NULL DEFAULT '8085',
  `icon` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `realmflags` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT 'Supported masks: 0x1 (invalid, not show in realm list), 0x2 (offline, set by mangosd), 0x4 (show version and build), 0x20 (new players), 0x40 (recommended)',
  `timezone` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `allowedSecurityLevel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `population` float unsigned NOT NULL DEFAULT '0',
  `realmbuilds` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Realm System';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `realmlist`
--

LOCK TABLES `realmlist` WRITE;
/*!40000 ALTER TABLE `realmlist` DISABLE KEYS */;
/*!40000 ALTER TABLE `realmlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uptime`
--

DROP TABLE IF EXISTS `uptime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uptime` (
  `realmid` int(11) unsigned NOT NULL COMMENT 'Realm identifier',
  `starttime` bigint(20) unsigned NOT NULL DEFAULT '0',
  `startstring` varchar(64) NOT NULL DEFAULT '',
  `uptime` bigint(20) unsigned NOT NULL DEFAULT '0',
  `maxplayers` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`realmid`,`starttime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Uptime system';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uptime`
--

LOCK TABLES `uptime` WRITE;
/*!40000 ALTER TABLE `uptime` DISABLE KEYS */;
/*!40000 ALTER TABLE `uptime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- table structure for warden_log
--

DROP TABLE IF EXISTS `warden_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `warden_log` (
  `entry` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Log entry ID',
  `check` smallint(5) unsigned NOT NULL COMMENT 'Failed Warden check ID',
  `action` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Action taken (enum WardenActions)',
  `account` int(11) unsigned NOT NULL COMMENT 'Account ID',
  `guid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Player GUID',
  `map` int(11) unsigned DEFAULT NULL COMMENT 'Map ID',
  `position_x` float DEFAULT NULL COMMENT 'Player position X',
  `position_y` float DEFAULT NULL COMMENT 'Player position Y',
  `position_z` float DEFAULT NULL COMMENT 'Player position Z',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date of the log entry',
  PRIMARY KEY (`entry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Warden log of failed checks';
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed