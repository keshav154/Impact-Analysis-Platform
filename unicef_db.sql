-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2019 at 10:54 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.5.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unicef_db`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `module_attempt`
--
CREATE TABLE `module_attempt` (
`GL_MODULE_NAME` varchar(100)
,`UUID` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `t_unicef_usractivity`
--

CREATE TABLE `t_unicef_usractivity` (
  `ACTIVITY_ID` int(11) NOT NULL,
  `UUID` varchar(100) NOT NULL,
  `USR_NAME` varchar(50) NOT NULL,
  `USR_AGE` tinyint(3) NOT NULL,
  `USR_AVATAR` varchar(30) DEFAULT NULL,
  `USR_GENDER` enum('Boy','Girl') NOT NULL,
  `USR_LANGUAGE` varchar(10) NOT NULL,
  `GL_MODULE_ID` varchar(10) NOT NULL,
  `GL_MODULE_NAME` varchar(100) NOT NULL,
  `GL_LEVEL_ID` varchar(5) NOT NULL,
  `GL_LEVEL_NAME` varchar(80) NOT NULL,
  `GL_LEVEL_KNOWLEDGE_DOMAIN` varchar(50) NOT NULL,
  `GL_LEVEL_COGNITIVE_DOMAIN` varchar(50) NOT NULL,
  `GL_LEVEL_TYPE` varchar(50) NOT NULL,
  `GL_LEVEL_INTERACTIVITY` varchar(50) NOT NULL,
  `GL_QUESTION_ID` varchar(10) NOT NULL,
  `GL_QUESTION_COGNITIVE` varchar(50) NOT NULL,
  `GL_QUESTION_ACTION_VERB` varchar(50) NOT NULL,
  `LL_QUESTION_TYPE` varchar(50) NOT NULL,
  `LL_MAX_SCORE` tinyint(3) NOT NULL DEFAULT '1',
  `TR_USER_SCORE` tinyint(3) NOT NULL,
  `HOST_IP` varchar(15) DEFAULT NULL,
  `DEVICE_BROWSER_VERSION` varchar(80) DEFAULT NULL,
  `DEVICE_MODEL` varchar(50) DEFAULT NULL,
  `DEVICE_KERNEL_VERSION` varchar(100) DEFAULT NULL,
  `DEVICE_SERIAL_NUMBER` varchar(50) DEFAULT NULL,
  `DEVICE_PLATFORM` varchar(20) NOT NULL,
  `ATTEMPTED_ON` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure for view `module_attempt`
--
DROP TABLE IF EXISTS `module_attempt`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `module_attempt`  AS  select `t_unicef_usractivity`.`GL_MODULE_NAME` AS `GL_MODULE_NAME`,`t_unicef_usractivity`.`UUID` AS `UUID` from `t_unicef_usractivity` group by `t_unicef_usractivity`.`UUID` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_unicef_usractivity`
--
ALTER TABLE `t_unicef_usractivity`
  ADD PRIMARY KEY (`ACTIVITY_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_unicef_usractivity`
--
ALTER TABLE `t_unicef_usractivity`
  MODIFY `ACTIVITY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
