<?php
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
$postStr = file_get_contents("php://input"); // 这里拿到微信返回的数据结果
libxml_disable_entity_loader(true);
$xmlstring = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
$payreturn = json_decode(json_encode($xmlstring),true);
if ($payreturn['result_code'] == 'SUCCESS' && $payreturn['result_code'] == 'SUCCESS') {

    $ordersn = trim($payreturn['out_trade_no']);
    $params = array(
        'transaction_id'=> $payreturn['transaction_id'],
        'paytime'		=> time(),
        'status'		=> 1
    );
    pdo_update('hcdoudou_upgrade',$params,array('trade_no'=>$ordersn));

    $upgrade = pdo_get('hcdoudou_upgrade',array('trade_no'=>$ordersn));


    pdo_update('hcdoudou_users',array('level'=>$upgrade['level']),array('openid'=>$upgrade['openid']));
    echo 'success';
    return ;
}
