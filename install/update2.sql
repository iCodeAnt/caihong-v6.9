ALTER TABLE  `{DBQZ}_job` ADD  `type` INT( 4 ) NOT NULL DEFAULT  '0';

ALTER TABLE  `{DBQZ}_user` ADD  `qqnum` INT( 150 ) NOT NULL DEFAULT  '0',
ADD  `email` varchar(150) NULL;

ALTER TABLE `{DBQZ}_config` 
MODIFY COLUMN `multi` varchar(150) NOT NULL DEFAULT '0-0-0-0-0-0-0-0',
 ADD  `sitetitle` text NULL,
 ADD  `css2` INT( 1 ) NOT NULL DEFAULT  '1',
 ADD  `jifen` INT( 1 ) NOT NULL DEFAULT  '0',
 ADD  `rules` TEXT NULL,

 ADD  `cronkey` VARCHAR(150) DEFAULT NULL,

 ADD  `qqapiid` INT(4) NOT NULL DEFAULT 0,
 ADD  `qqloginid` INT(4) NOT NULL DEFAULT 1,
 ADD  `mail_name` VARCHAR(150) NULL,
 ADD  `mail_pwd` VARCHAR(150) NULL,
 ADD  `mail_stmp` VARCHAR(150) NULL,
 ADD  `mail_port` VARCHAR(150) NULL,
 ADD  `siteurl` VARCHAR(150) NULL;

DROP TABLE IF EXISTS `{DBQZ}_qq`;
create table `{DBQZ}_qq` (
`id` int(11) NOT NULL auto_increment,
`lx` varchar(150) NOT NULL default '0',
`qq` varchar(20) NOT NULL,
`pw` varchar(150) NULL,
`sid` varchar(150) NULL,
`skey` varchar(150) NULL,
`apiid` INT(4) NOT NULL default '0',
`status` INT(1) NOT NULL default '0',
`status2` int(4) NOT NULL default '0',
`time` datetime NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE  `{DBQZ}_job` ADD  `cookie` text NULL;

INSERT INTO `{DBQZ}_info`(`sysid`, `times`) VALUES
('3001', '0')