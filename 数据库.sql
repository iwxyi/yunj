/*
MySQL Backup
Source Server Version: 5.5.53
Source Database: yunj
Date: 2018/6/27 17:08:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `history`
-- ----------------------------
DROP TABLE IF EXISTS `history`;
CREATE TABLE `history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext,
  `info` longtext,
  `time` varchar(255) DEFAULT NULL,
  `IP` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=gbk;

-- ----------------------------
--  Table structure for `notes`
-- ----------------------------
DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `info` longtext,
  `code` longtext,
  `create_time` varchar(255) DEFAULT NULL,
  `alter_time` varchar(255) DEFAULT NULL,
  `open` int(11) DEFAULT NULL,
  `save` int(11) DEFAULT NULL,
  `share` int(11) DEFAULT NULL,
  `read` int(11) DEFAULT NULL,
  `IP` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4787 DEFAULT CHARSET=gbk;
