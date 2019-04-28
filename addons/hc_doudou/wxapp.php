<?php

//decode by http://www.yunlu99.com/
defined("IN_IA") or exit("Access Denied");
require_once IA_ROOT . "/addons/hc_doudou/functions.php";
require_once IA_ROOT . "/addons/hc_doudou/wxBizDataCrypt.php";
class Hc_doudouModuleWxapp extends WeModuleWxapp
{
	public function doPageGetopenid()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$code = $_GPC["code"];
		$account = pdo_get("account_wxapp", array("uniacid" => $_W["uniacid"]));
		$url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $account["key"] . "&secret=" . $account["secret"] . "&js_code=" . $code . "&grant_type=authorization_code";
		$result = ihttp_get($url);
		$result = json_decode($result["content"], true);
		return $this->result(0, "获取成功", $result);
	}
	public function doPageGetuserinfo()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$sessionKey = $_GPC["session_key"];
		$encryptedData = $_GPC["encryptedData"];
		$iv = $_GPC["iv"];
		$openid = $_GPC["openid"];
		$appid = pdo_getcolumn("account_wxapp", array("uniacid" => $weid), array("key"));
		$pc = new WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data);
		file_put_contents("test.txt", date('Y-m-d H:i:s').$data.PHP_EOL, FILE_APPEND);
		$return = json_decode($data, true);
		if (empty($openid) || $openid == "undefined") {
			return $this->result(1, "参数错误，缺少OPENID");
		}
		$ishave = pdo_get("hcdoudou_users", array("openid" => $openid));
		if (empty($ishave)) {
			$arr["createtime"] = time();
			$arr["weid"] = $weid;
			$arr["openid"] = $openid;
			$arr["nickname"] = $return["nickName"];
			$arr["gender"] = $return["gender"];
			$arr["city"] = $return["city"];
			$arr["province"] = $return["province"];
			$arr["country"] = $return["country"];
			$arr["avatar"] = $return["avatarUrl"];
			$arr["unionid"] = $return["unionId"];
			$arr["sessionkey"] = $sessionKey;
			$result = pdo_insert("hcdoudou_users", $arr);
			if (!empty($result)) {
				$uid = pdo_insertid();
			}
		} else {
			$arr["sessionkey"] = $sessionKey;
			if ($return["unionId"]) {
				$arr["unionid"] = $return["unionId"];
			}
			pdo_update("hcdoudou_users", $arr, array("uid" => $ishave["uid"]));
			$uid = $ishave["uid"];
		}
		$mc_have = pdo_get("mc_mapping_fans", array("openid" => $openid));
		if (empty($mc_have)) {
			$mc_data = array("acid" => $weid, "uniacid" => $weid, "uid" => $uid, "openid" => $openid, "nickname" => $arr["nickname"]);
			pdo_insert("mc_mapping_fans", $mc_data);
		}
		return $this->result(0, "操作成功", $uid);
	}
	public function doPageGoods()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$list = pdo_getall("hcdoudou_goods", array("weid" => $weid), array(), '', "sort ASC");
		foreach ($list as $key => $val) {
			$list[$key]["thumb"] = tomedia($val["thumb"]);
		}
		return $this->result(0, "获取成功", $list);
	}
	public function doPageMymoney()
	{
	   
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$money = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("money"));
		//define(PDO_DEBUG, TRUE);
		$unionid = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("unionid"));
		if (!empty($unionid)) {
			$money = pdo_getcolumn("bh_st_member", array("unionid" => $unionid), array("currency"));
		}
		//pdo_debug();
		return $this->result(0, "获取成功", $money);
	}
	public function doPageJine()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$pay = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "pay" . $weid), array("value")), "true");
		$money = explode("|", $pay["money"]);
		return $this->result(0, "获取成功", $money);
	}
	public function doPageRecharge()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$fee = $_GPC["money"];
		$uid = $_GPC["uid"];
		$pay = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "pay" . $weid), array("value")), "true");
		$money = explode("|", $pay["money"]);
		$i = 0;
		ILJAu:
		if ($i < count($money)) {
			if ($money[$i] != $fee) {
				$i++;
				goto ILJAu;
			}
			$status = true;
		}
		if (!$status) {
			return $this->result(1, "金额错误");
		}
		$trade_no = date("YmdHis") . rand(100000, 999999);
		$openid = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("openid"));
		$params = array("weid" => $weid, "uid" => $uid, "openid" => $openid, "trade_no" => $trade_no, "money" => $fee, "createtime" => time());
		$res = pdo_insert("hcdoudou_paylog", $params);
		if ($res) {
			$pid = pdo_insertid();
			$paylog = pdo_get("hcdoudou_paylog", array("id" => $pid));
			$this->payment($paylog);
		} else {
			return $this->result(1, "购买失败");
		}
	}
	public function payment($order)
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		load()->model("payment");
		load()->model("account");
		$setting = uni_setting($weid, array("payment"));
		$wechat_payment = array("appid" => $_W["account"]["key"], "signkey" => $setting["payment"]["wechat"]["signkey"], "mchid" => $setting["payment"]["wechat"]["mchid"]);
		$notify_url = $_W["siteroot"] . "addons/hc_doudou/wxpay.php";
		$res = $this->getPrePayOrder($wechat_payment, $notify_url, $order, $order["openid"]);
		if ($res["return_code"] == "FAIL") {
			return $this->result(1, "操作失败", $res["return_msg"]);
		}
		if ($res["result_code"] == "FAIL") {
			return $this->result(1, "操作失败", $res["err_code"] . $res["err_code_des"]);
		}
		if ($res["return_code"] == "SUCCESS") {
			$wxdata = $this->getOrder($res["prepay_id"], $wechat_payment);
			return $this->result(0, "操作成功", $wxdata);
		} else {
			return $this->result(1, "操作失败");
		}
	}
	public function getPrePayOrder($wechat_payment, $notify_url, $order, $openid)
	{
		$model = new HcfkModel();
		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		$data["appid"] = $wechat_payment["appid"];
		$data["body"] = "会员充值";
		$data["mch_id"] = $wechat_payment["mchid"];
		$data["nonce_str"] = $model->getRandChar(32);
		$data["notify_url"] = $notify_url;
		$data["out_trade_no"] = $order["trade_no"];
		$data["spbill_create_ip"] = $model->get_client_ip();
		$data["total_fee"] = $order["money"] * 100;
		$data["trade_type"] = "JSAPI";
		$data["openid"] = $openid;
		$data["sign"] = $model->getSign($data, $wechat_payment["signkey"]);
		$xml = $model->arrayToXml($data);
		$response = $model->postXmlCurl($xml, $url);
		return $model->xmlstr_to_array($response);
	}
	public function getOrder($prepayId, $wechat_payment)
	{
		$model = new HcfkModel();
		$data["appId"] = $wechat_payment["appid"];
		$data["nonceStr"] = $model->getRandChar(32);
		$data["package"] = "prepay_id=" . $prepayId;
		$data["signType"] = "MD5";
		$data["timeStamp"] = time();
		$data["sign"] = $model->MakeSign($data, $wechat_payment["signkey"]);
		return $data;
	}
	public function doPagePlay()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$gid = $_GPC["gid"];
		$uid = $_GPC["uid"];
		$goods = pdo_get("hcdoudou_goods", array("id" => $gid));
		$users = pdo_get("hcdoudou_users", array("uid" => $uid));
		$member = array();
		if ($users['unionid']) {
			$member = pdo_get("bh_st_member", array("unionid" => $users['unionid']));
		}

		//载入日志函数
		//load()->func('logging');
		//记录文本日志
		//logging_run('模块日志专属文件', 'trace', 'we7demo');
		if ($member) {
			$users["money"] = $member['currency'];
		}
		if ($users["money"] < $goods["price"]) {
			return $this->result(1, "余额不足");
		}
		$params = array("weid" => $weid, "gid" => $gid, "uid" => $uid, "openid" => $users["openid"], "title" => $goods["title"], "trade_no" => date("YmdHis") . rand(100000, 999999), "price" => $goods["price"], "createtime" => time());
		$res = pdo_insert("hcdoudou_order", $params);
		//define(PDO_DEBUG, TRUE);
		if ($member) {
			pdo_update("bh_st_member", array("currency" => $users["money"] - $goods["price"]), array("unionid" => $users['unionid']));
			$this->change_step_currency($member['id'],$goods["price"],12, "游戏消费积分");
		}
		else
			pdo_update("hcdoudou_users", array("money" => $users["money"] - $goods["price"]), array("uid" => $uid));
		//pdo_debug();
		if ($res) {
			return $this->result(0, "付款成功", $params);
		} else {
			return $this->result(1, "下单失败");
		}
	}
	public function doPageResult()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$trade_no = $_GPC["orderid"];
		$uid = $_GPC["uid"];
		$level = $_GPC["level"];
		$result = $_GPC["result"];
		$params["level"] = $level;
		$users = pdo_get("hcdoudou_users", array("uid" => $uid));
		$order = pdo_get("hcdoudou_order", array("trade_no" => $trade_no));
		$goods = pdo_get("hcdoudou_goods", array("id" => $order['gid']));
		if ($users['unionid']) {
			$step_users = pdo_get("bh_st_member", array("unionid" => $users['unionid']));
		}
		if ($level == 3 && $result == 2) {
			$params["type"] = 1;
			$params["status"] = 1;
			$params["passtime"] = time();
	/*	} elseif ($level == 1 && $result == 2 && $uid == 12) {
			$params["type"] = 1;
			$params["status"] = 1;
			$params["passtime"] = time();*/
		} else {
			$params["type"] = 2;
		}
		$res = pdo_update("hcdoudou_order", $params, array("trade_no" => $trade_no, "uid" => $uid));
		//define(PDO_DEBUG, TRUE);
		//pdo_update("bh_st_member", array("currency +=" => $goods["storeprice"]), array("unionid" => $users['unionid']));
       // pdo_debug();
		if ($params['type'] == 1 && $step_users  && $users['unionid'] && $goods["category"] == 2) {
		    
			pdo_update("bh_st_member", array("currency +=" => $goods["storeprice"]), array("unionid" => $users['unionid']));
			$this->change_step_currency($step_users['id'],$goods["storeprice"],11, "游戏赢积分");
		}


		if ($res) {
			return $this->result(0, "成功");
		} else {
			return $this->result(1, "闯关失败");
		}
	}
	public function doPageAddress()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$username = $_GPC["username"];
		$mobile = $_GPC["mobile"];
		$address = $_GPC["address"];
		$data = array("weid" => $weid, "uid" => $_GPC["uid"], "username" => $_GPC["username"], "mobile" => $_GPC["mobile"], "address" => $_GPC["address"]);
		pdo_insert("hcdoudou_address", $data);
		return $this->result(0, "填写成功");
	}
	public function doPageMyrouge()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$count = pdo_getcolumn("hcdoudou_order", array("uid" => $uid, "type" => 1), array("count(id)"));
		return $this->result(0, "成功", $count);
	}
	public function doPageOrder()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$pageindex = max(1, intval($_GPC["page"]));
		$pagesize = 10;
		$list = pdo_getslice("hcdoudou_order", array("weid" => $weid, "uid" => $uid), array($pageindex, $pagesize), $total, array(), '', "createtime desc");
		foreach ($list as $key => $val) {
			$goods = pdo_get("hcdoudou_goods", array("id" => $val["gid"]));
			$goods["thumb"] = tomedia($goods["thumb"]);
			$list[$key]["goods"] = $goods;
			unset($goods);
			$list[$key]["createtime"] = date("Y-m-d H:i:s", $val["createtime"]);
			$list[$key]["passtime"] = date("Y-m-d H:i:s", $val["passtime"]);
			$address = pdo_get("hcdoudou_address", array("uid" => $val["uid"]));
			$list[$key]["address"] = $address;
			unset($address);
		}
		$page = pagination($total, $pageindex, $pagesize);
		return $this->result(0, "成功", $list);
	}
	public function doPageCheckgoods()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$list = pdo_getall("hcdoudou_checkgoods", array("weid" => $weid), array(), '', "sort ASC");
		foreach ($list as $key => $val) {
			$list[$key]["thumb"] = tomedia($val["thumb"]);
		}
		return $this->result(0, "获取成功", $list);
	}
	public function doPageCheckgoods_detail()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$id = $_GPC["gid"];
		$info = pdo_get("hcdoudou_checkgoods", array("weid" => $weid, "id" => $id));
		if (!empty($info)) {
			$info["thumb"] = tomedia($info["thumb"]);
		}
		return $this->result(0, "获取成功", $info);
	}
	public function doPageBindnexus()
	{
		global $_GPC, $_W;
		$uid = $_GPC["uid"];
		$pid = empty($_GPC["pid"]) ? 0 : $_GPC["pid"];
		$self = pdo_get("hcdoudou_users", array("uid" => $uid));
		if (empty($self["pid"]) && $uid != $pid && time() - $self["createtime"] < 5) {
			$data = array("pid" => $pid, "uid" => $uid, "ctime" => time());
			$ppid = pdo_getcolumn("hcdoudou_users", array("uid" => $pid), array("pid"));
			if (!empty($ppid)) {
				$data["ppid"] = $ppid;
				$pppid = pdo_getcolumn("hcdoudou_users", array("uid" => $ppid), array("pid"));
				if (!empty($pppid)) {
					$data["pppid"] = $pppid;
				}
			}
			pdo_insert("hcdoudou_nexus", $data);
			pdo_update("hcdoudou_users", array("pid" => $pid), array("uid" => $uid));
		}
		return $this->result(0, "绑定成功");
	}
	public function doPageQrcode()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$model = new HcfkModel();
		$qrcode = $model->wxappqrcode($uid);
		$fenxiao = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "fenxiao" . $weid), array("value")), "true");
		$promo = random(16) . ".png";
		$image = $model->qrcode(tomedia($fenxiao["bgimg"]), $qrcode, $promo);
		$promo_code = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("promo_code"));
		unlink(IA_ROOT . "/addons/hc_doudou/upload/" . $promo_code);
		pdo_update("hcdoudou_users", array("promo_code" => $promo), array("uid" => $uid));
		return $this->result(0, "操作成功", $_W["siteroot"] . $image);
	}
	public function doPageTeamlist()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$level = $_GPC["level"];
		$pageindex = max(1, intval($_GPC["page"]));
		$pagesize = 20;
		if ($level == 1) {
			$where["pid"] = $uid;
		} else {
			if ($level == 2) {
				$where["ppid"] = $uid;
			} else {
				if ($level == 3) {
					$where["pppid"] = $uid;
				}
			}
		}
		$list = pdo_getslice("hcdoudou_nexus", $where, array($pageindex, $pagesize), $total, array("uid", "ctime"), '', "ctime desc");
		foreach ($list as $key => $val) {
			$list[$key]["ctime"] = date("Y-m-d H:i:s", $val["ctime"]);
			$user = pdo_get("hcdoudou_users", array("uid" => $val["uid"]), array("nickname", "avatar"));
			$list[$key]["nickname"] = $user["nickname"];
			$list[$key]["avatar"] = $user["avatar"];
			unset($user);
		}
		$page = pagination($total, $pageindex, $pagesize);
		return $this->result(0, "操作成功", $list);
	}
	public function doPageTeamcount()
	{
		global $_GPC, $_W;
		$uid = $_GPC["uid"];
		$data["level1"] = pdo_getcolumn("hcdoudou_nexus", array("pid" => $uid), array("count(uid)"));
		$data["level2"] = pdo_getcolumn("hcdoudou_nexus", array("ppid" => $uid), array("count(uid)"));
		$data["level3"] = pdo_getcolumn("hcdoudou_nexus", array("pppid" => $uid), array("count(uid)"));
		return $this->result(0, "操作成功", $data);
	}
	public function doPageCommissionList()
	{
		global $_GPC, $_W;
		$uid = $_GPC["uid"];
		$level = $_GPC["level"];
		$pageindex = max(1, intval($_GPC["page"]));
		$pagesize = 10;
		$list = pdo_getslice("hcdoudou_commission", array("user_id" => $uid, "sort" => $level), array($pageindex, $pagesize), $total, array(), '', "createtime desc");
		foreach ($list as $key => $val) {
			$list[$key]["createtime"] = date("Y-m-d H:i:s", $val["createtime"]);
			$user = pdo_get("hcdoudou_users", array("uid" => $val["sub_id"]), array("nickname", "avatar"));
			$list[$key]["nickname"] = $user["nickname"];
			$list[$key]["avatar"] = $user["avatar"];
			unset($user);
		}
		$page = pagination($total, $pageindex, $pagesize);
		return $this->result(0, "操作成功", $list);
	}
	public function doPageCommissionCount()
	{
		global $_GPC, $_W;
		$uid = $_GPC["uid"];
		$data["level1"] = pdo_getcolumn("hcdoudou_commission", array("user_id" => $uid, "sort" => 1), array("count(id)"));
		$data["level2"] = pdo_getcolumn("hcdoudou_commission", array("user_id" => $uid, "sort" => 2), array("count(id)"));
		$data["level3"] = pdo_getcolumn("hcdoudou_commission", array("user_id" => $uid, "sort" => 3), array("count(id)"));
		return $this->result(0, "操作成功", $data);
	}
	public function doPageCanmoney()
	{
		global $_GPC, $_W;
		$uid = $_GPC["uid"];
		$weid = $_W["uniacid"];
		$where = array("user_id" => $uid, "status" => 0, "freeze" => 0, "createtime <" => time() - 86400 * 0);
		$money = pdo_getcolumn("hcdoudou_commission", $where, array("sum(profit)"));
		$money = empty($money) ? 0 : $money;
		$fenxiao = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "fenxiao" . $weid), array("value")), "true");
		$level = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("level"));
		$levelname = $fenxiao["grade"][$level - 1]["grade"];
		return $this->result(0, "操作成功", array("money" => $money, "level" => $levelname, "levelno" => $level));
	}
	public function doPageCash()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$openid = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("openid"));
		$cash = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "cash" . $weid), array("value")), "true");
		$minmoney = $cash["min"];
		$maxmoney = $cash["max"];
		$feerate = $cash["fee"];
		$cashtype = $cash["type"];
		$basic = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "basic" . $weid), array("value")), "true");
		$where = array("user_id" => $uid, "status" => 0, "createtime <" => time());
		$cashmoney = pdo_getcolumn("hcdoudou_commission", $where, array("sum(profit)"));
		$transid = date("Ymdhis") . rand(11111, 99999);
		$fee = round($cashmoney * $feerate / 100, 2);
		$money = $cashmoney - $fee;
		if ($cashtype == 1) {
			if ($cashmoney < $minmoney) {
				return $this->result(0, "最低提现金额" . $minmoney . "元");
			} else {
				$undeal = pdo_get("hcdoudou_cash", array("uid" => $uid, "status" => 0));
				if (!empty($undeal)) {
					return $this->result(0, "您有待处理的提现请求，请联系客服处理后再试");
				} else {
					pdo_insert("hcdoudou_cash", array("weid" => $weid, "uid" => $uid, "transid" => $transid, "money" => $cashmoney, "fee" => $fee, "type" => 1, "status" => 0, "createtime" => time()));
					pdo_update("hcdoudou_commission", array("freeze" => 1), $where);
					return $this->result(0, "提现请求已发送");
				}
			}
		} else {
			if ($cashmoney >= $minmoney && $cashmoney < $maxmoney) {
				$res = $this->cash($openid, $money, $transid, $basic["title"]);
				if ($res["result_code"] == "FAIL") {
					return $this->result(0, "提现失败", $res["err_code_des"]);
				} else {
					pdo_insert("hcdoudou_cash", array("weid" => $weid, "uid" => $uid, "transid" => $transid, "money" => $cashmoney, "fee" => $fee, "status" => 1, "createtime" => time()));
					pdo_update("hcdoudou_commission", array("status" => 1), $where);
					return $this->result(0, "提现成功");
				}
			} else {
				if ($cashmoney >= $maxmoney) {
					$undeal = pdo_get("hcdoudou_cash", array("uid" => $uid, "status" => 0));
					if (!empty($undeal)) {
						return $this->result(0, "您有待处理的提现请求，请联系客服处理后再试");
					} else {
						pdo_insert("hcdoudou_cash", array("weid" => $weid, "uid" => $uid, "transid" => $transid, "money" => $cashmoney, "fee" => $fee, "status" => 0, "createtime" => time()));
						pdo_update("hcdoudou_commission", array("freeze" => 1), $where);
						return $this->result(0, "提现请求已发送");
					}
				} else {
					return $this->result(0, "最低提现金额" . $minmoney . "元");
				}
			}
		}
	}
	public function cash($openid, $money, $transid, $wxappname)
	{
		global $_W;
		$weid = $_W["uniacid"];
		load()->model("payment");
		load()->model("account");
		$setting = uni_setting($_W["uniacid"], array("payment"));
		$mch_appid = $_W["account"]["key"];
		$signkey = $setting["payment"]["wechat"]["signkey"];
		$mchid = $setting["payment"]["wechat"]["mchid"];
		$model = new HcfkModel();
		$pars = array();
		$url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers";
		$pars["mch_appid"] = $mch_appid;
		$pars["mchid"] = $mchid;
		$pars["nonce_str"] = random(32);
		$pars["partner_trade_no"] = $transid;
		$pars["openid"] = $openid;
		$pars["check_name"] = "NO_CHECK";
		$pars["amount"] = intval($money * 100);
		$pars["desc"] = $wxappname . "余额提现";
		$pars["spbill_create_ip"] = $model->get_client_ip();
		$pars["sign"] = $model->getSign($pars, $signkey);
		$xml = $model->array2xml($pars);
		$cert = array("CURLOPT_SSLCERT" => IA_ROOT . "/addons/hc_doudou/cert/apiclient_cert_" . $weid . ".pem", "CURLOPT_SSLKEY" => IA_ROOT . "/addons/hc_doudou/cert/apiclient_key_" . $weid . ".pem");
		$resp = ihttp_request($url, $xml, $cert);
		return $model->xmlstr_to_array($resp["content"]);
	}
	public function doPageCashList()
	{
		global $_GPC, $_W;
		$uid = $_GPC["uid"];
		$pageindex = max(1, intval($_GPC["page"]));
		$pagesize = 20;
		$list = pdo_getslice("hcdoudou_cash", array("status" => 1, "uid" => $uid), array($pageindex, $pagesize), $total, array(), '', "createtime desc");
		foreach ($list as $key => $val) {
			$list[$key]["createtime"] = date("Y-m-d H:i:s", $val["createtime"]);
			$user = pdo_get("hcdoudou_users", array("uid" => $val["uid"]), array("nickname", "avatar"));
			$list[$key]["nickname"] = $user["nickname"];
			$list[$key]["avatar"] = $user["avatar"];
			unset($user);
		}
		$page = pagination($total, $pageindex, $pagesize);
		return $this->result(0, "操作成功", $list);
	}
	public function doPageUpgrade()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$fenxiao = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "fenxiao" . $weid), array("value")), "true");
		$arr[0] = $fenxiao["grade"][1];
		$arr[0]["pic"] = tomedia($fenxiao["grade"][1]["pic"]);
		$arr[0]["commission"] = $fenxiao["commission"][1];
		$arr[0]["pricebg"] = $_W["siteroot"] . "/addons/hc_doudou/public/middle.png";
		$arr[1] = $fenxiao["grade"][2];
		$arr[1]["pic"] = tomedia($fenxiao["grade"][2]["pic"]);
		$arr[1]["commission"] = $fenxiao["commission"][2];
		$arr[1]["pricebg"] = $_W["siteroot"] . "/addons/hc_doudou/public/high.png";
		return $this->result(0, "操作成功", $arr);
	}
	public function doPageUplevel()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$level = $_GPC["level"];
		$fenxiao = json_decode(pdo_getcolumn("hcdoudou_setting", array("only" => "fenxiao" . $weid), array("value")), "true");
		$openid = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("openid"));
		$data = array("weid" => $weid, "title" => $fenxiao["grade"][$level - 1]["grade"], "trade_no" => date("YmdHis") . rand(10000, 99999), "uid" => $uid, "openid" => $openid, "price" => $fenxiao["grade"][$level - 1]["money"], "level" => $level, "createtime" => time());
		$res = pdo_insert("hcdoudou_upgrade", $data);
		if ($res) {
			$this->upgradePayment($data);
		} else {
			return $this->result(1, "网络错误");
		}
	}
	public function upgradePayment($order)
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		load()->model("payment");
		load()->model("account");
		$setting = uni_setting($weid, array("payment"));
		$wechat_payment = array("appid" => $_W["account"]["key"], "signkey" => $setting["payment"]["wechat"]["signkey"], "mchid" => $setting["payment"]["wechat"]["mchid"]);
		$notify_url = $_W["siteroot"] . "addons/hc_doudou/uplevel.php";
		$res = $this->getPrePayOrder2($wechat_payment, $notify_url, $order, $order["openid"]);
		if ($res["return_code"] == "FAIL") {
			return $this->result(1, "操作失败", $res["return_msg"]);
		}
		if ($res["result_code"] == "FAIL") {
			return $this->result(1, "操作失败", $res["err_code"] . $res["err_code_des"]);
		}
		if ($res["return_code"] == "SUCCESS") {
			$wxdata = $this->getOrder($res["prepay_id"], $wechat_payment);
			return $this->result(0, "操作成功", $wxdata);
		} else {
			return $this->result(1, "操作失败");
		}
	}
	public function getPrePayOrder2($wechat_payment, $notify_url, $order, $openid)
	{
		$model = new HcfkModel();
		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		$data["appid"] = $wechat_payment["appid"];
		$data["body"] = "会员充值";
		$data["mch_id"] = $wechat_payment["mchid"];
		$data["nonce_str"] = $model->getRandChar(32);
		$data["notify_url"] = $notify_url;
		$data["out_trade_no"] = $order["trade_no"];
		$data["spbill_create_ip"] = $model->get_client_ip();
		$data["total_fee"] = $order["price"] * 100;
		$data["trade_type"] = "JSAPI";
		$data["openid"] = $openid;
		$data["sign"] = $model->getSign($data, $wechat_payment["signkey"]);
		$xml = $model->arrayToXml($data);
		$response = $model->postXmlCurl($xml, $url);
		return $model->xmlstr_to_array($response);
	}
	public function doPageUploadimg()
	{
		global $_GPC, $_W;
		if (empty($_FILES["image"])) {
			return $this->result(1, "请上传图片！");
		}
		$type = $_FILES["image"]["type"];
		$type = explode("/", $type);
		$newfilename = "wxapp" . date("YmdHis") . rand(1000, 9999);
		$dir = IA_ROOT . "/addons/hc_doudou/upload/";
		if (!file_exists($dir)) {
			mkdir($dir);
			chmod($dir, 0777);
		}
		if (move_uploaded_file($_FILES["image"]["tmp_name"], "../addons/hc_doudou/upload/" . $newfilename . "." . $type[1])) {
			$thumb = $_W["siteroot"] . "addons/hc_doudou/upload/" . $newfilename . "." . $type[1];
			return $this->result(0, "上传成功", $thumb);
		} else {
			return $this->result(1, "上传失败");
		}
	}
	public function doPageUploadmoneycode()
	{
		global $_GPC, $_W;
		$uid = $_GPC["user_id"];
		$moneycode = $_GPC["moneycode"];
		$weid = $_W["uniacid"];
		pdo_update("hcdoudou_users", array("receipt_code" => $moneycode), array("weid" => $weid, "uid" => $uid));
		return $this->result(0, "提交成功");
	}
	public function doPageCheckmoneycode()
	{
		global $_GPC, $_W;
		$uid = $_GPC["uid"];
		$weid = $_W["uniacid"];
		$code = pdo_getcolumn("hcdoudou_users", array("weid" => $weid, "uid" => $uid), array("receipt_code"));
		return $this->result(0, "检测成功", $code);
	}
	public function doPageYuetomoney()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$uid = $_GPC["uid"];
		$undeal = pdo_get("hcdoudou_cash", array("uid" => $uid, "status" => 0));
		if (!empty($undeal)) {
			return $this->result(0, "您有待处理的提现请求，请联系客服处理后再试");
		}
		$where = array("user_id" => $uid, "status" => 0, "createtime <" => time());
		$money = pdo_getcolumn("hcdoudou_commission", $where, array("sum(profit)"));
		if ($money <= 0) {
			return $this->result(1, "收益金额不足");
		}
		$trade_no = date("YmdHis") . rand(100000, 999999);
		$openid = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("openid"));
		$params = array("weid" => $weid, "uid" => $uid, "openid" => $openid, "trade_no" => $trade_no, "status" => 2, "money" => $money, "createtime" => time(), "paytime" => time());
		$res = pdo_insert("hcdoudou_paylog", $params);
		if ($res) {
			pdo_update("hcdoudou_commission", array("status" => 1, "freeze" => 1), $where);
			$yue = pdo_getcolumn("hcdoudou_users", array("uid" => $uid), array("money"));
			pdo_update("hcdoudou_users", array("money" => $yue + $money), array("uid" => $uid));
			return $this->result(0, "充值成功");
		} else {
			return $this->result(1, "充值失败");
		}
	}
	public function doPageNotice()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$list = pdo_getall("hcdoudou_notice", array("weid" => $weid), array(), '', "createtime desc");
		return $this->result(0, "获取成功", $list);
	}
	public function doPageNoticedetail()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$id = $_GPC["id"];
		$info = pdo_get("hcdoudou_notice", array("weid" => $weid, "id" => $id));
		return $this->result(0, "获取成功", $info);
	}
	public function doPageSys()
	{
		global $_GPC, $_W;
		$weid = $_W["uniacid"];
		$conf = pdo_getall("hcdoudou_setting", array("weid" => $weid), array("title", "value"));
		foreach ($conf as $key => $val) {
			if ($val["title"] == "basic") {
				$list["basic"] = json_decode($val["value"], true);
			} else {
				if ($val["title"] == "icon") {
					$list["icon"] = json_decode($val["value"], true);
				} else {
					if ($val["title"] == "pay") {
						$list["pay"] = json_decode($val["value"], true);
					} else {
						if ($val["title"] == "forward") {
							$list["forward"] = json_decode($val["value"], true);
						} else {
							if ($val["title"] == "version") {
								$list["version"] = json_decode($val["value"], true);
							} else {
								if ($val["title"] == "cash") {
									$list["cash"] = json_decode($val["value"], true);
								} else {
									if ($val["title"] == "gzh") {
										$list["gzh"] = json_decode($val["value"], true);
									} else {
										if ($val["title"] == "fenxiao") {
											$fenxiao = json_decode($val["value"], true);
											$fenxiao["grade"][1]["pic"] = tomedia($fenxiao["grade"][1]["pic"]);
											$fenxiao["grade"][2]["pic"] = tomedia($fenxiao["grade"][2]["pic"]);
											$fenxiao["bgimg"] = tomedia($fenxiao["bgimg"]);
										}
									}
								}
							}
						}
					}
				}
			}
		}
		if ($list["version"]["number"] == $_GPC["v"]) {
			$list["stake"] = 1;
		} else {
			$list["stake"] = 0;
		}
		foreach ($list as $key => $val) {
			foreach ($val as $k => $v) {
				if (strpos($v, "images") !== false || strpos($v, "audios") !== false || strpos($v, "videos") !== false) {
					$list[$key][$k] = tomedia($v);
				}
			}
		}
		$list["fenxiao"] = $fenxiao;
		return $this->result(0, "获取成功", $list);
	}
	
	/**
	 * @param $member_id
	 * @param $currency
	 * @param int $type
	 * @param string $remarks
	 * @return bool
	 */
	private function change_step_currency($member_id, $currency, $type = 1, $remarks = '')
	{
		$data = array(
			'member_id' => $member_id,
			'type' => $type,
			'uniacid' => 2,
			'number' => $currency,
			'remarks' => $remarks,
			'created' => time()
		);
		
		//define(PDO_DEBUG, TRUE);
			pdo_insert('bh_st_currency_log', $data);       
		//更新当天记录
		$time = strtotime(date('Y-m-d', time()));
		$condition = array('member_id' => $member_id, 'created' => $time);
		$today = pdo_get('bh_st_today', $condition);
		if (empty($today)) {
			$data = array(
				'member_id' => $member_id,
				'uniacid' => 2,
				'currency' => $currency,
				'created' => $time
			);
			pdo_insert('bh_st_today', $data);
		} else {
			pdo_update('bh_st_today', array('currency' => $today['currency'] + $currency), $condition);
		}
		
		//pdo_debug();


	}
}