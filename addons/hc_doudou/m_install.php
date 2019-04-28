<?php
pdo_query("DROP TABLE IF EXISTS `ims_hcdoudou_address`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_address` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`username` varchar(100) NOT NULL,
`mobile` varchar(12) NOT NULL,
`address` varchar(200) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `uid` (`uid`),
KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_cash`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_cash` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`transid` varchar(20) NOT NULL,
`money` decimal(10,2) NOT NULL,
`fee` decimal(10,2) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT '0',
`createtime` char(10) NOT NULL,
`type` tinyint(1) NOT NULL DEFAULT '0',
`weid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_checkgoods`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_checkgoods` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`model` varchar(200) NOT NULL,
`price` int(11) NOT NULL,
`thumb` varchar(300) NOT NULL,
`sort` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_commission`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_commission` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`sub_id` int(11) NOT NULL,
`trade_no` varchar(30) NOT NULL,
`price` decimal(10,2) NOT NULL,
`rate` int(11) NOT NULL,
`profit` decimal(10,2) NOT NULL,
`level` tinyint(1) NOT NULL,
`sort` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT '0',
`freeze` tinyint(1) NOT NULL DEFAULT '0',
`createtime` char(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_goods`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_goods` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`model` varchar(200) NOT NULL,
`storeprice` int(11) NOT NULL,
`price` int(11) NOT NULL,
`thumb` varchar(300) NOT NULL,
`sort` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_guan`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_guan` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sort` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`loadpic` varchar(300) NOT NULL,
`rollpic` varchar(300) NOT NULL,
`proppic` varchar(300) NOT NULL,
`gamebgm` varchar(300) NOT NULL,
`passbgm` varchar(300) NOT NULL,
`losebgm` varchar(300) NOT NULL,
`times` tinyint(4) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_nexus`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_nexus` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`pppid` int(11) NOT NULL,
`ppid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`ctime` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `uid` (`uid`),
KEY `pppid` (`pppid`),
KEY `ppid` (`ppid`),
KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_notice`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_notice` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(300) NOT NULL,
`content` text NOT NULL,
`createtime` char(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_order`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`gid` int(11) DEFAULT NULL,
`uid` int(11) DEFAULT NULL,
`openid` varchar(30) NOT NULL,
`title` varchar(300) NOT NULL,
`trade_no` varchar(30) DEFAULT NULL COMMENT '订单编号',
`price` decimal(10,2) NOT NULL DEFAULT '0.00',
`level` tinyint(1) DEFAULT NULL DEFAULT '0',
`type` tinyint(1) DEFAULT NULL DEFAULT '0' COMMENT '1中奖2未中奖',
`status` tinyint(1) NOT NULL DEFAULT '0',
`passtime` char(10) NOT NULL,
`expresn` varchar(30) DEFAULT NULL COMMENT '快递编号',
`expretime` char(10) NOT NULL,
`createtime` char(10) DEFAULT NULL,
`isdelete` tinyint(1) DEFAULT NULL DEFAULT '0',
PRIMARY KEY (`id`),
KEY `weid` (`weid`),
KEY `uid` (`uid`),
KEY `trade_no` (`trade_no`),
KEY `gid` (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_paylog`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_paylog` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`money` decimal(10,2) NOT NULL,
`uid` int(11) NOT NULL,
`openid` varchar(30) NOT NULL,
`trade_no` varchar(18) NOT NULL,
`transaction_id` varchar(50) NOT NULL,
`total_fee` decimal(10,2) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT '0',
`createtime` char(10) NOT NULL,
`paytime` char(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `uid` (`uid`),
KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_setting`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_setting` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL DEFAULT '0',
`only` varchar(20) DEFAULT NULL,
`title` varchar(50) NOT NULL,
`value` text NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `only` (`only`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_upgrade`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_upgrade` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(100) NOT NULL,
`trade_no` varchar(20) NOT NULL,
`uid` int(11) NOT NULL,
`openid` varchar(50) NOT NULL,
`price` decimal(10,2) NOT NULL,
`transaction_id` varchar(50) NOT NULL,
`createtime` char(10) NOT NULL,
`paytime` char(10) NOT NULL,
`level` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT '0',
`weid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_hcdoudou_users`;
CREATE TABLE IF NOT EXISTS `ims_hcdoudou_users` (
`uid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`avatar` varchar(200) DEFAULT NULL,
`nickname` varchar(50) DEFAULT NULL,
`openid` varchar(50) DEFAULT NULL,
`mobile` varchar(15) NOT NULL,
`gender` tinyint(1) NOT NULL,
`province` varchar(50) DEFAULT NULL,
`city` varchar(50) DEFAULT NULL,
`country` varchar(50) DEFAULT NULL,
`money` decimal(10,2) NOT NULL DEFAULT '0.00',
`createtime` char(10) DEFAULT NULL,
`sessionkey` varchar(50) NOT NULL,
`unionid` varchar(50) DEFAULT NULL,
`status` char(1) DEFAULT NULL DEFAULT '1',
`level` tinyint(1) NOT NULL DEFAULT '1',
`promo_code` varchar(300) NOT NULL,
`receipt_code` varchar(300) NOT NULL,
PRIMARY KEY (`uid`),
UNIQUE KEY `uid` (`uid`),
KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
