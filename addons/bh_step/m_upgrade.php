<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_bh_st_address` (
`address_id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`name` varchar(30) NOT NULL,
`phone` varchar(30) NOT NULL,
`address` text NOT NULL,
`uniacid` int(11) NOT NULL,
`addtime` int(11) NOT NULL,
PRIMARY KEY (`address_id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_bag` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`money` decimal(10,2) NOT NULL,
`a_few` tinyint(4) NOT NULL DEFAULT '1' COMMENT '当天第几个',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_cash_pwd` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`days` int(11) NOT NULL,
`pwd` varchar(500) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_category` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`category_name` varchar(255) NOT NULL,
`category_describe` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`states` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:显示在首页2:显示在步数商城3:都显示4:都不显示',
`is_delete` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:正常2:删除',
`sort` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_config` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`key` varchar(50) NOT NULL,
`value` text NOT NULL,
`uniacid` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`,`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_currency` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`currency` decimal(10,2) NOT NULL,
`member_id` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`today` int(11) NOT NULL,
`source` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:完成任务2:步数换取,3:签到,6授权,8分享到群',
`status` tinyint(255) NOT NULL DEFAULT '1' COMMENT '1:未领取2:已领取',
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_currency_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`type` tinyint(4) NOT NULL COMMENT '1:完成任务,2:步数换取,3:签到,4:邀请好友,5:领取红包,6授权,7:关注公众号8:分享到群9:兑换商品10:取消商品',
`number` decimal(10,2) NOT NULL,
`remarks` varchar(500) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_form_id` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL COMMENT '用户id',
`form_id` varchar(50) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_goods` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`category_id` int(11) NOT NULL,
`type` int(11) NOT NULL DEFAULT '1' COMMENT '1:实物2:虚拟',
`is_under_line` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:非线下,2:线下核销',
`original_price` decimal(10,2) DEFAULT NULL COMMENT '原价',
`goods_name` varchar(255) NOT NULL,
`share_title` varchar(300) NOT NULL,
`exchange_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:活力币兑换,2:当天自己与好友步数兑换,3步数加钱,4活力币加钱',
`money` decimal(10,2) NOT NULL DEFAULT '0.00',
`exchange_number` int(11) NOT NULL,
`cover_image` int(11) NOT NULL COMMENT '封面',
`image` varchar(255) NOT NULL COMMENT '轮播多张用逗号隔开',
`is_free` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:包邮2:不包邮',
`free` decimal(10,2) NOT NULL,
`invitation_number` int(11) NOT NULL DEFAULT '0' COMMENT '需要邀请多少好友才能兑换',
`inventory_type` tinyint(4) NOT NULL COMMENT '1:每天限量提供,2总计',
`inventory` int(11) NOT NULL,
`introduce_type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '商品介绍方式:1:文字2:图片',
`introduce` text NOT NULL,
`allow_number` int(11) NOT NULL DEFAULT '0' COMMENT '每人允许兑换的数量0:无限兑换',
`uniacid` int(11) NOT NULL,
`status` int(11) NOT NULL DEFAULT '1' COMMENT '1:上架2:下架',
`sigin` int(11) NOT NULL DEFAULT '0',
`is_delete` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:正常2:删除',
`shop_id` int(11) NOT NULL,
`sort` int(11) NOT NULL DEFAULT '0',
`bag_money` decimal(10,2) NOT NULL,
`is_exhibition` tinyint(4) NOT NULL DEFAULT '1',
`appid` varchar(100) NOT NULL,
`path` varchar(150) NOT NULL,
`parameter` varchar(500) NOT NULL,
`order_explain` varchar(500) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_goods_fictitious` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`shop_id` int(11) NOT NULL DEFAULT '0',
`goods_id` int(11) NOT NULL,
`member_id` int(11) NOT NULL DEFAULT '0',
`uniacid` int(11) NOT NULL,
`content` varchar(255) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未领取2:已经领取',
`is_under_line` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:非线下,2:线下核销',
`updated` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `goods_id` (`goods_id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_goods_give` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goods_id` int(11) NOT NULL,
`member_id` int(11) NOT NULL,
`friend_id` int(11) NOT NULL,
`step` int(11) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未使用2:已使用',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`,`goods_id`),
KEY `friend_id` (`friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_goods_share` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goods_id` int(11) NOT NULL,
`member_id` int(11) NOT NULL,
`friend_is` int(11) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未使用2:已使用',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`,`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_jump` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`icon` int(11) NOT NULL,
`title` varchar(255) NOT NULL,
`describe` varchar(255) NOT NULL,
`position` tinyint(4) NOT NULL COMMENT '1:首页上方,2:闲来玩玩,3:首页中间,4:商城上方,5:签到活动福利,6:签到弹窗,7:签到中间',
`type` tinyint(4) NOT NULL COMMENT '1:跳转到其他小程序2:当前小程序内',
`mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:自定义,2:流量组',
`flow_group_id` varchar(100) NOT NULL,
`appid` varchar(60) NOT NULL,
`path` varchar(500) DEFAULT NULL,
`uniacid` int(11) NOT NULL,
`click_number` int(11) NOT NULL DEFAULT '0',
`sort` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_member` (
`id` int(8) unsigned NOT NULL AUTO_INCREMENT,
`openid` varchar(50) NOT NULL COMMENT '用户唯一标识',
`nickname` varchar(50) NOT NULL COMMENT '用户昵称',
`head` varchar(200) NOT NULL COMMENT '用户头像路径',
`gender` tinyint(1) NOT NULL COMMENT '用户性别 0：未知  1：男  2：女',
`province` varchar(50) NOT NULL COMMENT '省份',
`city` varchar(50) NOT NULL COMMENT '城市',
`country` varchar(50) NOT NULL COMMENT '国家',
`money` decimal(10,2) NOT NULL COMMENT '账户余额',
`total_money` decimal(10,2) NOT NULL COMMENT '总计兑换红包金额',
`add_time` int(11) NOT NULL COMMENT '用户注册时间',
`set_time` int(12) NOT NULL COMMENT '用户登录时间',
`status` tinyint(1) NOT NULL DEFAULT '1',
`parent_id` int(11) NOT NULL DEFAULT '0',
`cash` tinyint(4) NOT NULL DEFAULT '0' COMMENT '提现次数',
`receive_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后领取步数时间',
`shop_id` int(11) NOT NULL DEFAULT '0',
`uniacid` int(11) NOT NULL,
`currency` decimal(10,2) NOT NULL,
`is_fictitious` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:真实2虚拟',
`parent_type` tinyint(4) NOT NULL DEFAULT '1',
`goods_id` int(11) NOT NULL DEFAULT '0',
`wx_uniacid` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_member_voucher` (
`id` int(11) NOT NULL,
`shop_id` int(11) NOT NULL,
`shop_name` varchar(255) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_merchant` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`shop_name` varchar(255) NOT NULL,
`locality` varchar(500) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_money_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL COMMENT '用户id',
`money` decimal(10,2) NOT NULL COMMENT '变动金额',
`type` tinyint(4) NOT NULL COMMENT '1:充值2:邮费3:领取红包4:提现',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_my_voucher` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`shop_id` int(11) NOT NULL,
`goods_id` int(11) NOT NULL,
`content` varchar(255) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未核销2:已核销',
`write_off_id` tinyint(4) NOT NULL DEFAULT '0',
`uniacid` int(11) NOT NULL,
`updated` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goods_id` int(11) NOT NULL,
`member_id` int(11) NOT NULL,
`exchange_type` tinyint(11) NOT NULL DEFAULT '1' COMMENT '1:活力币兑换,2:当天自己与好友步数兑换',
`exchange_number` int(11) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未发货2:已发货3:已完成4:已取消',
`type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:实物2:虚拟',
`fictitious` varchar(255) NOT NULL,
`courier_name` varchar(255) NOT NULL COMMENT '快递公司名字',
`courier_number` varchar(100) NOT NULL COMMENT '快递单号',
`address` varchar(1000) NOT NULL COMMENT '收货人详细地址',
`uniacid` int(11) NOT NULL,
`delivery_time` int(11) NOT NULL COMMENT '发货时间',
`complete_time` int(11) NOT NULL COMMENT '完成时间',
`name` varchar(50) NOT NULL COMMENT '收货人姓名',
`phone` varchar(50) NOT NULL COMMENT '收货人电话',
`created` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`),
KEY `goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_pay_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`trade_no` char(50) NOT NULL,
`transaction_id` char(32) NOT NULL,
`pay_time` int(11) NOT NULL COMMENT '支付成功时间',
`money` decimal(10,2) NOT NULL COMMENT '充值金额',
`type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:邮费',
`uniacid` int(11) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未支付 2:已支付',
`crested` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_question` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`content` text NOT NULL,
`sort` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_remind` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未提醒2:已提醒',
`remind_time` int(11) NOT NULL COMMENT '提醒时间',
`remind_day` int(11) NOT NULL,
`type` int(11) NOT NULL DEFAULT '1' COMMENT '1:红包提醒2签到提醒',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_resource` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL DEFAULT '0' COMMENT '上传者',
`route` varchar(255) NOT NULL COMMENT '路径',
`type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:图片',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_share_group` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`openGId` varchar(100) NOT NULL,
`today` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_shop` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`shop_name` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_signin` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`days` tinyint(4) NOT NULL,
`all_sigin` int(11) NOT NULL,
`current_days` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`updated` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_task` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`describe` varchar(500) NOT NULL,
`icon` int(11) NOT NULL,
`currency` decimal(10,0) NOT NULL,
`appid` varchar(60) NOT NULL,
`path` varchar(255) NOT NULL,
`type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1:每天一次2:只能参加一次',
`is_home` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:非首页,2:首页',
`sort` int(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_task_member` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`task_id` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`),
KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_today` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`step` int(11) NOT NULL DEFAULT '0',
`currency` decimal(10,2) NOT NULL DEFAULT '0.00',
`effective_step` int(11) NOT NULL DEFAULT '0' COMMENT '有效步数',
`use_step` int(255) NOT NULL DEFAULT '0' COMMENT '使用步数',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_token` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`token` char(32) NOT NULL,
`member_id` int(11) NOT NULL,
`session_key` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_bh_st_withdrawals` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL,
`money` decimal(10,2) NOT NULL COMMENT '提现金额',
`status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:申请成功 2:提现才成功 3:提现失败',
`success_time` int(11) NOT NULL COMMENT '提现成功时间',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('bh_st_address')) {
	if(!pdo_fieldexists('bh_st_address',  'address_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_address')." ADD `address_id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_address')) {
	if(!pdo_fieldexists('bh_st_address',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_address')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_address')) {
	if(!pdo_fieldexists('bh_st_address',  'name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_address')." ADD `name` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_address')) {
	if(!pdo_fieldexists('bh_st_address',  'phone')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_address')." ADD `phone` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_address')) {
	if(!pdo_fieldexists('bh_st_address',  'address')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_address')." ADD `address` text NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_address')) {
	if(!pdo_fieldexists('bh_st_address',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_address')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_address')) {
	if(!pdo_fieldexists('bh_st_address',  'addtime')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_address')." ADD `addtime` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_bag')) {
	if(!pdo_fieldexists('bh_st_bag',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_bag')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_bag')) {
	if(!pdo_fieldexists('bh_st_bag',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_bag')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_bag')) {
	if(!pdo_fieldexists('bh_st_bag',  'money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_bag')." ADD `money` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_bag')) {
	if(!pdo_fieldexists('bh_st_bag',  'a_few')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_bag')." ADD `a_few` tinyint(4) NOT NULL DEFAULT '1' COMMENT '当天第几个';");
	}	
}
if(pdo_tableexists('bh_st_bag')) {
	if(!pdo_fieldexists('bh_st_bag',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_bag')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_bag')) {
	if(!pdo_fieldexists('bh_st_bag',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_bag')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_cash_pwd')) {
	if(!pdo_fieldexists('bh_st_cash_pwd',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_cash_pwd')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_cash_pwd')) {
	if(!pdo_fieldexists('bh_st_cash_pwd',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_cash_pwd')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_cash_pwd')) {
	if(!pdo_fieldexists('bh_st_cash_pwd',  'days')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_cash_pwd')." ADD `days` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_cash_pwd')) {
	if(!pdo_fieldexists('bh_st_cash_pwd',  'pwd')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_cash_pwd')." ADD `pwd` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_cash_pwd')) {
	if(!pdo_fieldexists('bh_st_cash_pwd',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_cash_pwd')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_cash_pwd')) {
	if(!pdo_fieldexists('bh_st_cash_pwd',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_cash_pwd')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_category')) {
	if(!pdo_fieldexists('bh_st_category',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_category')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_category')) {
	if(!pdo_fieldexists('bh_st_category',  'category_name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_category')." ADD `category_name` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_category')) {
	if(!pdo_fieldexists('bh_st_category',  'category_describe')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_category')." ADD `category_describe` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_category')) {
	if(!pdo_fieldexists('bh_st_category',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_category')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_category')) {
	if(!pdo_fieldexists('bh_st_category',  'states')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_category')." ADD `states` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:显示在首页2:显示在步数商城3:都显示4:都不显示';");
	}	
}
if(pdo_tableexists('bh_st_category')) {
	if(!pdo_fieldexists('bh_st_category',  'is_delete')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_category')." ADD `is_delete` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:正常2:删除';");
	}	
}
if(pdo_tableexists('bh_st_category')) {
	if(!pdo_fieldexists('bh_st_category',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_category')." ADD `sort` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_config')) {
	if(!pdo_fieldexists('bh_st_config',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_config')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_config')) {
	if(!pdo_fieldexists('bh_st_config',  'key')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_config')." ADD `key` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_config')) {
	if(!pdo_fieldexists('bh_st_config',  'value')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_config')." ADD `value` text NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_config')) {
	if(!pdo_fieldexists('bh_st_config',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_config')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'currency')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `currency` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'today')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `today` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'source')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `source` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:完成任务2:步数换取,3:签到,6授权,8分享到群';");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `status` tinyint(255) NOT NULL DEFAULT '1' COMMENT '1:未领取2:已领取';");
	}	
}
if(pdo_tableexists('bh_st_currency')) {
	if(!pdo_fieldexists('bh_st_currency',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency_log')) {
	if(!pdo_fieldexists('bh_st_currency_log',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_currency_log')) {
	if(!pdo_fieldexists('bh_st_currency_log',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency_log')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency_log')) {
	if(!pdo_fieldexists('bh_st_currency_log',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency_log')." ADD `type` tinyint(4) NOT NULL COMMENT '1:完成任务,2:步数换取,3:签到,4:邀请好友,5:领取红包,6授权,7:关注公众号8:分享到群9:兑换商品10:取消商品';");
	}	
}
if(pdo_tableexists('bh_st_currency_log')) {
	if(!pdo_fieldexists('bh_st_currency_log',  'number')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency_log')." ADD `number` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency_log')) {
	if(!pdo_fieldexists('bh_st_currency_log',  'remarks')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency_log')." ADD `remarks` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency_log')) {
	if(!pdo_fieldexists('bh_st_currency_log',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency_log')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_currency_log')) {
	if(!pdo_fieldexists('bh_st_currency_log',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_currency_log')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_form_id')) {
	if(!pdo_fieldexists('bh_st_form_id',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_form_id')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_form_id')) {
	if(!pdo_fieldexists('bh_st_form_id',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_form_id')." ADD `member_id` int(11) NOT NULL COMMENT '用户id';");
	}	
}
if(pdo_tableexists('bh_st_form_id')) {
	if(!pdo_fieldexists('bh_st_form_id',  'form_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_form_id')." ADD `form_id` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_form_id')) {
	if(!pdo_fieldexists('bh_st_form_id',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_form_id')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_form_id')) {
	if(!pdo_fieldexists('bh_st_form_id',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_form_id')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'category_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `category_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `type` int(11) NOT NULL DEFAULT '1' COMMENT '1:实物2:虚拟';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'is_under_line')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `is_under_line` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:非线下,2:线下核销';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'original_price')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `original_price` decimal(10,2) DEFAULT NULL COMMENT '原价';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'goods_name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `goods_name` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'share_title')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `share_title` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'exchange_type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `exchange_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:活力币兑换,2:当天自己与好友步数兑换,3步数加钱,4活力币加钱';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'exchange_number')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `exchange_number` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'cover_image')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `cover_image` int(11) NOT NULL COMMENT '封面';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'image')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `image` varchar(255) NOT NULL COMMENT '轮播多张用逗号隔开';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'is_free')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `is_free` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:包邮2:不包邮';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'free')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `free` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'invitation_number')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `invitation_number` int(11) NOT NULL DEFAULT '0' COMMENT '需要邀请多少好友才能兑换';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'inventory_type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `inventory_type` tinyint(4) NOT NULL COMMENT '1:每天限量提供,2总计';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'inventory')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `inventory` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'introduce_type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `introduce_type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '商品介绍方式:1:文字2:图片';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'introduce')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `introduce` text NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'allow_number')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `allow_number` int(11) NOT NULL DEFAULT '0' COMMENT '每人允许兑换的数量0:无限兑换';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `status` int(11) NOT NULL DEFAULT '1' COMMENT '1:上架2:下架';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'sigin')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `sigin` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'is_delete')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `is_delete` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:正常2:删除';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'shop_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `shop_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `sort` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'bag_money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `bag_money` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'is_exhibition')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `is_exhibition` tinyint(4) NOT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'appid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `appid` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'path')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `path` varchar(150) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'parameter')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `parameter` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'order_explain')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `order_explain` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods')) {
	if(!pdo_fieldexists('bh_st_goods',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'shop_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `shop_id` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'goods_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `goods_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `member_id` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'content')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `content` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未领取2:已经领取';");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'is_under_line')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `is_under_line` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:非线下,2:线下核销';");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'updated')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `updated` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_fictitious')) {
	if(!pdo_fieldexists('bh_st_goods_fictitious',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_fictitious')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'goods_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `goods_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'friend_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `friend_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'step')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `step` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未使用2:已使用';");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_give')) {
	if(!pdo_fieldexists('bh_st_goods_give',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_give')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_share')) {
	if(!pdo_fieldexists('bh_st_goods_share',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_share')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_goods_share')) {
	if(!pdo_fieldexists('bh_st_goods_share',  'goods_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_share')." ADD `goods_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_share')) {
	if(!pdo_fieldexists('bh_st_goods_share',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_share')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_share')) {
	if(!pdo_fieldexists('bh_st_goods_share',  'friend_is')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_share')." ADD `friend_is` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_share')) {
	if(!pdo_fieldexists('bh_st_goods_share',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_share')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未使用2:已使用';");
	}	
}
if(pdo_tableexists('bh_st_goods_share')) {
	if(!pdo_fieldexists('bh_st_goods_share',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_share')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_goods_share')) {
	if(!pdo_fieldexists('bh_st_goods_share',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_goods_share')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'icon')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `icon` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'title')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'describe')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `describe` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'position')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `position` tinyint(4) NOT NULL COMMENT '1:首页上方,2:闲来玩玩,3:首页中间,4:商城上方,5:签到活动福利,6:签到弹窗,7:签到中间';");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `type` tinyint(4) NOT NULL COMMENT '1:跳转到其他小程序2:当前小程序内';");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'mode')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:自定义,2:流量组';");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'flow_group_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `flow_group_id` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'appid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `appid` varchar(60) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'path')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `path` varchar(500) DEFAULT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'click_number')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `click_number` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `sort` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_jump')) {
	if(!pdo_fieldexists('bh_st_jump',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_jump')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `id` int(8) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `openid` varchar(50) NOT NULL COMMENT '用户唯一标识';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `nickname` varchar(50) NOT NULL COMMENT '用户昵称';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'head')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `head` varchar(200) NOT NULL COMMENT '用户头像路径';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'gender')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `gender` tinyint(1) NOT NULL COMMENT '用户性别 0：未知  1：男  2：女';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'province')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `province` varchar(50) NOT NULL COMMENT '省份';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'city')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `city` varchar(50) NOT NULL COMMENT '城市';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'country')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `country` varchar(50) NOT NULL COMMENT '国家';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `money` decimal(10,2) NOT NULL COMMENT '账户余额';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'total_money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `total_money` decimal(10,2) NOT NULL COMMENT '总计兑换红包金额';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'add_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `add_time` int(11) NOT NULL COMMENT '用户注册时间';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'set_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `set_time` int(12) NOT NULL COMMENT '用户登录时间';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'parent_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `parent_id` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'cash')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `cash` tinyint(4) NOT NULL DEFAULT '0' COMMENT '提现次数';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'receive_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `receive_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后领取步数时间';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'shop_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `shop_id` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'currency')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `currency` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'is_fictitious')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `is_fictitious` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:真实2虚拟';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'parent_type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `parent_type` tinyint(4) NOT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'goods_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `goods_id` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_member')) {
	if(!pdo_fieldexists('bh_st_member',  'wx_uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member')." ADD `wx_uniacid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_member_voucher')) {
	if(!pdo_fieldexists('bh_st_member_voucher',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member_voucher')." ADD `id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_member_voucher')) {
	if(!pdo_fieldexists('bh_st_member_voucher',  'shop_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member_voucher')." ADD `shop_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_member_voucher')) {
	if(!pdo_fieldexists('bh_st_member_voucher',  'shop_name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member_voucher')." ADD `shop_name` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_member_voucher')) {
	if(!pdo_fieldexists('bh_st_member_voucher',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_member_voucher')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_merchant')) {
	if(!pdo_fieldexists('bh_st_merchant',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_merchant')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_merchant')) {
	if(!pdo_fieldexists('bh_st_merchant',  'shop_name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_merchant')." ADD `shop_name` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_merchant')) {
	if(!pdo_fieldexists('bh_st_merchant',  'locality')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_merchant')." ADD `locality` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_merchant')) {
	if(!pdo_fieldexists('bh_st_merchant',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_merchant')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_merchant')) {
	if(!pdo_fieldexists('bh_st_merchant',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_merchant')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_money_log')) {
	if(!pdo_fieldexists('bh_st_money_log',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_money_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_money_log')) {
	if(!pdo_fieldexists('bh_st_money_log',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_money_log')." ADD `member_id` int(11) NOT NULL COMMENT '用户id';");
	}	
}
if(pdo_tableexists('bh_st_money_log')) {
	if(!pdo_fieldexists('bh_st_money_log',  'money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_money_log')." ADD `money` decimal(10,2) NOT NULL COMMENT '变动金额';");
	}	
}
if(pdo_tableexists('bh_st_money_log')) {
	if(!pdo_fieldexists('bh_st_money_log',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_money_log')." ADD `type` tinyint(4) NOT NULL COMMENT '1:充值2:邮费3:领取红包4:提现';");
	}	
}
if(pdo_tableexists('bh_st_money_log')) {
	if(!pdo_fieldexists('bh_st_money_log',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_money_log')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_money_log')) {
	if(!pdo_fieldexists('bh_st_money_log',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_money_log')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'shop_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `shop_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'goods_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `goods_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'content')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `content` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未核销2:已核销';");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'write_off_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `write_off_id` tinyint(4) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'updated')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `updated` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_my_voucher')) {
	if(!pdo_fieldexists('bh_st_my_voucher',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_my_voucher')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'goods_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `goods_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'exchange_type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `exchange_type` tinyint(11) NOT NULL DEFAULT '1' COMMENT '1:活力币兑换,2:当天自己与好友步数兑换';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'exchange_number')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `exchange_number` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未发货2:已发货3:已完成4:已取消';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:实物2:虚拟';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'fictitious')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `fictitious` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'courier_name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `courier_name` varchar(255) NOT NULL COMMENT '快递公司名字';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'courier_number')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `courier_number` varchar(100) NOT NULL COMMENT '快递单号';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'address')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `address` varchar(1000) NOT NULL COMMENT '收货人详细地址';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'delivery_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `delivery_time` int(11) NOT NULL COMMENT '发货时间';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'complete_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `complete_time` int(11) NOT NULL COMMENT '完成时间';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `name` varchar(50) NOT NULL COMMENT '收货人姓名';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'phone')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `phone` varchar(50) NOT NULL COMMENT '收货人电话';");
	}	
}
if(pdo_tableexists('bh_st_order')) {
	if(!pdo_fieldexists('bh_st_order',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_order')." ADD `created` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'trade_no')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `trade_no` char(50) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'transaction_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `transaction_id` char(32) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'pay_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `pay_time` int(11) NOT NULL COMMENT '支付成功时间';");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `money` decimal(10,2) NOT NULL COMMENT '充值金额';");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:邮费';");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未支付 2:已支付';");
	}	
}
if(pdo_tableexists('bh_st_pay_order')) {
	if(!pdo_fieldexists('bh_st_pay_order',  'crested')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_pay_order')." ADD `crested` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_question')) {
	if(!pdo_fieldexists('bh_st_question',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_question')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_question')) {
	if(!pdo_fieldexists('bh_st_question',  'title')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_question')." ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_question')) {
	if(!pdo_fieldexists('bh_st_question',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_question')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_question')) {
	if(!pdo_fieldexists('bh_st_question',  'content')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_question')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_question')) {
	if(!pdo_fieldexists('bh_st_question',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_question')." ADD `sort` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:未提醒2:已提醒';");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'remind_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `remind_time` int(11) NOT NULL COMMENT '提醒时间';");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'remind_day')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `remind_day` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `type` int(11) NOT NULL DEFAULT '1' COMMENT '1:红包提醒2签到提醒';");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_remind')) {
	if(!pdo_fieldexists('bh_st_remind',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_remind')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_resource')) {
	if(!pdo_fieldexists('bh_st_resource',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_resource')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_resource')) {
	if(!pdo_fieldexists('bh_st_resource',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_resource')." ADD `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '上传者';");
	}	
}
if(pdo_tableexists('bh_st_resource')) {
	if(!pdo_fieldexists('bh_st_resource',  'route')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_resource')." ADD `route` varchar(255) NOT NULL COMMENT '路径';");
	}	
}
if(pdo_tableexists('bh_st_resource')) {
	if(!pdo_fieldexists('bh_st_resource',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_resource')." ADD `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:图片';");
	}	
}
if(pdo_tableexists('bh_st_resource')) {
	if(!pdo_fieldexists('bh_st_resource',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_resource')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_resource')) {
	if(!pdo_fieldexists('bh_st_resource',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_resource')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_share_group')) {
	if(!pdo_fieldexists('bh_st_share_group',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_share_group')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_share_group')) {
	if(!pdo_fieldexists('bh_st_share_group',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_share_group')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_share_group')) {
	if(!pdo_fieldexists('bh_st_share_group',  'openGId')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_share_group')." ADD `openGId` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_share_group')) {
	if(!pdo_fieldexists('bh_st_share_group',  'today')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_share_group')." ADD `today` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_share_group')) {
	if(!pdo_fieldexists('bh_st_share_group',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_share_group')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_share_group')) {
	if(!pdo_fieldexists('bh_st_share_group',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_share_group')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_shop')) {
	if(!pdo_fieldexists('bh_st_shop',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_shop')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_shop')) {
	if(!pdo_fieldexists('bh_st_shop',  'shop_name')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_shop')." ADD `shop_name` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_shop')) {
	if(!pdo_fieldexists('bh_st_shop',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_shop')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_shop')) {
	if(!pdo_fieldexists('bh_st_shop',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_shop')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'days')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `days` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'all_sigin')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `all_sigin` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'current_days')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `current_days` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'updated')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `updated` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_signin')) {
	if(!pdo_fieldexists('bh_st_signin',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_signin')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'title')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'describe')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `describe` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'icon')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `icon` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'currency')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `currency` decimal(10,0) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'appid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `appid` varchar(60) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'path')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `path` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'type')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '1:每天一次2:只能参加一次';");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'is_home')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `is_home` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:非首页,2:首页';");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `sort` int(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task')) {
	if(!pdo_fieldexists('bh_st_task',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task_member')) {
	if(!pdo_fieldexists('bh_st_task_member',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_task_member')) {
	if(!pdo_fieldexists('bh_st_task_member',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task_member')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task_member')) {
	if(!pdo_fieldexists('bh_st_task_member',  'task_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task_member')." ADD `task_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task_member')) {
	if(!pdo_fieldexists('bh_st_task_member',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task_member')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_task_member')) {
	if(!pdo_fieldexists('bh_st_task_member',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_task_member')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'step')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `step` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'currency')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `currency` decimal(10,2) NOT NULL DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'effective_step')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `effective_step` int(11) NOT NULL DEFAULT '0' COMMENT '有效步数';");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'use_step')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `use_step` int(255) NOT NULL DEFAULT '0' COMMENT '使用步数';");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_today')) {
	if(!pdo_fieldexists('bh_st_today',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_today')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_token')) {
	if(!pdo_fieldexists('bh_st_token',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_token')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_token')) {
	if(!pdo_fieldexists('bh_st_token',  'token')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_token')." ADD `token` char(32) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_token')) {
	if(!pdo_fieldexists('bh_st_token',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_token')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_token')) {
	if(!pdo_fieldexists('bh_st_token',  'session_key')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_token')." ADD `session_key` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_token')) {
	if(!pdo_fieldexists('bh_st_token',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_token')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_token')) {
	if(!pdo_fieldexists('bh_st_token',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_token')." ADD `created` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_withdrawals')) {
	if(!pdo_fieldexists('bh_st_withdrawals',  'id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_withdrawals')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('bh_st_withdrawals')) {
	if(!pdo_fieldexists('bh_st_withdrawals',  'member_id')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_withdrawals')." ADD `member_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_withdrawals')) {
	if(!pdo_fieldexists('bh_st_withdrawals',  'money')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_withdrawals')." ADD `money` decimal(10,2) NOT NULL COMMENT '提现金额';");
	}	
}
if(pdo_tableexists('bh_st_withdrawals')) {
	if(!pdo_fieldexists('bh_st_withdrawals',  'status')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_withdrawals')." ADD `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:申请成功 2:提现才成功 3:提现失败';");
	}	
}
if(pdo_tableexists('bh_st_withdrawals')) {
	if(!pdo_fieldexists('bh_st_withdrawals',  'success_time')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_withdrawals')." ADD `success_time` int(11) NOT NULL COMMENT '提现成功时间';");
	}	
}
if(pdo_tableexists('bh_st_withdrawals')) {
	if(!pdo_fieldexists('bh_st_withdrawals',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_withdrawals')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('bh_st_withdrawals')) {
	if(!pdo_fieldexists('bh_st_withdrawals',  'created')) {
		pdo_query("ALTER TABLE ".tablename('bh_st_withdrawals')." ADD `created` int(11) NOT NULL;");
	}	
}
