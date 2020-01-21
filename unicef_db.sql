/*
Navicat MySQL Data Transfer

Source Server         : UNICEF- VEATVE Staging
Source Server Version : 50635
Source Database       : unicef_db
Target Server Type    : MYSQL
Target Server Version : 50635
File Encoding         : 65001

Date: 2020-01-21 18:56:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_unicef_user`
-- ----------------------------
DROP TABLE IF EXISTS `t_unicef_user`;
CREATE TABLE `t_unicef_user` (
  `USER_OID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(55) DEFAULT NULL,
  `EMAIL_ID` varchar(85) DEFAULT NULL,
  `PASSWORD` varchar(50) DEFAULT NULL,
  `FIRST_NAME` varchar(50) DEFAULT NULL,
  `LAST_NAME` varchar(50) DEFAULT NULL,
  `USER_AGE` varchar(3) DEFAULT NULL,
  `GENDER_ID` enum('M','F') DEFAULT NULL,
  `IP_ADDRESS` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL COMMENT 'to track the user location',
  `CREATED_ON` datetime DEFAULT NULL,
  `LAST_UPDATED_BY` int(11) DEFAULT '0',
  `LAST_UPDATED_ON` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`USER_OID`),
  UNIQUE KEY `UK_USR_EMAIL` (`EMAIL_ID`) USING BTREE,
  UNIQUE KEY `UK_USR_UNAME` (`USERNAME`) USING BTREE,
  KEY `USER_OID` (`USER_OID`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=427 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `t_unicef_usractivity`
-- ----------------------------
DROP TABLE IF EXISTS `t_unicef_usractivity`;
CREATE TABLE `t_unicef_usractivity` (
  `ACTIVITY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_OID` int(11) NOT NULL,
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
  `ATTEMPTED_ON` datetime NOT NULL,
  PRIMARY KEY (`ACTIVITY_ID`),
  KEY `USER_OID` (`USER_OID`)
) ENGINE=InnoDB AUTO_INCREMENT=1429 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `t_unicef_usractivity_copy`
-- ----------------------------
DROP TABLE IF EXISTS `t_unicef_usractivity_copy`;
CREATE TABLE `t_unicef_usractivity_copy` (
  `ACTIVITY_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_OID` int(11) NOT NULL,
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
  `ATTEMPTED_ON` datetime NOT NULL,
  PRIMARY KEY (`ACTIVITY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_unicef_usractivity_copy
-- ----------------------------

-- ----------------------------
-- View structure for `module_attempt`
-- ----------------------------
DROP VIEW IF EXISTS `module_attempt`;
CREATE ALGORITHM=UNDEFINED DEFINER=`veativeuser`@`%` SQL SECURITY DEFINER VIEW `module_attempt` AS select `t_unicef_usractivity`.`GL_MODULE_NAME` AS `GL_MODULE_NAME`,count(`t_unicef_usractivity`.`GL_MODULE_NAME`) AS `COUNT` from `t_unicef_usractivity` group by `t_unicef_usractivity`.`GL_MODULE_NAME` ;
