# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.14)
# Database: webport
# Generation Time: 2017-02-07 09:42:01 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table amyen_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `amyen_types`;

CREATE TABLE `amyen_types` (
  `amyen_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`amyen_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_colors
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_colors`;

CREATE TABLE `boat_colors` (
  `boat_color_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`boat_color_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_engines
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_engines`;

CREATE TABLE `boat_engines` (
  `boat_id` int(11) DEFAULT NULL,
  `engine_id` int(11) DEFAULT NULL,
  KEY `fk_boats_has_engines_engines1_idx` (`engine_id`),
  KEY `fk_boats_has_engines_boats1_idx` (`boat_id`),
  CONSTRAINT `fk_boats_has_engines_boats1` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`boat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_boats_has_engines_engines1` FOREIGN KEY (`engine_id`) REFERENCES `engines` (`engine_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_histories`;

CREATE TABLE `boat_histories` (
  `boat_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `boat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`boat_history_id`),
  KEY `fk_histories_boats1_idx` (`boat_id`),
  CONSTRAINT `fk_histories_boats1` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`boat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_images`;

CREATE TABLE `boat_images` (
  `boat_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `boat_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`boat_image_id`),
  KEY `fk_boat_images_boats1_idx` (`boat_id`),
  CONSTRAINT `fk_boat_images_boats1` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`boat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_kinds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_kinds`;

CREATE TABLE `boat_kinds` (
  `boat_kind_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`boat_kind_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_materials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_materials`;

CREATE TABLE `boat_materials` (
  `boat_material_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`boat_material_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_owners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_owners`;

CREATE TABLE `boat_owners` (
  `boat_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `get_date` date DEFAULT NULL,
  `percent` float(10,2) DEFAULT NULL,
  `owner_status_id` int(11) DEFAULT NULL,
  KEY `fk_boats_has_owners_owner1_idx` (`owner_id`),
  KEY `fk_boats_has_owners_boats1_idx` (`boat_id`),
  KEY `fk_boat_owners_owner_status1_idx` (`owner_status_id`),
  CONSTRAINT `fk_boats_has_owners_boats1` FOREIGN KEY (`boat_id`) REFERENCES `boats` (`boat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_boats_has_owners_owners1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_boat_owners_owner_status1` FOREIGN KEY (`owner_status_id`) REFERENCES `owner_status` (`owner_status_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_ports
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_ports`;

CREATE TABLE `boat_ports` (
  `boat_port_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`boat_port_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_status`;

CREATE TABLE `boat_status` (
  `boat_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`boat_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boat_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boat_types`;

CREATE TABLE `boat_types` (
  `boat_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`boat_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table boats
# ------------------------------------------------------------

DROP TABLE IF EXISTS `boats`;

CREATE TABLE `boats` (
  `boat_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `boat_port_id` int(11) DEFAULT NULL,
  `registry_type_id` int(11) DEFAULT NULL,
  `registry_number` varchar(255) DEFAULT NULL,
  `registry_date` date DEFAULT NULL,
  `amyen_type_id` int(11) DEFAULT NULL,
  `amyen_number` varchar(255) DEFAULT NULL,
  `dds` varchar(255) DEFAULT NULL,
  `dsp` varchar(255) DEFAULT NULL,
  `length` varchar(255) DEFAULT NULL,
  `width` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `boat_color_id` int(11) DEFAULT NULL,
  `boat_material_id` int(11) DEFAULT NULL,
  `boat_type_id` int(11) DEFAULT NULL,
  `is_fast` tinyint(1) DEFAULT NULL,
  `license_expired_date` date DEFAULT NULL,
  `boat_status_id` int(11) DEFAULT NULL,
  `movement_type_id` int(11) DEFAULT NULL,
  `builder` varchar(255) DEFAULT NULL,
  `boat_kind_id` int(11) DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`boat_id`),
  KEY `fk_boats_ports_idx` (`boat_port_id`),
  KEY `fk_boats_registry_types1_idx` (`registry_type_id`),
  KEY `fk_boats_amyen_types1_idx` (`amyen_type_id`),
  KEY `fk_boats_colors1_idx` (`boat_color_id`),
  KEY `fk_boats_materials1_idx` (`boat_material_id`),
  KEY `fk_boats_boat_types1_idx` (`boat_type_id`),
  KEY `fk_boats_boat_status1_idx` (`boat_status_id`),
  KEY `fk_boats_movement_types1_idx` (`movement_type_id`),
  KEY `fk_boats_boat_kinds1_idx` (`boat_kind_id`),
  CONSTRAINT `fk_boats_amyen_types1` FOREIGN KEY (`amyen_type_id`) REFERENCES `amyen_types` (`amyen_type_id`),
  CONSTRAINT `fk_boats_boat_kinds1` FOREIGN KEY (`boat_kind_id`) REFERENCES `boat_kinds` (`boat_kind_id`),
  CONSTRAINT `fk_boats_boat_status1` FOREIGN KEY (`boat_status_id`) REFERENCES `boat_status` (`boat_status_id`),
  CONSTRAINT `fk_boats_boat_types1` FOREIGN KEY (`boat_type_id`) REFERENCES `boat_types` (`boat_type_id`),
  CONSTRAINT `fk_boats_colors1` FOREIGN KEY (`boat_color_id`) REFERENCES `boat_colors` (`boat_color_id`),
  CONSTRAINT `fk_boats_materials1` FOREIGN KEY (`boat_material_id`) REFERENCES `boat_materials` (`boat_material_id`),
  CONSTRAINT `fk_boats_movement_types1` FOREIGN KEY (`movement_type_id`) REFERENCES `movement_types` (`movement_type_id`),
  CONSTRAINT `fk_boats_ports` FOREIGN KEY (`boat_port_id`) REFERENCES `boat_ports` (`boat_port_id`),
  CONSTRAINT `fk_boats_registry_types1` FOREIGN KEY (`registry_type_id`) REFERENCES `registry_types` (`registry_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engine_brands
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engine_brands`;

CREATE TABLE `engine_brands` (
  `engine_brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`engine_brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engine_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engine_histories`;

CREATE TABLE `engine_histories` (
  `engine_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `event_date` varchar(255) DEFAULT NULL,
  `engine_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`engine_history_id`),
  KEY `fk_engine_histories_engines1_idx` (`engine_id`),
  CONSTRAINT `fk_engine_histories_engines1` FOREIGN KEY (`engine_id`) REFERENCES `engines` (`engine_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engine_images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engine_images`;

CREATE TABLE `engine_images` (
  `engine_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `engine_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`engine_image_id`),
  KEY `fk_engine_images_engines1_idx` (`engine_id`),
  CONSTRAINT `fk_engine_images_engines1` FOREIGN KEY (`engine_id`) REFERENCES `engines` (`engine_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engine_kinds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engine_kinds`;

CREATE TABLE `engine_kinds` (
  `engine_kind_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`engine_kind_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engine_power_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engine_power_types`;

CREATE TABLE `engine_power_types` (
  `engine_power_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`engine_power_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engine_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engine_status`;

CREATE TABLE `engine_status` (
  `engine_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`engine_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engine_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engine_types`;

CREATE TABLE `engine_types` (
  `engine_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`engine_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table engines
# ------------------------------------------------------------

DROP TABLE IF EXISTS `engines`;

CREATE TABLE `engines` (
  `engine_id` int(11) NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(255) DEFAULT NULL,
  `engine_type_id` int(11) DEFAULT NULL,
  `engine_kind_id` int(11) DEFAULT NULL,
  `engine_power_type_id` int(11) DEFAULT NULL,
  `power` varchar(255) DEFAULT NULL,
  `engine_status_id` int(11) DEFAULT NULL,
  `engine_brand_id` int(11) DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`engine_id`),
  KEY `fk_engines_engine_types1_idx` (`engine_type_id`),
  KEY `fk_engines_engine_kinds1_idx` (`engine_kind_id`),
  KEY `fk_engines_power_types1_idx` (`engine_power_type_id`),
  KEY `fk_engines_engine_status1_idx` (`engine_status_id`),
  KEY `fk_engines_engine_brands1_idx` (`engine_brand_id`),
  CONSTRAINT `fk_engines_engine_brands1` FOREIGN KEY (`engine_brand_id`) REFERENCES `engine_brands` (`engine_brand_id`),
  CONSTRAINT `fk_engines_engine_kinds1` FOREIGN KEY (`engine_kind_id`) REFERENCES `engine_kinds` (`engine_kind_id`),
  CONSTRAINT `fk_engines_engine_status1` FOREIGN KEY (`engine_status_id`) REFERENCES `engine_status` (`engine_status_id`),
  CONSTRAINT `fk_engines_engine_types1` FOREIGN KEY (`engine_type_id`) REFERENCES `engine_types` (`engine_type_id`),
  CONSTRAINT `fk_engines_power_types1` FOREIGN KEY (`engine_power_type_id`) REFERENCES `engine_power_types` (`engine_power_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table license_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `license_status`;

CREATE TABLE `license_status` (
  `license_statu_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`license_statu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table movement_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `movement_types`;

CREATE TABLE `movement_types` (
  `movement_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`movement_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table options
# ------------------------------------------------------------

DROP TABLE IF EXISTS `options`;

CREATE TABLE `options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(255) DEFAULT NULL,
  `option_value` text,
  PRIMARY KEY (`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table owner_engines
# ------------------------------------------------------------

DROP TABLE IF EXISTS `owner_engines`;

CREATE TABLE `owner_engines` (
  `engine_id` int(11) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  KEY `fk_engines_has_owners_owners1_idx` (`owner_id`),
  KEY `fk_engines_has_owners_engines1_idx` (`engine_id`),
  CONSTRAINT `fk_engines_has_owners_engines1` FOREIGN KEY (`engine_id`) REFERENCES `engines` (`engine_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_engines_has_owners_owners1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table owner_histories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `owner_histories`;

CREATE TABLE `owner_histories` (
  `owner_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`owner_history_id`),
  KEY `fk_owner_histories_owners1_idx` (`owner_id`),
  CONSTRAINT `fk_owner_histories_owners1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table owner_images
# ------------------------------------------------------------

DROP TABLE IF EXISTS `owner_images`;

CREATE TABLE `owner_images` (
  `owner_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`owner_image_id`),
  KEY `fk_owner_images_owners1_idx` (`owner_id`),
  CONSTRAINT `fk_owner_images_owners1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table owner_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `owner_status`;

CREATE TABLE `owner_status` (
  `owner_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`owner_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table owners
# ------------------------------------------------------------

DROP TABLE IF EXISTS `owners`;

CREATE TABLE `owners` (
  `owner_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `fathername` varchar(255) DEFAULT NULL,
  `adt` varchar(255) DEFAULT NULL,
  `afm` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table page_records
# ------------------------------------------------------------

DROP TABLE IF EXISTS `page_records`;

CREATE TABLE `page_records` (
  `page_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`page_record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`permission_id`, `name`)
VALUES
	(1,'Administrator'),
	(2,'User'),
	(3,'Viewer');

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table registry_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `registry_types`;

CREATE TABLE `registry_types` (
  `registry_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`registry_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=greek;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL COMMENT '	',
  `password` varchar(255) DEFAULT NULL,
  `permission_id` int(11) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `fk_users_permissions1_idx` (`permission_id`),
  CONSTRAINT `fk_users_permissions1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`user_id`, `username`, `password`, `permission_id`, `firstname`, `lastname`)
VALUES
	(1,'admin','202cb962ac59075b964b07152d234b70',1,'Administrator','System');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
