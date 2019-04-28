<?php
pdo_query("DROP TABLE IF EXISTS `ims_bh_st_address`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_address` (
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

DROP TABLE IF EXISTS `ims_bh_st_bag`;
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

DROP TABLE IF EXISTS `ims_bh_st_cash_pwd`;
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

DROP TABLE IF EXISTS `ims_bh_st_category`;
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

DROP TABLE IF EXISTS `ims_bh_st_config`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_config` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`key` varchar(50) NOT NULL,
`value` text NOT NULL,
`uniacid` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`,`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_bh_st_currency`;
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

DROP TABLE IF EXISTS `ims_bh_st_currency_log`;
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

DROP TABLE IF EXISTS `ims_bh_st_form_id`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_form_id` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL COMMENT '用户id',
`form_id` varchar(50) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_bh_st_goods`;
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

DROP TABLE IF EXISTS `ims_bh_st_goods_fictitious`;
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

DROP TABLE IF EXISTS `ims_bh_st_goods_give`;
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

DROP TABLE IF EXISTS `ims_bh_st_goods_share`;
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

DROP TABLE IF EXISTS `ims_bh_st_jump`;
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

DROP TABLE IF EXISTS `ims_bh_st_member`;
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

DROP TABLE IF EXISTS `ims_bh_st_member_voucher`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_member_voucher` (
`id` int(11) NOT NULL,
`shop_id` int(11) NOT NULL,
`shop_name` varchar(255) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_bh_st_merchant`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_merchant` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`shop_name` varchar(255) NOT NULL,
`locality` varchar(500) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_bh_st_money_log`;
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

DROP TABLE IF EXISTS `ims_bh_st_my_voucher`;
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

DROP TABLE IF EXISTS `ims_bh_st_order`;
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

DROP TABLE IF EXISTS `ims_bh_st_pay_order`;
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

DROP TABLE IF EXISTS `ims_bh_st_question`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_question` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`content` text NOT NULL,
`sort` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_bh_st_remind`;
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

DROP TABLE IF EXISTS `ims_bh_st_resource`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_resource` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`member_id` int(11) NOT NULL DEFAULT '0' COMMENT '上传者',
`route` varchar(255) NOT NULL COMMENT '路径',
`type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:图片',
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_bh_st_share_group`;
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

DROP TABLE IF EXISTS `ims_bh_st_shop`;
CREATE TABLE IF NOT EXISTS `ims_bh_st_shop` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`shop_name` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`created` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_bh_st_signin`;
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

DROP TABLE IF EXISTS `ims_bh_st_task`;
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

DROP TABLE IF EXISTS `ims_bh_st_task_member`;
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

DROP TABLE IF EXISTS `ims_bh_st_today`;
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

DROP TABLE IF EXISTS `ims_bh_st_token`;
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

DROP TABLE IF EXISTS `ims_bh_st_withdrawals`;
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
