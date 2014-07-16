-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 15, 2014 at 05:01 AM
-- Server version: 5.5.37
-- PHP Version: 5.4.4-14+deb7u11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sba_techtracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `activation`
--
-- Creation: Jul 15, 2014 at 10:34 AM
--

DROP TABLE IF EXISTS `activation`;
CREATE TABLE IF NOT EXISTS `activation` (
  `store_number` varchar(8) NOT NULL,
  `start_timestamp_gmt` bigint(20) DEFAULT NULL,
  `activation_type` varchar(45) DEFAULT NULL,
  `primary_access_vendor` varchar(45) DEFAULT NULL,
  `backup_carrier` varchar(45) DEFAULT NULL,
  `eon_number` int(6) unsigned zerofill DEFAULT NULL,
  `ops_console_number` varchar(9) DEFAULT NULL,
  `activation_is_tonight` tinyint(1) NOT NULL DEFAULT '0',
  `activation_past_start` tinyint(1) NOT NULL DEFAULT '0',
  `activation_waiting` tinyint(1) NOT NULL DEFAULT '0',
  `activation_in_progress` tinyint(1) NOT NULL DEFAULT '0',
  `bridge_access_code` int(7) DEFAULT NULL,
  `sa_tech` varchar(45) DEFAULT NULL,
  `field_tech_name` varchar(100) NOT NULL,
  `current_step` int(11) DEFAULT NULL,
  `time_step_changed` bigint(20) NOT NULL,
  `help` tinyint(1) NOT NULL DEFAULT '0',
  `help_time` bigint(20) NOT NULL,
  `activation_completed` tinyint(1) NOT NULL DEFAULT '0',
  `abort` tinyint(4) NOT NULL DEFAULT '0',
  `help_reason` varchar(255) NOT NULL,
  PRIMARY KEY (`store_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `activation_steps`
--
-- Creation: Jul 07, 2014 at 10:16 AM
--

DROP TABLE IF EXISTS `activation_steps`;
CREATE TABLE IF NOT EXISTS `activation_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `step_number` int(11) NOT NULL,
  `activation_type` varchar(45) NOT NULL,
  `step_data` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `bridge`
--
-- Creation: Jul 15, 2014 at 10:34 AM
--

DROP TABLE IF EXISTS `bridge`;
CREATE TABLE IF NOT EXISTS `bridge` (
  `bridge_access_code` int(7) NOT NULL,
  `bridge_pin` int(4) DEFAULT NULL,
  PRIMARY KEY (`bridge_access_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `escalation_information`
--
-- Creation: Jul 15, 2014 at 10:35 AM
--

DROP TABLE IF EXISTS `escalation_information`;
CREATE TABLE IF NOT EXISTS `escalation_information` (
  `vendor_name` varchar(45) NOT NULL,
  `vendor_number` varchar(20) DEFAULT NULL,
  `escalation_number_1` varchar(20) DEFAULT NULL,
  `escalation_number_2` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`vendor_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `field_tech`
--
-- Creation: Jul 15, 2014 at 05:44 AM
--

DROP TABLE IF EXISTS `field_tech`;
CREATE TABLE IF NOT EXISTS `field_tech` (
  `field_tech_name` varchar(45) NOT NULL,
  `field_tech_cell_number` varchar(20) DEFAULT NULL,
  `field_tech_email` varchar(45) DEFAULT NULL,
  `field_tech_vendor` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`field_tech_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ft_ratings`
--
-- Creation: Jul 15, 2014 at 09:22 AM
--

DROP TABLE IF EXISTS `ft_ratings`;
CREATE TABLE IF NOT EXISTS `ft_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_tech_name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `field_tech_rating` int(11) NOT NULL,
  `comments` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `ip`
--
-- Creation: Jul 15, 2014 at 10:36 AM
--

DROP TABLE IF EXISTS `ip`;
CREATE TABLE IF NOT EXISTS `ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number_ip` varchar(8) DEFAULT NULL,
  `primary_peer_ip` varchar(15) DEFAULT NULL,
  `backup_peer_ip` varchar(15) DEFAULT NULL,
  `gateway` varchar(15) DEFAULT NULL,
  `ip_range_upper` int(3) unsigned zerofill DEFAULT NULL,
  `ip_range_lower` int(3) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_number_ip` (`store_number_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lec_precheck`
--
-- Creation: Jul 15, 2014 at 10:38 AM
--

DROP TABLE IF EXISTS `lec_precheck`;
CREATE TABLE IF NOT EXISTS `lec_precheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number_lec` varchar(8) NOT NULL,
  `lec_call_date` date DEFAULT NULL,
  `lec_call_time` bigint(20) DEFAULT NULL,
  `lech_tech` varchar(45) DEFAULT NULL,
  `five_contiguous_ips` bit(1) NOT NULL DEFAULT b'0',
  `modem_online` bit(1) NOT NULL DEFAULT b'0',
  `bridge_mode` bit(1) NOT NULL DEFAULT b'0',
  `lec_ticket_number` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_number_lec` (`store_number_lec`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `precheck`
--
-- Creation: Jul 15, 2014 at 10:39 AM
--

DROP TABLE IF EXISTS `precheck`;
CREATE TABLE IF NOT EXISTS `precheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number_precheck` varchar(8) DEFAULT NULL,
  `assigned_sa_tech` varchar(45) DEFAULT NULL,
  `lec_ticket_number` varchar(45) DEFAULT NULL,
  `tunnels_enabled` bit(1) NOT NULL DEFAULT b'0',
  `fortimanager_checked` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  KEY `store_number_precheck` (`store_number_precheck`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `sa_tech`
--
-- Creation: Jul 15, 2014 at 10:40 AM
--

DROP TABLE IF EXISTS `sa_tech`;
CREATE TABLE IF NOT EXISTS `sa_tech` (
  `sa_tech_name` varchar(45) NOT NULL,
  `location` text,
  PRIMARY KEY (`sa_tech_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--
-- Creation: Jul 15, 2014 at 10:48 AM
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE IF NOT EXISTS `store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number` varchar(8) DEFAULT NULL,
  `store_zip_code` varchar(10) DEFAULT NULL,
  `store_state` varchar(2) DEFAULT NULL,
  `store_city` varchar(45) DEFAULT NULL,
  `store_address` varchar(250) DEFAULT NULL,
  `store_phone_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_number` (`store_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `store_annals`
--
-- Creation: Jul 15, 2014 at 10:49 AM
--

DROP TABLE IF EXISTS `store_annals`;
CREATE TABLE IF NOT EXISTS `store_annals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number_records` varchar(8) NOT NULL,
  `wait_start_time` bigint(20) DEFAULT NULL,
  `start_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `end_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `total_activation_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `1_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `2_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `3_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `4_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `5_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `6_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `7_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `8_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `9_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `10_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `11_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `12_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `13_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  `14_time` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `store_number_records` (`store_number_records`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ip`
--
ALTER TABLE `ip`
  ADD CONSTRAINT `store_number_ip` FOREIGN KEY (`store_number_ip`) REFERENCES `activation` (`store_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lec_precheck`
--
ALTER TABLE `lec_precheck`
  ADD CONSTRAINT `store_number_lec` FOREIGN KEY (`store_number_lec`) REFERENCES `activation` (`store_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `precheck`
--
ALTER TABLE `precheck`
  ADD CONSTRAINT `store_number_precheck` FOREIGN KEY (`store_number_precheck`) REFERENCES `activation` (`store_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `store_number` FOREIGN KEY (`store_number`) REFERENCES `activation` (`store_number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `store_annals`
--
ALTER TABLE `store_annals`
  ADD CONSTRAINT `store_number_records` FOREIGN KEY (`store_number_records`) REFERENCES `activation` (`store_number`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
