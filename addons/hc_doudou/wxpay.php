<?php
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
$postStr = file_get_contents("php://input"); // 这里拿到微信返回的数据结果
libxml_disable_entity_loader(true);
$xmlstring = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
$res = json_decode(json_encode($xmlstring),true);
// file_put_contents(IA_ROOT."/addons/hc_doudou/log",json_encode($xmlstring));
if ($res['return_code'] == 'SUCCESS' && $res['return_code'] == 'SUCCESS') {
    $trade_no  = trim($res['out_trade_no']);
    $total_fee = $res['total_fee']/100;
    $transaction_id = $res['transaction_id'];
    $openid    = $res['openid'];
    $params = array(
        'total_fee'      => $total_fee,
        'status'         => 1,
        'transaction_id' => $transaction_id,
        'paytime'        => time()
    );
    pdo_update('hcdoudou_paylog',$params,array('trade_no'=>$trade_no));
    $money = pdo_getcolumn('hcdoudou_users',array('openid'=>$openid),array('money'));
    pdo_update('hcdoudou_users',array('money'=>$money+$total_fee),array('openid'=>$res['openid']));
    //分销开始
    $weid = pdo_getcolumn('hcdoudou_users',array('openid'=>$openid),array('weid'));
    $fenxiao = json_decode(pdo_getcolumn('hcdoudou_setting',array('only'=>'fenxiao'.$weid),array('value')),'true');
    
    $userInfo = pdo_get('hcdoudou_users',array('openid'=>$openid),array('pid','uid'));
    $level_one_uid = $userInfo['pid'];
    if(!empty($level_one_uid)){
        $level_one_info = pdo_get('hcdoudou_users',array('uid'=>$level_one_uid),array('pid','level'));
        $level_one = $level_one_info['level'];
        $level_one_com = array(
            'user_id'    => $level_one_uid,
            'sub_id'     => $userInfo['uid'],
            'trade_no'   => $trade_no,
            'price'      => $total_fee,
            'rate'       => $fenxiao['commission'][$level_one-1]['commission1'],
            'profit'     => round($total_fee*$fenxiao['commission'][$level_one-1]['commission1']/100,2),
            'level'      => $level_one,
            'sort'       => 1,
            'createtime' => time()
        );
        pdo_insert('hcdoudou_commission',$level_one_com);
    }
    $level_two_uid = $level_one_info['pid'];
    if($fenxiao['level']>1 && !empty($level_two_uid)){
        $level_two_info = pdo_get('hcdoudou_users',array('uid'=>$level_two_uid),array('pid','level'));
        $level_two = $level_two_info['level'];
        $level_two_com = array(
            'user_id'    => $level_two_uid,
            'sub_id'     => $userInfo['uid'],
            'trade_no'   => $trade_no,
            'price'      => $total_fee,
            'rate'       => $fenxiao['commission'][$level_two-1]['commission2'],
            'profit'     => round($total_fee*$fenxiao['commission'][$level_two-1]['commission2']/100,2),
            'level'      => $level_two,
            'sort'       => 2,
            'createtime' => time()
        );
        pdo_insert('hcdoudou_commission',$level_two_com);
    }
    $level_thr_uid = $level_two_info['pid'];
    if($fenxiao['level']>2 && !empty($level_thr_uid)){
        $level_thr_info = pdo_get('hcdoudou_users',array('uid'=>$level_thr_uid),array('level'));
        $level_thr = $level_thr_info['level'];
        $level_thr_com = array(
            'user_id'    => $level_thr_uid,
            'sub_id'     => $userInfo['uid'],
            'trade_no'   => $trade_no,
            'price'      => $total_fee,
            'rate'       => $fenxiao['commission'][$level_thr-1]['commission3'],
            'profit'     => round($total_fee*$fenxiao['commission'][$level_thr-1]['commission3']/100,2),
            'level'      => $level_thr,
            'sort'       => 3,
            'createtime' => time()
        );
        pdo_insert('hcdoudou_commission',$level_thr_com);
    }



    echo 'success';
    return ;
}
