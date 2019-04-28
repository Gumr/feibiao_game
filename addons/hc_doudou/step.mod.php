<?php
defined('IN_IA') or exit('Access Denied');

class step{

    public $error = '';

    /**
     * @var string
     */
    protected $url  = 'wx.miaobeiwang.com';

    public $signin_days = array(
        1 => array(
            'key' => 'signin_one_day',
            'default' => 2
        ),
        2 => array(
            'key' => 'signin_two_day',
            'default' => 2
        ),
        3 => array(
            'key' => 'signin_three_day',
            'default' => 2
        ),
        4 => array(
            'key' => 'signin_four_day',
            'default' => 2
        ),
        5 => array('key' => 'signin_five_day',  'default' => 2),
        6 => array('key' => 'signin_six_day',  'default' => 2),
        7 => array('key' => 'signin_seven_day',  'default' => 2)
    );

    /**
     *
     * @return bool
     */
    public function getCurrentDomain()
    {
        global  $_W;
        $basename = pathinfo($_W['siteroot']);
        if ($this->url == $basename['basename']) {
            return true;
        }
        return false;
    }
    /**
     * 获取签到天数
     * @param $member_id
     * @return int
     */
    public function get_signin($member_id)
    {
        $signin = p_get('signin', array('member_id' => $member_id));
        if (empty($signin)) {
            return 0;
        }

        if ($signin['updated'] > strtotime(date('Y-m-d', time())) - 86400) {
            return $signin['days'];
        }
        return 0;
    }


    public function cash_password()
    {
        $follow_cash_pwd = getConfig('follow_cash_pwd');
        if ($this->getCurrentDomain('')) {
            $date = date('Y-m-d H:i', time());
            $follow_cash_pwd = md5($date . $follow_cash_pwd);
            $follow_cash_pwd = substr($follow_cash_pwd, 12);
        }

        return $follow_cash_pwd;
    }


    /**
     * @param $member_id
     * @return bool
     */
    public function today_signin($member_id)
    {
        $signin = p_get('signin', array('member_id' => $member_id));
        if (empty($signin)) {
            return false;
        }
        $time = strtotime(date('Y-m-d', time()));
        return $signin['updated'] >= $time;
    }


    /**
     * 签到
     * @param $member_id
     * @return bool|int
     */
    public function signin($member_id)
    {
        try {
            pdo_begin();
            $sigin = p_get('signin', array('member_id' => $member_id));
            if (empty($sigin)) {
                $insert = array(
                    'member_id' => $member_id,
                    'days' => 1,
                    'all_sigin' => 1,
                    'current_days' => 1,
                    'updated' => time(),
                    'created' => time()
                );
                if (!p_insert('signin', $insert)) {
                    throw new Exception('签到失败,稍后再试');
                }
                $days = 1;
            } else {

                $today = strtotime(date('Y-m-d', time()));
                if ($sigin['updated'] >= $today) {
                    return $sigin['days'];
                }
                $update = array(
                    'all_sigin' => $sigin['all_sigin'] + 1,
                    'updated' => time()
                );
                if ($sigin['updated'] >= $today - 86400) {
                    $update['current_days'] = $sigin['current_days'] + 1;
                    //$update['days'] = $sigin['days'] > 6 ? 1 : ($sigin['days'] + 1);
                    $update['days'] = $sigin['days'] + 1;
                } else {
                    $update['current_days'] = 1;
                    $update['days'] = 1;
                }

                if (!p_update('signin', $update, array('id' => $sigin['id']))) {
                    throw new Exception('签到失败,稍后再试');
                }
                $days = $update['days'];
            }

            if (!$this->signin_reward($days, $member_id)) {
                throw new Exception($this->error);
            }
            pdo_commit();
            return $days;
        } catch (Exception $e) {
            pdo_rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }



    /**
     * 签到奖励活力币
     * @param $days
     * @param $member_id
     * @return bool
     */
    public function signin_reward($days, $member_id)
    {
        if (!isset($this->signin_days[$days])) {
            $this->error = '签到天数不存在';
            return false;
        }
        $currency = getConfig($this->signin_days[$days]['key']) ? : $this->signin_days[$days]['default'];
        $data = array(
            'currency' => $currency,
            'member_id' => $member_id,
            'today' => strtotime(date('Y-m-d', time())),
            'source' => 3,
            'created' => time(),
        );
        if (!p_insert('currency', $data)) {
            $this->error = '签到失败';
            return false;
        }

        return true;
    }


    /**
     * 邀请好友获得活力币
     *
     * @param $member_id
     * @param $be_invited_id
     * @return bool
     */
    public function invitation_currency($member_id, $be_invited_id)
    {
        $invited_number = getConfig('invitation_effective_number');
        $time = strtotime(date('Y-m-d', time()));
        $count = p_fetchcolumn('SELECT COUNT(*) FROM ' . prefix('member') . " WHERE parent_id={$member_id} AND add_time> {$time} AND uniacid=" . UNIACID);

        if ($count >= $invited_number) {
            return true;
        }
        if (p_get('currency_log', array('member_id' => $member_id, 'type' => 4, 'remarks' => $be_invited_id))) {
            return true;
        }

        $start_share = getConfig('invitation_currency_start') * 100;
        $start_end = getConfig('invitation_currency_end') * 100;
        $currency = mt_rand($start_share, $start_end) / 100;

        return $this->change_currency($member_id, $currency, 4, $be_invited_id);
    }



    /**
     * 更新活力币
     * @param $member_id
     * @param $currency
     * @param int $type
     * @param string $remarks
     * @return bool
     */
    public function change_currency($member_id, $currency, $type = 1, $remarks = '')
    {
        if ($currency <= 0) {
            return true;
        }
        $member = p_get('member', array('id' => $member_id));
        if (in_array($type, array(1, 2, 3, 4, 6, 7, 8, 10))) {
            $new_currency = $member['currency'] + $currency;
        } else {
            if ($currency > $member['currency']) {
                $this->error = '您的' . getConfig('currency_name', '活力币') . '不足';
                return false;
            }
            $new_currency = $member['currency'] - $currency;
        }
        if (!p_update('member', array('currency' => $new_currency), array('id' => $member_id))) {
            $this->error = getConfig('currency_name', '活力币') . '变更失败';
            return false;
        }

        $types = array(
            1 => '任务奖励',
            2 => '步数转化',
            3 => '签到奖励',
            4 => '邀请奖励',
            5 => '领取每日红包',
            6 => '授权金币',
            7 => '关注公众号',
            8 => '分享群奖励',
            9 => '兑换商品',
            10 => '取消订单'
        );
        if (empty($remarks)) {
            $remarks = $types[$type];
        }

        $data = array(
            'member_id' => $member_id,
            'type' => $type,
            'number' => $currency,
            'remarks' => $remarks,
            'created' => time()
        );
        if (!p_insert('currency_log', $data)) {
            $this->error = getConfig('currency_name', '活力币') . '变更失败';
            return false;
        }

        //更新当天记录
        $time = strtotime(date('Y-m-d', time()));
        $condition = array('member_id' => $member_id, 'created' => $time);
        $today = p_get('today', $condition);
        if (empty($today)) {
            $data = array(
                'member_id' => $member_id,
                'currency' => $currency,
                'created' => $time
            );
            if (!p_insert('today', $data)) {
                $this->error = getConfig('currency_name', '活力币') . '变更失败';
                return false;
            }
        } else {
            if (!p_update('today', array('currency' => $today['currency'] + $currency), $condition)) {
                $this->error = '活力币变更失败';
                return false;
            }
        }

        return true;
    }


    /**
     * 商品邀请
     * @param $member_id
     * @param $friend_is
     * @param $goods_id
     * @return bool
     */
    public function goods_share($member_id, $friend_is, $goods_id)
    {
        $goods = p_get('goods', array('id' => $goods_id, 'is_delete' => 1, 'status' => 1));
        if (empty($goods) || $goods['invitation_number'] < 1) {
            $this->error = '商品不存在';
            return false;
        }
        if (p_get('goods_share', array('member_id' => $member_id, 'goods_id' => $goods_id, 'friend_is' => $friend_is))) {
            return false;
        }
        $data = array(
            'goods_id' => $goods_id,
            'member_id' => $member_id,
            'friend_is' => $friend_is,
            'created' => time()
        );
        if (p_insert('goods_share', $data)) {
            return true;
        }
        return false;
    }



    /**
     * 某个红包领取情况
     * @param $member_id
     * @param $frequency
     * @return int
     */
    public function is_receive_bag($member_id, $frequency)
    {
        if ($bag = $this->is_today_receive($member_id, $frequency)) {
            $this->error = '该红包你已经领取过';
            return 1;
        }

        if ($frequency > 1) {
            $first = $this->is_today_receive($member_id, 1);
            $keys = array(
                2 => 'bag_one_cooling',
                3 => 'bag_two_cooling',
                4 => 'bag_three_cooling',
                5 => 'bag_four_cooling'
            );
            $cooling = getConfig($keys[$frequency]);
            if (time() - $first['created'] < $cooling * 3600) {
                $number = p_fetchcolumn("SELECT COUNT(*) FROM " . prefix('member') . " WHERE parent_id={$member_id} AND add_time >= {$first['created']} AND (nickname != '' || head != '') AND uniacid=" . UNIACID);

                $bag_invitations_number = getConfig('bag_invitations_number');

                if ($bag_invitations_number * ($frequency - 1) > $number) {
                    $this->error = '您邀请的好友数量还不够';
                    return 2;
                }
            }
        }
        return 3;
    }



    /**
     * 领取红包
     * @param $member_id
     * @return bool
     */
    public function receive($member_id, $frequency = 1)
    {
        if ($this->is_receive_bag($member_id, $frequency) != 3) {
            return false;
        }

        $start = 0;
        $end = 0;
        /*$probability = getConfig('special_bag_probability', 0);
        if ($probability > 0) {
            $mt = mt_rand(1, 100);
            $special_bag_start = getConfig('special_bag_start', 0);
            $special_bag_end = getConfig('special_bag_end', 0);
            if ($special_bag_end > 0
                && $special_bag_start > 0
                && $special_bag_end > $special_bag_start
                && $mt <= $probability
            ) {
                $start = $special_bag_start * 100;
                $end = $special_bag_end * 100;
            }
        }
        if ($end == 0) {
            $ordinary_bag_start = getConfig('ordinary_bag_start');
            $ordinary_bag_end = getConfig('ordinary_bag_end');
            if ($ordinary_bag_start > 0 && $ordinary_bag_end > 0) {
                $start = $ordinary_bag_start * 100;
                $end = $ordinary_bag_end * 100;
            }
        }*/
        if (p_get('bag', array('member_id' => $member_id))) {
            $starts = array(
                1 => 'ordinary_bag_start_one',
                2 => 'ordinary_bag_start_two',
                3 => 'ordinary_bag_start_three',
                4 => 'ordinary_bag_start_four',
                5 => 'ordinary_bag_start_five'
            );
            $ends = array(
                1 => 'ordinary_bag_end_one',
                2 => 'ordinary_bag_end_two',
                3 => 'ordinary_bag_end_three',
                4 => 'ordinary_bag_end_four',
                5 => 'ordinary_bag_end_five'
            );

            $start = getConfig($starts[$frequency], getConfig('ordinary_bag_start')) * 100;
            $end = getConfig($ends[$frequency], getConfig('ordinary_bag_start')) * 100;

        } else {
            $start = getConfig('new_ordinary_bag_start', getConfig('ordinary_bag_start')) * 100;
            $end = getConfig('new_ordinary_bag_end', getConfig('ordinary_bag_end')) * 100;
        }

        if ($start > 0 && $end > 0) {
            $data = array(
                'money' => mt_rand($start, $end) / 100,
                'member_id' => $member_id,
                'a_few' => $frequency,
                'created' => time()
            );
            $step_number_bag = getConfig('bag_step_number');

            $today = $this->day_info($member_id);
            if ($today['step'] < $step_number_bag) {
                //$this->error = '你今天步数还没走够';
                //return false;
            }

            //$member =p_get('member', ['id' => $member_id]);
            $bag_currency = getConfig('bag_currency');
            $bag_currency_last = getConfig('bag_currency_last');

            try {
                pdo_begin();

                if (($frequency == 1 || $bag_currency_last == 1)) {
                    if (!$this->change_currency($member_id, $bag_currency, 5)) {
                        throw new Exception($this->error);
                    }
                }

                if (!p_insert('bag', $data)) {
                    throw new Exception('领取失败');
                }
                if (!$this->change_money($member_id, $data['money'], 3)) {
                    throw new Exception($this->error);
                }

                pdo_commit();
                return $data['money'];
            } catch (Exception $e) {
                pdo_rollback();
                $this->error = $e->getMessage();
            }
        } else {
            $this->error = '领取失败';
        }
        return false;
    }



    /**
     * 今天领取的红包
     * @param $member_id
     * @param int $frequency
     * @return array
     */
    public function is_today_receive($member_id, $frequency = 1)
    {
        $yd_bag = prefix('bag');
        $start = strtotime(date('Y-m-d', time()));
        $end = time();

        $sql = "SELECT * FROM {$yd_bag} WHERE member_id = {$member_id} AND a_few={$frequency} AND created >= {$start} AND created <= {$end}";

        return p_fetch($sql);
    }


    /**
     * @param $member_id
     * @param int $time
     * @return array
     */
    public function day_info($member_id, $time = 0)
    {
        $time = $time ? : strtotime(date('Y-m-d', time()));

        return  p_get('today', array('member_id' => $member_id, 'created' => $time));
    }



    /**
     * 资金变动
     *
     * @param $member_id
     * @param $money
     * @param int $type
     * @return bool
     */
    public function change_money($member_id, $money, $type = 1)
    {
        if ($money <= 0) {
            return true;
        }

        $member = p_get('member', array('id' => $member_id));

        if (in_array($type, array(1, 3, 6))) {
            $after_money = $member['money'] + $money;
        } else {

            //if (bccomp($member['money'], $money) < 0) {
            if (intval($member['money'] * 100) < intval($money * 100)) {
                $this->error = '余额不足';
                return false;
            }
            $after_money = (intval($member['money'] * 100) - intval($money * 100)) / 100;
            //$after_money = bcsub($member['money'], $money, 2);//$member['money'] - $money;
        }

        $update = array('money' => $after_money);
        if (!p_update('member', $update, array('id' => $member_id))) {
            $this->error = '余额变动失败';
            return false;
        }

        $money_log = array(
            'member_id' => $member_id,
            'money' => $money,
            'type' => $type,
            'created' => time()
        );
        if (p_insert('money_log', $money_log)) {
            return true;
        }

        $this->error = '日志更新出错';
        return false;
    }


    /**
     * 页面商品
     * @param int $type
     * @return array
     */
    public function getGoodsList($type = 1, $member_id = 0)
    {
        $where = 'is_delete = 1 AND uniacid=' . UNIACID . ' AND (states = 3 OR ' . ($type == 1 ? 'states = 1' : 'states = 2') . ') ORDER BY sort DESC';
        $sql = 'SELECT * FROM ' .prefix('category') . ' WHERE ' . $where;
        if (!$category = p_fetchall($sql)) {
            return array();
        }
        foreach ($category as $k => $value) {
            $category[$k]['goods'] = p_getall('goods', array('category_id' => $value['id'], 'is_delete' => 1, 'status' => 1), array(), '', array('sort DESC'));
            if ($category[$k]['goods']) {
                foreach ($category[$k]['goods'] as $key => $val) {
                    $category[$k]['goods'][$key]['cover_image_url'] = getImage($val['cover_image']);
                    if ($type == 1 && $val['is_exhibition'] == 2) {
                        $count = p_getcolumn('order', array('member_id' => $member_id, 'goods_id' => $val['id']), 'COUNT(*)') ? : 0;
                        if ($count >= $val['allow_number']) {
                            unset($category[$k]['goods'][$key]);
                        }
                    }
                }
            }
        }

        return $category;
    }



    /**
     * 步数转换成活力币
     * @param $member_id
     * @param $step
     * @return bool
     */
    public function calculation_currency($step)
    {
        $step_currency = getConfig('step_currency');
        return round($step/$step_currency , 2);
    }


    /**
     * 更新自己步数
     * @param $member_id
     * @param $step_number
     * @return bool
     */
    public function update_currency($member_id, $step_number)
    {
        if ($step_number < 1) {
            return true;
        }
        $time = strtotime(date('Y-m-d', time()));
        $where = array(
            'member_id' => $member_id,
            'created' => $time
        );
        $row = p_get('today', $where);
        $effective_step_currency = getConfig('effective_step_currency');
        $conversion = $effective_step_currency / 100;
        //var_dump($row);exit;
        if ($row && ($row['step'] == $step_number || $step_number - $row['step'] < $conversion)) {
            return true;
        }
        $currency = 0;
        try {
            pdo_begin();
            if (empty($row)) {
                $update_upper = $step_number >= $effective_step_currency ? $effective_step_currency : $step_number;

                $currency = $this->calculation_currency($update_upper);

                $insert = array(
                    'member_id' => $member_id,
                    'step' => $step_number,
                    'currency' => $currency,
                    'effective_step' => $update_upper,
                    'created' => $time
                );
                if (!p_insert('today', $insert)) {
                    throw new Exception('更新步数失败');
                }
            } else {
                $update = array('step' => $step_number);
                $new_step = $step_number - $row['step'];
                if ($row['step'] >= $effective_step_currency) {
                    $update_upper = 0;
                } else if ($new_step + $row['step'] >= $effective_step_currency) {
                    $update_upper = $effective_step_currency - $row['step'];
                } else {
                    $update_upper = $new_step;
                }
                if ($update_upper > 0) {
                    $currency = $this->calculation_currency($update_upper);
                    $update['currency'] = $row['currency'] + $currency;
                    $update['effective_step'] = $row['effective_step'] + $update_upper;
                }

                if (!p_update('today', $update, array('id' => $row['id']))) {
                    throw new Exception('更新步数失败');
                }
            }
            if ($currency > 0) {
                $data = array(
                    'currency' => $currency,
                    'member_id' => $member_id,
                    'today' => $time,
                    'source' => 2,
                    'created' => time(),
                );
                if (!p_insert('currency', $data)) {
                    throw new Exception('更新步数失败');
                }
            }

            $this->pull_black($member_id);

            pdo_commit();
            return true;
        } catch (Exception $e){
            $this->error = $e->getMessage();
            pdo_rollback();
        }
        return false;
    }


    public function pull_black($member_id)
    {
        $member_continuous_day = getConfig('member_continuous_day', 0);
        if (!$member_continuous_day) {
            return true;
        }

        $time = strtotime(date('Y-m-d', time()));
        $where = array(
            'member_id' => $member_id,
            'created' => $time
        );
        if (!$today = p_get('today', $where)) {
            return true;
        }
        $effective_step_currency = getConfig('effective_step_currency', 20000);
        if ($today['step'] < $effective_step_currency) {
            return true;
        }
        if ($member_continuous_day > 1) {
            $where = array('member_id' => $member_id);
            for ($i = 1; $i < $member_continuous_day; $i++) {
                $where['created'] = $time - ($i * 86400);
                $res = p_get('today', $where);
                if (empty($res) || $res['step'] < $effective_step_currency) {
                    return true;
                }
            }
        }

        return p_update('member', array('status' => 2), array('id' => $member_id));
    }


    /**
     * 用户活力币记录
     * @param $member_id
     * @param $p
     * @param int $page_size
     * @return array
     */
    public function currency_log($member_id, $p, $page_size = 10)
    {
        $limit = ($p - 1) * $page_size;
        $log = p_getall('currency_log', array('member_id' => $member_id), array(),'', array('id desc'), "{$limit}, {$page_size}");

        if ($log) {
            foreach ($log as & $value) {
                if ($value['type'] == 4) {
                    $member = p_get('member', array('id' => $value['remarks']));
                    if ($member['nickname']) {
                        $value['remarks'] = '邀请奖励-' . $member['nickname'];
                    } else {
                        $value['remarks'] = '邀请奖励-游客' . substr($member['openid'], 0, 6);
                    }
                }
                $value['mold'] = in_array($value['type'], array(1, 2, 3, 4, 6, 7, 8)) ? 1 : 2;
                $value['createTime'] = date('Y.m.d', $value['created']);
            }
        }

        return $log;
    }


    /**
     * 用户资金记录
     * @param $member_id
     * @param $p
     * @param int $page_size
     * @return array
     */
    public function money_log($member_id, $p, $page_size = 10)
    {
        $limit = ($p - 1) * $page_size;
        $log = p_getall('money_log', array('member_id' => $member_id), array(),'', array('id desc'), "{$limit}, {$page_size}");

        if ($log) {
            $status = array(
                '1' => '充值',
                '2' => '邮费',
                '3' => '领取红包',
                '4' => '提现',
                '5' => '兑换商品',
                '6'  => '兑换红包商品'
            );
            foreach ($log as & $value) {
                $value['remarks'] = $status[$value['type']];
                $value['mold'] = in_array($value['type'], array(1, 3, 6)) ? 1 : 2;
                $value['createTime'] = date('Y.m.d', $value['created']);
            }
        }

        return $log;
    }




    /**
     * 提现
     * @param $id
     * @return bool
     */
    public function cash($id)
    {
        $info = p_get('withdrawals', array('id' => $id));
        $member = p_get('member', array('id' => $info['member_id']));

        $MerchPay = new MerchPay();
        $trade_no = date("YmdHis", time()) . $member['id'] . rand(100, 999);
        $res = $MerchPay->pay($member['openid'], $trade_no, $info['money'], ('来自'. getConfig('xcx_title') .'小程序提现'), '');//本机ip

        if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS') {

            return true;
        }
        if ($_GET['dd']) {
            var_dump($res);
        }
        $this->error = isset($res[0]) ? $res[0] : '提现失败';
        return false;
    }


    /**
     * 是否可以赠送步数
     * @param $member_id
     * @param $firend_id
     * @param $goods_id
     * @return bool
     */
    public function is_give($member_id, $firend_id, $goods_id)
    {
        if ($member_id == $firend_id) {
            $this->error = '自己不能赠送给自己';
            return false;
        }
        $give = p_fetch("SELECT * FROM " . prefix('goods_give') . " WHERE friend_id=:firend_id AND created>" . (time() - 3600), array(':firend_id' => $firend_id));

        if (p_get('goods_give', array('member_id' => $member_id, 'friend_id' => $firend_id, 'goods_id' => $goods_id))) {
            $this->error = '你已经帮他砍过';
            return false;
        }

        if (!empty($give)) {
            $this->error = '一个小时内只能赠送一次';
            return false;
        }

        $goods = p_get('goods', array('id' => $goods_id));
        if (empty($goods) || $goods['is_delete'] == 2 || $goods['status'] == 2) {
            $this->error = '商品不存在或者已经下架';
            return false;
        }

        if ($goods['exchange_type'] != 2 && $goods['exchange_type'] != 3) {
            $this->error = '该商品不能收集步数';
            return false;
        }

        if ($goods['allow_number'] > 0) {
            $count = p_getcolumn('order', array('member_id' => $member_id, 'goods_id' => $goods_id), 'COUNT(*)');
            if ($count >= $goods['allow_number']) {
                $this->error = '该用户不能再购买此商品';
                return false;
            }
        }

        return true;
    }

    /**
     * 生成支付订单
     * @param $member_id
     * @param $money
     * @return bool|string
     */
    public function unifiedOrder($member_id, $money)
    {
        $trade_no = date("YmdHis", time()) . $member_id . mt_rand(1000, 9999);
        $pay = array(
            'member_id' => $member_id,
            'trade_no' => $trade_no,
            'money' => $money,
            'crested' => time()
        );

        if (p_insert('pay_order', $pay)) {
            return $trade_no;
        }
        return false;
    }


    /**
     * 生成优惠券
     * @param $shop_id
     * @param $goods_id
     * @param $number
     * @return bool
     */
    public function create_voucher($shop_id, $goods_id, $number, $is_under_line = 1)
    {
        $data = array(
            'goods_id' => $goods_id,
            'shop_id' => $shop_id,
            'is_under_line' => $is_under_line,
            'created' => time()
        );
        for ($i = 0; $i < $number; $i++) {
            if (!p_insert('goods_fictitious', $data)) {
                continue;
            }
            $id = pdo_insertid();
            $no = $id . time();
            $content = str_pad($no, 18, '0', STR_PAD_LEFT);
            p_update('goods_fictitious', array('content' => $content), array('id' => $id));
        }
        return true;
    }


    /**
     * 今天步数是否能领取红包
     * @param $member_id
     * @return bool
     */
    public function is_receive_step($member_id)
    {
        $step_number_bag = getConfig('step_number_bag');
        $step = $this->day_step($member_id);
        return  $step && ($step['step_number'] + $step['step_friends']) > $step_number_bag;
    }

}