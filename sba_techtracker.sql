-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 08, 2014 at 09:16 PM
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
-- Table structure for table `activation_information`
--

CREATE TABLE IF NOT EXISTS `activation_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_number` varchar(6) NOT NULL,
  `type` varchar(50) NOT NULL,
  `primary_vendor` varchar(50) NOT NULL,
  `backup_carrier` varchar(50) NOT NULL,
  `eon` int(6) unsigned zerofill NOT NULL,
  `ops_console` int(7) unsigned zerofill NOT NULL,
  `bridge` int(7) unsigned zerofill NOT NULL,
  `tech_working` varchar(50) NOT NULL,
  `ft_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `activation_information`
--

INSERT INTO `activation_information` (`id`, `store_number`, `type`, `primary_vendor`, `backup_carrier`, `eon`, `ops_console`, `bridge`, `tech_working`, `ft_name`) VALUES
(1, 'S10000', 'Migration', 'Comcast', 'Verizon', 123456, 1234567, 1234567, '', 'Ryan Miller'),
(2, 'S10001', 'Migration', 'Comcast', 'Verizon', 902145, 8746925, 7651248, '', 'Matt Duchene');

-- --------------------------------------------------------

--
-- Table structure for table `activation_status`
--

CREATE TABLE IF NOT EXISTS `activation_status` (
  `id_status` int(11) NOT NULL,
  `revisit` int(1) NOT NULL,
  `tonight` int(1) NOT NULL,
  `late` int(1) NOT NULL,
  `waiting` int(1) NOT NULL,
  `underway` int(1) NOT NULL,
  `step` int(1) NOT NULL,
  `changed` bigint(255) NOT NULL,
  `help` int(1) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `complete` int(1) NOT NULL,
  `migrate_fail` int(1) NOT NULL,
  `fail` int(1) NOT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activation_status`
--

INSERT INTO `activation_status` (`id_status`, `revisit`, `tonight`, `late`, `waiting`, `underway`, `step`, `changed`, `help`, `reason`, `complete`, `migrate_fail`, `fail`) VALUES
(1, 0, 1, 0, 0, 0, 0, 0, 0, '', 0, 0, 0),
(2, 1, 1, 0, 0, 0, 0, 0, 0, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `activation_times`
--

CREATE TABLE IF NOT EXISTS `activation_times` (
  `id_times` int(11) NOT NULL,
  `scheduled` bigint(255) NOT NULL,
  `start` bigint(255) NOT NULL,
  `end` bigint(255) NOT NULL,
  `1time` int(11) NOT NULL,
  `2time` int(11) NOT NULL,
  `3time` int(11) NOT NULL,
  `4time` int(11) NOT NULL,
  `5time` int(11) NOT NULL,
  `6time` int(11) NOT NULL,
  `7time` int(11) NOT NULL,
  `8time` int(11) NOT NULL,
  `9time` int(11) NOT NULL,
  `10time` int(11) NOT NULL,
  `11time` int(11) NOT NULL,
  `12time` int(11) NOT NULL,
  `13time` int(11) NOT NULL,
  `14time` int(11) NOT NULL,
  PRIMARY KEY (`id_times`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activation_times`
--

INSERT INTO `activation_times` (`id_times`, `scheduled`, `start`, `end`, `1time`, `2time`, `3time`, `4time`, `5time`, `6time`, `7time`, `8time`, `9time`, `10time`, `11time`, `12time`, `13time`, `14time`) VALUES
(1, 1412253000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 1412253000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ip`
--

CREATE TABLE IF NOT EXISTS `ip` (
  `id_ip` int(11) NOT NULL,
  PRIMARY KEY (`id_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `precheck`
--

CREATE TABLE IF NOT EXISTS `precheck` (
  `id_precheck` int(11) NOT NULL,
  `assigned_tech` varchar(50) NOT NULL,
  PRIMARY KEY (`id_precheck`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `precheck`
--

INSERT INTO `precheck` (`id_precheck`, `assigned_tech`) VALUES
(1, 'Joe Rasmussen'),
(2, 'Jason Connett');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activation_status`
--
ALTER TABLE `activation_status`
  ADD CONSTRAINT `activation_status_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `activation_information` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `activation_times`
--
ALTER TABLE `activation_times`
  ADD CONSTRAINT `activation_times_ibfk_1` FOREIGN KEY (`id_times`) REFERENCES `activation_information` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ip`
--
ALTER TABLE `ip`
  ADD CONSTRAINT `ip_ibfk_1` FOREIGN KEY (`id_ip`) REFERENCES `activation_information` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `precheck`
--
ALTER TABLE `precheck`
  ADD CONSTRAINT `precheck_ibfk_1` FOREIGN KEY (`id_precheck`) REFERENCES `activation_information` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
