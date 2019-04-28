<?php

class merchant extends bh_stepModuleWxapp {

    public function __construct() {
        parent::__construct();
        $op = $this->get('op');
        if (in_array($op, array('index'))) {
            $this->{$op}();
        }
    }


    /**
     * 入驻申请
     */
    public function entering()
    {
        $shop_name = $this->get('shop_name');
        $salesman_name = $this->get('salesman_name');
        $phone = $this->get('phone');
        $logo = $this->get('logo', 0);
        $address = $this->get('address');

        if (empty($shop_name) || empty($salesman_name) || empty($phone) || empty($address)) {
            json('商户名称，业务员姓名，门店地址，手机号码必填', 0);
        }
        $data = array(
            'member_id' => $this->member['id'],
            'shop_name' => $shop_name,
            'salesman_name' => $salesman_name,
            'phone' => $phone,
            'logo' => $logo,
            'address' => $address,
            'updated' => time(),
            'created' => time()
        );

        if (p_insert('merchant_entering', $data)) {
            json('ok');
        }
        json('申请失败，请稍后再试', 0);
    }


    /**
     * 附近
     */
    public function nearby()
    {
        $time = time();
        $sql = "SELECT * FROM " . prefix('merchant') . " AS A LEFT JOIN " . prefix('merchant_detail') . " AS B ON A.id=B.merchant_id WHERE expiry_date>{$time} AND A.uniacid=" . UNIACID;

        $merchant = p_fetchall($sql);
        if (empty($merchant)) {
            json(array());
        }
        $longitude = $this->get('longitude');
        $latitude = $this->get('latitude');

        $order = $this->get('order');
        if ($order) {

        }

        foreach ($merchant as $value) {
            //计算距离
            if ($longitude && $latitude) {
                $value['distance'] = getDistance($value['longitude'], $value['latitude'], $longitude, $latitude);
            } else {
                $value['distance'] = 0;
            }
            //排序

            //最新

            $value['new_goods'] = p_getall('goods', array('shop_id' => $value['id']), array(), '', array('id DESC'), 3);
        }

        json($merchant);
    }


    public function merchant()
    {
        $merchant_id = $this->get('merchant_id');


    }






}

