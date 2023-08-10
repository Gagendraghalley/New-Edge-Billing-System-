/*
SQLyog Community v12.5.0 (64 bit)
MySQL - 10.4.24-MariaDB : Database - task_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`task_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `task_db`;

/*Table structure for table `organization_details` */

DROP TABLE IF EXISTS `organization_details`;

CREATE TABLE `organization_details` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `org_code` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(3) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `organization_details` */

insert  into `organization_details`(`id`,`name`,`description`,`org_code`,`status`,`created_at`,`created_by`) values 
('1a9246f8-61fb-4ab5-805a-e547fcd0c896','New Edge Technologies Pvt Limited','New Edge Technologies is one of private company with IT solution like software, network and system','0002',1,'2023-07-10 04:50:05','19eb962b-4134-4200-bf92-afa120b180e6'),
('4d52cf3c-a6c9-4cdd-ab50-d30380f62ee3','NGN Pvt Limited','NGN is one of the private company in bhutan','0003',1,'2023-07-10 04:11:45','19eb962b-4134-4200-bf92-afa120b180e6'),
('8070fd6f-fc67-4379-99c3-048e325f7d27','No Company','Meaning independently working','0003',1,'2023-07-10 04:26:38','19eb962b-4134-4200-bf92-afa120b180e6'),
('a4d723c0-37ab-4e48-9690-50bfaa3c8ca8','Tashi Cell Private Limited','Tashi Cell Private Limited is one of the biggest private company in the bhutan','0001',1,'2023-07-09 07:51:46','19eb962b-4134-4200-bf92-afa120b180e6');

/*Table structure for table `task_details` */

DROP TABLE IF EXISTS `task_details`;

CREATE TABLE `task_details` (
  `id` varchar(36) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `priority` char(20) DEFAULT NULL,
  `taskStatus` char(20) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `userId` varchar(36) DEFAULT NULL,
  `ownership` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `task_details` */

/*Table structure for table `task_history` */

DROP TABLE IF EXISTS `task_history`;

CREATE TABLE `task_history` (
  `id` varchar(36) NOT NULL,
  `parent_id` varchar(36) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `priority` char(20) DEFAULT NULL,
  `taskStatus` char(20) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `userId` varchar(36) DEFAULT NULL,
  `ownership` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `task_history` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `company` char(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `phone_number` int(12) DEFAULT NULL,
  `system_role` varchar(20) DEFAULT NULL,
  `profile_path` varchar(50) DEFAULT NULL,
  `org_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`password`,`company`,`position`,`name`,`phone_number`,`system_role`,`profile_path`,`org_id`,`created_at`,`created_by`,`status`) values 
('19eb962b-4134-4200-bf92-afa120b180e6','taskinfo41@gmail.com','$2y$10$j47N./SAwvFMMY.7CVs4QOOz1qlYnK9MffED0qlJYomGthqHGyqAy',NULL,'System Administrator','Administrator',17511745,'Admin','userProfile/1689006263_dddddtttt (1).jpg','8070fd6f-fc67-4379-99c3-048e325f7d27','2023-07-11 07:30:09',NULL,1);

/*Table structure for table `users_history` */

DROP TABLE IF EXISTS `users_history`;

CREATE TABLE `users_history` (
  `id` varchar(36) NOT NULL,
  `parent_id` varchar(36) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `company` char(50) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `name` char(50) DEFAULT NULL,
  `phone_number` int(12) DEFAULT NULL,
  `system_role` varchar(20) DEFAULT NULL,
  `profile_path` varchar(50) DEFAULT NULL,
  `org_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` varchar(36) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `users_history` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
