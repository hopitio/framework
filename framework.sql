/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.29 : Database - framework
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cores_department` */

DROP TABLE IF EXISTS `cores_department`;

CREATE TABLE `cores_department` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `depCode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `depName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `depFk` int(11) DEFAULT '0',
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stt` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cores_department` */

insert  into `cores_department`(`pk`,`depCode`,`depName`,`depFk`,`path`,`stt`) values (1,'lap-trinh','Lap trinh',0,'/1/',1),(2,'trien-khai','Trien khai',1,'/1/2/',1),(3,'kinh-doanh','Kinh Doanh',0,'/3/',1),(4,'hanh-chinh','Hanh chinh',2,'/1/2/4/',1),(5,'tes','tes',0,'/5/',1),(6,'Nghien cuu','Nghien cuu',0,'/6/',1),(7,'adasdsad','sdasd',0,'/7/',1),(8,'sadsa','sdsadas',1,'/1/8/',1);

/*Table structure for table `cores_group` */

DROP TABLE IF EXISTS `cores_group`;

CREATE TABLE `cores_group` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `groupCode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `groupName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stt` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cores_group` */

/*Table structure for table `cores_group_permission` */

DROP TABLE IF EXISTS `cores_group_permission`;

CREATE TABLE `cores_group_permission` (
  `groupFk` int(11) NOT NULL,
  `permission` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`groupFk`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cores_group_permission` */

/*Table structure for table `cores_preference` */

DROP TABLE IF EXISTS `cores_preference`;

CREATE TABLE `cores_preference` (
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `preference` text COLLATE utf8_unicode_ci,
  `userFk` int(11) DEFAULT '0',
  PRIMARY KEY (`uri`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cores_preference` */

/*Table structure for table `cores_user` */

DROP TABLE IF EXISTS `cores_user`;

CREATE TABLE `cores_user` (
  `pk` int(11) NOT NULL AUTO_INCREMENT,
  `fullName` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jobTitle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `depFk` int(11) DEFAULT NULL,
  `account` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pass` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stt` tinyint(4) DEFAULT '1',
  `isAdmin` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`pk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cores_user` */

insert  into `cores_user`(`pk`,`fullName`,`jobTitle`,`depFk`,`account`,`pass`,`email`,`phone`,`stt`,`isAdmin`) values (1,'Admin','Admin',0,'admin',NULL,NULL,NULL,1,1);

/*Table structure for table `cores_user_permission` */

DROP TABLE IF EXISTS `cores_user_permission`;

CREATE TABLE `cores_user_permission` (
  `userFk` int(11) NOT NULL,
  `permission` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`userFk`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cores_user_permission` */

/*Table structure for table `cores_user_search` */

DROP TABLE IF EXISTS `cores_user_search`;

CREATE TABLE `cores_user_search` (
  `userFk` int(11) NOT NULL,
  `keyword` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`keyword`,`userFk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cores_user_search` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
