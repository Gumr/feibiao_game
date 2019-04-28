<?php

/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 * User: fanyk
 * Date: 2017/12/10
 * Time: 14:46.
 */
include "lib/Wxadoc.php";
include "lib/function.php";
include "model/step.mod.php";
include "lib/WxpayAPI_php_v3/WxpayAPI_php_v3.php";
define('LOCAL_IP',$_SERVER['SERVER_ADDR']);

class bh_stepModuleWxapp extends WeModuleWxapp {


    public $gpc;
    public $w;
    public $member;
    public $step;
    public $session_key;

    public function __construct() {

        global $_W;
        global $_GPC;
        $this->gpc = $_GPC;
        $this->w = $_W;
        $this->step = new step();

        define('UNIACID', $this->w['uniacid']);

        $noLogin = array('login', 'upload', 'image', 'open', 'share', 'crontab','questionList' ,'currency', 'activity', 'aldkey', 'wechat', 'random','vipgoods');
        if (isset($_GPC['debug'])) {
            error_reporting(E_ALL);
        }

        if (empty($_GPC['do']) ||in_array($_GPC['do'], $noLogin)) {
            return true;
        }

        $token = $this->get('token');
        $res = p_get('token', array('token' => $token));
        if (empty($res)) {
            json('unlogin', -1);
        }
        $member_id = $res['member_id'];
        if ($member_id >= 1) {
            $member = p_get('member', array('id' => $member_id));
            if ($member) {

                if ($member['status'] == 2) {
                    exit;
                    $blacklist = array('signinRemind', 'bag', 'bgRemind', 'updateStep', 'receive', 'taskComplete', 'shareGroup', 'withdrawals', 'exchange');
                    if (in_array($_GPC['do'], $blacklist)) {
                        json('由于你有违规操作,已经被拉黑,请与管理员联系', 0);
                    }
                }
                if ($member['is_fictitious'] == 2) {
                    json('');
                }

                $this->member = $member;
                $this->session_key = $res['session_key'];
                $form_id = $this->get('formid');
                if ($form_id && $form_id != 'the formId is a mock one') {
                    p_insert('form_id', array('member_id' => $member_id, 'form_id' => $form_id, 'created' => time()));
                }
                return true;
            }
        }
        json('unlogin', -1);
    }


    public function doPageMerchant()
    {
        include 'controller/merchant.php';
        new merchant();
    }


    public function doPageQuestionList()
    {
        define('IN_SYS', true); //入口常量定义
        $table = prefix('question');
        $where = "uniacid = {$this->w['uniacid']}";
        $list = pdo_fetchall('SELECT * FROM '.$table." WHERE {$where} ORDER BY sort DESC,id DESC ");
        $product_name = getConfig('xcx_title', '步赚');
        include $this->template('question_list');
    }
    /**
     * 了解活力币
     */
    public function doPageCurrency()
    {
        define('IN_SYS', true); //入口常量定义
        $currency = p_get('config', array('key' => 'realize_currency'));
        $currency_name = getConfig('currency_name', '活力币');
        include $this->template('currency');
    }
    /**
     * 活动说明
     */
    public function doPageActivity()
    {
        define('IN_SYS', true); //入口常量定义
        $activity = p_get('config', array('key' => 'activity'));
        include $this->template('activity');
    }


    public function doPageRandom()
    {
        define('IN_SYS', true); //入口常量定义

        if (!$this->step->getCurrentDomain('')) {
            json('非法请求', 0);
        }

        $pwd = $this->step->cash_password();
        $product_name = getConfig('xcx_title', '步赚');
        include $this->template('random');
    }


    /**
     * 首页
     */
    public function doPageIndex()
    {
        $time = strtotime(date('Y-m-d', time())) + 86400;
        $sigin = array(
            'days' => $this->step->signin($this->member['id']),
            'is_remind' => p_get('remind', array('member_id' => $this->member['id'], 'remind_day' => $time, 'type' => 2)) ? 1 : 0
        );
        $sigin['currency'] = getConfig($this->step->signin_days[$sigin['days']]['key']) ? : $this->step->signin_days[$sigin['days']]['default'];
        $upper = p_getall('jump', array('position' => 1), array(), '', array('sort desc'), 5);
        if (!empty($upper)) {
            foreach ($upper as & $value) {
                $value['icon'] = getImage($value['icon']);
                $value = ad($value);
            }
        }
        $home_middle = p_getall('jump', array('position' => 3));
        if ($home_middle && is_array($home_middle)) {
            foreach ($home_middle as $key => $val) {
                $home_middle[$key]['icon'] = getImage($val['icon']);
                $home_middle[$key] = ad($home_middle[$key]);
            }


        }
        $ad_pop = p_get('jump', array('position' => 8));
        if ($ad_pop) {
            $ad_pop['icon'] = getImage($ad_pop['icon']);
            $ad_pop = ad($ad_pop);
        }
        $advertisement = array(
            'upper' => $upper,
            'home_middle' => $home_middle,
            'ad_pop' => $ad_pop
        );
        $bag = array(
            'is_open' => getConfig('bag_switch'),
            'is_remind' => p_get('remind', array('member_id' => $this->member['id'], 'remind_day' => $time, 'type' => 1)) ? 1 : 0,
            'is_bag' => $this->step->is_today_receive($this->member['id']) ? 1 : 0,
            'bag_currency' => getConfig('bag_currency'),
            'number' => p_getcolumn('bag', array(), 'COUNT(*)') + getConfig('bag_initial_number', 0),
            'bag_invitations_number' => getConfig('bag_invitations_number'),
            'bag_total_amount' => getConfig('bag_total_amount'),
            'bag_remind_switch' => getConfig('bag_remind_switch')
        );
        $invitation = array(
            'invitation_currency_start' => getConfig('invitation_currency_start'),
            'invitation_currency_end' => getConfig('invitation_currency_end')
        );
        $goods = $this->step->getGoodsList(1, $this->member['id']);
        $share = array(
            'bag' => array(
                'share_text' => str_replace('{name}', $this->member['nickname'], getConfig('share_red_packet_text')),
                'share_image' => getConfig('share_red_packet_image'),
            ),
            'ordinary' => array(
                'share_text' => str_replace('{name}', $this->member['nickname'], getConfig('share_text')),
                'share_image' => getConfig('share_image'),
            )
        );
        $give_step = array();


        $goods_id = $this->get('goods_id', 0);
        $parent_id = $this->get('parent_id', 0);
        if ($goods_id > 0
            && $parent_id > 0
            && $this->step->is_give($parent_id, $this->member['id'], $goods_id)
        ) {
            $give_step['member'] = p_get('member', array('id' => $parent_id));
            $give_step['goods'] = p_get('goods', array('id' => $goods_id));
            $give_step['goods']['cover_image'] = getImage($give_step['goods']['cover_image']);
        }

        $author_show = getConfig('author_show');
        if ($author_show == 1 && empty($this->member['nickname']) && empty($this->member['head'])) {
            $author_show = 1;
        } else{
            $author_show = 2;
        }

        $home_background_image = getImage(getConfig('home_background_image'));
        $home_background_image = $home_background_image ?  : ($this->w['siteroot'] . 'addons/bh_step/template/image/wechat/indexbc3.png');

        $home_suspension_coin_img = getImage(getConfig('home_suspension_coin_img'));
        $home_suspension_coin_img = $home_suspension_coin_img ?  : ($this->w['siteroot'] . '/addons/bh_step/template/image/step2gift/coin.png');

        $home_my_coin_image = getImage(getConfig('home_my_coin_image'));
        $home_my_coin_image = $home_my_coin_image ? : ($this->w['siteroot'] . '/addons/bh_step/template/image/wechat/heardbc.png');

        $home_redpack_open_image = getImage(getConfig('home_redpack_open_image'));
        $home_redpack_open_image = $home_redpack_open_image ? : ($this->w['siteroot'] . '/addons/bh_step/template/image/daily-red-pack/open_daily_red_pack.gif');

        $home_redpack_send_image = getImage(getConfig('home_redpack_send_image'));
        $home_redpack_send_image = $home_redpack_send_image ?  : ($this->w['siteroot'] . '/addons/bh_step/template/image/daily-red-pack/send_daily_red_pack.gif');

        $home_invitation_coin = getImage(getConfig('home_invitation_coin'));
        $home_invitation_coin = $home_invitation_coin ? : ($this->w['siteroot'] . '/addons/bh_step/template/image/444.png');


        $home_red_pack_bg = getImage(getConfig('home_red_pack_bg'));
        $home_red_pack_bg = $home_red_pack_bg ? : ($this->w['siteroot'] . '/addons/bh_step/template/image/daily-red-pack/daily_red_pack_bg.png');


        $ui = array(
            'home_background_image' => $home_background_image, //首页背景图

            'home_suspension_coin_img' => $home_suspension_coin_img,//首页悬浮金币图片  84*84
            'home_suspension_coin_color' => getConfig('home_suspension_coin_color') ? : '#554545', //首页悬浮金币内文字颜色
            'home_suspension_coin_describe_color' => getConfig('home_suspension_coin_describe_color') ? : '#554545', //首页悬浮金币描述文字颜色

            'home_my_coin_image' => $home_my_coin_image, //首页我的活力币背景图 309 * 309
            'home_my_coin_color' => getConfig('home_my_coin_color') ? : '#fff', //首页我的活力币文字颜色

            'home_today_step_color' => getConfig('home_today_step_color') ? : '#666666', //首页今日步数文字颜色
            'home_today_step_num_color' => getConfig('home_today_step_num_color') ? : '#434343', //首页今日步数步数颜色

            'home_share_start_color' =>  getConfig('home_share_start_color') ? : '#26bcc5', //首页邀请好即得渐变初始值
            'home_share_end_color' =>  getConfig('home_share_end_color') ? : '#1dd49e', //首页邀请好友即得渐变结束值
            'home_share_color' =>  getConfig('home_share_color') ? : '#fff', //首页邀请好友即得文字颜色

            'home_sigin_color' => getConfig('home_sigin_color') ? : '#fff', //首页签到文字颜色
            'home_sigin_start_color' => getConfig('home_sigin_start_color') ? : '#26bcc5', //首页签到天数背景渐变初始值
            'home_sigin_end_color' =>  getConfig('home_sigin_end_color') ? : '#1dd49e', //首页签到天数背景渐变结束值

            'home_understand_coin_color' =>  getConfig('home_understand_coin_color') ? : '#000', //首页了解活力币文字颜色

            'home_redpack_open_image' => $home_redpack_open_image, //首页开红包背景图 712 * 202
            'home_redpack_send_image' => $home_redpack_send_image, //首页发红包背景图 712 * 202
            'home_redpack_color' => getConfig('home_redpack_color') ? : '#FFFFCB', //首页领红包文字颜色

            'home_invitation_coin' => $home_invitation_coin,  //首页邀请领活力币图标 98 * 98

            'home_red_pack_bg' => $home_red_pack_bg  //开第一个红包背景图


        );

        $authorization_first = getConfig('authorization_first', '授权微信个人信息');
        $authorization_two = getConfig('authorization_two', '为您提供全部功能');
        $home_bottom_font = getConfig('home_bottom_font', '每天上限分享至5个群，次日可重新分享');


        $xcx_title = getConfig('xcx_title');
        $audit_pattern = $this->audit_pattern();
        $member = $this->member;
        $today = $this->step->day_info($this->member['id']);
        $time = date('Ymd', time());

        //$ald_key = getConfig('ald_key');
        $currency_name = getConfig('currency_name', '活力币');

        $system_notice = [];
        $home_guide = [];
        if ($this->step->getCurrentDomain()) {
            $system_notice = getConfig('system_notice');
            $system_notice = $system_notice ? json_decode($system_notice) : [];
            $system_notice = $system_notice[0] ? $system_notice : [];

            $home_guide = p_getall('home_guide');
            if ($home_guide) {
                foreach ($home_guide as & $value) {
                    $value['icon'] = getImage($value['icon']);
                    $value['guide_image'] = $value['guide_image'] ? getImage($value['guide_image']) : '';
                }
            }
        }

        $home_bottom_share = getConfig('home_bottom_share');

        json(compact('home_bottom_share','home_guide', 'system_notice', 'customized', 'home_bottom_font', 'authorization_two', 'authorization_first', 'currency_name', 'home_background_image', 'ui', 'time', 'sigin', 'advertisement', 'bag', 'goods', 'member', 'today', 'xcx_title', 'invitation', 'share', 'give_step', 'audit_pattern', 'author_show'));
    }


    public function doPageAldkey()
    {
        $ald_key = getConfig('ald_key');
        json($ald_key);
    }


    public function doPageDecode()
    {
        $path = $this->get('path');
        $path = urldecode($path);
        json($path);
    }


    /**
     * 签到
     */
    public function doPageSigin()
    {
        $signin_days = $this->step->signin_days;
        foreach ($signin_days as & $val) {
            $val['value'] = getConfig($val['key']) ? : $val['default'];
        }

        $invitation = array(
            'start' => getConfig('invitation_currency_start'),
            'end' => getConfig('invitation_currency_end'),
        );

        $time = strtotime(date('Y-m-d', time())) + 86400;
        $days = $this->step->get_signin($this->member['id']);
        $sigin = array(
            'days' => $days,
            'is_remind' => p_get('remind', array('member_id' => $this->member['id'], 'remind_day' => $time, 'type' => 2)) ? 1 : 0,
            'currency' => getConfig($signin_days[$days]['key'])
        );
        $ad = array(
            'welfare' =>  p_getall('jump', array('position' => 5)),  //活动福利
            'popup' =>  p_getall('jump', array('position' => 6), array(), '', array('id DESC'), 1),  //
            'middle' =>  p_getall('jump', array('position' => 7), array(), '', array('id DESC'), 3),  //
        );
        foreach ($ad as $k => $value) {
            if ($value) {
                foreach ($value as $key => $item) {
                    $value[$key] = ad($value[$key]);
                    $value[$key]['icon'] = getImage($item['icon']);
                }
                $ad[$k] = $value;
            }
        }
        $type = $this->get('type', 1);
        $time = date('Y-m-d');

        $sigin_remind_switch = getConfig('sigin_remind_switch');
        $currency_name = getConfig('currency_name', '活力币');
        $audit_pattern = $this->audit_pattern();

        json(compact('audit_pattern','currency_name', 'invitation', 'signin_days', 'sigin', 'ad', 'type', 'time', 'sigin_remind_switch'));
    }


    /**
     * 步数商城
     */
    public function doPageShopping()
    {
        $shop_top_image = getImage(getConfig('shop_top_image'));
        $shop_top_image = $shop_top_image ? : ($this->w['siteroot'] . '/addons/bh_step/template/image/wechat/goodslist/banner1.jpeg');

        $advertisement = p_get('jump', array('position' => 4));
        if (!empty($advertisement)) {
            $advertisement = ad($advertisement);
            $advertisement['icon'] = getImage($advertisement['icon']);
        }
        $goods_top = array();
        $goods = $this->step->getGoodsList(2);
        if ($goods) {
            $goods_top = $goods[0];
            unset($goods[0]);
        }
        $currency_name = getConfig('currency_name', '活力币');
        $xcx_title = getConfig('xcx_title');

        json(compact('currency_name', 'advertisement', 'goods_top', 'goods', 'xcx_title', 'shop_top_image'));
    }


    /**
     * 分类
     */
    public function doPageCategory()
    {
        $id = $this->get('id');
        $category = p_get('category', array('id' => $id, 'is_delete' => 1));
        if (empty($category)) {
            json('该分类不存在', 0);
        }
        $category['goods'] = p_getall('goods', array('category_id' => $id, 'is_delete' => 1, 'status' => 1), array(), '', array('sort DESC'));
        if ($category['goods']) {
            foreach ($category['goods'] as & $value) {
                $value['cover_image_url'] = getImage($value['cover_image']);
            }
        }
        json($category);
    }


    /**
     * 新签到提醒
     */
    public function doPageNsRemind()
    {
        $time = strtotime(date('Y-m-d', time())) + 86400;
        $type = $this->get('type', 1);

        $time = strtotime(date('Y-m-d', time())) + 86400;
        if ($type == 1) {
            p_delete('remind', array('member_id' => $this->member['id'], 'remind_day' => $time, 'type' => 2));
            json('取消成功');
        }

        $this->doPageSigninRemind();
    }



    /**
     * 设置签到提醒
     */
    public function doPageSigninRemind()
    {
        $time = strtotime(date('Y-m-d', time())) + 86400;
        //$t = $time + $this->get('time') * 3600;
        //p_delete('remind', ['member_id' => $this->member['id'], 'remind_day' => $time, 'type' => 2]);
        if (p_get('remind', array('member_id' => $this->member['id'], 'remind_day' => $time, 'type' => 2))) {
            json('设置成功');
        }

        $data = array(
            'member_id' => $this->member['id'],
            //'remind_time' => $t,
            'remind_day' => $time,
            'type' => 2,
            'created' => time()
        );
        if (p_insert('remind', $data)) {
            json('设置成功');
        }
        json('设置失败', 0);
    }


    /**
     * 个人中心
     */
    public function doPageMy()
    {
        $jump = p_getall('jump', array('position' => 2));
        if ($jump) {
            foreach ($jump as & $value) {
                $value = ad($value);
                $value['icon'] = getImage($value['icon']);
            }
        }
        $member_id = $this->member['id'];
        $sql = "SELECT COUNT(*) FROM " . prefix('member') . " WHERE parent_id={$member_id} AND (nickname !='' OR head != '')";

        $step_log_banner = getImage(getConfig('step_log_banner'));
        $step_log_banner = $step_log_banner ?  : ($this->w['siteroot'] . 'addons/bh_step/template/image/yundong.png');


        $contantus_pop = getConfig('contantus_pop', 1);
        $my_center_group = getConfig('my_center_group');

        $data = array(
            'currency' => $this->member['currency'],
            'nickname' => $this->member['nickname'],
            'head' => $this->member['head'],
            'money' => $this->member['money'],
            'signin_days' => $this->step->get_signin($this->member['id']),
            //'friends_num' => p_getcolumn('member', array('parent_id' => $this->member['id']), 'COUNT(*)') ? : 0,
            'friends_num' => p_fetchcolumn($sql) ? : 0,
            'jump' => $jump,
            'bag_switch' => getConfig('bag_switch'),
            'shop_id' => $this->member['shop_id'],
            'open_under_line' => getConfig('open_under_line'),
            'currency_name' => getConfig('currency_name', '活力币'),
            'step_log_banner' => $step_log_banner,
            'contantus_pop' => $contantus_pop,
            'my_center_group' => $my_center_group
        );

        json($data);
    }


    /**
     * 领取红包
     */
    public function doPageBag()
    {
        $frequency = $this->get('frequency', 1);
        $frequency = ($frequency > 5 || $frequency < 1) ? 1 : $frequency;

        if ($res = $this->step->receive($this->member['id'], $frequency)) {
            json($res);
        }

        if (version_compare($this->get('version'), '2.2.7') >= 0) {
            json($this->step->error, 2);
        }
        json($this->step->error, 0);
    }


    /**
     * 解锁红包
     */
    public function doPageShareBag()
    {
        $first = $this->step->is_today_receive($this->member['id']);
        if (empty($first)) {
            $this->doPageBag();
        }
        $keys = array(
            2 => 'bag_one_cooling',
            3 => 'bag_two_cooling',
            4 => 'bag_three_cooling',
            5 => 'bag_four_cooling'
        );
        $data = array();
        foreach ($keys as $key => $value) {
            $type = $this->step->is_receive_bag($this->member['id'], $key);
            $money = 0;
            if ($type == 1) {
                $bag = $this->step->is_today_receive($this->member['id'], $key);
                $money = $bag['money'];
            }
            $cooling = $type == 2 ? ceil((($first['created'] + getConfig($value) * 3600) - time()) / 3600) : 0;
            $data[] = array(
                'type' => $type,
                'cooling' => $cooling,
                'money' => $money
            );
        }
        json($data);
    }


    /**
     * 设置红包提醒
     */
    public function doPageBgRemind()
    {
        $type = $this->get('type');
        $time = strtotime(date('Y-m-d', time())) + 86400;
        p_delete('remind', array('member_id' => $this->member['id'], 'remind_day' => $time, 'type' => 1));
        if ($type == 1) {
            json('取消提醒成功');
        }
        $data = array(
            'member_id' => $this->member['id'],
            'remind_day' => $time,
            'created' => time()
        );
        if (p_insert('remind', $data)) {
            json('设置成功');
        }
        json('设置失败', 0);
    }


    /**
     * 更新步数
     */
    public function doPageUpdateStep()
    {
        $encryptedData = $this->get('encryptedData');
        $iv = $this->get('iv');
        include 'lib/WXBizDataCrypt.php';
        $wxbd = new WXBizDataCrypt($this->w['uniaccount']['key'], $this->session_key);
        $res = $wxbd->decryptData($encryptedData, $iv, $data);
        if ($res != ErrorCode::$OK) {
            json('unlogin', -1);
            //json('步数更新失败', 0);
        }
        //file_put_contents("test2.txt", date('Y-m-d H:i:s').$data.PHP_EOL, FILE_APPEND);
        $data = json_decode($data, true);

        $time = strtotime(date('Y-m-d', time()));
        $step = end($data['stepInfoList']);
        if ($step['timestamp'] == $time) {
            $step_number = $step['step'];
        } else {
            $step_number = 0;
        }
        if ($this->step->update_currency($this->member['id'], $step_number)) {
            $this->upvipUser(); //更新用户vip状态
            json('更新成功');
        }

        json($this->step->error, 0);
    }


    /**
     * 首页悬浮
     */
    public function doPageSuspension()
    {
        $data = array('author' => array('is_author' => 1));
        $limit = 3;

        if (empty($this->member['nickname']) && empty($this->member['head'])) {
            $limit = 2;
            $data['author']['is_author'] = 0;
            $data['author']['currency'] = getConfig('author_currency');
        }

        $time = strtotime(date('Y-m-d', time()));
        $data['currency'] = p_getall('currency', array('member_id' => $this->member['id'], 'status' => 1, 'today' => $time), array(), '', array('source desc'), $limit);

        if ($data['currency']) {
            $msgs = array(
                1 => '完成任务',
                2 => '步数转化',
                3 => '签到奖励',
                6 => '首次授权',
                8 => '分享到群',
            );
            foreach ($data['currency'] as & $value) {
                $value['msg'] = $msgs[$value['source']];
            }
            $limit = $limit - count($data['currency']);
        }
        if ($limit > 0) {
            $task = p_get('task', array('is_home' => 2));
            if ($task) {
                if (!p_get('task_member', array('member_id' => $this->member['id'], 'task_id' => $task['id']))) {
                    $task['icon_url'] = getImage($task['icon']);
                    $data['task'] = $task;
                }
            }
        }
        $data['my_currency'] = $this->member['currency'];
        $data['toady'] = $this->step->day_info($this->member['id']);

        json($data);
    }


    /**
     * 领取活力币
     */
    public function doPageReceive()
    {
        $id = $this->get('id');
        $time = strtotime(date('Y-m-d', time()));
        $currency = p_get('currency', array('id' => $id));
        if (empty($currency)
            || $currency['member_id'] != $this->member['id']
            || $currency['status'] != 1
            //|| ($currency['today'] != $time && $currency['source'] != 2)
            || ($currency['today'] != $time)
        ) {
            if (version_compare($this->get('version'), '2.3.1') >= 0) {
                json('你无法领取', 2);
            }
            json('你无法领取', 0);
        }
        try {
            pdo_begin();
            if (!p_update('currency', array('status' => 2), array('id' => $id, 'status' => 1))) {
                throw new Exception('领取失败');
            }
            if (!$this->step->change_currency($this->member['id'], $currency['currency'], $currency['source'])) {
                throw new Exception($this->step->error);
            }
            pdo_commit();
            json($this->member['currency'] + $currency['currency']);
        } catch (Exception $e) {
            pdo_rollback();
            if (version_compare($this->get('version'), '2.3.1') >= 0) {
                json($e->getMessage(), 2);
            }
            json($e->getMessage(), 0);
        }
    }


    /**
     * 任务页面
     */
    public function doPageTask()
    {
        $currency = $this->member['currency'];
        $invitation = array(
            'start' => getConfig('invitation_currency_start'),
            'end' => getConfig('invitation_currency_end')
        );
        $author = array(
            'is_author' => (empty($this->member['nickname']) && empty($this->member['head'])) ? 1 : 0,
            'currency' => getConfig('author_currency')
        );
        $follow = array(
            'is_follow' => p_get('currency_log', array('member_id' => $this->member['id'], 'type' => 7)) ? 1 : 0,
            'currency' => getConfig('follow_currency')
        );
        $task = p_getall('task', array('is_home' => 1), array(), '', array('sort desc'));
        if ($task) {
            foreach ($task as & $value) {
                $value['is_complete'] = p_get('task_member', array('member_id' => $this->member['id'], 'task_id' => $value['id'])) ? 1 : 0;
                $value['icon'] = getImage($value['icon']);
            }
        }

        $member_id = $this->member['id'];
        $currency_name = getConfig('currency_name', '活力币');

        json(compact('currency_name', 'currency', 'invitation', 'author', 'follow', 'task', 'member_id'));
    }


    /**
     * 完成任务
     */
    public function doPageTaskComplete()
    {
        $task_id = $this->get('task_id');
        $task = p_get('task', array('id' => $task_id));
        if (empty($task)) {
            json('任务不存在', 0);
        }
        $res = p_get('task_member', array('member_id' => $this->member['id'], 'task_id' => $task_id));
        if ($res) {
            json($this->member['currency']);
        }
        $from = $this->get('form', 2);
        try {
            pdo_begin();
            $data = array(
                'member_id' => $this->member['id'],
                'task_id' => $task_id,
                'created' => time()
            );
            if (!p_insert('task_member', $data)) {
                throw new Exception('领取失败');
            }
            if ($from == 1) {
                if (!$this->step->change_currency($this->member['id'], $task['currency'], 1)) {
                    throw new Exception($this->step->error);
                }
            } else {
                $data = array(
                    'currency' => $task['currency'],
                    'member_id' => $this->member['id'],
                    'source' => 1,
                    'today' => strtotime(date('Y-m-d', time())),
                    'created' => time(),
                );
                if (!p_insert('currency', $data)) {
                    throw new Exception('领取失败');
                }
            }
            pdo_commit();
            $member = p_get('member', array('id' => $this->member['id']));
            json($member['currency']);
        }catch (Exception $e) {
            pdo_rollback();
            json($e->getMessage());
        }
    }


    /**
     * 登录
     */
    public function doPageLogin()
    {
        $code = $this->get('code');
        if (empty($code)) {
            json('参数错误', 0);
        }

        $option = array(
            'appid' => $this->w['uniaccount']['key'],
            'secret' => $this->w['uniaccount']['secret'],
        );

        $wxObj = new Wxadoc($option);
        if (false === $res = $wxObj->jscode2session($code)) {
            json($wxObj->getError(), 0);
        }
        $member = p_get('member', array('openid' => $res['openid']));
        if (empty($member)) {
            $data = array(
                'openid' => $res['openid'],
                'add_time' => time()
            );
            if ($this->get('parent_id')) {
                $data['parent_id'] = $this->get('parent_id');
                $data['parent_type'] = $this->get('share_tpye', 1);
                $data['goods_id'] = $this->get('goods_id', 0);
            }
            p_insert('member', $data);
            $member['id'] = pdo_insertid();
            /*if (!empty($data['parent_id'])) {
                //1:普通分享 2:红包 3:步数 4:活力币
                $share_tpye = $this->get('share_tpye', 1);
                $goods_id = $this->get('goods_id');

                $this->step->invitation_currency($data['parent_id'], $member['id']);

                if (!empty($goods_id) && $share_tpye == 4) {
                    $this->step->goods_share($data['parent_id'], $member['id'], $goods_id);
                }
            }*/
        }

        $token = md5($member['id'] . time() . PREFIX);

        $insert = array(
            'token' => $token,
            'session_key' => $res['session_key'],
            'member_id' => $member['id'],
            'created' => time()
        );

        p_delete('token', array('member_id' => $member['id']));
        p_insert('token', $insert);

        $data = array(
            'token' => $token,
            'is_register' => ($member['head'] || $member['nickname']) ? 1 : 2
        );

        json($data);
    }


    /**
     * 注册
     */
    public function doPageRegister()
    {
        $iv = $this->get('iv');
        $encryptedData = $this->get('encryptedData');


        include 'lib/WXBizDataCrypt.php';
        $wxbd = new WXBizDataCrypt($this->w['uniaccount']['key'], $this->session_key);

        $res = $wxbd->decryptData($encryptedData, $iv, $data);
        if ($res != ErrorCode::$OK) {
            json('更新失败', -1);
        }
		
		//file_put_contents("test2.txt", date('Y-m-d H:i:s').$data.PHP_EOL, FILE_APPEND);

        $data = json_decode($data, true);
        $member_id = $this->member['id'];

        if (empty($this->member['nickname']) && empty($this->member['head'])) {

            if (!p_get('currency_log', array('member_id' => $this->member['id'], 'type' => 6))) {
                $this->step->change_currency($this->member['id'], getConfig('author_currency'), 6);
            }


            if (!empty($this->member['parent_id'])) {
                //1:普通分享 2:红包 3:步数 4:活力币

                $this->step->invitation_currency($this->member['parent_id'], $this->member['id']);

                if ($this->member['goods_id'] > 0 && $this->member['parent_type'] == 4) {
                    $this->step->goods_share($this->member['parent_id'], $this->member['id'], $this->member['goods_id']);
                }
            }
        }
        if (follow_cash() && !empty($data['unionId'])) {
            if (!p_get('wechat', array('unionid' => $data['unionId']))) {
                $wechat = array(
                    'member_id' => $this->member['id'],
                    'unionid' => $data['unionId'],
                    'created' => time()
                );
                p_insert('wechat', $wechat);
            }
        }

        $update = array(
            'nickname' => $data['nickName'],
			'unionid' => $data['unionId'],
            'head' => $data['avatarUrl']
        );
        p_update('member', $update, array('id' => $member_id));
        json('ok');
        //json(p_get('member', ['id' => $member_id]));
    }


    /**
     * 分享到群
     */
    public function doPageShareGroup()
    {   exit;
        $encryptedData = $this->get('encryptedData');
        $iv = $this->get('iv');
        include 'lib/WXBizDataCrypt.php';
        $wxbd = new WXBizDataCrypt($this->w['uniaccount']['key'], $this->session_key);
        $res = $wxbd->decryptData($encryptedData, $iv, $data);
        if ($res != ErrorCode::$OK) {
            json('unlogin', -1);
        }
        $data = json_decode($data, true);
        $time = strtotime(date('Y-m-d', time()));

        $open_gId_count = p_getcolumn('share_group', array('member_id' => $this->member['id'], 'today' => $time, 'openGId' => $data['openGId']), 'COUNT(*)');
        if ($open_gId_count) {
            json($data['openGId']);
        }

        $count = p_getcolumn('share_group', array('member_id' => $this->member['id'], 'today' => $time), 'COUNT(*)');
        if ($count >= 5) {
            json($data['openGId']);
        }
        $insert = array(
            'member_id' => $this->member['id'],
            'openGId' => $data['openGId'],
            'today' => $time,
            'created' => time()
        );
        pdo_begin();
        try {
            if (!p_insert('share_group', $insert)) {
                throw new Exception('更新失败');
            }
            $share_group_currency = getConfig('share_group_currency');
            $data = array(
                'currency' => $share_group_currency,
                'member_id' => $this->member['id'],
                'today' => $time,
                'source' => 8,
                'created' => time()
            );

            if (!p_insert('currency', $data)) {
                throw new Exception('更新失败');
            }
            pdo_commit();
            json($insert['openGId']);
        } catch (Exception $e) {
            pdo_rollback();
            json($e->getMessage(), 0);
        }

    }



    /**
     * 活力币记录
     */
    public function doPageCurrencyLog()
    {
        $currency = $this->member['currency'];
        $member_id = $this->member['id'];
        //$today = $this->step->day_info($this->member['id']);
        //$today_currency = $today['currency'] ?  : 0;

        $p = $this->get('p', 1);
        $data = $this->step->currency_log($this->member['id'], $p);
		//file_put_contents('2.txt', 'res' . var_export($data, true) . "/r/n", FILE_APPEND);

        $time = strtotime(date('Y-m-d', time()));

        $today_currency = p_fetchcolumn("SELECT SUM(number) FROM " . prefix('currency_log') . " WHERE member_id={$member_id} AND type IN(1,2,3,4,6,7,8,10,11) AND created>={$time}") ? : 0;
        //var_dump($time);
        //var_dump("SELECT SUM(number) FROM " . prefix('currency_log') . " WHERE member_id={$member_id} AND type IN(1,2,3,4,6,7,8,10) AND created>={$time}");
        $currency_name = getConfig('currency_name', '活力币');
        json(compact('currency_name', 'currency', 'today_currency', 'data', 'member_id'));
    }


    /**
     * 资金记录
     */
    public function doPageMoneyLog()
    {
        $p = $this->get('p', 1);
        $data = $this->step->money_log($this->member['id'], $p);
        json($data);
    }


    /**
     * 提现页面
     */
    public function doPageCash()
    {
        $first_presentation = getConfig('first_presentation');
        $follow_up_presentation = getConfig('follow_up_presentation');

        //$cash_follow = getConfig('cash_follow');

        $cash_explain = getConfig('cash_explain');
        $cash_explain = $cash_explain ? json_decode($cash_explain, true) : '';



        $follow_cash = array('type' => false);
        if (getConfig('follow_cash_switch') == 2 && follow_cash()) {
            $follow_cash['type'] = true;
            $follow_cash['is_pwd'] = false;
            $follow_cash_verification = getConfig('follow_cash_verification');
            if ($follow_cash_verification == 1) {
                $todays = strtotime(date('Y-m-d', time()));
                $cash_pwd = p_get('cash_pwd', array('member_id' => $this->member['id'], 'days' => $todays));
                if ($cash_pwd) {
                    $follow_cash['is_pwd'] = true;
                }
            }
        }

        $follow_cash_image = getImage(getConfig('follow_cash_image'));
        $follow_cash_image = $follow_cash_image ? : ($this->w['siteroot'] . '/addons/bh_step/template/image/762075235142305580.png');

        $data = array(
            'first_presentation' => $first_presentation,
            'follow_up_presentation' => $follow_up_presentation,
            'moeny' => $this->member['money'],
            'member_id' => $this->member['id'],
            'is_follow' => 1,
            'bag_daily_upper' => getConfig('bag_daily_upper'),
            'least_money' => $this->member['cash'] ? $follow_up_presentation : $first_presentation,
            'cash_explain' => $cash_explain,
            'follow_cash' => $follow_cash,
            'follow_cash_image' => $follow_cash_image,
            'authorization_first' => getConfig('authorization_first', '授权微信个人信息'),
            'authorization_two' => getConfig('authorization_two', '为您提供全部功能')
        );
        json($data);
    }


    /**
     * 提现
     */
    public function doPageWithdrawals()
    {
        $money = $this->get('money', 0);

        if ($this->member['cash']) {
            $follow_up_presentation = getConfig('follow_up_presentation');
            if ($money < $follow_up_presentation) {
                json('非首笔提现金额需满' . $follow_up_presentation, 2);
            }
        } else {
            $first_presentation = getConfig('first_presentation');
            if ($money < $first_presentation) {
                json('首笔提现金额需满' . $first_presentation, 2);
            }
        }


        $bag_daily_upper = getConfig('bag_daily_upper');
        $time = strtotime(date('Y-m-d', time()));
        $sql = "SELECT SUM(money) FROM" . prefix('withdrawals') . "WHERE member_id={$this->member['id']} AND created>={$time}";
        if (pdo_fetchcolumn($sql) + $money > $bag_daily_upper) {
            json('每天提现上限为' . $bag_daily_upper . '元', 2);
        }

        if (empty($this->member['head']) && empty($this->member['nickname'])) {
            //json('', 3);
        }

        if (getConfig('follow_cash_switch') == 2 && follow_cash()) {
            $is_pwd = false;
            $follow_cash_verification = getConfig('follow_cash_verification');
            $todays = strtotime(date('Y-m-d', time()));
            if ($follow_cash_verification == 1) {
                $cash_pwd = p_get('cash_pwd', array('member_id' => $this->member['id'], 'days' => $todays));
                if ($cash_pwd) {
                    $is_pwd = true;
                }
            }
            if (!$is_pwd) {
                $pwd = $this->get('pwd');
                if (empty($pwd)) {
                    json('请填写提现口令', 2);
                }
                if ($pwd != $this->step->cash_password()) {
                    json('您提现口令已失效，请获取最新口令', 2);
                }
                $insert = array('member_id' => $this->member['id'], 'days' => $todays, 'pwd' => $pwd, 'created' => time());
                if (!p_insert('cash_pwd', $insert)) {
                    json('提现失败,稍后再试', 2);
                }
            }
        }


        $cash_reviewed = getConfig('cash_reviewed');
        $status = $money >= $cash_reviewed ? 1 : 2;

        pdo_begin();
        try {
            $insert = array(
                'member_id' => $this->member['id'],
                'money' => $money,
                'status' => $status,
                'created' => time()
            );
            $this->setPayment();

            if (!$this->step->change_money($this->member['id'], $money, 4)) {
                throw new Exception($this->step->error);
            }
            if (!p_insert('withdrawals', $insert)) {
                throw new Exception('提现失败');
            }
            $insert_id = pdo_insertid();
            if(!p_update('member', array('cash' => $this->member['cash'] + 1), array('id' => $this->member['id']))) {
                throw new Exception('提现失败');
            }
            if ($status == 2) {
                if (!$this->step->cash($insert_id)) {
                    throw new Exception($this->step->error);
                }
            }

            pdo_commit();

        } catch (Exception $e) {
            pdo_rollback();
            json($e->getMessage(), 2);
        }

        json(array('money' => $this->member['money'] - $money), 1);
    }


    /**
     * 设置地址
     */
    public function doPageSetAddress()
    {
        $data = array(
            'name' =>  $this->get('name'),
            'phone' =>  $this->get('phone'),
            'address' =>  $this->get('address'),
        );

        if ($address = p_get('address', array('member_id' => $this->member['id']))) {
            p_update('address', $data, array('address_id' => $address['address_id']));
        } else {
            $data['member_id'] = $this->member['id'];
            $data['addtime'] = time();
            p_insert('address', $data);
        }
        json('设置成功');
    }


    /**
     * 获取用户地址
     */
    public function doPageGetAddress()
    {
        json(p_get('address', array('member_id' => $this->member['id'])));
    }


    /**
     * 赠送步数
     */
    public function doPageGive()
    {
        $goods_id = $this->get('goods_id');
        $member_id = $this->get('parent_id');

        if (!$this->step->is_give($member_id, $this->member['id'], $goods_id)) {
            json($this->step->error, 0);
        }
        $cut_step_max = getConfig('cut_step_max');

        $toady = $this->step->day_info($this->member['id']);
        if (empty($toady) || $toady['effective_step'] == 0 || $toady['effective_step'] - $toady['use_step'] < 1) {
            json('你没有多余的有效步数', 0);
        }
        $step = $toady['effective_step'] - $toady['use_step'];

        $give_step = $step > $cut_step_max ? $cut_step_max : $step;


        pdo_begin();
        try {
            $data = array(
                'goods_id' => $goods_id,
                'member_id' => $member_id,
                'friend_id' => $this->member['id'],
                'step' => $give_step,
                'created' => time()
            );
            if (!p_insert('goods_give', $data)) {
                throw new Exception('赠送失败');
            }

            if (!p_update('today', array('use_step' => $toady['use_step'] + $give_step), array('id' => $toady['id']))) {
                throw new Exception('赠送失败');
            }
            pdo_commit();
            json($give_step);
        } catch (Exception $e) {
            pdo_rollback();
            json($e->getMessage(), 0);
        }
    }


    /**
     * 邀请的好友
     */
    public function doPageFriends()
    {
        $p = $this->get('p', 1);
        $page_size = 20;
        $limit = ($p - 1) * $page_size;
        //$firends = p_getall('member', array('parent_id' => $this->member['id']), array(),'', array('id desc'), "{$limit}, {$page_size}");

        $member_id = $this->member['id'];
        $sql = "SELECT * FROM " . prefix('member') . " WHERE parent_id={$member_id} AND (nickname!='' or head!='') ORDER BY id DESC LIMIT {$limit}, {$page_size}";

        $firends = p_fetchall($sql);

        if ($firends) {
            foreach ($firends as & $value) {
                $value['add_time'] = date('Y-m-d', $value['add_time']);
            }
        }

        $data = array(
            'firends' => $firends
        );
        json($data);
    }

    /**
     * VIP用户状态和天数
     * @param $uid
     * @return vip_status 状态 endtime 到期时间戳
     */
    public function doPageVIPUser()
    {
        //$uid = 29030;
        $uid = $this->member['id'];
        if (!$uid) {
            $res = array('vip_status'=>0,'endtime'=>0,'msg'=>'未登陆');
            return json($res);
        }
        $sql="select o.status,o.id as order_id, o.created, m.vip_days,m.id from " . tablename("bh_st_order") . " o"  . " left join " . tablename("bh_st_goods") . " m on o.goods_id = m.id  "." where o.type = 5  and  o.member_id = :uid ORDER BY o.id asc ";
        $scenic = pdo_fetchall($sql,array(':uid' => $uid));
        $lastTime = 0;
        if ($scenic && is_array($scenic)) {
            foreach ($scenic as $key => $val) {
                $endtime = $val['created']+$val['vip_days']*24*60*60;
                if ($endtime > time()) {
                    $lastTime = $val['created'];
                    break;
                }
            }

            $sql="select SUM(m.vip_days) as totalday from " . tablename("bh_st_order") . " o"  . " left join " . tablename("bh_st_goods") . " m on o.goods_id = m.id  "." where o.type = 5  and o.created >= :created  and  o.member_id = :uid  ";
            $scenicdata = pdo_fetch($sql,array(':created' => $lastTime,':uid' => $uid));
           //pdo_debug();
            $endtime = $lastTime +$scenicdata['totalday']*24*60*60;
            $res = array('vip_status'=>1,'endtime'=>$endtime,'totalday'=>$scenicdata['totalday']);

        }
        else
            $res = array('vip_status'=>0,'endtime'=>0);
        if ($res["vip_status"] == 1) {
            $arr = array('isvip'=>1);
        }
        else
            $arr = array('isvip'=>0);
        pdo_update("bh_st_member", $arr, array("id" => $this->member['id']));
        return json($res);
    }

    /**
     * 同步更新 vip
     */

    private function upvipUser()
    {
        //$uid = 29030;
        $uid = $this->member['id'];

        $sql="select o.status,o.id as order_id, o.created, m.vip_days,m.id from " . tablename("bh_st_order") . " o"  . " left join " . tablename("bh_st_goods") . " m on o.goods_id = m.id  "." where o.type = 5  and  o.member_id = :uid ORDER BY o.id asc ";
        $scenic = pdo_fetchall($sql,array(':uid' => $uid));
        $lastTime = 0;
        if ($scenic && is_array($scenic)) {
            foreach ($scenic as $key => $val) {
                $endtime = $val['created']+$val['vip_days']*24*60*60;
                if ($endtime > time()) {
                    $lastTime = $val['created'];
                    break;
                }
            }

            $sql="select SUM(m.vip_days) as totalday from " . tablename("bh_st_order") . " o"  . " left join " . tablename("bh_st_goods") . " m on o.goods_id = m.id  "." where o.type = 5  and o.created >= :created  and  o.member_id = :uid  ";
            $scenicdata = pdo_fetch($sql,array(':created' => $lastTime,':uid' => $uid));
            //pdo_debug();
            $endtime = $lastTime +$scenicdata['totalday']*24*60*60;
            $res = array('vip_status'=>1,'endtime'=>$endtime,'totalday'=>$scenicdata['totalday']);

        }
        else
            $res = array('vip_status'=>0,'endtime'=>0);
        if ($res["vip_status"] == 1) {
            $arr = array('isvip'=>1);
        }
        else
            $arr = array('isvip'=>0);
        pdo_update("bh_st_member", $arr, array("id" => $this->member['id']));
        //return json($res);
    }

    /**
     * 相差天数
     */
        private function  time2string($second){
        $day = floor($second/(3600*24));
        $second = $second%(3600*24);//除去整天之后剩余的时间
        $hour = floor($second/3600);
        $second = $second%3600;//除去整小时之后剩余的时间
        $minute = floor($second/60);
        $second = $second%60;//除去整分钟之后剩余的时间
            $result = array(
                'day'=>$day,
                'hour'=>$hour,
                'minute'=>$minute,
                'second'=>$second,
            );
        //返回字符串
      //  return $day.'天'.$hour.'小时'.$minute.'分'.$second.'秒';
        return $result;
        }

    /**
     * 加成包商品
     * @param  $type  类别
     */
    public function doPageVIPGoods()
    {
        $days = $this->get('type');
        //$days = 3;
        $goods = p_get('goods', array('type' => 5,'vip_days'=>$days));
        //pdo_debug();
        if (empty($goods) || $goods['status'] == 2 || $goods['is_delete'] == 2) {
            json('商品不存在或已下架', 0);
        }
        $res = array('status'=>1,'data'=> $goods['id'] );
        return json($res);
    }


    /**
     * 商品详情
     */
    public function doPageGoods()
    {
        $id = $this->get('id');

        $goods = p_get('goods', array('id' => $id));
        if (empty($goods) || $goods['status'] == 2 || $goods['is_delete'] == 2) {
            json('商品不存在或已下架', 0);
        }

        $time = strtotime(date('Y-m-d', time()));

        $sale = p_getcolumn('order', array('goods_id' => $id), 'COUNT(*)');
        $toady_sale = p_fetchcolumn("SELECT COUNT(*) FROM " .prefix('order') . " WHERE goods_id=:goods_id AND created>={$time} AND uniacid=" . UNIACID, array(':goods_id' => $id));

        if ($goods['image']) {
            $goods['image'] = json_decode($goods['image'], true);

            $image = array();
            foreach ($goods['image'] as $value) {
                $image[] = getImage($value);
            }
            $goods['image'] = $image;
        }

        //计算商品库存
        if ($goods['type'] == 2) { //虚拟
            $inventory = p_getcolumn('goods_fictitious', array('goods_id' => $id, 'status' => 1), 'COUNT(*)') ? : 0;
            if ($goods['inventory_type'] == 2) {
                $goods['inventory'] = ($inventory - $sale) ? : 0;
            } else {
                $i = $goods['inventory'] - $toady_sale;
                $goods['inventory'] = ($i >= $inventory ? $inventory : $i) ? : 0;
            }
        } else {
            if ($goods['inventory_type'] == 2) {
                $goods['inventory'] = ($goods['inventory'] - $sale) > 0 ? ($goods['inventory'] - $sale) : 0;
            } else {
                $goods['inventory'] = ($goods['inventory'] - $toady_sale) > 0 ? ($goods['inventory'] - $toady_sale) : 0;
            }
        }

        $goods['is_purchase'] = p_getcolumn('order', array('goods_id' => $id, 'member_id' => $this->member['id']), 'COUNT(*)');

        //是否还可以购买
        /*if ($goods['allow_number']  == 0) {
            $is_buy = 1;
        } else {
            $is_buy = $goods['allow_number'] - $goods['is_purchase'] > 0 ? 1 : 0;
        }*/
        $is_buy = $goods['allow_number'] - $goods['is_purchase'] > 0 ? ($goods['allow_number'] - $goods['is_purchase']) : 0;


        $goods['is_buy'] = $is_buy;
        if ($goods['introduce_type'] == 2 && $goods['introduce']) {
            $goods['introduce'] = json_decode($goods['introduce'], true);
            $broadcast = array();
            foreach ($goods['introduce'] as $value) {
                $broadcast[] = getImage($value);
            }
            $goods['introduce'] = $broadcast;
        }
        $today = $this->step->day_info($this->member['id']);
        $today['step'] = $today['step'] ? : 0;

        $goods['invitation_list_length'] = 0;
        //活力币需要邀请人
        if (($goods['exchange_type'] == 1 || $goods['exchange_type'] == 4) && $goods['invitation_number'] > 0) {
            $goods['invitation_list'] = p_getall('goods_share', array('goods_id' => $id, 'member_id' => $this->member['id'], 'status' => 1));
            if ($goods['invitation_list']) {
                foreach ($goods['invitation_list'] as & $item) {
                    $item['member'] = p_get('member', array('id' => $item['friend_is']));
                    $item['member']['nickname'] = $item['member']['nickname'] ? : substr($item['member']['openid'], 0, 6);
                }
            }
            $goods['invitation_list_length'] = count($goods['invitation_list']);

        }
        elseif ($goods['exchange_type'] == 2 || $goods['exchange_type'] == 3) {
            $goods['share_step'] = 0;
            $goods['give_step'] = p_getall('goods_give', array('member_id' => $this->member['id'], 'status' => 1, 'goods_id' => $id));
            if ($goods['give_step']) {
                foreach ($goods['give_step'] as & $line) {
                    $goods['share_step'] += $line['step'];
                    $line['member'] = p_get('member', array('id' => $line['friend_id']));
                }
            }

            $friend_progress = getConfig('friend_progress');
            $progress = 100 - $friend_progress;

            $effective_step = $today['effective_step'] - $today['use_step'];
            $max_step = ($goods['exchange_number'] * $progress) / 100;

            $goods['effective_step'] = floor($max_step > $effective_step ? $effective_step : $max_step);


            $goods['my_proportion'] = ($goods['effective_step'] / $goods['exchange_number']) * 100;
            $goods['fre_progress'] = ($goods['share_step'] / $goods['exchange_number']) * 100;
            $goods['today_step'] =  $goods['effective_step'] + $goods['share_step'];

            $goods['still_poor_step'] = $goods['exchange_number'] - $goods['today_step'];

            $goods['still_poor_step'] = $goods['still_poor_step'] > 0 ? $goods['still_poor_step'] : 0;

            $goods['friend_progress'] = $friend_progress;

            $goods['fre_progress'] = $goods['fre_progress'] > 100 ? 100 : $goods['fre_progress'];
            $goods['my_proportion'] = $goods['my_proportion'] > $progress ? $progress : $goods['my_proportion'];

            if ($goods['fre_progress'] >100) {
                $goods['my_proportion'] = 0;
            } elseif ($goods['fre_progress'] + $goods['my_proportion'] >= 100) {
                $goods['my_proportion'] = 100 - $goods['fre_progress'];
            }
        }
        $goods['cover_image'] = getImage($goods['cover_image']);
        $address = p_get('address', array('member_id' => $this->member['id']));

        $goods['is_author'] = ($this->member['nickname'] || $this->member['head']);
        $member_id = $this->member['id'];
        $audit_pattern = $this->audit_pattern();
        $currency_name = getConfig('currency_name', '活力币');

        $goods_flow_group = getConfig('goods_flow_group');
        $author_show = getConfig('author_show');

        $authorization_first = getConfig('authorization_first', '授权微信个人信息');
        $authorization_two = getConfig('authorization_two', '为您提供全部功能');

        $order = p_getall('order', array('goods_id' => $id), array(), array(), array('id DESC'), 10);

        if ($order) {
            foreach ($order as & $line) {
                $line['member'] = p_get('member', array('id' => $line['member_id']));
                $line['formatTime'] = formatTime($line['created']);
            }
        }



        json(compact('order', 'authorization_two', 'authorization_first', 'author_show', 'goods_flow_group', 'currency_name', 'goods', 'today', 'member_id', 'address', 'audit_pattern'));
    }


    /**
     * 订单列表
     */
    public function doPageOrder()
    {
        $p = $this->get('p', 1);
        $page_size = 10;
        $limit = ($p - 1) * $page_size;
        //$order = p_getall('order', ['member_id' => $this->member['id']], [],'', ['id desc'], "{$limit}, {$page_size}");
        $order = p_getall('order', array('member_id' => $this->member['id']), array(),'', array('id desc')) ? : array();
        if ($order) {
            $status = array(
                1 => '未发货',
                2 => '已发货',
                3 => '已完成',
                4 => '已取消'
            );
            foreach ($order as & $value) {
                $value['goods'] = p_get('goods', array('id' => $value['goods_id']));
                $value['goods']['img'] = getImage($value['goods']['cover_image']);
                $value['status_msg'] = $value['type'] != 4 ? $status[$value['status']] : '第三方代发';
                $value['created'] = date('Y-m-d H:i:s', $value['created']);
            }
        }
        json($order);
    }


    /**
     * 优惠券列表
     */
    public function doPageMyVoucher()
    {
        $p = $this->get('p', 1);
        $page_size = 10;
        $limit = ($p - 1) * $page_size;
        $voucher = p_getall('my_voucher', array('member_id' => $this->member['id']), array(), '', array('id desc')) ? : array();

        if ($voucher) {
            $status_msgs = array(
                1 => '未使用',
                2 => '已使用'
            );
            foreach ($voucher as & $value) {
                $value['goods'] = p_get('goods', array('id' => $value['goods_id']));
                $value['goods']['img'] = getImage($value['goods']['cover_image']);
                $value['shop'] = p_get('merchant', array('id' => $value['shop_id']));
                $value['created'] = date('Y/m/d H::s', $value['created']);
                $value['status_msg'] =$status_msgs[$value['status']];
            }
        }
        json($voucher);
    }


    /**
     * 优惠券
     */
    public function doPageVoucherDetails()
    {
        $id = $this->get('id');
        $voucher = p_get('my_voucher', array('id' => $id, 'member_id' => $this->member['id']));
        if (empty($voucher)) {
            json('优惠券不存在', 0);
        }
        $goods = p_get('goods', array('id' => $voucher['goods_id']));

        $shop = p_get('merchant', array('id' => $voucher['shop_id']));

        json(compact('voucher', 'goods', 'shop'));
    }


    /**
     * 核销
     */
    public function doPageWriteoff()
    {
        $voucher = $this->get('voucher');
        $voucher = p_get('my_voucher', array('content' => $voucher));
        if (empty($voucher) || $voucher['shop_id'] != $this->member['shop_id']) {
            json('你没有核销该优惠券的权限', 0);
        }
        if ($voucher['status'] == 2) {
            json('该优惠券已经被使用', 0);
        }
        try{
            pdo_begin();
            $updata = array('status' => 2, 'write_off_id' => $this->member['id'], 'updated' => time());
            if (!p_update('my_voucher' , $updata, array('id' => $voucher['id']))) {
                json('核销失败', 0);
            }
            if (!p_update('goods_fictitious', array('status' => 3, 'updated' => time()), array('content' => $voucher))) {
                json('核销失败', 0);
            }
            pdo_commit();
            json('核销成功');
        } catch (Exception $e) {
            pdo_rollback();
            json($e->getMessage(), 0);
        }
    }


    /**
     * 兑换商品
     */
    public function doPageExchange()
    {
        $id = $this->get('id');

        $goods = p_get('goods', array('id' => $id));
        if (empty($goods) || $goods['is_delete'] == 2 || $goods['status'] == 2) {
            json('商品不存在或者已经下架', 0);
        }

        $count = p_getcolumn('order', array('member_id' => $this->member['id'], 'goods_id' => $id), 'COUNT(*)');
        if ($count >= $goods['allow_number']) {
            json('该商品只能限量兑换' . $goods['allow_number'] . '个', 0);
        }

        if ($goods['sigin'] > 0 && $this->step->get_signin($this->member['id']) < $goods['sigin']) {
            json('该商品需要连续签到' . $goods['sigin'] . '天才能兑换', 0);
        }

        $sale = p_getcolumn('order', array('goods_id' => $id), 'COUNT(*)');

        $time = strtotime(date('Y-m-d', time()));

        $today_sale = p_fetchcolumn("SELECT COUNT(*) FROM " .prefix('order') . " WHERE goods_id=:goods_id AND created>={$time} AND uniacid=" . UNIACID, array(':goods_id' => $id));

        //计算商品库存
        if ($goods['type'] == 2) { //虚拟
            $inventory = p_getcolumn('goods_fictitious', array('goods_id' => $id, 'status' => 1), 'COUNT(*)') ? : 0;
            if ($goods['inventory_type'] == 2) {
                $goods['inventory'] = ($inventory - $sale) ? : 0;
            } else {
                $i = $goods['inventory'] - $today_sale;
                $goods['inventory'] = ($i >= $inventory ? $inventory : $i) ? : 0;
            }
        } else {
            if ($goods['inventory_type'] == 2) {
                $goods['inventory'] = ($goods['inventory'] - $sale) ? : 0;
            } else {
                $goods['inventory'] = ($goods['inventory'] - $today_sale) ? : 0;
            }
            if ($goods['type'] == 1) {
                $address = p_get('address', array('member_id' => $this->member['id']));
                if (empty($address)) {
                    json('请填写收货地址', 0);
                }
            }
        }

        if ($goods['inventory'] < 1) {
            json('库存不足', 0);
        }

        try {
            pdo_begin();
            $money = 0;

            if ($goods['exchange_type'] > 2) {
                $money += $goods['money'];
            }
            if ($goods['is_free'] == 2) {
                $money += $goods['free'];
            }



            if ($goods['exchange_type'] == 1 || $goods['exchange_type'] == 4) {

                if ($goods['exchange_number'] > $this->member['currency']) {
                    throw new Exception('您的' . getConfig('currency_name', '活力币') . '不足');
                }
                if ($goods['invitation_number'] > 0) {
                    $share = p_getcolumn('goods_share', array('member_id' => $this->member['id'], 'goods_id' => $id, 'status' => 1), 'COUNT(*)');
                    if ($share < $goods['invitation_number']) {
                        throw new Exception('邀请的人数还不够');
                    }

                }



                if ($money > 0) {
                    //if ($this->member['money'] >= $money) {
                    if (intval($this->member['money'] * 100) >= intval($money * 100)) {
                        if (!$this->step->change_money($this->member['id'], $money, 5)) {
                            throw new Exception($this->step->error);
                        }
                    } else {
                        //$pay_money = $money - $this->member['money'];
                        $pay_money = (intval($money * 100) - intval($this->member['money'] * 100)) / 100;
                        $orderid = $this->step->unifiedOrder($this->member['id'], $pay_money);

                        $order = array(
                            'tid' => $orderid, //订单号
                            'fee' => floatval($pay_money), //支付参数
                            'title' => $goods['goods_name'], //标题
                        );

                        global $_W;
                        $_W['openid'] = $this->member['openid'];
                        $_W['member']['uid'] = $this->member['id'];
                        $paydata = $this->pay($order);
                        if (is_error($paydata)) {
                            throw new Exception($paydata['message']);
                        }
                        pdo_commit();
                        json($paydata, 2);
                    }
                }



                p_update('goods_share', array('status' => 2), array('member_id' => $this->member['id'], 'goods_id' => $id));

                $remarks = '兑换商品[' . $goods['goods_name'] . ']';
                if (!$this->step->change_currency($this->member['id'], $goods['exchange_number'], 9, $remarks)) {
                    throw new Exception($this->step->error);
                }
            } else {
                $give_stmp = p_getcolumn('goods_give', array('member_id' => $this->member['id'], 'goods_id' => $id, 'status' => 1), 'SUM(step)') ? : 0;


                $today = $this->step->day_info($this->member['id']);
                $today_step = 0;
                if ($today) {
                    $friend_progress = getConfig('friend_progress') ? : 10;
                    $progress = 100 - $friend_progress;

                    $effective_step = $today['effective_step'] - $today['use_step'];
                    $max_step = ($goods['exchange_number'] * $progress) / 100;

                    $today_step = floor($max_step > $effective_step ? $effective_step : $max_step);
                }
                if ($give_stmp + $today_step < $goods['exchange_number']) {
                    throw new Exception('步数不足');
                }


                if ($money > 0) {
                    if (intval($this->member['money'] * 100) >= intval($money * 100)) {
                        if (!$this->step->change_money($this->member['id'], $money, 5)) {
                            throw new Exception($this->step->error);
                        }
                    } else {
                        $pay_money = (intval($money * 100) - intval($this->member['money'] * 100)) / 100;
                        //$pay_money = bcsub($money, $this->member['money'], 2);
                        $orderid = $this->step->unifiedOrder($this->member['id'], $pay_money);

                        $order = array(
                            'tid' => $orderid, //订单号
                            'fee' => floatval($pay_money), //支付参数
                            'title' => $goods['goods_name'], //标题
                        );

                        global $_W;
                        $_W['openid'] = $this->member['openid'];
                        $_W['member']['uid'] = $this->member['id'];
                        $paydata = $this->pay($order);
                        if (is_error($paydata)) {
                            throw new Exception($paydata['message']);
                        }
                        pdo_commit();
                        json($paydata, 2);
                    }
                }



                if ($goods['exchange_number'] > $give_stmp) {
                    $use_step = $today['use_step'] + $goods['exchange_number'] - $give_stmp;
                    if (!p_update('today', array('use_step' => $use_step), array('id' => $today['id']))) {
                        throw new Exception('兑换失败,请稍后再试');
                    }
                }
                if ($give_stmp > 0) {
                    if (!p_update('goods_give', array('status' => 2), array('member_id' => $this->member['id'], 'goods_id' => $id))) {
                        throw new Exception('兑换失败,请稍后再试');
                    }
                }
            }
            $order= array(
                'goods_id' => $id,
                'member_id' => $this->member['id'],
                'status' => 1,
                'type' => $goods['type'],
                'created' => time()
            );

            //虚拟
            if ($goods['type'] == 2) {
                $update = array(
                    'member_id' => $this->member['id'],
                    'status' => 2,
                    'updated' => time()
                );
                $fictitious = p_get('goods_fictitious', array('goods_id' => $id, 'status' => 1));
                if (empty($fictitious)) {
                    throw new Exception('库存不足');
                }
                if (!p_update('goods_fictitious', $update, array('id' => $fictitious['id'], 'status' => 1))) {
                    throw new Exception('兑换失败,请稍后再试');
                }

                if ($fictitious['is_under_line'] == 2) {
                    $voucher = array(
                        'member_id' => $this->member['id'],
                        'shop_id' => $fictitious['shop_id'],
                        'goods_id' => $fictitious['goods_id'],
                        'content' => $fictitious['content'],
                        'created' => time()
                    );
                    if (!p_insert('my_voucher', $voucher)) {
                        throw new Exception('兑换失败,请稍后再试');
                    }
                }

                $order['fictitious'] = $fictitious['content'];
                $order['status'] = 3;
            } else {
                if ($goods['type'] == 3) {
                    if (!$this->step->change_money($this->member['id'], $goods['bag_money'], 6)) {
                        throw new Exception('兑换失败,请稍后再试');
                    }
                    $order['status'] = 3;
                } else if ($goods['type'] == 4){
                    $order['status'] = 3;
                } else {
                    $order['address'] = $address['address'];
                    $order['name'] = $address['name'];
                    $order['phone'] = $address['phone'];
                }
            }
            $order['exchange_type'] = $goods['exchange_type'];
            $order['exchange_number'] = $goods['exchange_number'];

            if (!p_insert('order', $order)) {
                throw new Exception('兑换失败,请稍后再试');
            }
            pdo_commit();
            json('兑换成功');
        } catch (Exception $e) {
            pdo_rollback();
            json($e->getMessage(), 0);
        }
    }


    /**
     * 海报
     */
    public function doPagePoster()
    {
        $id = $this->get('id');
        $goods = p_get('goods', array('id' => $id));
        $member = $this->member;


        $goods_share_img = getImage(getConfig('poster_background'));
        $goods_share_img = $goods_share_img ?  : ($this->w['siteroot'] . 'addons/bh_step/template/image/goods-share-img-bg-2.png');

        $logo = getImage(getConfig('poster_icon'));
        $logo = $logo ? : ($this->w['siteroot'] . 'addons/bh_step/icon.jpg');

        $goods['cover_image'] = getImage($goods['cover_image']);
        $xcx_title = getConfig('xcx_title');

        if (is_oss()) {
            $goods_img_name = md5($goods['cover_image']);
            $cover_image_path = ATTACHMENT_ROOT . $this->w['uniacid'] .'goods/' . $goods_img_name . '.png';
            if (!is_file($cover_image_path)) {
                if (!file_exists(ATTACHMENT_ROOT . $this->w['uniacid'] .'goods')) {
                    @mkdir(ATTACHMENT_ROOT . $this->w['uniacid'] .'goods',0777,true);
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_URL, $goods['cover_image']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $ret = curl_exec($ch);

                if ($_GET['dd']) {
                    var_dump($goods['cover_image']);
                    var_dump(curl_error($ch));
                    var_dump($ret);
                }
                curl_close($ch);
                file_put_contents($cover_image_path, $ret);
            }

            $goods['cover_image'] = $this->w['siteroot'] . 'attachment/' . $this->w['uniacid'] .'goods/' . $goods_img_name . '.png';
        }




        $scene = $this->member['id'] . '_' . $id;

        $qrpath = ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr/Pbh' . $scene . '.png';

        if (!file_exists(ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr')) {
            @mkdir(ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr',0777,true);
        }


        if (!is_file($qrpath)) {
            include "lib/Gd.class.php";
            include "lib/Image.class.php";
            $Image = new Image();

            $qr_temp = ATTACHMENT_ROOT . 'qr_temp' . $this->member['id'] . '.jpg';
            $option = array(
                'appid' => $this->w['uniaccount']['key'],
                'secret' => $this->w['uniaccount']['secret'],
            );


            $path = in_array($goods['exchange_type'], array(1, 4)) ? 'bh_step/pages/currencyGoods/currencyGoods' : 'bh_step/pages/goods/goods';

            $wxObj = new Wxadoc($option);
            $res = $wxObj->createwxaqrcode($scene, $path, 430, false, array('r' => 72, 'g' => 145, 'b' => 92));
            if ($res) {
                file_put_contents($qr_temp, $res);
                $qrimg = $Image->open($qr_temp);
                $qrimg->thumb(217, 217)->save($qrpath);

                unlink($qr_temp);
            }
        }
        $qrcodeUrl = $this->w['siteroot'] . 'attachment/' . $this->w['uniacid'] .'mqr/Pbh' . $scene . '.png';


        //头像
        $m_acatar = ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr/bhmm1' . $this->member['id'] . '.png';

        if (!is_file($m_acatar) && $this->member['head']) {
            //file_put_contents($m_acatar, file_get_contents($this->member['head']));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $this->member['head']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $ret = curl_exec($ch);

            if ($_GET['dd']) {
                var_dump($this->member['head']);
                var_dump(curl_error($ch));
                var_dump($ret);
            }
            curl_close($ch);

            file_put_contents($m_acatar, $ret);
        }
        $head = $this->w['siteroot'] . 'attachment/' . $this->w['uniacid'] .'mqr/bhmm1' . $this->member['id'] . '.png';


        json(compact('goods', 'member', 'goods_share_img', 'logo', 'xcx_title', 'head', 'qrcodeUrl'));
    }


    public function doPageShareImage()
    {
        $url = $this->w['siteroot'] . 'addons/bh_step/template/image/wechat/luck/share';
        $image = array(
            array(
                'posterImg' => $url . '/bg_9_6_1.jpg',
                'useNameStyle' => 'color:#ffffff;',
                'dateStyle' => 'color:rgba(255,255,255,0.80);',
                'stepNumStyle' => 'top:120px;right:29px;color:#ffffff;',
                'stepNumCss' => array(
                    'top' => '283rpx',
                    'right' => '70rpx',
                    'color' => '#ffffff',
                ),
                'useNameCss' => array(
                    'color' => '#ffffff'
                )
            ),
            array(
                'posterImg' => $url . '/bg_9_6_2.jpg',
                'useNameStyle' => 'color:#ffffff;',
                'dateStyle' => 'color:rgba(255,255,255,0.80);',
                'stepNumStyle' => 'top:128px;left:46px;color:#ffffff;',
                'stepNumCss' => array(
                    'top' => '302rpx',
                    'left' => '110rpx',
                    'color' => '#ffffff'
                ),
                'useNameCss' => array( 'color' => '#ffffff')
            ),
            array(
                'posterImg' => $url . '/bg_9_6_4.jpg',
                'useNameStyle' => 'color:#ffffff;',
                'dateStyle' => 'color:rgba(255,255,255,0.80);',
                'stepNumStyle' => 'top:120px;right:29px;color:#434343;',
                'stepNumCss' => array(
                    'top' => '283rpx',
                    'right' => '29px',
                    'color' => '#434343'
                ),
                'useNameCss' => array( 'color' => '#ffffff')
            ),
            array(
                'posterImg' => $url . '/bg_9_6_3.jpg',
                'useNameStyle' => 'color:#ffffff;',
                'dateStyle' => 'color:rgba(255,255,255,0.80);',
                'stepNumStyle' => 'color:#ffffff;left:46px;top:128px;',
                'stepNumCss' => array(
                    'top' => '302rpx',
                    'left' => '110rpx',
                    'color' => '#ffffff'
                ),
                'useNameCss' => array( 'color' => '#ffffff')
            ),
            array(
                'posterImg' => $url . '/bg_9_6_5.jpg',
                'useNameStyle' => 'color:#333333;',
                'dateStyle' => 'color:rgba(51,51,51,0.80);',
                'stepNumStyle' => 'color:#FF8E00;left:45px;top:126px;',
                'stepNumCss' => array(
                    'top' => '296rpx',
                    'left' => '106rpx',
                    'color' => '#FF8E00'
                ),
                'useNameCss' => array('color' => '#ffffff')
            )
        );
        $today = $this->step->day_info($this->member['id']);
        $qrpath = ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr/bh1' . $this->member['id'] . '.png';

        if (!file_exists(ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr')) {
            @mkdir(ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr',0777,true);
        }

        if (!is_file($qrpath)) {
            include "lib/Gd.class.php";
            include "lib/Image.class.php";
            $Image1 = new Image();

            $qr_temp = ATTACHMENT_ROOT . 'qr_temp' . $this->member['id'] . '.jpg';
            $option = array(
                'appid' => $this->w['uniaccount']['key'],
                'secret' => $this->w['uniaccount']['secret'],
            );
            $wxObj = new Wxadoc($option);
            $res = $wxObj->createwxaqrcode($this->member['id'], 'bh_step/pages/index/index', 430, false, array('r' => 72, 'g' => 145, 'b' => 92));
            if ($res) {
                file_put_contents($qr_temp, $res);
                $qrimg = $Image1->open($qr_temp);
                $qrimg->thumb(217, 217)->save($qrpath);

                unlink($qr_temp);
            }
        }
        $m_acatar = ATTACHMENT_ROOT . $this->w['uniacid'] .'mqr/bhmm1' . $this->member['id'] . '.png';


        if (!is_file($m_acatar) && $this->member['head']) {
            //file_put_contents($m_acatar, file_get_contents($this->member['head']));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $this->member['head']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $ret = curl_exec($ch);

            if ($_GET['dd']) {
                var_dump($this->member['head']);
                var_dump(curl_error($ch));
                var_dump($ret);
            }
            curl_close($ch);

            file_put_contents($m_acatar, $ret);
        }
        $head = $this->w['siteroot'] . 'attachment/' . $this->w['uniacid'] .'mqr/bhmm1' . $this->member['id'] . '.png';

        $data = array(
            'stepNum' => $today['step'],
            'userInfo' => array('avatar_url' => $head, 'nick_name' => $this->member['nickname']),
            'image' => $image,
            'date' => date('Y/m/d', time()),
            'qrcodeUrl' => $this->w['siteroot'] . 'attachment/' . $this->w['uniacid'] .'mqr/bh1' . $this->member['id'] . '.png',
            //'qrcodeUrl' => 'http://www.small.com/addons/bh_step/template/image/444.png',
            'xcx_title' => getConfig('xcx_title')
        );
        $step_journal_one = getConfig('step_journal_one') ? : '保存图片分享到朋友圈，展现自己的运动态度';
        $step_journal_two = getConfig('step_journal_two') ? : '好友通过图片分享进入你同样可获得邀请奖励';

        $audit_pattern = $this->audit_pattern();
        $data['content'] = array(
            'one' => $audit_pattern == 2 ? '' : $step_journal_one,
            'two' => $audit_pattern == 2 ? '' : $step_journal_two
        );

        json($data);
    }



    /**
     * 排行
     */
    public function doPageRankings()
    {

        $sql ="SELECT * FROM (SELECT id,nickname,head,currency,uniacid, (@rowNum:=@rowNum+1) AS rowNo FROM ".  prefix('member') .",(SELECT(@rowNum:=0)) b ORDER BY currency DESC) c WHERE id= ".$this->member['id'].' AND (nickname != "" || head!= "") '." and uniacid = ".UNIACID;
        $myRowNo = p_fetch($sql);


        $count = 1;

        $sql = "SELECT id,nickname,head,currency FROM " . prefix('member') . " WHERE id = {$this->member['id']} || (parent_id={$this->member['id']} AND uniacid=" . UNIACID . ' AND (nickname != "" || head!= "")) ORDER BY currency DESC LIMIT 100';
        $friends = p_fetchall($sql);

        if ($friends) {
            foreach ($friends as $key => & $value) {
                $value['signin'] = $this->step->today_signin($value['id']);
                $value['num'] = $key + 1;
                if ($value['id'] == $this->member['id']) {
                    $count = $value['num'];
                }

            }
        }


        $sql = "SELECT id,nickname,head,currency FROM " . prefix('member') . " WHERE uniacid=" . UNIACID . ' AND (nickname != "" || head!= "") ORDER BY currency DESC LIMIT 200';
        $top = p_fetchall($sql);



        $menber = array(
            'id' => $this->member['id'],
            'head' => $this->member['head'],
            'nickname' => $this->member['nickname'],
            'currency' => $this->member['currency'],
            'count' => $count,
            'auth' => ($this->member['nickname'] || $this->member['head']) ? 1 : 0,
            'isSignIn' => $this->step->today_signin($this->member['id'])
        );

        $rank_top_image = getImage(getConfig('rank_top_image'));
        $rank_top_image = $rank_top_image ? : ($this->w['siteroot'] . 'addons/bh_step/template/image/20180915215005.jpg');

        $data = array(
            'friends' => $friends,
            'top' => $top,
            'menber' => $menber,
            'myRowNo' => $myRowNo['rowNo'],
            'share' => array(
                'text' => str_replace('{name}', $this->member['nickname'], getConfig('share_top_text')),
                'image' => getImage(getConfig('share_top_text_image'))
            ),
            'audit_pattern' => $this->audit_pattern(),
            'ui' => array(
                'rank_top_image' => $rank_top_image,
                'left_list_name' => getConfig('left_list_name') ? : '好友排行',
                'right_list_name' => getConfig('right_list_name') ? : '我的排名'
            ),
            'currency_name' => getConfig('currency_name', '活力币')

        );
        json($data);
    }


    /**
     * 获取配置
     */
    public function doPageConfig()
    {
        if (!$key = $this->get('key')) {
            json('参数错误');
        }

        $data = array('member_id' => $this->member['id']);
        $keys = explode(',', $key);
        foreach ($keys as $value) {
            $data[$value] = getConfig($value);
            if (in_array($value, array('share_text', 'share_red_packet_text'))) {
                $data[$value] = str_replace('{name}', $this->member['nickname'], $data[$value]);
            }
        }
        json($data);
    }


    /**
     * 图片上传
     */
    public function doPageUpload()
    {
        $jietu = $this->get('jietu');
        if ($jietu) {
            $_FILES['file']['name'] = $_FILES['file']['name'] . '.png';
        }
        if (!isset($_FILES['file'])) {
            json('请上传图片', 0);
        }
        load()->func('file');
        $reslut = file_upload($_FILES['file']);
        if (isset($reslut['errno'])) {
            json($reslut['message'], 0);
        }
        $pic =  '/' . $reslut['path'];

        if (is_oss()) {
            $remotestatus = file_remote_upload($reslut['path']);
            if (is_error($remotestatus)) {
                json('远程附件上传失败', 0);
            }
        }

        $data = array(
            'member_id' => $this->member['id'],
            'route' => $pic,
            'created' => time()
        );
        if (!p_insert('resource', $data)) {
            json('上传失败', 0);
        }
        json(pdo_insertid());
    }


    public function doPageImage()
    {
        $id = $this->get('id');
        if (!is_numeric($id)) {
            json('参数错误', 0);
        }
        json(getImage($id));
    }

    /**
     * desc 获取用户信息
     */
    public function doPageGetuserinfo()
    {
        global $_GPC, $_W;
        $weid = $this->w['uniacid'];
        $sessionKey = $_GPC["session_key"];
        $encryptedData = $_GPC["encryptedData"];
        $iv = $_GPC["iv"];
        $openid = $_GPC["openid"];
		//$strr = "session_key:".$sessionKey.":encryptedData:".$encryptedData.":iv:".$iv.":openid:".$openid;
		//file_put_contents("test3.txt", date('Y-m-d H:i:s').$strr.PHP_EOL, FILE_APPEND);
        //define(PDO_DEBUG, TRUE);
        $appid = pdo_getcolumn("account_wxapp", array("uniacid" => $weid), array("key"));
        //pdo_debug();
        //file_put_contents("test3.txt", date('Y-m-d H:i:s').$appid.'|'.$this->session_key.PHP_EOL, FILE_APPEND);
        include 'lib/WXBizDataCrypt.php';
        $pc = new WXBizDataCrypt($appid, $this->session_key);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        file_put_contents("test3.txt", date('Y-m-d H:i:s').$errCode.PHP_EOL, FILE_APPEND);
        file_put_contents("test3.txt", date('Y-m-d H:i:s').$data.PHP_EOL, FILE_APPEND);
        $return = json_decode($data, true);
        $openid = $return['openId'];
        if (empty($openid) || $openid == "undefined") {
            return $this->result(1, "参数错误，缺少OPENID");
        }
        $ishave = pdo_get("bh_st_member", array("openid" => $openid));
        if (empty($ishave)) {
            return $this->result(1, "用户不存在");

        } else {
            //$arr["sessionkey"] = $this->session_key;
            if ($return["unionId"]) {
                $arr["unionid"] = $return["unionId"];
            }
            pdo_update("bh_st_member", $arr, array("id" => $ishave["id"]));
            $uid = $ishave["id"];
        }
        return $this->result(0, "操作成功", $uid);
    }


    /**
     * 计划任务
     */
    public function doPageCrontab()
    {
        $option = array(
            'appid' => $this->w['uniaccount']['key'],
            'secret' => $this->w['uniaccount']['secret'],
        );
        $wxObj = new Wxadoc($option);
        set_time_limit(0);

        $bag_send_hour = getConfig('bag_send_hour');
        $bag_send_minute = getConfig('bag_send_minute');

        $h = date('H');
        $i = date('i');

        $time = strtotime(date('Y-m-d', time()));

        $sign_send_hour = getConfig('sign_send_hour');
        $sign_send_minute = getConfig('sign_send_minute');
        $bag_template_id = getConfig('bag_template_id');
        if ($bag_template_id && ($bag_send_hour < $h || ($bag_send_hour == $h && $bag_send_minute <= $i))) {
            $sql = 'SELECT id,member_id FROM ' . prefix('remind') . ' WHERE `type` = 1 AND  status=1 AND remind_day=' . $time . ' AND uniacid=' . UNIACID;
            if ($data = p_fetchall($sql)) {

                $content = array(
                    'keyword1' => array('value' => '每日免费领取红包'),
                    'keyword2' => array('value' => '点击进入领取红包'),
                    'keyword3' => array('value' => '未完成'),
                    'keyword4' => array( 'value' => '别人已经领红包啦,你赶紧来领哦↓↓↓'),
                );
                $t = time() - (7 * 86400);

                foreach ($data as $value) {
                    $form_id = p_fetch("SELECT * FROM " . prefix('form_id') . " WHERE member_id={$value['member_id']} AND created > {$t}");
                    $openid = p_getcolumn('member', array('id' => $value['member_id']), 'openid');
                    /********判断今天是否签到**********/
                    if (!$this->step->today_signin($value['member_id'])) {
                        if (!empty($form_id) && !empty($openid)) {
                            $wxObj->sendTemplateMessage($openid, $bag_template_id, $content, $form_id['form_id'], 'bh_step/pages/index/index');
                            p_delete('form_id', array('id' => $form_id['id']));
                        }
                    }
                    p_update('remind', array('status' => 2, 'remind_time' => time()), array('id' => $value['id']));
                }
            }
        }
        $signIn_template_id = getConfig('signin_template_id');
        if ($signIn_template_id && ($sign_send_hour < $h || ($sign_send_hour == $h && $sign_send_minute <= $i))) {
            $sql = 'SELECT id,member_id FROM ' . prefix('remind') . ' WHERE `type` = 2 AND status=1 AND remind_day=' . $time . ' AND uniacid=' . UNIACID;
            if ($data = p_fetchall($sql)) {
                $content = array(
                    'keyword1' => array('value' => "每日签到换礼品"),
                    'keyword2' => array('value' => "预约成功"),
                    //'keyword3' => ['value' => "真棒!,你已累计签到{$day}天了,坚持你就能获得更多!"],
                    'keyword4' => array('value' => "签到奖励有效期为当天23:59快去领↓↓↓")
                );
                $t = time() - (7 * 86400);
                foreach ($data as $value) {
                    $form_id = p_fetch("SELECT * FROM " . prefix('form_id') . " WHERE member_id={$value['member_id']} AND created > {$t}");
                    $openid = p_getcolumn('member', array('id' => $value['member_id']), 'openid');

                    $day = $this->step->get_signin($value['member_id']);
                    $content['keyword3']['value'] = "真棒!,你已累计签到{$day}天了,坚持你就能获得更多!";

                    /******判断今天是否领取了红包******/
                    if (!$this->step->is_today_receive($value['member_id'])) {
                        if (!empty($form_id) && !empty($openid)) {
                            $wxObj->sendTemplateMessage($openid, $signIn_template_id, $content, $form_id['form_id'], 'bh_step/pages/index/index');
                            p_delete('form_id', array('id' => $form_id['id']));
                        }
                    }
                    p_update('remind', array('status' => 2, 'remind_time' => time()), array('id' => $value['id']));
                }
            }
        }
        json('yes');
    }



    private function setPayment()
    {
        WxPayConfig::$APPID = $this->w['uniaccount']['key'];
        WxPayConfig::$MCHID = $this->w['account']['setting']['payment']['wechat']['mchid'];
        WxPayConfig::$KEY = $this->w['account']['setting']['payment']['wechat']['signkey'];
        WxPayConfig::$APPSECRET = $this->w['uniaccount']['secret'];

        $apiclient_cert = getConfig('apiclient_cert');
        if (!empty($apiclient_cert)) {
            WxPayConfig::$SSLCERT_PATH = $apiclient_cert;
        }
        $apiclient_key = getConfig('apiclient_key');
        if (!empty($apiclient_key)) {
            WxPayConfig::$SSLKEY_PATH = $apiclient_key;
        }
    }



    public function get($key, $default = '') {
        return isset($this->gpc[$key]) ? $this->gpc[$key] : $default;
    }


    /**
     * 充值回调
     */
    public function payResult($data)
    {
        global $_GPC, $_W;
        $orderid = $data['tid'] ? $data['tid'] : $_GPC['orderid'];
        $paylog = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => 'bh_step', 'tid' => $orderid));

        $status = intval($paylog['status']) === 1;
        if (!$status) {
            exit;
        }

        $order = pdo_get('bh_st_pay_order', array('trade_no' => $orderid));

        if (empty($order) || $order['status'] == 2) {
            exit;
        }
        //更新余额
        $this->step->change_money($order['member_id'], $order['money'], 1);
        //file_put_contents(ATTACHMENT_ROOT . '1.txt', 'res' . var_export($res, true) . "/r/n", FILE_APPEND);
        //更新充值记录
        $update = array(
            'transaction_id' => $paylog['encrypt_code'],
            'pay_time' => time(),
            'status' => 2
        );
        pdo_update('bh_st_pay_order', $update, array('id' => $order['id']));
        //充值后续操作

        exit('ok');
    }

    /**
     * 是否开启审核模式
     * @param $v
     * @return int
     */
    public function audit_pattern()
    {
        if ($this->get('version') == $this->w['current_module']['version'] && getConfig('audit_pattern') == 2) {
        //if (($this->get('version') == '2.2.5' || $this->get('version')  == '2.2.6') && getConfig('audit_pattern') == 2) {
            return 2;
        }
        return 1;
    }
}