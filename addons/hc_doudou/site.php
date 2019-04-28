<?php

//decode by http://www.yunlu99.com/
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/hc_doudou/functions.php';
class Hc_doudouModuleSite extends WeModuleSite
{
	public function doWebSetting()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if ($_GPC['act'] == 'submit') {
			$data = array("basic" => json_encode($_GPC['basic']), "icon" => json_encode($_GPC['icon']), "pay" => json_encode($_GPC['pay']), "forward" => json_encode($_GPC['forward']), "version" => json_encode($_GPC['version']), "fenxiao" => json_encode($_GPC['fenxiao']), "cash" => json_encode($_GPC['cash']), "gzh" => json_encode($_GPC['gzh']));
			foreach ($data as $key => $val) {
				pdo_insert('hcdoudou_setting', array("weid" => $weid, "only" => $key . $weid, "title" => $key, "value" => $val), 'true');
			}
			$dir = IA_ROOT . '/addons/hc_doudou/cert';
			if (!file_exists($dir)) {
				mkdir($dir);
				chmod($dir, 0777);
			}
			if (!empty($_GPC['apiclient_cert'])) {
				file_put_contents($dir . '/apiclient_cert_' . $weid . '.pem', $_GPC['apiclient_cert']);
			}
			if (!empty($_GPC['apiclient_key'])) {
				file_put_contents($dir . '/apiclient_key_' . $weid . '.pem', $_GPC['apiclient_key']);
			}
			message('保存成功', 'referer', 'info');
		} else {
			$res = pdo_getall('hcdoudou_setting', array("weid" => $weid));
			foreach ($res as $key => $val) {
				$set[$val['title']] = json_decode($val['value'], true);
			}
			include $this->template('web/setting');
		}
	}
	public function doWebUsers()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 10;
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where = array("weid" => $weid, "nickname like" => '%' . $_GPC['keyword'] . '%');
		} else {
			$where = array("weid" => $weid);
		}
		$level = $_GPC['level'];
		if (!empty($level)) {
			$where = array("level" => $level);
		}
		$users = pdo_getslice('hcdoudou_users', $where, array($pageindex, $pagesize), $total, array(), '', 'createtime desc');
		$page = pagination($total, $pageindex, $pagesize);
		$fenxiao = json_decode(pdo_getcolumn('hcdoudou_setting', array("only" => 'fenxiao' . $weid), array("value")), 'true');
		foreach ($fenxiao['grade'] as $key => $val) {
			$fx[$key + 1] = $val['grade'];
			$fxs[$key]['id'] = $key + 1;
			$fxs[$key]['name'] = $val['grade'];
		}
		include $this->template('web/users');
	}
	public function doWebUserdo()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if ($_GPC['act'] == 'del') {
			pdo_delete('hcdoudou_users', array("uid" => $_GPC['uid']));
			message('操作成功', $this->createWebUrl('users'), 'success');
		} else {
			if ($_GPC['act'] == 'changelevel') {
				pdo_update('hcdoudou_users', array("level" => $_GPC['level']), array("uid" => $_GPC['id']));
				message('操作成功', $this->createWebUrl('users'), 'success');
			} else {
				if ($_GPC['act'] == 'team') {
					$uid = $_GPC['uid'];
					$level = $_GPC['level'];
					$p = $_GPC['p'];
					$l = $_GPC['l'];
					$k = $_GPC['k'];
					$pageindex = max(1, intval($_GPC['page']));
					$pagesize = 10;
					if ($level == 1) {
						$where['pid'] = $uid;
					} else {
						if ($level == 2) {
							$where['ppid'] = $uid;
						} else {
							if ($level == 3) {
								$where['pppid'] = $uid;
							} else {
								$level = 1;
								$where['pid'] = $uid;
							}
						}
					}
					$nickname = pdo_getcolumn('hcdoudou_users', array("uid" => $uid), array("nickname"));
					$list = pdo_getslice('hcdoudou_nexus', $where, array($pageindex, $pagesize), $total, array("uid", "ctime"), '', 'ctime desc');
					foreach ($list as $key => $val) {
						$list[$key]['user'] = pdo_get('hcdoudou_users', array("uid" => $val['uid']));
					}
					$page = pagination($total, $pageindex, $pagesize);
					include $this->template('web/team');
				} else {
					if ($_GPC['act'] == 'commission') {
						$uid = $_GPC['uid'];
						$level = $_GPC['level'];
						if (empty($level)) {
							$level = 1;
						}
						$p = $_GPC['p'];
						$l = $_GPC['l'];
						$k = $_GPC['k'];
						$pageindex = max(1, intval($_GPC['page']));
						$pagesize = 10;
						$nickname = pdo_getcolumn('hcdoudou_users', array("uid" => $uid), array("nickname"));
						$list = pdo_getslice('hcdoudou_commission', array("user_id" => $uid, "sort" => $level), array($pageindex, $pagesize), $total, array(), '', 'createtime desc');
						foreach ($list as $key => $val) {
							$list[$key]['user'] = pdo_get('hcdoudou_users', array("uid" => $val['sub_id']));
						}
						$page = pagination($total, $pageindex, $pagesize);
						include $this->template('web/commission');
					} else {
						$info = pdo_get('hcdoudou_users', array("uid" => $_GPC['uid']));
						$fenxiao = json_decode(pdo_getcolumn('hcdoudou_setting', array("only" => 'fenxiao' . $weid), array("value")), 'true');
						foreach ($fenxiao['grade'] as $key => $val) {
							$fx[$key]['id'] = $key + 1;
							$fx[$key]['name'] = $val['grade'];
						}
						include $this->template('web/users_post');
					}
				}
			}
		}
	}
	public function doWebGuan()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where['title like'] = '%' . $keyword . '%';
		}
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_guan', $where, array($pageindex, $pagesize), $total, array(), '', 'id desc');
		foreach ($list as $key => $val) {
			$list[$key]['loadpic'] = tomedia($val['loadpic']);
			$list[$key]['rollpic'] = tomedia($val['rollpic']);
			$list[$key]['proppic'] = tomedia($val['proppic']);
			$list[$key]['gamebgm'] = tomedia($val['gamebgm']);
			$list[$key]['passbgm'] = tomedia($val['passbgm']);
			$list[$key]['losebgm'] = tomedia($val['losebgm']);
		}
		$page = pagination($total, $pageindex, $pagesize);
		include $this->template('web/guan');
	}
	public function doWebGuan_post()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if ($_GPC['act'] == 'edit') {
			$data = array("weid" => $weid, "sort" => $_GPC['sort'], "times" => $_GPC['times'], "loadpic" => $_GPC['loadpic'], "rollpic" => $_GPC['rollpic'], "proppic" => $_GPC['proppic'], "gamebgm" => $_GPC['gamebgm'], "passbgm" => $_GPC['passbgm'], "losebgm" => $_GPC['losebgm']);
			pdo_update('hcdoudou_guan', $data, array("id" => $_GPC['id']));
			message('操作成功', $this->createWebUrl('guan'), 'success');
		} else {
			if (!empty($_GPC['id'])) {
				$info = pdo_get('hcdoudou_guan', array("id" => $_GPC['id']));
			}
			include $this->template('web/guan_post');
		}
	}
	public function doWebGoods()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where['title like'] = '%' . $keyword . '%';
		}
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_goods', $where, array($pageindex, $pagesize), $total, array(), '', 'id desc');
		foreach ($list as $key => $val) {
			$list[$key]['thumb'] = tomedia($val['thumb']);
		}
		$page = pagination($total, $pageindex, $pagesize);
		include $this->template('web/goods');
	}
	public function doWebGoods_post()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if ($_GPC['act'] == 'add') {
			$data = array("weid" => $weid, "sort" => $_GPC['sort'], "title" => $_GPC['title'], "model" => $_GPC['model'], "price" => $_GPC['price'], "storeprice" => $_GPC['storeprice'], "thumb" => $_GPC['thumb'], "category" => $_GPC['type']);
			pdo_insert('hcdoudou_goods', $data);
			message('操作成功', $this->createWebUrl('goods'), 'success');
		} else {
			if ($_GPC['act'] == 'edit') {
				$data = array("weid" => $weid, "sort" => $_GPC['sort'], "title" => $_GPC['title'], "model" => $_GPC['model'], "price" => $_GPC['price'], "storeprice" => $_GPC['storeprice'], "thumb" => $_GPC['thumb'], "category" => $_GPC['type']);
				pdo_update('hcdoudou_goods', $data, array("id" => $_GPC['id']));
				message('操作成功', $this->createWebUrl('goods'), 'success');
			} else {
				if ($_GPC['act'] == 'del') {
					pdo_delete('hcdoudou_goods', array("id" => $_GPC['id']));
					message('操作成功', $this->createWebUrl('goods'), 'success');
				} else {
					if ($_GPC['act'] == 'moredel') {
						foreach (explode(',', $_GPC['ids']) as $key => $val) {
							pdo_delete('hcdoudou_goods', array("id" => $val));
						}
						message('操作成功', $this->createWebUrl('question'), 'success');
					} else {
						if (!empty($_GPC['id'])) {
							$info = pdo_get('hcdoudou_goods', array("id" => $_GPC['id']));
						}
						include $this->template('web/goods_post');
					}
				}
			}
		}
	}
	public function doWebCount()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$type = $_GPC['type'];
		if ($type == 1) {
			$where['type'] = 1;
		}
		if ($type == 2) {
			$where['type'] = 0;
		}
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where['trade_no'] = $keyword;
		}
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_order', $where, array($pageindex, $pagesize), $total, array(), '', 'id desc');
		foreach ($list as $key => $val) {
			$user = pdo_get('hcdoudou_users', array("uid" => $val['uid']), array("avatar", "nickname"));
			$list[$key]['avatar'] = $user['avatar'];
			$list[$key]['nickname'] = $user['nickname'];
			unset($user);
			$goods = pdo_get('hcdoudou_goods', array("id" => $val['gid']), array("thumb"));
			$list[$key]['goodsthumb'] = tomedia($goods['thumb']);
			unset($goods);
		}
		$page = pagination($total, $pageindex, $pagesize);
		$total_price = pdo_getcolumn('hcdoudou_order', array("weid" => $weid), array("sum(price)"));
		include $this->template('web/count');
	}
	public function doWebOrder()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$keywordtype = $_GPC['keywordtype'];
		$keyword = $_GPC['keyword'];
		$status = $_GPC['status'];
		if (!empty($status)) {
			$where['status'] = $status;
		}
		if ($keywordtype == '1') {
			$where['trade_no'] = $keyword;
		} else {
			if ($keywordtype == '2') {
				$where['openid'] = $keyword;
			} else {
				if ($keywordtype == '3') {
					$where['title like'] = '%' . $keyword . '%';
				} else {
					if ($keywordtype == '4') {
						$where['gid'] = $keyword;
					}
				}
			}
		}
		$where['type'] = 1;
		$where['weid'] = $weid;
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$list = pdo_getslice('hcdoudou_order', $where, array($pageindex, $pagesize), $total, array(), '', 'createtime desc');
		foreach ($list as $key => $val) {
			$user = pdo_get('hcdoudou_users', array("uid" => $val['uid']), array("avatar", "nickname"));
			$list[$key]['avatar'] = $user['avatar'];
			$list[$key]['nickname'] = $user['nickname'];
			unset($user);
			$goods = pdo_get('hcdoudou_goods', array("id" => $val['gid']));
			$list[$key]['goodsthumb'] = tomedia($goods['thumb']);
			$list[$key]['model'] = $goods['model'];
			unset($goods);
		}
		$page = pagination($total, $pageindex, $pagesize);
		include $this->template('web/order');
	}
	public function doWebOrder_post()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if ($_GPC['act'] == 'addexpresn') {
			pdo_update('hcdoudou_order', array("expretime" => time(), "expresn" => $_GPC['code'], "status" => 2), array("id" => $_GPC['id']));
			message('操作成功', $this->createWebUrl('order'), 'success');
		} else {
			$info = pdo_get('hcdoudou_order', array("trade_no" => $_GPC['trade_no']));
			$address = pdo_get('hcdoudou_address', array("uid" => $info['uid'], "weid" => $weid));
			include $this->template('web/order_post');
		}
	}
	public function doWebRecharge()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$start = $_GPC['start'];
		$end = $_GPC['end'];
		if (!empty($start)) {
			$where['createtime >='] = $starts = strtotime($start);
		} else {
			$where['createtime >='] = $starts = strtotime(date('Ymd'));
		}
		if (!empty($end)) {
			$where['createtime <'] = $ends = strtotime($end) + 86400;
		} else {
			$where['createtime <'] = $ends = strtotime(date('Ymd')) + 86400;
		}
		$status = $_GPC['status'];
		if ($status == 1) {
			$where['status'] = 1;
		} else {
			if ($status == 2) {
				$where['status'] = 2;
			} else {
				if ($status == 3) {
					$where['status'] = 0;
				}
			}
		}
		$keyword = $_GPC['keyword'];
		if (!empty($keyword)) {
			$where['trade_no'] = $keyword;
		}
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_paylog', $where, array($pageindex, $pagesize), $total, array(), '', 'createtime desc');
		foreach ($list as $key => $val) {
			$user = pdo_get('hcdoudou_users', array("uid" => $val['uid']), array("avatar", "nickname"));
			$list[$key]['avatar'] = $user['avatar'];
			$list[$key]['nickname'] = $user['nickname'];
			unset($user);
		}
		$page = pagination($total, $pageindex, $pagesize);
		$total_fee = pdo_getcolumn('hcdoudou_paylog', array("status" => 1, "weid" => $weid), array("sum(total_fee)"));
		$yizhifu = array("status" => 1, "weid" => $weid, "createtime >=" => $starts, "createtime <" => $ends);
		$yizhifu_fee = pdo_getcolumn('hcdoudou_paylog', $yizhifu, array("sum(total_fee)"));
		$daizhifu = array("status" => 0, "weid" => $weid, "createtime >=" => $starts, "createtime <" => $ends);
		$daizhifu_fee = pdo_getcolumn('hcdoudou_paylog', $daizhifu, array("sum(money)"));
		$zhuanru = array("status" => 2, "weid" => $weid, "createtime >=" => $starts, "createtime <" => $ends);
		$zhuanru_fee = pdo_getcolumn('hcdoudou_paylog', $zhuanru, array("sum(money)"));
		include $this->template('web/recharge');
	}
	public function doWebCheckgoods()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_checkgoods', $where, array($pageindex, $pagesize), $total, array(), '', 'id desc');
		foreach ($list as $key => $val) {
			$list[$key]['thumb'] = tomedia($val['thumb']);
		}
		$page = pagination($total, $pageindex, $pagesize);
		include $this->template('web/checkgoods');
	}
	public function doWebCheckgoods_post()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if ($_GPC['act'] == 'add') {
			$data = array("weid" => $weid, "sort" => $_GPC['sort'], "title" => $_GPC['title'], "model" => $_GPC['model'], "price" => $_GPC['price'], "thumb" => $_GPC['thumb']);
			pdo_insert('hcdoudou_checkgoods', $data);
			message('操作成功', $this->createWebUrl('checkgoods'), 'success');
		} else {
			if ($_GPC['act'] == 'edit') {
				$data = array("weid" => $weid, "sort" => $_GPC['sort'], "title" => $_GPC['title'], "model" => $_GPC['model'], "price" => $_GPC['price'], "thumb" => $_GPC['thumb']);
				pdo_update('hcdoudou_checkgoods', $data, array("id" => $_GPC['id']));
				message('操作成功', $this->createWebUrl('checkgoods'), 'success');
			} else {
				if ($_GPC['act'] == 'del') {
					pdo_delete('hcdoudou_checkgoods', array("id" => $_GPC['id']));
					message('操作成功', $this->createWebUrl('checkgoods'), 'success');
				} else {
					if (!empty($_GPC['id'])) {
						$info = pdo_get('hcdoudou_checkgoods', array("id" => $_GPC['id']));
					}
					include $this->template('web/checkgoods_post');
				}
			}
		}
	}
	public function doWebNotice()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 20;
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_notice', $where, array($pageindex, $pagesize), $total, array(), '', 'createtime desc');
		$page = pagination($total, $pageindex, $pagesize);
		include $this->template('web/notice');
	}
	public function doWebNotice_post()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		if ($_GPC['act'] == 'add') {
			$data = array("weid" => $weid, "title" => $_GPC['title'], "content" => $_GPC['content'], "createtime" => time());
			pdo_insert('hcdoudou_notice', $data);
			message('操作成功', $this->createWebUrl('notice'), 'success');
		} else {
			if ($_GPC['act'] == 'edit') {
				$data = array("weid" => $weid, "title" => $_GPC['title'], "content" => $_GPC['content'], "createtime" => time());
				pdo_update('hcdoudou_notice', $data, array("id" => $_GPC['id']));
				message('操作成功', $this->createWebUrl('notice'), 'success');
			} else {
				if ($_GPC['act'] == 'del') {
					pdo_delete('hcdoudou_notice', array("id" => $_GPC['id']));
					message('操作成功', $this->createWebUrl('notice'), 'success');
				} else {
					if (!empty($_GPC['id'])) {
						$info = pdo_get('hcdoudou_notice', array("id" => $_GPC['id']));
					}
					include $this->template('web/notice_post');
				}
			}
		}
	}
	public function doWebUpgrade()
	{
		global $_GPC, $_W;
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 10;
		$weid = $_W['uniacid'];
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_upgrade', $where, array($pageindex, $pagesize), $total, array(), '', 'createtime desc');
		foreach ($list as $key => $val) {
			$user = pdo_get('hcdoudou_users', array("uid" => $val['uid']), array("avatar", "nickname"));
			$list[$key]['avatar'] = $user['avatar'];
			$list[$key]['nickname'] = $user['nickname'];
			unset($user);
		}
		$page = pagination($total, $pageindex, $pagesize);
		include $this->template('web/upgrade');
	}
	public function doWebCash()
	{
		global $_GPC, $_W;
		$pageindex = max(1, intval($_GPC['page']));
		$pagesize = 10;
		$weid = $_W['uniacid'];
		$where['weid'] = $weid;
		$list = pdo_getslice('hcdoudou_cash', $where, array($pageindex, $pagesize), $total, array(), '', 'createtime desc');
		foreach ($list as $key => $val) {
			$user = pdo_get('hcdoudou_users', array("uid" => $val['uid']), array("avatar", "nickname", "receipt_code"));
			$list[$key]['avatar'] = $user['avatar'];
			$list[$key]['nickname'] = $user['nickname'];
			$list[$key]['receipt_code'] = $user['receipt_code'];
			unset($user);
		}
		$page = pagination($total, $pageindex, $pagesize);
		include $this->template('web/cash');
	}
	public function doWebSyscash()
	{
		global $_GPC, $_W;
		$weid = $_W['uniacid'];
		$id = $_GPC['id'];
		$type = $_GPC['type'];
		$cash = pdo_get('hcdoudou_cash', array("id" => $id));
		$uid = $cash['uid'];
		$where = array("user_id" => $uid, "status" => 0, "freeze" => 1);
		if ($type == 1) {
			$openid = pdo_getcolumn('hcdoudou_users', array("uid" => $uid), array("openid"));
			$conf = json_decode(pdo_getcolumn('hcdoudou_setting', array("only" => 'basic' . $weid), array("value")), 'true');
			$money = $cash['money'] - $cash['fee'];
			$res = $this->cash($openid, $money, $cash['transid'], $conf['title']);
			if ($res['result_code'] == 'FAIL') {
				message($res['err_code_des'], '', 'error');
			} else {
				pdo_update('hcdoudou_cash', array("status" => 1), array("id" => $id));
				pdo_update('hcdoudou_commission', array("freeze" => 0, "status" => 1), $where);
				message('提现成功', '', 'success');
			}
		} else {
			if ($type == 2) {
				pdo_update('hcdoudou_cash', array("status" => 2), array("id" => $id));
				pdo_update('hcdoudou_commission', array("freeze" => 0), $where);
				message('拒绝成功', '', 'success');
			} else {
				if ($type == 3) {
					pdo_update('hcdoudou_cash', array("status" => 1), array("id" => $id));
					pdo_update('hcdoudou_commission', array("freeze" => 0, "status" => 1), $where);
					message('发放成功', '', 'success');
				}
			}
		}
	}
	public function cash($openid, $money, $transid, $wxappname)
	{
		global $_W;
		$weid = $_W['uniacid'];
		load()->model('payment');
		load()->model('account');
		$setting = uni_setting($_W['uniacid'], array("payment"));
		$mch_appid = $_W['account']['key'];
		$signkey = $setting['payment']['wechat']['signkey'];
		$mchid = $setting['payment']['wechat']['mchid'];
		$model = new HcfkModel();
		$pars = array();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
		$pars['mch_appid'] = $mch_appid;
		$pars['mchid'] = $mchid;
		$pars['nonce_str'] = random(32);
		$pars['partner_trade_no'] = $transid;
		$pars['openid'] = $openid;
		$pars['check_name'] = 'NO_CHECK';
		$pars['amount'] = intval($money * 100);
		$pars['desc'] = $wxappname . '余额提现';
		$pars['spbill_create_ip'] = $model->get_client_ip();
		$pars['sign'] = $model->getSign($pars, $signkey);
		$xml = $model->array2xml($pars);
		$cert = array("CURLOPT_SSLCERT" => IA_ROOT . '/addons/hc_doudou/cert/apiclient_cert_' . $weid . '.pem', "CURLOPT_SSLKEY" => IA_ROOT . '/addons/hc_doudou/cert/apiclient_key_' . $weid . '.pem');
		$resp = ihttp_request($url, $xml, $cert);
		return $model->xmlstr_to_array($resp['content']);
	}
	public function doMobileGames()
	{
		global $_GPC, $_W;
		$game = $_GPC['game'];
		if ($game == 1) {
			include $this->template('taste');
		} else {
			include $this->template('play');
		}
	}
}