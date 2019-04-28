<?php
/**
 * 微擎卡券模块微站定义
 *
 * @author 微擎团队
 * @url
 */
defined('IN_IA') or exit('Access Denied');

include "lib/Wxadoc.php";
include "lib/function.php";
include "model/step.mod.php";
include "lib/WxpayAPI_php_v3/WxpayAPI_php_v3.php";
class bh_stepModuleSite extends WeModuleSite
{

    public $settings;
    public $siteRoot;
    public $w = array();
    public $gpc = array();
    private $step;


    protected  $courier_name = array(
        '顺风快递',
        '百世快递',
        '韵达快递',
        '圆通速递',
        '申通快递',
    );

    /**
     *
     * @var array
     */
    protected $custom_user = array(
        'weiqing.laonianwangxiao.cn'  => 'older_online_school_order',
         //'www.wechatframework.com'    => 'member_whatever',
    );
    public function __construct()
    {
        global $_GPC, $_W;
        $this->gpc = $_GPC;
        $this->w = $_W;
        $this->step = new step();
        $this->siteRoot = $this->w['siteroot'];
        define('UNIACID', $this->w['uniacid']);

    }

    public function doWebEditorUpload()
    {
        if (!isset($_FILES['imgFile'])) {
            json('请上传图片', 0);
        }
        load()->func('file');
        $reslut = file_upload($_FILES['imgFile']);
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

        die(json_encode(array('error' => 0, 'url' => getImage($pic))));
    }


    /**
     * 基本设置
     */
    public function doWebConfig()
    {
        load()->func('tpl');
        $siteRoot = $this->siteRoot;
        $modular_name = $this->gpc['m'];
        $upload = $siteRoot."/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=upload&m={$this->gpc['m']}&a=wxapp";
        $image = $siteRoot. "/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=image&m={$this->gpc['m']}&a=wxapp";
        $crontab = $this->w['siteroot'] . "app/index.php?i={$this->w['uniacid']}&from=wxapp&c=entry&a=wxapp&do=crontab&m=bh_step";
        $kyes = array(
            'signin' => $this->step->signin_days,
            'bag' => array(
                array('key' => 'ordinary_bag_start', 'default' => 0.01),
                array('key' => 'ordinary_bag_end', 'default' => 0.05),
                array('key' => 'new_ordinary_bag_start', 'default' => 0.01),
                array('key' => 'new_ordinary_bag_end', 'default' => 0.05),
                array('key' => 'ordinary_bag_start_one', 'default' => 0.01),
                array('key' => 'ordinary_bag_end_one', 'default' => 0.05),
                array('key' => 'ordinary_bag_start_two', 'default' => 0.01),
                array('key' => 'ordinary_bag_end_two', 'default' => 0.05),
                array('key' => 'ordinary_bag_start_three', 'default' => 0.01),
                array('key' => 'ordinary_bag_end_three', 'default' => 0.05),
                array('key' => 'ordinary_bag_start_four', 'default' => 0.01),
                array('key' => 'ordinary_bag_end_four', 'default' => 0.05),
                array('key' => 'ordinary_bag_start_five', 'default' => 0.01),
                array('key' => 'ordinary_bag_end_five', 'default' => 0.05),
                array('key' => 'bag_currency', 'default' => 5),
                array('key' => 'bag_step_number', 'default' => 500),
                array('key' => 'bag_switch', 'default' => 1),
                array('key' => 'bag_template_id', 'default' => ''),
                array('key' => 'bag_send_hour', 'default' => '10'),
                array('key' => 'bag_send_minute', 'default' => '0'),
                array('key' => 'bag_one_cooling', 'default' => '5'),
                array('key' => 'bag_two_cooling', 'default' => '10'),
                array('key' => 'bag_three_cooling', 'default' => '15'),
                array('key' => 'bag_four_cooling', 'default' => '20'),
                array('key' => 'bag_invitations_number', 'default' => '1'),
                array('key' => 'bag_currency_last', 'default' => 2),
                array('key' => 'bag_total_amount', 'default' => 5000),
                array('key' => 'bag_daily_upper', 'default' => 20),
                array('key' => 'bag_initial_number', 'default' => 10),
                array('key' => 'cash_explain', 'default' =>'')
            ),
            'ordinary' => array(
                array('key' => 'invitation_currency_start', 'default' => 1),
                array('key' => 'invitation_currency_end', 'default' => 2),
                array('key' => 'invitation_effective_number', 'default' => 7),
                array('key' => 'step_currency', 'default' => 1000),
                array('key' => 'effective_step_currency', 'default' => 20000),
                array('key' => 'author_currency', 'default' => 2),
                array('key' => 'signin_template_id', 'default' => ''),
                array('key' => 'follow_currency', 'default' => 10),
                array('key' => 'xcx_title', 'default' => '步步为赢'),
                array('key' => 'first_presentation', 'default' => 1),
                array('key' => 'follow_up_presentation', 'default' => 2),
                array('key' => 'share_group_currency', 'default' => 1),
                array('key' => 'author_currency', 'default' => 2),
                array('key' => 'sign_send_hour', 'default' => '10'),
                array('key' => 'sign_send_minute', 'default' => '0'),
                array('key' => 'friend_progress',   'default' => 5),
                array('key' => 'apiclient_cert ',   'default' => ''),
                array('key' => 'apiclient_key',   'default' => ''),
                array('key' => 'cut_step_max',   'default' => '2000'),
                array('key' => 'open_under_line',   'default' => 2),
                array('key' => 'audit_pattern',   'default' => 1),
                array('key' => 'author_show',   'default' => 2),
                array('key' => 'cash_reviewed',   'default' => 0),
                array('key' => 'bag_remind_switch',   'default' => 2),
                array('key' => 'sigin_remind_switch',   'default' => 2),
                array('key' => 'ald_key',   'default' => ''),
                array('key' => 'currency_name', 'default' => '活力币'),
                array('key' => 'goods_flow_group'),
                array('key' => 'my_center_group'),
                array('key' => 'authorization_first', 'default' => '授权个人基本信息'),
                array('key' => 'authorization_two' ,'default' => '体验所有功能'),
                array('key' => 'home_bottom_font' ,'default' => '每天上限分享至5个群，次日可重新分享'),
                array('key' => 'home_bottom_share' ,'default' => '分享至群活的福利'),
                array('key' => 'contantus_pop' ,'default' => '1'), // 1:不开启,2:开启,  默认是不开启
                array('key' => 'system_notice', 'default'  => '' ), // 系统公告
                array('key' => 'send_message_id', 'default'  => '' ), // 发货消息id
                array('key' => 'member_continuous_day', 'default'  => 0 ), // 连续多少天达到最高步数拉黑
                array('key' => 'nomal_echange_currency', 'default'  => '1000' ), // 普通用户多少步转化1活力币
                array('key' => 'vip_echange_currency', 'default'  => '1000' ), // VIP用户多少步转化1活力币
            ),
            'currency' => array(
                array('key' => 'realize_currency', 'default' => '编辑活力币内容')
            ),
            'activity' => array(
                array('key' => 'activity', 'default' => '添加活动说明')
            ),
            'share' => array(
                array('key' => 'share_text', 'default' => '1'),
                array('key' => 'share_image', 'default' => '1'),
                array('key' => 'share_red_packet_text', 'default' => '1'),
                array('key' => 'share_red_packet_image', 'default' => '1'),
                array('key' => 'share_top_text', 'default' => ''),
                array('key' => 'share_top_text_image', 'default' => '')
            ),
            'ui' => array(
                array('key' => 'home_red_pack_bg'),
                array('key' => 'home_background_image',   'default' => ''),
                array('key' => 'home_suspension_coin_img'), //
                array('key' => 'home_my_coin_image'), //
                array('key' => 'home_suspension_coin_color', 'default' => '#554545'), //
                array('key' => 'home_suspension_coin_describe_color', 'default' => '#554545'), //
                array('key' => 'home_today_step_color' , 'default' => '#666666'),
                array('key' => 'home_today_step_num_color' , 'default' => '#434343'),
                array('key' => 'home_my_coin_color' , 'default' => '#fff'),
                array('key' => 'home_share_start_color' , 'default' => '#26BCC5'),
                array('key' => 'home_share_end_color' , 'default' => '#1DD49E'),
                array('key' => 'home_share_color' ,  'default' => '#fff'),
                array('key' => 'home_sigin_color' ,  'default' => '#fff'),
                array('key' => 'home_sigin_start_color' ,  'default' => '#26bcc5'),
                array('key' => 'home_sigin_end_color' ,  'default' => '#1dd49e'),
                array('key' => 'home_understand_coin_color' ,  'default' => '#000'),
                array('key' => 'home_redpack_color' ,  'default' => '#FFFFCB'),
                array('key' => 'home_redpack_open_image'),
                array('key' => 'home_redpack_send_image'),
                array('key' => 'home_invitation_coin'),
                array('key' => 'shop_top_image'),
                array('key' => 'rank_top_image'),
                array('key' => 'poster_icon'),        //图标72*72
                array('key' => 'poster_background'), //670 * 1030
                array('key' => 'left_list_name' ,  'default' => '好友排行'),
                array('key' => 'right_list_name',  'default' => '全国榜单'),
                array('key' => 'step_journal_one',  'default' => '保存图片分享到朋友圈，展现自己的运动态度'),
                array('key' => 'step_journal_two',  'default' => '好友通过图片分享进入你同样可获得邀请奖励'),
                array('key' => 'step_log_banner'),
            ),
            'withdrawal' => array(
                array('key' => 'follow_cash_switch', 'default' => 1),        // 提现开关, 1 :关闭,2:开启,默认关闭
                array('key' => 'follow_cash_pwd'),                           // 提现口令
                array('key' => 'follow_cash_verification', 'default' => 1), // 验证口令方式 1:当天第一次提现验证口令,2:每次提现都需要验证口令
                array('key' => 'follow_cash_image'), // 提现自定义图片
            ),
        );

        $op = $this->get('op', 'ordinary');
        if (!isset($kyes[$op])) {
            message('参数错误', $this->createWebUrl('config'));
        }
        $siteroot = $this->w['siteroot'];
        $root = IA_ROOT;

        if ($this->w['ispost']) {
            foreach ($kyes[$op] as $value) {
                if ($value['key'] == 'cash_explain') {
                    $cash_explain = json_encode($this->get('cash_explain'));
                    p_update('config', array('value' => $cash_explain), array('key' => 'cash_explain'));
                    continue;
                }
                if ($value['key'] == 'system_notice') {
                    $system_notice = json_encode($this->get('system_notice'));
                    p_update('config', array('value' => $system_notice), array('key' => 'system_notice'));
                    continue;
                }
                $names = array('home_red_pack_bg','home_background_image', 'share_image', 'share_red_packet_image', 'share_top_text_image', 'home_redpack_open_image', 'home_redpack_send_image','home_invitation_coin', 'shop_top_image','rank_top_image', 'poster_icon', 'poster_background');
                if (!in_array($value['key'], array('apiclient_cert ', 'apiclient_key'))) {
                    $val = in_array($value['key'], $names) ? resource($value['key']) : $this->get($value['key']);
                    p_update('config', array('value' => $val), array('key' => $value['key']));
                }
            }
            $path1 = ATTACHMENT_ROOT . $this->w['uniacid'] . 'cert/';
            if (!file_exists($path1)) {
                @mkdir($path1,0777,true);
            }
            if (isset($_FILES['_apiclient_cert']['tmp_name']) && file_exists($_FILES['_apiclient_cert']['tmp_name'])) {
                $path = $path1 . 'apiclient_cert.pem';
                if (move_uploaded_file($_FILES['_apiclient_cert']['tmp_name'], $path)) {
                    p_update('config', array('value' => $path), array('key' => 'apiclient_cert'));
                }
            }
            if (isset($_FILES['_apiclient_key']['tmp_name']) && file_exists($_FILES['_apiclient_key']['tmp_name'])) {
                $path = $path1 . 'apiclient_key.pem';
                if (move_uploaded_file($_FILES['_apiclient_key']['tmp_name'], $path)) {
                    p_update('config', array('value' => $path), array('key' => 'apiclient_key'));
                }
            }
        }

        $initial = function ($key, $default = 0){
            $config = p_get('config', array('key' => $key));
            if (empty($config)) {
                $config = array(
                    'key' => $key,
                    'value' => $default
                );
                p_insert('config', $config);
            }
            return $config;


        };
        foreach ($kyes[$op] as $value) {
            $k = $value['key'];
            $$k = $initial($k, $value['default']);
        }

        if ($op == 'bag') {
            $cash_explain['value'] = $cash_explain['value'] ? json_decode($cash_explain['value'], true) : [ 0 => ''];
        }

        if ($op == "ordinary") {
            $system_notice['value'] = $system_notice['value'] ? json_decode($system_notice['value'], true) : [ 0 => ''];
        }
        $follow_cash  = follow_cash();
        $apiclient_cert = p_get('config', array('key' =>'apiclient_cert'));
        $apiclient_key = p_get('config', array('key' =>'apiclient_key'));

        $isDomain = $this->step->getCurrentDomain() ? 1 : 0;
        include $this->template('config');
    }

    public function uploadFile()
    {
        $path1 = ATTACHMENT_ROOT . $this->w['uniacid'] . 'cert/';
        if (!file_exists($path1)) {
            @mkdir($path1,0777,true);
        }

        if (isset($_FILES['apiclient_cert']['tmp_name']) && file_exists($_FILES['apiclient_cert']['tmp_name'])) {
            $path = $path1 . 'apiclient_cert.pem';
            if (move_uploaded_file($_FILES['apiclient_cert']['tmp_name'], $path)) {
                p_update('config', array('value' => $path), array('key' => 'apiclient_cert'));
            }
        }
        if (isset($_FILES['apiclient_key']['tmp_name']) && file_exists($_FILES['apiclient_key']['tmp_name'])) {
            $path = $path1 . 'apiclient_key.pem';
            if (move_uploaded_file($_FILES['apiclient_key']['tmp_name'], $path)) {
                p_update('config', array('value' => $path), array('key' => 'apiclient_key'));
            }
        }
    }
    /**
     * 操作行为
     * @param string $type
     * @return string
     */
    protected function getFunctionType($type = '')
    {
        $add = 'add';
        $operation = 'show';

        switch ($type) {
            case 'idle':
                $op = $add.$type. $operation;
                break;
            case 'other':
                $op = $add.$type. $operation;
                break;
            case 'home':
            case '':
                $op = $add.$type. $operation;
                break;
            default:
                $op = $add.$type.$operation;
        }
        return $op;

    }

    /**
     *  广告管理
     */
    public function doWebAdvertisement()
    {
        load()->func('tpl');
        /**key 就是op,
         * type就是所属类型,
         * number表示广告最大个数,
         * 0的话就表示无限个**/
        $table = prefix('jump');
        $position_define = array(
            'home' => array(
                'type'      => 1, //首页上方
                'number'    => 5
            ),
            'idle' => array(
                'type'      => 2, //闲来玩玩
                'number'    => 0
            ),
            'sign' =>array(
                array(
                    'msg'       => '签到弹窗',
                    'position'  => 6,
                    'number'    => 1,

                ),
                array(
                    'msg'       => '活动福利',
                    'position'  => 5,
                    'number'    => 0,
                ),
                array(
                    'msg'       => '签到中间',
                    'position'  => 7,
                    'number'    => 3,

                ),

            ),
            'other' => array(
                array(
                    'msg' => '首页中间',
                    'position' => 3,
                    'number' => 1
                ),
                array(
                    'msg' => '首页弹窗',
                    'position' => 8,
                    'member' => 1,
                ),
                array(
                    'msg' => '商城上方',
                    'position' => 4,
                    'member' => 1
                ),

            )
        );
        $op = $this->get('op');
        $add = 'add';
        $operation = 'show';
        $where = " uniacid={$this->w['uniacid']}";
        if(in_array($op, array('','home', 'idle','other','sign'))) {
            if ($op == '') {
                $op = 'home';
                $positions = 1;
            }
            switch ($op) {
                case 'home':
                    $positions = 1;
                    break;
                case 'idle':
                    $positions = 2;
                    break;
                case 'sign':
                    $positions = array(5,6,7);
                    break;
                default:
                    $positions = array(3,4,8);
            }
            $behavior =  $add . $op . $operation;
            if (is_array($positions)) {
                $positionString = implode(',', $positions);
                $where .= " AND position in ({$positionString})";
            } else  {
                $where .= " AND position = {$positions}";
            }
            $pindex = max(1, intval($this->get('page')));
            $psize = 20;
            $list = p_fetchall("SELECT * FROM {$table} WHERE {$where} ORDER BY sort DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
            if ($list) {
                foreach ($list as &$value) {
                    $value['icon_path'] = $this->getImage($value['icon']);
                    $value['position_place'] = $this->getPosition($value['position']);
                    $value['type_place'] = $this->getJumpType($value['type']);
                }
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
            $pager = pagination($total, $pindex, $psize);
            if ($op== 'other') {
                include $this->template('advertisement_other_list');
            }elseif ($op == 'sign') {
                include $this->template('advertisement_sign_list');
            } else{
                include $this->template('advertisement');
            }

        }
        $data = array(
            'icon'  => resource('icon'),
            'title' => $this->get('title'),
            'describe' => $this->get('describe'),
            'type' => $this->get('type'),
            'appid' => $this->get('appid'),
            'path' => $this->get('path'),
            'uniacid' => $this->w['uniacid'],
            'flow_group_id' => $this->get('flow_group_id'),
            'mode'          => 1,
            'created' => time(),
        );
        $otherData = array(
            'mode'          => $this->get('mode'),
            'position'      => $this->get('position'),
            'flow_group_id' => $this->get('flow_group_id'),
            'title'         => $this->get('title'),
            'describe'      => $this->get('describe'),
            'icon'          => resource('icon'),
            'type'          => $this->get('type'),
            'appid'         => $this->get('appid'),
            'path'          => $this->get('path'),
            'created'       => time(),
        );

        if ($op == 'settop') {
            $id = $this->get('id');
            p_update('jump', array('sort' => time()), array('id' => $id));
            json('ok');
        }
        if ($op == 'addsignshow') {
            $position = '签到';
            $position_define['sign'];
            $data['position'] = $this->get('position');
            $sign_data['flow_group_id'] = $this->get('flow_group_id');
            $sign_data_mode = array(
                'icon'  => resource('icon'),
                'type'  => $this->get('type'),
                'appid' => $this->get('appid'),
                'path'  => $this->get('path'),
                'position' => $data['position']
            );
            if (isset($sign_data['flow_group_id']) && !empty($sign_data['flow_group_id'])) {
                p_insert('jump', array('position' => $data['position'],'mode'=> 2, 'flow_group_id' => $sign_data['flow_group_id'],'created'=> time()));
                message('添加成功', $this->createWebUrl("advertisement", array('op' => 'sign')));
            } else {

                if ($sign_data_mode['type'] == 1) {
                    if (empty($sign_data_mode['appid'])) {
                        message('appId不能为空', $this->createWebUrl('advertisement', array('op' => 'sign')));
                    }
                }
                if ($data['icon']) {
                    p_insert('jump', $sign_data_mode);
                    message('添加成功', $this->createWebUrl("advertisement", array('op' => 'sign')));
                } else {
                    include $this->template('advertisement_add_sign');
                }
            }
        }
        if ($op == 'addhomeshow' || $op == 'addidleshow') {
            $position = $op == 'addhomeshow' ?  '首页上方' : '闲来玩玩';
            $data['position'] = $op == 'addhomeshow' ? 1 : 2;
            $checkNumber = $op == 'addhomeshow' ? $position_define['home']: $position_define['idle'];
            if ($data['icon']) {
                if ($checkNumber['type'] == 1 ) {
                    $where .= " AND position =1";
                    $typeCount = p_fetchall("SELECT COUNT(*) as typeCount FROM {$table} WHERE {$where} ORDER BY id DESC ");
                    if ($typeCount['typeCount'] > $checkNumber['number']) {
                        message("首页的广告不能超过{$checkNumber['number']}个", $this->createWebUrl('addshow'));
                    }
                }
                $op =  $op == 'addhomeshow' ?  'home' : 'idle';
                if ($data['type'] == 1) {
                    if (empty($data['appid'])) {
                        message('appId不能为空', $this->createWebUrl('advertisement', array('op' => $op)));
                    }
                }

                p_insert('jump', $data);
                message('添加成功', $this->createWebUrl("advertisement", array('op' => $op)));
            }
            include $this->template('advertisement_add');
        }
        if ($op == 'addothershow' ) {
            $position = '其他';
            $position_define['other'];
            if (!empty($otherData['mode'])) {
                p_insert('jump', $otherData);
                message('添加成功', $this->createWebUrl('advertisement', array('op' => 'other')));
            }
            include $this->template('advertisement_other');
        }
        if ($op == 'delete') {
            $id = $this->get('id');
            p_delete('jump', array('id' => $id));
            json(array('status' => 1));
        }
        if ($op == 'editshow') {
            $id = $this->get('id');
            $data = p_get('jump', array('id' => $id));
            $data['icon_path'] = $this->getImage($data['icon']);
            $data['position_place'] = $this->getPosition($data['position']);
            if (in_array($this->get('position'), array(5,6,7))) {
                $position = "编辑广告";
                $position_define['sign'];
                $op == 'sign';
                include $this->template('advertisement_edit_sign');
            } else {
                if ($data['title']) {
                    include $this->template('advertisement_edit');
                } else {
                    $position_define['other'];
                    $op == 'other';
                    include $this->template('advertisement_edit_other');
                }
            }
        }
        if ($op == 'sign_edit') {
            $id = $this->get('id');
            $data['position'] = $this->get('position');
            $data['mode'] = $this->get('mode');
            $data['flow_group_id'] = $this->get('flow_group_id');
            $type  = $this->get('type');
            $appId = $this->get('appid');
            if ($type == 1) {
                if (empty($appId)) {
                    message('appId不能为空', $this->createWebUrl('advertisement', array('op' => 'editshow', 'id' => $id)));
                }
            }
            $sign_data_edit = array(
                'icon' => resource('icon'),
                'position' => $data['position'],
                'type' => $type,
                'mode' => $data['mode'],
                'appid'=> $appId,
                'path' => $this->get('path'),
                'flow_group_id' => $data['flow_group_id'],
                'created'       => time(),
            );
            p_update('jump', $sign_data_edit, array('id' => $id));
            message('编辑成功', $this->createWebUrl('advertisement', array('op' => 'sign', 'id' => $id)));
        }

        if ($op == 'edit') {
            $id = $this->get('id');
            $data['position'] = $this->get('position');
            $data['mode'] = $this->get('mode');
            if ($data['mode'] == 2) {
                $other_Data = array(
                    'mode'          => $this->get('mode'),
                    'position'      => $this->get('position'),
                    'flow_group_id' => $this->get('flow_group_id'),
                    'icon'          => 0,
                    'type'          => 0,
                    'appid'         => '',
                    'path'          => '',
                    'created'       => time(),
                );
            } else {
                if ($otherData['type'] == 1 ) {
                    if (empty($otherData['appid'])) {
                        message('appId不能为空', $this->createWebUrl('advertisement', array('op' => 'editshow', 'id' => $id)));
                    }
                }
                $other_Data = $otherData;
            }

            if (in_array($data['mode'], array(1,2))) {
                if ($otherData['position'] ==1) {
                    $op = 'home';
                } elseif ($otherData['position'] ==2) {
                    $op = 'idle';
                } else {
                    $op = 'other';
                }
                p_update('jump', $other_Data, array('id' => $id));
            } else {
                if (in_array($data['position'], array(5,6,7))) {
                    $op ='sign';
                } else {
                    $op = $data['position'] == 1 ? 'home' : 'idle';
                }

                p_update('jump', $data, array('id' => $id));
            }
            message('编辑成功', $this->createWebUrl('advertisement', array('op' => $op)));
        }

    }

    /**
     * 广告位置
     * @param int $type
     * @return string
     */
    protected function getJumpType($type = 1)
    {
        switch ($type) {
            case 1:
                $type_place = "跳转到其他小程序";
                break;
            case 2:
                $type_place = "当前小程序内";
                break;
            case 3:
                $type_place = "H5";
                break;
            default:
                $type_place = '';
        }
        return $type_place;

    }
    /**
     * 返回广告位置
     * @param int $position
     * @return string
     */
    protected function getPosition($position = 1)
    {
        switch ($position){
            case 1:
                $position_place = '首页上方';
                break;
            case 2:
                $position_place = '闲来玩玩';
                break;
            case 3:
                $position_place = '首页中间';
                break;
            case 4:
                $position_place = '商城上方';
                break;
            case 5:
                $position_place = '签到活动福利';
                break;
            case 6:
                $position_place = '签到弹窗';
                break;
            case 7:
                $position_place = '签到中间';
                break;
            case 8:
                $position_place = '首页弹窗';
                break;
            default:
                $position_place = "其他";
        }
        return $position_place;
    }

    /**
     *  商品管理
     */
    public function doWebGoods()
    {
        load()->func('tpl');
        $exchange_types = array(
            1 => '活力币兑换',
            2 => '当天自己与好友步数兑换',
            3 => '步数加钱',
            4 => '活力币加钱'
        );

        $op = $this->get('op');
        $data = array(
            'goods_name'    => $this->get('goods_name'),
            'share_title'   => $this->get('share_title'),
            'category_id'   => $this->get('category_id'),
            'type'          => $this->get('type'),
            'original_price'=> $this->get('original_price'),
            'exchange_type' => $this->get('exchange_type'),
            'exchange_number' => $this->get('exchange_number'),
            'cover_image'   => resource('cover_image'),
            'is_free'       => $this->get('is_free'),
            'invitation_number' => $this->get('invitation_number', 0),
            'inventory_type'=> $this->get('inventory_type'),
            'inventory'     => $this->get('inventory'),
            'allow_number'  => $this->get('allow_number'),
            'status'        => $this->get('status'),
            'sigin'         => $this->get('sigin', 0),
            'is_exhibition' => $this->get('is_exhibition', 1),
        );
        if ($data['type'] == 4) {
            $data['appid'] = $this->get('appid');
            if (empty($data['appid'])) {
                message('第三方发货appid不能为空', $this->createWebUrl('goods', array('op' => 'addshow')));
            }
            $data['path']       = $this->get('path');
            $data['parameter']  = $this->get('parameter');
            $data['order_explain'] = $this->get('order_explain');
        }
        $siteRoot = $this->siteRoot;
        $modular_name = $this->gpc['m'];
        $upload = $siteRoot."/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=upload&m={$this->gpc['m']}&a=wxapp";
        $image = $siteRoot ."/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=image&m={$this->gpc['m']}&a=wxapp";
        $category = p_getall('category', array('is_delete' => 1));
        if ($op == 'addshow') {
            if (empty($category)) {
                message('还没有分类,请先添加分类', $this->createWebUrl('category'), array('op' => 'addshow'));
            }
            $shop = p_getall('merchant');
            include $this->template('goods_add');
        } elseif ($op == 'add') {
            if (empty($data['goods_name'])
                || empty($data['original_price'])
                || empty($data['exchange_number'])
                || empty($data['inventory'])
                || empty($data['allow_number'])
            ) {
                message('加红星的为必填项', $this->createWebUrl('goods', array('op' => 'addshow')));
            }
            if ($data['is_free'] == 2) {
                $data['free'] = $this->get('free');
                if (empty($data['free'])) {
                    message('不包邮请填写邮费', $this->createWebUrl('goods', array('op' => 'addshow')));
                }
            }
            if ($images = $this->get('images')) {
                $data['image'] = json_encode(resource('images'));
            }
            if ($introduce = $this->get('introduce')) {
                $data['introduce_type'] = $this->get('introduce_type');
                if ( $data['introduce_type'] == 1) {
                    $data['introduce'] = $this->get('introduce');
                } else {
                    $data['introduce'] = json_encode(resource('introduce'));
                }

            }
            if ($data['exchange_type'] > 2) {
                $data['money'] = $this->get('money');
                if ($data['money'] <= 0) {
                    message('请填写兑换所需的金额', $this->createWebUrl('goods', array('op' => 'addshow')));
                }
            }

            $data['shop_id'] = $this->get('shop_id');
            if ($data['type'] == 2) {
                $data['is_under_line'] = $this->get('is_under_line');
                if ($data['is_under_line'] == 2 && empty($data['shop_id'])) {
                    message('线下核销必须选择店铺', $this->createWebUrl('goods', array('op' => 'addshow')));
                }
            } elseif ($data['type'] == 3) {
                $data['bag_money'] = $this->get('bag_money');
                if (empty($data['bag_money']) || $data['bag_money'] <= 0) {
                    message('红包商品请填写金额', $this->createWebUrl('goods', array('op' => 'addshow')));
                }
            }elseif ($data['type'] == 5) {
                $data['vip_days'] = $this->get('vip_days');
                if (empty($data['vip_days']) || $data['vip_days'] <= 0) {
                    message('请填写加成天数', $this->createWebUrl('goods', array('op' => 'addshow')));
                }
            }

            $data['sort'] = time();
            p_insert('goods', $data);
            message('添加成功', $this->createWebUrl('goods'));
        } elseif ($op == 'delete') {
            $id = $this->get('id');
            p_update('goods', array('is_delete' => 2), array('id' => $id));
            json('ok');
        } elseif ($op == 'editshow') {
            $id = $this->get('id');
            $goodsInfo = p_get('goods', array('id' => $id));
            #todo 封面
            $goodsInfo['cover_image_path'] = $this->getImage($goodsInfo['cover_image']);
            #todo 商品详情轮播图片
            if ($goodsInfo['image']) {
                $image_data = json_decode($goodsInfo['image']);
                foreach ($image_data as $item ) {
                    $goodsInfo['image_data'][$item] = $this->getImage($item, true);
                }
            }
            #todo 商品描述
            if ($goodsInfo['introduce']) {
                $introduce_image_data = json_decode($goodsInfo['introduce']);
                foreach ($introduce_image_data as $value ) {
                    $goodsInfo['introduce_image_data'][$value] = $this->getImage($value, true);
                }
            }
            $goodsInfo['shop'] = $goodsInfo['shop_id'] > 0 ? p_get('merchant', array('id' => $goodsInfo['shop_id'])) : array('shop_name' => '自营');

            include $this->template('goods_edit');
        } elseif ( $op == 'edit') {
            $id = $this->get('id');
            if (empty($data['goods_name'])
                || empty($data['original_price'])
                || empty($data['exchange_number'])
                || empty($data['inventory'])
                || empty($data['allow_number'])
            ) {
                message('加红星的为必填项', $this->createWebUrl('goods', array('op' => 'editshow', 'id' => $id)));
            }
            if ($data['is_free'] == 2) {
                $data['free'] = $this->get('free');
                if (empty($data['free'])) {
                    message('不包邮请填写邮费', $this->createWebUrl('goods', array('op' => 'editshow', 'id' => $id)));
                }
            }
            if ($images = $this->get('images')) {
                $data['image'] = json_encode(resource('images'));
            }
            if ($introduce = $this->get('introduce')) {
                $data['introduce_type'] = $this->get('introduce_type');
                if ( $data['introduce_type'] == 1) {
                    $data['introduce'] = $this->get('introduce');
                } else {
                    $data['introduce'] = json_encode(resource('introduce'));
                }
            }
            if ($data['exchange_type'] > 2) {
                $data['money'] = $this->get('money');
                if ($data['money'] <= 0) {
                    message('请填写兑换所需的金额', $this->createWebUrl('goods', array('op' => 'editshow', 'id' => $id)));
                }
            } else {
                $data['money'] = 0;
            }

            $goods = p_get('goods', array('id' => $id));
            if ($goods['type'] == 3) {
                $data['bag_money'] = $this->get('bag_money');
                if (empty($data['bag_money']) || $data['bag_money'] <= 0) {
                    message('红包商品请填写金额', $this->createWebUrl('goods', array('op' => 'editshow', 'id' => $id)));
                }
            } elseif ($goods['type'] == 5) {
                $data['vip_days'] = $this->get('vip_days');
                if (empty($data['vip_days']) || $data['vip_days'] <= 0) {
                    message('请填写加成天数', $this->createWebUrl('goods', array('op' => 'editshow')));
                }
            }

            p_update('goods', $data, array('id' => $id));
            message('编辑成功', $this->createWebUrl('goods'));
        } elseif ($op == 'settop') {
            $id = $this->get('id');
            p_update('goods', array('sort' => time()), array('id' => $id));
            json('ok');
        } elseif ($op == 'sort') {
            $id = $this->get('id');
            $sort = $this->get('sort');
            $s = p_update('goods', array('sort' => $sort), array('id' => $id));
            json('ok');
        }else {
            $pindex = max(1, intval($this->get('page')));
            $psize = 20;
            $table = prefix('goods');
            $where = "is_delete=1 AND uniacid={$this->w['uniacid']}";
            $category_id = $this->get('category_id');
            if (!empty($category_id)) {
                $where .= " AND category_id={$category_id}";
            }

            $goods_name = $this->get('goods_name');
            if (!empty($goods_name)) {
                $where .= " AND goods_name LIKE '%{$goods_name}%'";
            }
            $list = p_fetchall("SELECT * FROM {$table} WHERE {$where} ORDER BY sort DESC,id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
            if ($list) {
                foreach ($list as & $value) {
                    $value['category'] = p_get('category', array('id' => $value['category_id']));
                }
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('goods');
        }
    }

    /**
     * 新增订单记录
     */
    public function doWebAdd_order()
    {
        $op = $this->get('op');
        $id = $this->get('id');
        $goods = p_get('goods', array('id' => $id));
        if ($op == 'addshow') {
            $member = p_getall('member', array('is_fictitious' =>2));
            include $this->template('add_order');
        } elseif ($op =='add') {
            $data = array(
                'goods_id' => $id,
                'member_id' => $this->get('member_id'),
                'exchange_type' => $goods['exchange_type'],
                'created' => time(),
            );
            p_insert('order', $data);
            message('添加成功', $this->createWebUrl('goods'));
        } else {
            $list = p_getall('order', array('goods_id' => $id));
            include $this->template('order');
        }

    }

    /**
     * 虚拟商品管理
     */
    public function doWebGoods_fictitious()
    {
        $op = $this->get('op');
        $table = prefix('goods_fictitious');
        $goodsId = $this->get('id');
        $c_id = $this->get('c_id');
        if (empty($goodsId)) {
            message('参数错误', $this->createWebUrl('goods'));
        }
        $goods = p_get('goods', array('id' => $goodsId));
        if (empty($goods)) {
            message('参数错误', $this->createWebUrl('goods'));
        }
        $data =  array(
            'goods_id' =>  $goodsId,
            'content'  =>  $this->get('content'),
            'is_under_line' => $goods['is_under_line'],
            'shop_id' => $goods['shop_id'],
            'created'  => time(),
        );
        if ($op == 'addshow') {
            include $this->template('goods_fictitious_add');
        } elseif ( $op == 'add') {
            if (p_get('goods_fictitious', array('content' => $data['content']))) {
                message('该兑换码已经存在', $this->createWebUrl('goods_fictitious', array('id' => $goodsId)));
            }
            p_insert('goods_fictitious', $data);
            message('添加成功', $this->createWebUrl('goods_fictitious', array('id' => $goodsId)));
        } elseif($op == 'editshow'){
            $goodData = p_get('goods_fictitious', array('id' => $goodsId));
            include $this->template('goods_fictitious_edit');
        } elseif ( $op == 'edit') {
            p_update('goods_fictitious', $data , array('id' => $goodsId));
            message('编辑成功', $this->createWebUrl('goods_fictitious'));
        }elseif($op =='delete') {
            $fictitiousInfo = p_get('goods_fictitious', array('id' => $c_id));
            if ($fictitiousInfo['status'] == 2) {
                message('兑换码已经兑换不能删除', $this->createWebUrl('goods_fictitious'));
            }
            p_delete('goods_fictitious', array('id' => $c_id));
            json(array('status' => 1));
        } elseif ($op == 'addshow_batch') {
            include $this->template('goods_addshow_batch');
        } elseif ($op == 'add_batch') {
            $number = $this->get('number');
            if ($number < 1) {
                message('请填写生成数量', $this->createWebUrl('Goods_fictitious', array('id' => $goodsId)));
            }
            $this->step->create_voucher($goods['shop_id'], $goodsId, $number, $goods['is_under_line']);
            message('生成成功', $this->createWebUrl('Goods_fictitious', array('id' => $goodsId)));
        } else{
            $pindex = max(1, intval($this->get('page')));
            $psize = 20;
            $list = p_fetchall("SELECT * FROM {$table} WHERE goods_id ={$goodsId} AND uniacid={$this->w['uniacid']} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE goods_id ={$goodsId} AND uniacid={$this->w['uniacid']}");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('goods_fictitious');
        }

    }
    /**
     * 订单管理
     */
    public function doWebOrder()
    {

        $siteRoot = $this->siteRoot;

        $member_name  = trim($this->get('member_name'));
        $goods_name = trim($this->get('goods_name'));
        $order_status  = $this->get('status');
        $start_time = $this->get('start_time');
        $end_time = $this->get('end_time');
        $pageSize_status = $this->get('pageSize');
        $status = array(
            '1' => '未发货',
            '2' => '已发货',
            '3' => '已完成',
            '4' => '已取消',
        );
        $pageSize = array(
            1 => 20,
            2 => 30,
            3 => 50,
            4 => 80,
            5 => 100,
        );
        $courier_name = $this->courier_name;
        $op = $this->get('op');

        if ($op == 'editshow') {
            $order_info = p_get('order', array('id' => $this->get('id')));
            $goodsInfo = $this->getGoods($order_info['goods_id']);
            $memberInfo = $this->getMember($order_info['member_id']);
            $order_info['goods_name'] = $goodsInfo['goods_name'];
            $order_info['member_name'] = $memberInfo['nickname'];
            //$order_info['member_address'] = $this->get_address($order_info['member_id']);
            include $this->template('order_edit');
        } elseif($op == 'edit') {
            $data['status']  = $this->get('status');
            $data['address'] = $this->get('address');
            $data['courier_number'] = $this->get('courier_number');
            if (empty($data['courier_number']) && $this->get('type') == 1 ) {
                message('实物订单物流单号必填写', $this->createWebUrl('order', array('op' => 'editshow', 'id' => $this->get('id'))), 'warning');
            }
            if ($data['status'] == 2) {
                $data['delivery_time'] =  time();
            }
            if ($data['status'] == 3) {
                $data['complete_time'] =  time();
            }
            $courier_id = (int)$this->get('courier_id');
            $courier_name=  $this->get('courier_name');
            if (!empty($courier_name)) {
                $data['courier_name'] = $courier_name;
            } else {
                $data['courier_name'] = $this->courier_name[$courier_id];
            }
            $data['created'] = time();
            p_update('order', $data, array('id' => $this->get('id')));
            $this->sendOut($this->get('id'),getConfig('xcx_title'),getConfig('send_message_id'), $data['courier_number'], $data['courier_name']);
            message('更新订单状态成功', $this->createWebUrl('order'));
        } elseif ($op == 'success') {
            $id = $this->get('id');
            p_update('order', array('status' => 3), array('id' => $id));
            message('订单完成', $this->createWebUrl('order'));
        }elseif ($op == 'cancel') {
            $id = $this->get('id');
            $orderInfo = p_get('order', array('id' => $this->get('id')));
            if ($orderInfo['exchange_type'] == 1 && $orderInfo['type'] == 1) {
                $this->step->change_currency($orderInfo['member_id'], $orderInfo['exchange_number'], 10);
            }
            p_update('order', array('status' => 4), array('id' => $id));
            message('订单取消成功', $this->createWebUrl('order'));
        } elseif($op == 'details'){
            $id = $this->get('id');
            $orderData = p_get('order', array('id' => $id));
            include $this->template('order_details');
        }elseif ($op == "courier_name") {
            $id = $this->get('id');
            $courier_name = $this->get('courier_name');
            p_update('order', array('courier_name' => $courier_name), array('id' => $id));
            $this->getOrderCourier($id);
            json(array('status' => 1));
        } elseif ($op == 'courier_number') {
            $id = $this->get('id');
            $courier_number = $this->get('courier_number');
            p_update('order', array('courier_number' => $courier_number), array('id' => $id));
            $this->getOrderCourier($id);
            json(array('status' => 1));
        } else {
            $where = '1=1';
            if (!empty($member_name)) {
                $member_info = $this->getMemberId($member_name);
                if ($member_info) {
                    $where .= " AND member_id in ({$member_info})";
                }
            }
            if ($order_status) {
                $where .= " AND status = {$order_status}";
            }
            if ($goods_name) {
               $goods_id =  $this->getGoodId($goods_name);
                $where .= " AND goods_id IN ({$goods_id})";
            }
            if ($start_time && $end_time) {
                $startTime = strtotime($start_time);
                $endTime = strtotime($end_time);
                $where .= " AND created BETWEEN {$startTime} AND {$endTime}";
            }
            $table = prefix('order');
            $pindex = max(1, intval($this->get('page')));

            $psize = $pageSize_status ? $pageSize[$pageSize_status] : 20;

            if ($this->get('op') == 'excel') {
                $list = p_fetchall("SELECT `name`,address,phone,goods_id,member_id FROM {$table} WHERE {$where} AND uniacid={$this->w['uniacid']} ORDER BY id DESC");
                if ($list) {
                    foreach ($list as &$value) {
                        $goodsInfo  = $this->getGoods($value['goods_id']);
                        $memberInfo = $this->getMember($value['member_id']);
                        $value['shop_name'] = $goodsInfo['goods_name'];
                        $value['member_name'] = $memberInfo['nickname'];
                        $value['user_name'] = $value['name'];
                        $value['user_address'] = $value['address'];
                        $value['user_phone'] = $value['phone'];
                        unset($value['goods_id']);
                        unset($value['member_id']);
                        unset($value['name']);
                        unset($value['address']);
                        unset($value['phone']);
                    }
                }
                $name   = '订单信息';
                $header = array('商品名称','会员昵称','姓名','地址','联系电话');
                $this->excelExport($name, $header, $list);
            } else {
                if ($op == 'sendOut') {
                    $where .= " AND type =1 AND status =1";
                }
                //echo  "SELECT * FROM {$table} WHERE {$where} AND uniacid={$this->w['uniacid']} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize;
                $list = p_fetchall("SELECT * FROM {$table} WHERE {$where} AND uniacid={$this->w['uniacid']} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
                if ($list) {
                    foreach ($list as &$value) {
                        $goodsInfo  = $this->getGoods($value['goods_id']);
                        $memberInfo = $this->getMember($value['member_id']);
                        $value['shop_name'] = $goodsInfo['goods_name'];
                        $value['member_name'] = $memberInfo['nickname'];
                        $value['status_name'] = $this->getOrderStatus($value['status']);
                    }
                }
                $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where} AND uniacid={$this->w['uniacid']}");
                $pager = pagination($total, $pindex, $psize);
                $pathInfo = pathinfo($siteRoot);
                $isCustom = $this->custom_user[$pathInfo['basename']];
            }

            if ($op == 'sendOut') {
                include $this->template('send_order');
            } else {
                if ($isCustom) {
                    include $this->template($isCustom);
                } else {
                    include $this->template('order');
                }
            }


        }
    }

    /**
     * 订单发货信息
     * @param $orderId
     * @return array
     */
    protected function getOrderCourier($orderId)
    {
        $orderInfo =p_get('order', array('id' => $orderId));
        if ($orderInfo['courier_name'] && $orderInfo['courier_number']) {
            return p_update('order',array('status' => 2), array('id' => $orderId));
        }
        return true;
    }
    /**
     * 订单管理
     */
    public function doWebMoney_log()
    {
        $op = $this->get('op');
        $member_name  = $this->get('member_name');
        $money_log_status  = $this->get('status');
        $status = array(
            '1' => '充值',
            '2' => '邮费',
            '3' => '领取红包',
            '4' => '提现',
            '5' => '兑换商品',
            '6' => '兑换红包商品'
        );
        $table = prefix('money_log');
        $pindex = max(1, intval($this->get('page')));
        $psize = 20;
        $where = '1=1';
        if (!empty($member_name)) {
            $member_info = $this->getMemberName($member_name);
            if ($member_info) {
                $where .= " AND member_id = {$member_info['id']}";
            }
        }
        if ($money_log_status) {
            $where .= " AND type = {$money_log_status}";
        }

        $list = p_fetchall("SELECT * FROM {$table} WHERE {$where} AND uniacid={$this->w['uniacid']} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
        if ($list) {
            foreach ($list as & $val ) {
                $memberInfo = $this->getMember($val['member_id']);
                $val['member_name'] = $memberInfo['nickname'];
                $val['status_name'] = $this->getMoneyLogStatus($val['type']);
            }
        }

        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where} AND uniacid={$this->w['uniacid']}");
        $pager = pagination($total, $pindex, $psize);
        include $this->template('money_log');
    }
    /**
     *
     * 地址信息
     * @param $member_id
     * @return bool
     */
    public function get_address($member_id)
    {
        $table = prefix('address');
        return p_fetchall("SELECT * FROM {$table} WHERE member_id = {$member_id}  ORDER BY address_id DESC");
    }

    /**
     *  提现管理
     */
    public function doWebCash()
    {
        $op = $this->get('op');

        if ($op == 'sgree') {
            $id = $this->get('id');
            $cash = p_get('withdrawals', array('id' => $id));
            if ($cash['status'] > 1) {
                json(1);
            }
            pdo_begin();
            try{
                $this->setPayment();
                if (!p_update('withdrawals' , array('status' => 2, 'success_time' => time()), array('id' => $id, 'status' => 1))) {
                    throw new Exception('提现失败');
                }
                if (!$this->step->cash($id)) {
                    throw new Exception($this->step->error);
                }
                pdo_commit();
                json(1);
            } catch (Exception $e) {
                pdo_rollback();
                json($e->getMessage(), 0);
            }

        } elseif ($op == 'refuse') {
            $id = $this->get('id');
            $cash = p_get('withdrawals', array('id' => $id));
            if ($cash['status'] > 1) {
                json(1);
            }
            p_update('withdrawals', array('status' => 3), array('id' => $id));
            json(1);
        } else {
            $table = prefix('withdrawals');
            $where = "uniacid=" . UNIACID;

            $status = $this->get('status', 0);
            if ($status > 0) {
                $where .= " AND status={$status}";
            }

            $pindex = max(1, intval($this->get('page')));
            $psize = 20;

            $list = p_fetchall("SELECT * FROM {$table} WHERE {$where}  ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);

            if ($list) {
                foreach ($list as & $value) {
                    $value['member'] = p_get('member', array('id' => $value['member_id']));
                    $value['parent'] = $this->getMemberInvite($value['member_id']);
                }
            }

            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
            $pager = pagination($total, $pindex, $psize);

            include $this->template('cash');
        }
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


    /**
     * 任务日志
     */
    public function get_currency_log()
    {

    }
    /**
     * 邀请记录
     */
    public function get_goods_share()
    {

    }
    /**
     * 资金日志
     */
    public function get_money_log()
    {

    }
    /**
     * 充值
     */
    public function get_pay_order()
    {

    }
    /**
     * 会员签到记录
     */
    public function getSignIn()
    {

    }
    /**
     * 会员步数
     * step  今天的步数  currency 活力比
     */
    public function getMemberToday()
    {
        $table = prefix('today');
    }
    /**
     * 会员体现列表
     * @param $member_id
     */
    public function getMemberWithdrawals($member_id)
    {
        $where = '1=1';
        if ($member_id) {
            $where .= " AND member_id= {$member_id}";
        }
        $table = prefix('withdrawals');
        $pindex = max(1, intval($this->get('page')));
        $psize = 20;

        $list = p_fetchall("SELECT * FROM {$table} WHERE {$where} AND uniacid={$this->w['uniacid']} ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
    }
    /**
     * 获取用户信息
     * @param $name
     * @return array
     */
    protected function getMemberName($name)
    {
        $table = prefix('member');
        $where = '1=1';
        $where .= " AND nickname LIKE '%{$name}%'";
        return p_fetch("SELECT * FROM {$table} WHERE {$where} ORDER BY id DESC ");
    }

    /**
     * 订单状态
     * @param int $status
     * @return string
     */
    protected function getMoneyLogStatus($status = 0)
    {
        switch ($status) {
            case 1:
                $statusString  = '充值';
                break;
            case 2:
                $statusString = '邮费';
                break;
            case 3:
                $statusString ='领取红包';
                break;
            case 4:
                $statusString ='提现';
                break;
            case 5:
                $statusString ='兑换商品';
                break;
            case 6:
                $statusString ='兑换红包商品';
                break;
            default:
                $statusString ='记录异常';
        }
        return $statusString;
    }

    /**
     * 订单状态
     * @param int $status
     * @return string
     */
    protected function getOrderStatus($status = 0)
    {
        switch ($status) {
            case 1:
                $statusString  = '未发货';
                break;
            case 2:
                $statusString = '已发货';
                break;
            case 3:
                $statusString ='已完成';
                break;
            default:
                $statusString ='已取消';
        }
        return $statusString;
    }
    /**
     * 获取商品
     * @param $goods_id
     * @return bool
     */
    protected function getGoods($goods_id)
    {
        return p_get('goods', array('id' => $goods_id));
    }

    /**
     * 获取商品id
     * @param $goods_name
     * @return bool
     */
    protected function getGoodId($goods_name)
    {
        $table = prefix('goods');
        $where = '1=1';
        $where .= " AND goods_name LIKE '%{$goods_name}%' AND is_delete = 1";
        $goodsIds =  p_fetchall("SELECT id FROM {$table} WHERE {$where} ORDER BY id DESC ");
        if ($goodsIds) {
            foreach ($goodsIds as $item) {
                $goodsId[] =$item['id'];
            }
            return implode(',', $goodsId);
        }
        return '';
    }


    /**
     * 获取商品id
     * @param $member_name
     * @return bool
     */
    protected function getMemberId($member_name)
    {
        $table = prefix('member');
        $where = '1=1';
        $where .= " AND nickname LIKE '%{$member_name}%' AND is_fictitious = 1";
        $memberInfo =  p_fetchall("SELECT id FROM {$table} WHERE {$where} ORDER BY id DESC ");
        if ($memberInfo) {
            foreach ($memberInfo as $item) {
                $member_id[] =$item['id'];

            }
            return implode(',', $member_id);
        }
        return '';
    }

    /**
     * 获取会员信息
     * @param $member_id
     * @return bool
     */
    protected function getMember($member_id)
    {
        return p_get('member', array('id' => $member_id));
    }
    /**
     * 商品分类
     */
    public function doWebCategory()
    {
        $state = array(
            1 => '首页',
            2 => '步数商城',
            3 => '首页和步数商城',
            4 => '不显示'
        );
        $op = $this->get('op');

        if ($op == 'addshow') {
            include $this->template('category_add');
        } elseif ($op == 'add') {
            $category_name = $this->get('category_name');
            $category_describe = $this->get('category_describe');
            $states = $this->get('states');
            if (empty($category_name)) {
                message('类名必填', $this->createWebUrl('category', array('op' => 'addshow')));
            }
            $sort = time();
            p_insert('category', compact('category_name', 'category_describe', 'states', 'sort'));
            message('添加成功', $this->createWebUrl('category'));
        } elseif ($op == 'delete') {
            $id = $this->get('id');
            p_update('category', array('is_delete' => 2), array('id' => $id));
            json('ok');
        } elseif ($op == 'editshow') {
            $id = $this->get('id');
            if (!$category = p_get('category', array('id' => $id))) {
                message('分类不存在', $this->createWebUrl('category'));
            }
            include $this->template('category_edit');
        } elseif ($op == 'edit') {
            $category_name = $this->get('category_name');
            $category_describe = $this->get('category_describe');
            $states = $this->get('states');
            $id = $this->get('id');

            if (empty($category_name)) {
                message('类名必填', $this->createWebUrl('category', array('op' => 'editshow', 'id' => $id)));
            }
            p_update('category', compact('category_name', 'category_describe', 'states'), array('id' => $id));
            message('修改成功', $this->createWebUrl('category'));
        } elseif ($op == 'settop') {
            $id = $this->get('id');
            p_update('category', array('sort' => time()), array('id' => $id));
            json(1);
        }
        else {
            $table = prefix('category');
            $pindex = max(1, intval($this->get('page')));
            $psize = 20;

            $list = p_fetchall("SELECT * FROM {$table} WHERE is_delete=1 AND uniacid={$this->w['uniacid']} ORDER BY sort DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE is_delete=1 AND uniacid={$this->w['uniacid']}");
            $pager = pagination($total, $pindex, $psize);

            include $this->template('category');
        }
    }


    /**
     * 常见问题
     */
    public function doWebQuestion()
    {
        $op = $this->get('op');
        $data = array(
            'title'   => $this->get('title'),
            'content' => $this->get('content'),
            'uniacid' => $this->w['uniacid'],
            'sort' => time()
        );
        if ($op == 'addshow') {
            include $this->template('question_add');
        } elseif ($op == 'add') {
            if (empty($data['title'])) {
                message('标题必填', $this->createWebUrl('question', array('op' => 'addshow')));
            }
            p_insert('question', $data);
            message('添加成功', $this->createWebUrl('question'));
        } elseif ($op == 'editshow') {
            $id = $this->get('id');
            $question = p_get('question', array('id' => $id));
            include $this->template('question_edit');
        } elseif ($op =='edit'){
            if (empty($data['title'])) {
                message('标题必填', $this->createWebUrl('question', array('op' => 'editshow')));
            }
            $id = $this->get('id');
            p_update('question', $data, array('id' => $id));
            message('编辑成功', $this->createWebUrl('question'));
        } elseif ($op == 'delete') {
            $id = $this->get('id');
            p_delete('question', array('id' => $id));
            json(array('status' => 1));
        } elseif ($op == 'settop') {
            $id = $this->get('id');
            p_update('question', array('sort' => time()), array('id' => $id));
            json(1);
        }
        else {
            $table = prefix('question');
            $where = " uniacid={$this->w['uniacid']}";
            $pindex = max(1, intval($this->get('page')));
            $psize = 20;
            $list = p_fetchall("SELECT * FROM {$table} WHERE {$where} ORDER BY sort DESC,id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
            $pager = pagination($total, $pindex, $psize);

            include $this->template('question');
        }

    }

    /**
     * 任务管理
     * @return bool|null
     */
    public function doWebTask( )
    {
        load()->func('tpl');
        $siteRoot = $this->siteRoot;
        $table = prefix('task');
        $modular_name = $this->gpc['m'];
        $upload = $siteRoot. "/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=upload&m={$this->gpc['m']}&a=wxapp";
        $image = $siteRoot. "/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=image&m={$this->gpc['m']}&a=wxapp";
        $op = $this->get('op');
        $data = array(
            'uniacid'   => $this->w['uniacid'],
            'title'     => $this->get('title'),
            'describe'  => $this->get('describe'),
            'icon'      => resource('icon'),
            'currency'  => $this->get('currency'),
            'appid'     => $this->get('appid'),
            'path'      => $this->get('path'),
            'type'      => 2,
            'is_home'   => $this->get('is_home'),
            'created'   => time()
        );
        $id = $this->get('id');
        if ($op == 'addshow') {
            include $this->template('task_add');

        } elseif ($op == 'add') {
            if ($data['is_home'] == 2 ) {
                p_update('task',array('is_home' => 1));
            }
            p_insert('task', $data);
            message('添加成功', $this->createWebUrl('task'));
        }elseif($op == 'delete') {
            p_delete('task', array('id' => $id));
            json(array('status' => 1));
        }elseif ($op == 'editshow'){
            $task = p_get('task', array('id' => $id));
            $task['icon_path'] = $this->getImage($task['icon']);
            include $this->template('task_edit');
        }elseif ($op =='edit') {
            if ($data['is_home'] == 2 ) {
                p_update('task',array('is_home' => 1));
            }
            p_update('task', $data, array('id' => $id));
            message('编辑成功', $this->createWebUrl('task'));
        }elseif ($op == 'settop') {
            $id = $this->get('id');
            p_update('task', array('sort' => time()), array('id' => $id));
            json(1);
        }else{
            $where = " uniacid={$this->w['uniacid']}";
            $pindex = max(1, intval($this->get('page')));
            $psize = 20;

            $list = p_fetchall("SELECT * FROM {$table} WHERE $where ORDER BY sort DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);

            if ($list) {
                foreach ($list as &$item) {
                    $item['icon_path'] = $this->getImage($item['icon']);
                }
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE  $where");
            $pager = pagination($total, $pindex, $psize);

            include $this->template('task');
        }

    }


    /**
     * 用户列表
     */
    public function doWebMember()
    {
        $pathInfo = pathinfo($this->siteRoot);
        $isCustom = $this->custom_user[$pathInfo['basename']];
        $op = $this->get('op');
        /**
         * 邀请好友
         */
        $table = prefix('member', true);
        $pindex = max(1, intval($this->get('page')));
        $psize = 20;

        $where = "uniacid={$this->w['uniacid']} AND is_fictitious = 1";
        $nickname = $this->get('nickname', '');
        if (!empty($nickname)) {
            $where .= " AND nickname LIKE '%{$nickname}%'";
        }
        $id = $this->get('id');
        $member_id = $this->get('member_id');
        if ($member_id) {
            $where .= " AND id = {$member_id}";
        }
        $member_status = $this->get('member_status');
        if (in_array($member_status, [1,2])) {
            $where .= " AND status = {$member_status}";
        } elseif ($member_status == 3) {
            $where .= " AND isvip = 1";
        }
        $time = $this->get('time');
        if ($time)  {
            $startTime = strtotime($time['start']);
            $startEnd   = strtotime($time['end']);
            $where .= " AND add_time between {$startTime} AND {$startEnd}";
        }

        $sort = "id DESC";
        if ($op == 'money_sort') {
            $sort = "money DESC";
        }
        if ($op == 'money_r_sort') {
            $sort = "money ASC";
        }
        if ($op == 'currency_sort') {
            $sort = "currency DESC";
        }
        if ($op == 'currency_r_sort') {
            $sort = "currency ASC";
        }


     /*   if ($op == 'inviteNumber_sort') {
            $sort = "a_few DESC";
        }
        if ($op == 'inviteNumber_r_sort') {
            $sort = "a_few ASC";
        }*/

        if ($op == 'invite_number') {
            if ($id) {
                $where .= " AND parent_id = {$id}";
            }
            $list = pdo_fetchall('SELECT * FROM '.$table." WHERE {$where} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
            if ($list) {
                foreach ($list as &$value) {
                    $value['red_packets']  = $this->getMemberBag($value['id']);
                    $value['invite_number'] = $this->getMemberInvite($value['id']);
                }
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('member_invite');
            /**
             * 活力币
             */
        } elseif ($op == 'currency') {
            $data = $this->step->currency_log($id, $pindex, $psize);
            if ($data) {
                foreach ($data as & $value) {
                    $memberInfo = $this->getMember($value['member_id']);
                    $value['member_nickname'] = $memberInfo['nickname'];
                    $value['type_string'] = $this->getTypeString($value['type']);
                }
            }
            $currencyTable = prefix("currency_log");
            $currencyWhere = "uniacid={$this->w['uniacid']} AND member_id = {$id}";
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$currencyTable} WHERE {$currencyWhere}");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('member_currency');

            /**
             * 红包
             */
        } elseif ($op == 'red_packets') {
            $table = prefix('bag');
            $bagWhere =  "uniacid={$this->w['uniacid']} ";
            if ($id) {
                $bagWhere .= " AND  member_id ={$id}";
            }
            $list = pdo_fetchall('SELECT * FROM '.$table." WHERE {$bagWhere} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
            if ($list) {
                foreach ($list as & $value) {
                    $memberInfo = $this->getMember($value['member_id']);
                    $value['nickname'] = $memberInfo['nickname'];
                }
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$bagWhere}");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('member_red_packets');
            /**
             * 拉黑
             */
        } elseif($op == 'shielding') {
            p_update('member', array('status' => 2), array('id' => $id));
            message('拉黑成功', $this->createWebUrl('member'));
            include $this->template('member');
        } elseif ($op == 'cancel'){
            p_update('member', array('status' => 1), array('id' => $id));
            message('取消拉黑成功', $this->createWebUrl('member'));
            include $this->template('member');
        } elseif ($op == 'shop') {
            $shop = p_getall('merchant');
            if (empty($shop)) {
                message('请先添加店铺', $this->createWebUrl('shop'));
            }
            $member_id = $this->get('id');
            if($isCustom) {
                include $this->template('member_shop_whatever');
            } else {
                include $this->template('member_shop');
            }

        } elseif ($op == 'relation') {
            $id = $this->get('id');
            $shop_id = $this->get('shop_id');

            p_update('member', array('shop_id' => $shop_id), array('id' => $id));
            message('关联成功', $this->createWebUrl('member'));
        } elseif ($op == 'cancel_shop') {
            $id = $this->get('id');
            p_update('member', array('shop_id' => 0), array('id' => $id));
            json(1);
        } elseif ($op == 'userTodayStep'){
            $id = $this->get('id');
            $stepWhere = "uniacid={$this->w['uniacid']}";
            if ($id) {
                $stepWhere .= " AND member_id = {$id}";
            }
            $tableStep = prefix('today');
            $list = pdo_fetchall('SELECT * FROM '.$tableStep." WHERE {$stepWhere} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
            if ($list) {
                foreach ($list as &$value) {
                    $value['nickname']  = $this->getMember($value['member_id']);
                }
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$tableStep} WHERE {$stepWhere}");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('member_today');
        } else {
            $sql_default = 'SELECT * FROM '.$table." WHERE {$where} ORDER BY {$sort} LIMIT ".($pindex - 1) * $psize.','.$psize;
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
            if ($op == 'redPackets_sort') {
                $sql_default = 'SELECT A.*,(select count(*) from ims_bh_st_bag where member_id=A.id) as bag_count FROM ims_bh_st_member as A  ORDER BY bag_count DESC LIMIT '.($pindex - 1) * $psize.','.$psize;
            }
            if ($op == 'redPackets_r_sort') {
                $sql_default = 'SELECT A.*,(select count(*) from ims_bh_st_bag where member_id=A.id) as bag_count FROM ims_bh_st_member as A  ORDER BY bag_count ASC LIMIT '.($pindex - 1) * $psize.','.$psize;
            }

            if ($op == 'inviteNumber_sort') {
                $sql_default = 'SELECT A.*,(select count(*) from ims_bh_st_member where parent_id=A.id) as parent_count FROM ims_bh_st_member as A  ORDER BY parent_count DESC LIMIT '.($pindex - 1) * $psize.','.$psize;
            }
            if ($op == 'inviteNumber_r_sort') {
                $sql_default = 'SELECT A.*,(select count(*) from ims_bh_st_member where parent_id=A.id) as parent_count FROM ims_bh_st_member as A  ORDER BY parent_count ASC LIMIT '.($pindex - 1) * $psize.','.$psize;
            }

            $list = pdo_fetchall($sql_default);
            if ($list) {
                foreach ($list as &$value) {
                    $value['red_packets']  = $this->getMemberBag($value['id']);
                    $value['invite_number'] = $this->getMemberInvite($value['id']);
                    $value['invite_people'] = $this->getMember($value['parent_id']);
                    if ($value['shop_id'] > 0) {
                        $value['shop'] = p_get('merchant', array('id' => $value['shop_id']));
                    }
                }
            }
            $pager = pagination($total, $pindex, $psize);
            $todayMember = $this->getTodayEffectiveMember($time);
            include $this->template('member');
            /*if ($isCustom) {
                include $this->template($isCustom);
            }else{
                include $this->template('member');
            }*/

        }
    }

    /**
     * 首页引导
     */
    public function doWebHomeGuide()
    {
        $op = $this->get('op');
        if ($op == 'addshow') {
            include $this->template('add_home_guide');
        } elseif ($op == "add" || $op == 'edit') {
            $id = $this->get('id');
            $data = array(
                'type'      => $this->get('type'),
                'icon'      => resource('icon'),
                'created'   => time(),
            );
            if ($data['type'] ==2) {
                $data['guide_image'] = resource('guide_image');
            }
            if ($id) {
                p_update('home_guide', $data, array('id' => $id));
                message('修改成功', $this->createWebUrl('homeGuide'));
            } else {
                p_insert('home_guide', $data);
                message('添加成功', $this->createWebUrl('homeGuide'));
            }
        } elseif ($op == "editshow") {
            $id = $this->get('id');
            $homeGuide = p_get('home_guide', array('id' => $id));
            include $this->template('edit_home_guide');
        } elseif ($op == "delete") {
            p_delete('home_guide', array('id' => $this->get('id')));
            json(1);
        } else{
            $where = " uniacid={$this->w['uniacid']}";
            $pindex = max(1, intval($this->get('page')));
            $psize = 20;
            $table = prefix('home_guide');
            $list = p_fetchall("SELECT * FROM {$table} WHERE $where ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize.','.$psize);

            if ($list) {
                foreach ($list as &$item) {
                    $item['icon_path'] = $this->getImage($item['icon']);
                    $item['guide_image_path'] = $this->getImage($item['guide_image']);
                }
            }
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE  $where");
            $pager = pagination($total, $pindex, $psize);
            include $this->template('home_guide');
        }
    }

    public function doWebFictitious()
    {
        $table = prefix('member', true);
        $where = "uniacid={$this->w['uniacid']} AND is_fictitious = 2";
        $pindex = max(1, intval($this->get('page')));
        $psize = 20;
        $siteRoot = $this->siteRoot;
        $modular_name = $this->gpc['m'];
        $upload = $siteRoot . "/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=upload&m={$this->gpc['m']}&a=wxapp";
        $image = $siteRoot . "/app/index.php?i={$this->w['uniacid']}&c=entry&op=receive_card&do=image&m={$this->gpc['m']}&a=wxapp";
        $op = $this->get('op');
        if ($op == "addshow") {
            include $this->template('fictitious_add');
        } elseif ($op == 'add') {
            $data = array(
                'nickname' => $this->get('nickname'),
                'head'     => tomedia($this->get('head')),
                'currency' => $this->get('currency'),
                'is_fictitious' => 2
            );
            p_insert('member',$data);
            message('添加成功', $this->createWebUrl('fictitious'));
        } elseif ($op == 'edit_show'){
            $id = $this->get('id');
            $memberData = p_get('member', array('id' => $id));
            include $this->template('fictitious_edit');
        } elseif ($op == 'edit') {
            $head = (int)$this->get('head');
            if ($head == 0) {
                $head_img = $this->get('head');
            } else {
                $head_img = $this->get('head');
            }
            $data = array(
                'nickname' => $this->get('nickname'),
                'head'     => tomedia($head_img),
                'currency' => $this->get('currency')
            );
            p_update('member', $data, array('id' => $this->get('id')));
            message('修改成功', $this->createWebUrl('fictitious'));
        } elseif ($op == 'delete') {
            $id = $this->get('id');
            p_delete('member', array('id' => $id));
            json('1');
        }else {
            $list = pdo_fetchall('SELECT * FROM '.$table." WHERE {$where} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
/*
            if ($list) {
                foreach ($list as &$value) {
                    $value['red_packets']  = $this->getMemberBag($value['id']);
                    $value['invite_number'] = $this->getMemberInvite($value['id']);
                    if ($value['shop_id'] > 0) {
                        $value['shop'] = p_get('merchant', ['id' => $value['shop_id']]);
                    }
                }
            }*/
            $pager = pagination($total, $pindex, $psize);
            include $this->template('fictitious');

        }

    }

    /**
     * 常见问题列表页面
     */
    public function doWebQuestionList()
    {
        $table = prefix('question');
        $where = "uniacid = {$this->w['uniacid']}";
        $list = pdo_fetchall('SELECT * FROM '.$table." WHERE {$where} ORDER BY id DESC ");
        $product_name = getConfig('xcx_title', '步赚');
        include $this->template('question_list');
    }

    /**
     * 了解活力币
     */
    public function doWebCurrency()
    {
        $currency = p_get('config', array('key' => 'realize_currency'));
        include $this->template('currency');
    }
    /**
     * 活动说明
     */
    public function doWebActivity()
    {
        $activity = p_get('config', array('key' => 'activity'));
        include $this->template('activity');
    }


    /**
     * 店铺管理
     */
    public function doWebShop()
    {
        $pathInfo = pathinfo($this->siteRoot);
        $isCustom = $this->custom_user[$pathInfo['basename']];
        $op = $this->get('op');

        if ($op == 'addshow') {
            if ($isCustom) {
                $status = array(
                    1 => '待审核',
                    2 => '审核通过',
                    3 => '审核不通过',
                );
                include $this->template('shop_add_whatever');
            } else {
                include $this->template('shop_add');
            }
        } elseif ($op == 'add') {
            $shop_name = $this->get('shop_name');
            $locality = $this->get('locality');
            if (empty($shop_name)) {
                message('商户名不能为空', $this->createWebUrl('shop', array('op' => 'addshow')));
            }
            $data = array(
                'shop_name' => $shop_name,
                'locality'  => $locality,
                'created'   => time()
            );
            p_insert('merchant', $data);
            message('添加成功', $this->createWebUrl('shop'));
        } elseif ($op == 'editshow') {
            $id = $this->get('id');
            $shop = p_get('merchant', array('id' => $id));
            if (empty($shop)) {
                message('商户不存在', $this->createWebUrl('shop'));
            }
            include $this->template('shop_edit');
        } elseif ($op == 'edit') {
            $id = $this->get('id');
            $shop_name = $this->get('shop_name');
            $locality = $this->get('locality');
            if (empty($shop_name)) {
                message('商户名不能为空', $this->createWebUrl('shop', array('op' => 'edit', 'id' => $id)));
            }
            p_update('merchant', array('shop_name' => $shop_name, 'locality' => $locality), array('id' => $id));
            message('编辑成功', $this->createWebUrl('shop'));
        } elseif ($op == 'delete') {
            $id = $this->get('id');
            p_delete('merchant', array('id' => $id));
            json('1');
        }

        else {
            $table = prefix('merchant');
            $where = "uniacid = {$this->w['uniacid']}";

            $pindex = max(1, intval($this->get('page')));
            $psize = 20;

            $list = pdo_fetchall('SELECT * FROM '.$table." WHERE {$where} ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
            $total = pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");

            $pager = pagination($total, $pindex, $psize);
            include $this->template('shop');
        }
    }

    public function getTypeString($type =1)
    {
        switch ($type){
            case 1:
                $type_string = '完成任务';
                break;
            case 2:
                $type_string = '步数换取';
                break;
            case 3:
                $type_string = '签到';
                break;
            case 4:
                $type_string = '邀请好友';
                break;
            case 5:
                $type_string = '领取红包';
                break;
            case 6:
                $type_string = '授权';
                break;
            case 7:
                $type_string = '关注公众号';
                break;
            case 8:
                $type_string = '分享到群';
                break;
            case 9:
                $type_string = '兑换商品';
                break;
            case 10:
                $type_string = '取消订单';
                break;
            default:
                $type_string = '';
        }
        return $type_string;

    }
    /**
     * 返回用户红包个数
     * @param $member_id
     * @return int
     */
    protected function getMemberBag($member_id)
    {
        $table = prefix('bag');
        $where = '1=1';
        if ($member_id) {
            $where  .= " AND member_id = {$member_id} AND uniacid = {$this->w['uniacid']} ";
        }
        return pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
    }

    /**
     * 一天的有效用户
     * @param string $times
     * @return bool
     */
    protected function getTodayEffectiveMember($times = '')
    {
        if ($times) {
            $time['star'] = strtotime($times['start']);
            $time['end']  = strtotime($times['end']);
        } else {
            $time = $this->getLastTime();
        }
        $table = prefix('member');
        $where   = "  add_time between {$time['star']} AND {$time['end']}  AND uniacid = {$this->w['uniacid']} AND head != '' ";
        return pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
    }

    /**
     * 获取一天的开始时间和结束时间
     * @return mixed
     */
    protected function getLastTime()
    {
        $str = date("Y-m-d", strtotime("-1 day")) . " 0:0:0";
        $data["star"] = strtotime($str);
        $str = date("Y-m-d", strtotime("-1 day")) . " 24:00:00";
        $data["end"] = strtotime($str);
        return $data;
    }

    /**
     * 发货消息
     * @param   $id                    商品id
     * @param   $smallProgram          小程序名字
     * @param   $bag_template_id       模板消息id
     * @param   $courier_number        物流单号
     * @param   $courier_name          物流公司
     */
    protected function sendOut($id, $smallProgram,$bag_template_id, $courier_number, $courier_name)
    {
        $orderRs = p_get('order', array('id' => $id));
        if($orderRs['status'] == 2){
            $option = array(
                'appid' => $this->w['uniaccount']['key'],
                'secret' => $this->w['uniaccount']['secret'],
            );
            $wxObj = new Wxadoc($option);
            set_time_limit(0);
            $t = time() - (7 * 86400);
            $form_id = p_fetch("SELECT * FROM " . prefix('form_id') . " WHERE member_id={$orderRs['member_id']} AND created > {$t}");
            $goods_title = p_getcolumn('goods', array('id' => $orderRs['goods_id']), "goods_name");
            //发送提醒
            $content = array(
                'keyword1' => array('value' => $goods_title),
                'keyword2' => array('value' => $courier_number),
                'keyword3' => array('value' => $courier_name),
                'keyword4' => array('value' => "您在【{$smallProgram}】小程序兑换的(".$goods_title.")商品发货了,请注意查收↓↓↓"),
                'keyword5' => array('value' => date("Y-m-d H:i:s",time()))
            );
            $openid = p_getcolumn('member', array('id' => $orderRs['member_id']), 'openid');
            $wxObj->sendTemplateMessage($openid, $bag_template_id, $content, $form_id['form_id'], 'bh_step/pages/index/index');
            p_delete('form_id', array('id' => $form_id['id']));
        }
    }

    /**
     * 获取用户的邀请数量
     * @param $member_id
     * @return bool
     */
    protected function getMemberInvite($member_id)
    {
        $table = prefix('member');
        $where = '1=1';
        if ($member_id) {
            $where  .= " AND parent_id = {$member_id} AND uniacid = {$this->w['uniacid']}";
        }
        return pdo_fetchcolumn("SELECT COUNT(*) FROM {$table} WHERE {$where}");
    }
    private function get($key, $default = '')
    {
        return isset($this->gpc[$key]) ? $this->gpc[$key] : $default;
    }

    private function getImage($route, $path = false)
    {
        if (empty($route)) {
            return '';
        }

        if (is_numeric($route)) {
            $image = p_get('resource', array('id' => $route));
            $route = $image['route'];
        }
        if ($path == true) {
            return $route;
        }
        if ($this->_is_oss()) {
            return $this->w['attachurl'] . $route;
        }

        return $this->w['siteroot'] . '/attachment/' . $route;
    }

    private function _is_oss()
    {
        $is_oss = getConfig('is_oss');
        if ($is_oss) {
            $is_oss = $is_oss['value'];
        } else {
            $is_oss = 0;
        }

        return !empty($this->w['setting']['remote']['type']) && $is_oss == 0;
    }

    public function doWebExcel()
    {
        $name   = '订单信息';
        $header = array('姓名','金额');
        $data = array(
            array('张三','20'),
            array('李四','00'),
        );
        $this->excelExport($name, $header, $data);
    }

    /**

     * excel表格导出

     * @param string $fileName 文件名称

     * @param array $headArr 表头名称

     * @param array $data 要导出的数据

     * @author static7
     */
    public function excelExport($fileName = '', $headArr = [], $data = [])
    {
        load()->library('phpexcel/PHPExcel');
        $fileName .= "_" . date("Y_m_d", time()) . ".xls";
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();
        $key = ord("A"); // 设置表头

        foreach ($headArr as $v) {

            $colum = chr($key);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);

            $key += 1;

        }

        $column = 2;

        $objActSheet = $objPHPExcel->getActiveSheet();

        foreach ($data as $key => $rows) { // 行写入

            $span = ord("A");
            foreach ($rows as $keyName => $value) { // 列写入

                $objActSheet->setCellValue(chr($span) . $column, $value);
                $span++;
            }
            $column++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表

        $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表

        header('Content-Type: application/vnd.ms-excel');

        header("Content-Disposition: attachment;filename='$fileName'");

        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        $objWriter->save('php://output'); // 文件通过浏览器下载

        exit();

    }
    


}