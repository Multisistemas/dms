
# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.34)
# Database: dms
# Generation Time: 2017-05-18 13:12:59 +0000
# ************************************************************

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tblProcesses
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblProcesses`;

CREATE TABLE `tblProcesses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modified` int(11) DEFAULT NULL,
  `modifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idxProcessesNameUnique` (`name`),
  KEY `fkProcessesCreatedBy` (`createdBy`),
  KEY `fkProcessesModifiedBy` (`modifiedBy`),
  CONSTRAINT `fkProcessesCreatedBy` FOREIGN KEY (`createdBy`) REFERENCES `tblUsers` (`id`),
  CONSTRAINT `fkProcessesModifiedBy` FOREIGN KEY (`modifiedBy`) REFERENCES `tblUsers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgProcessesCreated` BEFORE INSERT ON `tblProcesses` FOR EACH ROW SET new.`created` = UNIX_TIMESTAMP(NOW()) */;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgProcessesModified` BEFORE UPDATE ON `tblProcesses` FOR EACH ROW SET new.`modified` = UNIX_TIMESTAMP(NOW()) */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblNonconformities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblNonconformities`;

CREATE TABLE `tblNonconformities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL DEFAULT '',
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  `analysis` VARCHAR(255) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modified` int(11) DEFAULT NULL,
  `modifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkNonconformitiesCreatedBy` (`createdBy`),
  KEY `fkNonconformitiesModifiedBy` (`modifiedBy`),
  CONSTRAINT `fkNonconformitiesCreatedBy` FOREIGN KEY (`createdBy`) REFERENCES `tblUsers` (`id`),
  CONSTRAINT `fkNonconformitiesModifiedBy` FOREIGN KEY (`modifiedBy`) REFERENCES `tblUsers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgNonconformitiesCreated` BEFORE INSERT ON `tblNonconformities` FOR EACH ROW SET new.`created` = UNIX_TIMESTAMP(NOW()) */;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgNonconformitiesModified` BEFORE UPDATE ON `tblNonconformities` FOR EACH ROW SET new.`modified` = UNIX_TIMESTAMP(NOW()) */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblActions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblActions`;

CREATE TABLE `tblActions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nonconformityId` int(11) NOT NULL,
  `description` VARCHAR(255) NOT NULL DEFAULT '',
  `dateEnd` int(11) NOT NULL, 
  `created` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modified` int(11) DEFAULT NULL,
  `modifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkActionsCreatedBy` (`createdBy`),
  KEY `fkActionsModifiedBy` (`modifiedBy`),
  CONSTRAINT `fkActionsCreatedBy` FOREIGN KEY (`createdBy`) REFERENCES `tblUsers` (`id`),
  CONSTRAINT `fkActionsModifiedBy` FOREIGN KEY (`modifiedBy`) REFERENCES `tblUsers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgActionsCreated` BEFORE INSERT ON `tblActions` FOR EACH ROW SET new.`created` = UNIX_TIMESTAMP(NOW()) */;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgActionsModified` BEFORE UPDATE ON `tblActions` FOR EACH ROW SET new.`modified` = UNIX_TIMESTAMP(NOW()) */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblProcessOwners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblProcessOwners`;

CREATE TABLE `tblProcessOwners` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `processId` int(11) unsigned NOT NULL,
  `userId` int(11) NOT NULL,
  `created` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modified` int(11) DEFAULT NULL,
  `modifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkProcessOwnersProcessId` (`processId`),
  KEY `fkProcessOwnersUserId` (`userId`),
  KEY `fkProcessOwnersCreatedBy` (`createdBy`),
  KEY `fkProcessOwnersModifiedBy` (`modifiedBy`),
  CONSTRAINT `fkProcessOwnersProcessId` FOREIGN KEY (`processId`) REFERENCES `tblProcesses` (`id`),
  CONSTRAINT `fkProcessOwnersUserId` FOREIGN KEY (`userId`) REFERENCES `tblUsers` (`id`),
  CONSTRAINT `fkProcessOwnersCreatedBy` FOREIGN KEY (`createdBy`) REFERENCES `tblUsers` (`id`),
  CONSTRAINT `fkProcessOwnersModifiedBy` FOREIGN KEY (`modifiedBy`) REFERENCES `tblUsers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgProcessOwnersCreated` BEFORE INSERT ON `tblProcessOwners` FOR EACH ROW SET new.`created` = UNIX_TIMESTAMP(NOW()) */;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgProcessOwnersModified` BEFORE UPDATE ON `tblProcessOwners` FOR EACH ROW SET new.`modified` = UNIX_TIMESTAMP(NOW()) */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblActionsFollows
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblActionsFollows`;

CREATE TABLE `tblActionsFollows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actionId` int(11) NOT NULL,
  `followResult` VARCHAR(255) NOT NULL DEFAULT '',
  `indicatorBefore` varchar(255) NOT NULL DEFAULT '',
  `indicatorAfter` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `modified` int(11) DEFAULT NULL,
  `modifiedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fkActionsFollowsActionId` (`actionId`),
  KEY `fkActionsFollowsCreatedBy` (`createdBy`),
  KEY `fkActionsFollowsModifiedBy` (`modifiedBy`),
  CONSTRAINT `fkActionsFollowsActionId` FOREIGN KEY (`actionId`) REFERENCES `tblActions` (`id`),
  CONSTRAINT `fkActionsFollowsCreatedBy` FOREIGN KEY (`createdBy`) REFERENCES `tblUsers` (`id`),
  CONSTRAINT `fkActionsFollowsModifiedBy` FOREIGN KEY (`modifiedBy`) REFERENCES `tblUsers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgActionsFollowsCreated` BEFORE INSERT ON `tblActionsFollows` FOR EACH ROW SET new.`created` = UNIX_TIMESTAMP(NOW()) */;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `tgActionsFollowsModified` BEFORE UPDATE ON `tblActionsFollows` FOR EACH ROW SET new.`modified` = UNIX_TIMESTAMP(NOW()) */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;