-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 30, 2015 at 11:42 AM
-- Server version: 5.5.42-cll
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rdeshapr_slenlfarmbot`
--

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE IF NOT EXISTS `farmers` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `farm_id` int(15) NOT NULL,
  `farmer_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `farms`
--

CREATE TABLE IF NOT EXISTS `farms` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `location` varchar(255) NOT NULL,
  `date_and_time` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `current` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
