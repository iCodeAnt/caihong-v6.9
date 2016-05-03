ALTER TABLE  `{DBQZ}_user`
ADD  `last` DATETIME NOT NULL;

ALTER TABLE  `{DBQZ}_job1` 
ADD  `pl` INT( 150 ) NOT NULL DEFAULT  '0',
ADD  `mc` VARCHAR( 255 ) NOT NULL DEFAULT  '网址挂刷任务' AFTER  `jobid`,
ADD  `sysid` INT( 150 ) NOT NULL DEFAULT  '1' AFTER  `jobid`,
ADD  `time` INT( 150 ) NOT NULL DEFAULT  '0';

DROP TABLE IF EXISTS `{DBQZ}_chat`;
CREATE TABLE `{DBQZ}_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(150) DEFAULT NULL,
  `sj` varchar(150) DEFAULT NULL,
  `nr` varchar(500) DEFAULT NULL,
  `to` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{DBQZ}_info`;
create table `{DBQZ}_info` (
`sysid` int(11) NOT NULL,
`last` datetime NULL,
`times` int(150) NOT NULL DEFAULT  '0',
  PRIMARY KEY  (`sysid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `{DBQZ}_info`(`sysid`, `times`) VALUES
('0', '0'),
('1', '0'),
('2', '0'),
('3', '0'),
('4', '0'),
('5', '0'),
('6', '0'),
('7', '0'),
('8', '0');

ALTER TABLE `{DBQZ}_config` DROP `jobtime`,
DROP `jobtimes`,
DROP `job1`,
DROP `job2`,
DROP `job3`,
DROP `job4`,
DROP `job5`,
DROP `job6`,
DROP `job7`,
DROP `job8`;

ALTER TABLE  `{DBQZ}_config` ADD  `bulk` INT( 10 ) NOT NULL DEFAULT  '10',
ADD  `adminid` INT( 10 ) NOT NULL,
ADD  `build` datetime NULL,
ADD  `seconds` VARCHAR( 150 ) NOT NULL DEFAULT  '0-0-0-0-0-0-0-0',
ADD  `show` TEXT NOT NULL ,
ADD  `block` TEXT NULL,
ADD  `apiserver` INT(2) NOT NULL ;

update `{DBQZ}_config` set `adminid` ='1',`show` ='15分钟|15分钟|5分钟|5分钟|5分钟|6小时|6小时|12小时',`apiserver` ='1' where `id`='1'