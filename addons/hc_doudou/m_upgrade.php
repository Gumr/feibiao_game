<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcdoudou_address` (
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

CREATE TABLE IF NOT EXISTS `ims_hcdoudou_notice` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(300) NOT NULL,
`content` text NOT NULL,
`createtime` char(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE IF NOT EXISTS `ims_hcdoudou_setting` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL DEFAULT '0',
`only` varchar(20) DEFAULT NULL,
`title` varchar(50) NOT NULL,
`value` text NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `only` (`only`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
if(pdo_tableexists('hcdoudou_address')) {
	if(!pdo_fieldexists('hcdoudou_address',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_address')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_address')) {
	if(!pdo_fieldexists('hcdoudou_address',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_address')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_address')) {
	if(!pdo_fieldexists('hcdoudou_address',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_address')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_address')) {
	if(!pdo_fieldexists('hcdoudou_address',  'username')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_address')." ADD `username` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_address')) {
	if(!pdo_fieldexists('hcdoudou_address',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_address')." ADD `mobile` varchar(12) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_address')) {
	if(!pdo_fieldexists('hcdoudou_address',  'address')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_address')." ADD `address` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'transid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `transid` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'money')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `money` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'fee')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `fee` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'status')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `createtime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'type')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `type` tinyint(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_cash')) {
	if(!pdo_fieldexists('hcdoudou_cash',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_cash')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_checkgoods')) {
	if(!pdo_fieldexists('hcdoudou_checkgoods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_checkgoods')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_checkgoods')) {
	if(!pdo_fieldexists('hcdoudou_checkgoods',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_checkgoods')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_checkgoods')) {
	if(!pdo_fieldexists('hcdoudou_checkgoods',  'title')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_checkgoods')." ADD `title` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_checkgoods')) {
	if(!pdo_fieldexists('hcdoudou_checkgoods',  'model')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_checkgoods')." ADD `model` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_checkgoods')) {
	if(!pdo_fieldexists('hcdoudou_checkgoods',  'price')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_checkgoods')." ADD `price` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_checkgoods')) {
	if(!pdo_fieldexists('hcdoudou_checkgoods',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_checkgoods')." ADD `thumb` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_checkgoods')) {
	if(!pdo_fieldexists('hcdoudou_checkgoods',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_checkgoods')." ADD `sort` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'user_id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `user_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'sub_id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `sub_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'trade_no')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `trade_no` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'price')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `price` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'rate')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `rate` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'profit')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `profit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'level')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `level` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `sort` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'status')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'freeze')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `freeze` tinyint(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_commission')) {
	if(!pdo_fieldexists('hcdoudou_commission',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_commission')." ADD `createtime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'title')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `title` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'model')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `model` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'storeprice')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `storeprice` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'price')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `price` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `thumb` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_goods')) {
	if(!pdo_fieldexists('hcdoudou_goods',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_goods')." ADD `sort` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `sort` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'loadpic')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `loadpic` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'rollpic')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `rollpic` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'proppic')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `proppic` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'gamebgm')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `gamebgm` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'passbgm')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `passbgm` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'losebgm')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `losebgm` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_guan')) {
	if(!pdo_fieldexists('hcdoudou_guan',  'times')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_guan')." ADD `times` tinyint(4) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_nexus')) {
	if(!pdo_fieldexists('hcdoudou_nexus',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_nexus')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_nexus')) {
	if(!pdo_fieldexists('hcdoudou_nexus',  'pppid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_nexus')." ADD `pppid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_nexus')) {
	if(!pdo_fieldexists('hcdoudou_nexus',  'ppid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_nexus')." ADD `ppid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_nexus')) {
	if(!pdo_fieldexists('hcdoudou_nexus',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_nexus')." ADD `pid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_nexus')) {
	if(!pdo_fieldexists('hcdoudou_nexus',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_nexus')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_nexus')) {
	if(!pdo_fieldexists('hcdoudou_nexus',  'ctime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_nexus')." ADD `ctime` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_notice')) {
	if(!pdo_fieldexists('hcdoudou_notice',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_notice')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_notice')) {
	if(!pdo_fieldexists('hcdoudou_notice',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_notice')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_notice')) {
	if(!pdo_fieldexists('hcdoudou_notice',  'title')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_notice')." ADD `title` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_notice')) {
	if(!pdo_fieldexists('hcdoudou_notice',  'content')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_notice')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_notice')) {
	if(!pdo_fieldexists('hcdoudou_notice',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_notice')." ADD `createtime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'gid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `gid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `uid` int(11) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `openid` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'title')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `title` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'trade_no')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `trade_no` varchar(30) DEFAULT NULL COMMENT '订单编号';");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'price')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'level')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `level` tinyint(1) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'type')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `type` tinyint(1) DEFAULT NULL DEFAULT '0' COMMENT '1中奖2未中奖';");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'status')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'passtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `passtime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'expresn')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `expresn` varchar(30) DEFAULT NULL COMMENT '快递编号';");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'expretime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `expretime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `createtime` char(10) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_order')) {
	if(!pdo_fieldexists('hcdoudou_order',  'isdelete')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_order')." ADD `isdelete` tinyint(1) DEFAULT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'money')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `money` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `openid` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'trade_no')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `trade_no` varchar(18) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'transaction_id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `transaction_id` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'total_fee')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `total_fee` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'status')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `createtime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_paylog')) {
	if(!pdo_fieldexists('hcdoudou_paylog',  'paytime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_paylog')." ADD `paytime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_setting')) {
	if(!pdo_fieldexists('hcdoudou_setting',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_setting')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_setting')) {
	if(!pdo_fieldexists('hcdoudou_setting',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_setting')." ADD `weid` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_setting')) {
	if(!pdo_fieldexists('hcdoudou_setting',  'only')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_setting')." ADD `only` varchar(20) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_setting')) {
	if(!pdo_fieldexists('hcdoudou_setting',  'title')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_setting')." ADD `title` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_setting')) {
	if(!pdo_fieldexists('hcdoudou_setting',  'value')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_setting')." ADD `value` text NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'title')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'trade_no')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `trade_no` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'price')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `price` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'transaction_id')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `transaction_id` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `createtime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'paytime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `paytime` char(10) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'level')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `level` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'status')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('hcdoudou_upgrade')) {
	if(!pdo_fieldexists('hcdoudou_upgrade',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_upgrade')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `uid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `pid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `avatar` varchar(200) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `nickname` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `openid` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `mobile` varchar(15) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'gender')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `gender` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'province')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `province` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'city')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `city` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'country')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `country` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'money')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `createtime` char(10) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'sessionkey')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `sessionkey` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'unionid')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `unionid` varchar(50) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'status')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `status` char(1) DEFAULT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'level')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `level` tinyint(1) NOT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'promo_code')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `promo_code` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('hcdoudou_users')) {
	if(!pdo_fieldexists('hcdoudou_users',  'receipt_code')) {
		pdo_query("ALTER TABLE ".tablename('hcdoudou_users')." ADD `receipt_code` varchar(300) NOT NULL;");
	}	
}
