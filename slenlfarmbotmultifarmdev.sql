-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2015 at 09:03 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `slenlfarmbot`
--

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE IF NOT EXISTS `farmers` (
`id` int(100) NOT NULL,
  `farm_id` int(15) NOT NULL,
  `farmer_name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=305 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `farm_id`, `farmer_name`) VALUES
(35, 21, '@Duval'),
(36, 21, '@RamdeshLota'),
(37, 21, '@Cyan017'),
(38, 21, '@CMNisal'),
(39, 21, '@Smurfban3'),
(43, 22, '@DarthThanaton'),
(45, 22, '@sirStinkySocks'),
(46, 22, '@P3ricles'),
(47, 22, '@CosmicCarrot'),
(49, 22, '@ultrasn0w'),
(50, 22, '@PrasannaYogee'),
(51, 22, '@bravenprem'),
(52, 22, '@painkillerSL'),
(53, 22, '@jssparrow'),
(54, 22, '@kulendraj'),
(56, 22, '@Upulpp'),
(60, 22, '@SLpooh'),
(63, 25, '@kulendraj'),
(64, 25, '@P3ricles'),
(66, 25, '@SLPooh'),
(69, 25, '@PA9'),
(81, 27, '@nAPs2JAAN(upgrade)'),
(83, 28, '@CMNisal'),
(87, 28, '@JackzDaniels'),
(89, 28, '@Crosshairs'),
(94, 28, '@DarthThanaton'),
(95, 28, '@Panduka70'),
(96, 28, '@FuturisticGroup'),
(101, 32, '@RamdeshLota'),
(108, 34, '@RamdeshLota'),
(109, 35, '@CMNisal'),
(110, 35, '@RamdeshLota'),
(111, 36, '@RamdeshLota'),
(112, 37, '@CMNisal'),
(113, 38, '@CMNisal'),
(114, 39, '@SPDES'),
(115, 39, '@YldKat'),
(116, 39, '@nAPs2JAAN'),
(117, 39, '@fnx1024'),
(118, 39, '@JAAN2nAPs'),
(119, 39, '@LaMesh'),
(120, 39, '@xKrAzYx'),
(121, 39, '@DarthThanaton'),
(122, 40, '@DarthThanaton'),
(123, 40, '@sirStinkySocks'),
(124, 41, '@sirStinkySocks'),
(125, 41, '@DarthThanaton'),
(126, 42, '@CMNisal'),
(127, 43, '@CMNisal'),
(128, 44, '@CMNisal'),
(129, 45, '@MaruSira007'),
(130, 45, '@DarthThanaton(Upgrade)'),
(131, 45, '@P3ricles'),
(133, 45, '@sirStinkySocks'),
(134, 45, '@jssparrow'),
(135, 45, '@CosmicCarrot'),
(137, 45, '@kulendraj'),
(138, 45, '@JackzDaniels'),
(140, 45, '@thushethan'),
(145, 46, '@LeznerJ'),
(146, 47, '@CMNisal'),
(147, 47, '@SPDES(Upgrade)'),
(148, 47, '@Yldkat(Upgrade)'),
(149, 47, '@LeznerJ(Upgrade)'),
(151, 47, '@Scizer'),
(152, 48, '@CosmicCarrot'),
(153, 48, '@DarthThanaton'),
(154, 48, '@CMNisal'),
(155, 48, '@Capric0rN'),
(156, 48, '@jaze87'),
(157, 48, '@P3ricles'),
(158, 48, '@sirStinkySocks'),
(162, 48, '@XplodingdoG'),
(163, 48, '@painkillerSL'),
(164, 48, '@Lordrodriguez'),
(165, 48, '@JackzDaniels'),
(166, 48, '@nAPs2JAAN'),
(167, 48, '@PrasannaYogee'),
(168, 48, '@Spryflapper'),
(169, 48, '@ultrasn0w'),
(171, 48, '@AbulhoLuco'),
(172, 48, '@MarusSira007'),
(173, 48, '@Nytstalk3r'),
(174, 48, '@RealDhakshi'),
(176, 48, '@Gamaya'),
(177, 48, '@GE3TH'),
(178, 48, '@DonnieX64'),
(180, 48, '@bboylaiiz'),
(182, 48, '@NiiDU'),
(183, 48, '@Scizer'),
(184, 48, 'DEXTER'),
(185, 48, 'Rukii'),
(188, 48, '@Chala007'),
(190, 48, '@TheNameless0ne'),
(191, 48, '@SLpooh'),
(192, 49, '@YldKat'),
(193, 49, '@SPDES'),
(194, 49, '@Spryflapper(Maybe)'),
(195, 49, '@fnx1024'),
(196, 49, '@Gamaya'),
(197, 49, '@kawup'),
(198, 49, '@FuturisticGroup'),
(199, 50, '@painkillerSL'),
(201, 50, '@jssparrow'),
(202, 50, '@isiraUwU'),
(203, 50, '@Gamaya'),
(206, 51, '@SPDES(Upgraded)'),
(207, 51, '@YldKat(Upgraded)'),
(209, 51, '@Gamaya90(Upgraded)'),
(210, 51, '@nAPs2JAAN(Upgraded)'),
(211, 52, '@CMNisal'),
(212, 53, '@jssparrow'),
(213, 53, '@Gamaya90'),
(214, 53, '@isiraUwU'),
(216, 54, '@Gamaya90(Upgraded)'),
(217, 54, '@SPDES(Upgraded)'),
(218, 54, '@YldKat(Upgraded)'),
(219, 54, '@nAPs2JAAN(Upgraded)'),
(220, 54, '@JAAN2nAPs(Upgraded)'),
(221, 54, '@fnx1024(Upgraded)'),
(223, 54, '@CMNisal'),
(228, 55, '@MaruSira007'),
(229, 56, '@MaruSira007'),
(230, 56, '@CosmicCarrot'),
(231, 56, '@P3ricles'),
(232, 57, '@sirStinkySocks'),
(233, 57, '@Niranga_EkVillain'),
(234, 57, '@P3ricles'),
(235, 57, '@MaruSira007'),
(242, 57, '@CosmicCarrot'),
(247, 57, '@nAPs2JAAN'),
(248, 57, '@TmanT'),
(249, 57, '@SPDES'),
(258, 57, '@Capric0rN'),
(266, 57, '@NataliaSala27'),
(267, 57, '@'),
(270, 58, '@'),
(271, 58, '@khaoust'),
(272, 58, '@EvilAVP'),
(273, 58, '@oveys7000'),
(274, 59, '@DJ_Trey767'),
(275, 59, 'bob'),
(276, 60, '@'),
(277, 61, '@Upulpp'),
(278, 62, '@layalgebewbie'),
(279, 62, '@DJ_Trey767'),
(280, 62, '@Upulpp'),
(281, 63, '@Upulpp'),
(286, 64, '@SPDES'),
(287, 64, '@TmanT'),
(288, 64, '@nAPs2JAAN'),
(289, 64, '@kawup'),
(290, 64, '@LaMesh'),
(291, 64, '@thushethan'),
(292, 64, '@deathstar'),
(293, 64, '@chutinisa'),
(294, 64, '@DarthThanaton'),
(295, 64, '@Ruckii'),
(296, 64, '@MaruSira007'),
(297, 65, '@kulendraj'),
(298, 65, '@ultrasn0w'),
(299, 65, '@RamdeshLota'),
(300, 65, '@RamdeshLota'),
(301, 65, '@RamdeshLota'),
(302, 65, '@RamdeshLota'),
(303, 66, '@RamdeshLota'),
(304, 67, '@RamdeshLota');

-- --------------------------------------------------------

--
-- Table structure for table `farms`
--

CREATE TABLE IF NOT EXISTS `farms` (
`id` int(15) NOT NULL,
  `location` varchar(255) NOT NULL,
  `date_and_time` varchar(255) NOT NULL,
  `creator` varchar(255) NOT NULL,
  `farm_group` int(120) NOT NULL,
  `current` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `farms`
--

INSERT INTO `farms` (`id`, `location`, `date_and_time`, `creator`, `farm_group`, `current`) VALUES
(21, 'OrionCityAssPortal', '0000h ', '@RamdeshLota', 0, 0),
(22, 'Indi', '6PM ', '@Peththa', 0, 0),
(25, 'Smurf', 'Today 6:00', '@kulendraj', 0, 0),
(26, 'Indi', 'WhenHellFreezesOver ', '@Upulpp', 0, 0),
(27, 'Indi', 'Today 06:00', '@DarthThanaton', 0, 0),
(28, 'indi', 'today 5pm', '@CMNisal', 0, 0),
(39, 'Deuramwehera', 'Sunday(5th) 4PM', '@SPDES', 0, 0),
(40, 'Gangaramaya', '6 pm', '@DarthThanaton', 0, 0),
(41, 'GangaramaTemple', '6-JUL 18:00', '@sirStinkySocks', 0, 0),
(42, '', '', '@CMNisal', 0, 0),
(43, 'q', 'a a', '@CMNisal', 0, 0),
(44, 'a', 'a a', '@CMNisal', 0, 0),
(45, 'Indi', 'Today7th 6PM', '@MaruSira007', 0, 0),
(46, '', '', '@LeznerJ', 0, 0),
(47, 'Dewramwehera', 'Wednesday8th 4PM', '@CMNisal', 0, 0),
(48, 'PartyðŸŽŠðŸŽ‰ðŸŽŠMountBeach', '10/07/2015 06:30PM', '@CosmicCarrot', 0, 0),
(49, 'Dewramwehera', 'Saturday11th 4PM', '@YldKat', 0, 0),
(50, 'Indy', 'today 7pm', '@painkillerSL', 0, 0),
(51, 'Dewramwehera', '', '@CMNisal', 0, 0),
(52, '', '', '@CMNisal', 0, 0),
(53, 'Indi', '', '@jssparrow', 0, 0),
(54, 'Dewramwehera', '', '@CMNisal', 0, 0),
(55, 'INDI', '2015/07/14 7pm', '@MaruSira007', 0, 0),
(56, 'INDI', '2015/07/14 7pm', '@MaruSira007', 0, 0),
(57, 'IndependentsSquare~FREE~Cokeâ„¢~', ' ', '@sirStinkySocks', 0, 0),
(63, 'Devramwehera', 'today 6pm', '@Upulpp', 0, 0),
(64, 'Indi', 'Today 7.30PM', '@SPDES', 0, 0),
(65, 'Indi', '6pm Today', '@kulendraj', 0, 1),
(66, 'Park', '', '@RamdeshLota', -34025370, 1),
(67, 'Indi', 'Today 7pm', '@RamdeshLota', -34025370, 1);

-- --------------------------------------------------------

--
-- Table structure for table `message_log`
--

CREATE TABLE IF NOT EXISTS `message_log` (
`id` int(11) NOT NULL,
  `message_text` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `message_log`
--

INSERT INTO `message_log` (`id`, `message_text`) VALUES
(1, '{\n                      "update_id": 89018516,\n                      "message": {\n                        "message_id": 62,\n                        "from": {\n                          "id": 63477295,\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\n                          "last_name": "Deshapriya",\n                          "username": "SLpooh"\n                        },\n                        "chat": {\n                          "id": -34025370,\n                          "title": "Bottest"\n                        },\n                        "date": 1435508622,\n                        "text": "/addfarmer @RamdeshLota"\n                      }\n                    }'),
(2, '{\n                      "update_id": 89018516,\n                      "message": {\n                        "message_id": 62,\n                        "from": {\n                          "id": 63477295,\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\n                          "last_name": "Deshapriya",\n                          "username": "SLpooh"\n                        },\n                        "chat": {\n                          "id": -34025370,\n                          "title": "Bottest"\n                        },\n                        "date": 1435508622,\n                        "text": "/addfarmer @kulendraj"\n                      }\n                    }'),
(3, '{\n                      "update_id": 89018516,\n                      "message": {\n                        "message_id": 62,\n                        "from": {\n                          "id": 63477295,\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\n                          "last_name": "Deshapriya",\n                          "username": "SLpooh"\n                        },\n                        "chat": {\n                          "id": -34025370,\n                          "title": "Bottest"\n                        },\n                        "date": 1435508622,\n                        "text": "/addfarmer @kulendraj"\n                      }\n                    }'),
(4, '{\n                      "update_id": 89018516,\n                      "message": {\n                        "message_id": 62,\n                        "from": {\n                          "id": 63477295,\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\n                          "last_name": "Deshapriya",\n                          "username": "kulendraj"\n                        },\n                        "chat": {\n                          "id": -34025370,\n                          "title": "Bottest"\n                        },\n                        "date": 1435508622,\n                        "text": "/createfarm Indi 6pm Today"\n                      }\n                    }'),
(5, '{\n                      "update_id": 89018516,\n                      "message": {\n                        "message_id": 62,\n                        "from": {\n                          "id": 63477295,\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\n                          "last_name": "Deshapriya",\n                          "username": "kulendraj"\n                        },\n                        "chat": {\n                          "id": -34025370,\n                          "title": "Bottest"\n                        },\n                        "date": 1435508622,\n                        "text": "/addfarmer @ultrasn0w"\n                      }\n                    }'),
(6, '{\n                      "update_id": 89018516,\n                      "message": {\n                        "message_id": 62,\n                        "from": {\n                          "id": 63477295,\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\n                          "last_name": "Deshapriya",\n                          "username": "kulendraj"\n                        },\n                        "chat": {\n                          "id": -34025370,\n                          "title": "Bottest"\n                        },\n                        "date": 1435508622,\n                        "text": "/addfarmer @ultrasn0w"\n                      }\n                    }'),
(7, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/createfarm Indi 6pm"\r\n                      }\r\n                    }'),
(8, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/createfarm Park 7pm"\r\n                      }\r\n                    }'),
(9, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/createfarm Park 7pm"\r\n                      }\r\n                    }'),
(10, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/createfarm Park 7pm"\r\n                      }\r\n                    }'),
(11, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/createfarm Indi Today 7pm"\r\n                      }\r\n                    }'),
(12, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/addmetofarm"\r\n                      }\r\n                    }'),
(13, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/addmetofarm"\r\n                      }\r\n                    }'),
(14, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/addmetofarm"\r\n                      }\r\n                    }'),
(15, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/addmetofarm"\r\n                      }\r\n                    }'),
(16, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/addmetofarm"\r\n                      }\r\n                    }'),
(17, '{\r\n                      "update_id": 89018516,\r\n                      "message": {\r\n                        "message_id": 62,\r\n                        "from": {\r\n                          "id": 63477295,\r\n                          "first_name": "Ramindu \\"RamdeshLota\\"",\r\n                          "last_name": "Deshapriya",\r\n                          "username": "RamdeshLota"\r\n                        },\r\n                        "chat": {\r\n                          "id":-34025370,\r\n                          "title": "Bottest"\r\n                        },\r\n                        "date": 1435508622,\r\n                        "text": "/addmetofarm"\r\n                      }\r\n                    }');

-- --------------------------------------------------------

--
-- Table structure for table `message_queue`
--

CREATE TABLE IF NOT EXISTS `message_queue` (
`id` int(11) NOT NULL,
  `user` varchar(120) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `farms`
--
ALTER TABLE `farms`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_log`
--
ALTER TABLE `message_log`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_queue`
--
ALTER TABLE `message_queue`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=305;
--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
MODIFY `id` int(15) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT for table `message_log`
--
ALTER TABLE `message_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `message_queue`
--
ALTER TABLE `message_queue`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
