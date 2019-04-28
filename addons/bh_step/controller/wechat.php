<?php

class wechatApp extends bh_stepModuleWxapp {

    public function __construct() {
        parent::__construct();
        $op = $this->get('op');
        if (in_array($op, array('index'))) {
            $this->{$op}();
        }
        die(1);
    }


    public function index()
    {
        $input = file_get_contents("php://input");

        $xml = json_decode(json_encode(simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        file_put_contents(ATTACHMENT_ROOT . '1.txt', var_export($xml, true));
        switch ($xml['MsgType']) {
            case 'event':
                //关注
                if ($xml['Event'] == 'subscribe') {
                    p_insert('wechat_cash', array('open_id' => $xml['FromUserName']));
                }
        }

    }

}

