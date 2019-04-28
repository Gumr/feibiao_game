<?php


define('PREFIX', 'bh_st_');


function customized()
{
    $cust = array('weiqing.laonianwangxiao.cn' => array('ald'));
    return $cust['weiqing.laonianwangxiao.cn'];

}


function follow_cash()
{
    global $_W;
    return version_compare($_W['current_module']['version'], '3.5.0') >= 0;
}


function yuan_img($imgpath = './tx.jpg') {
    $ext = pathinfo($imgpath);
    $src_img = null;
    switch ($ext['extension']) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'png':
            $src_img = imagecreatefrompng($imgpath);
            break;
    }
    $wh = getimagesize($imgpath);
    $w = $wh[0];
    $h = $wh[1];
    $w = min($w, $h);
    $h = $w;
    $img = imagecreatetruecolor($w, $h);
    //这一句一定要有
    imagesavealpha($img, true);
    //拾取一个完全透明的颜色,最后一个参数127为全透明
    $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
    imagefill($img, 0, 0, $bg);
    $r = ceil($w / 2); //圆半径
    $y_x = $r; //圆心X坐标
    $y_y = $r; //圆心Y坐标
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgbColor = imagecolorat($src_img, $x, $y);
            if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                imagesetpixel($img, $x, $y, $rgbColor);
            }
        }
    }
    return $img;
}

function getImage($route, $path = false)
{
    if (empty($route)) {
        return '';
    }

    if (is_numeric($route)) {
        $image = p_get('resource', array('id' => $route));

        $route = $image['route'];
        if (empty($route)) {
            return '';
        }
    }
    if ($path == true) {
        return $route;
    }
    global $_W;

    if (is_oss()) {
        return $_W['attachurl'] . $route;
    }
    return $_W['siteroot'] . 'attachment/' . $route;
}


function resource($name, $type = 1)
{
    global $_GPC;
    if (!isset($_GPC[$name])) {
        return 0;
    }

    if (!is_array($_GPC[$name])) {
        $insert = array(
            'route' => $_GPC[$name],
            'type' => $type,
            'created' => time()
        );
        p_insert('resource', $insert);
        return pdo_insertid();
    } else {
        $insertId = array();
        foreach ($_GPC[$name] as $value) {
            $insert = array(
                'route' => $value,
                'type' => $type,
                'created' => time()
            );
            p_insert('resource', $insert);
            $insertId[] =  pdo_insertid();
        }

        return $insertId;
    }
}


function is_oss()
{
    $is_oss = getConfig('is_oss');
    if ($is_oss) {
        $is_oss = $is_oss['value'];
    } else {
        $is_oss = 0;
    }
    global $_W;

    return !empty($_W['setting']['remote']['type']) && $is_oss == 0;
}


function prefix($table, $prefix = true)
{
    return $prefix ? tablename(PREFIX . $table) : (PREFIX . $table);
}


function getConfig($key, $default = '')
{
    $config = p_get('config', array('key' => $key));
    $image = array('share_image', 'share_red_packet_image');
    if ($config && in_array($key, $image)) {
        $config['value'] = getImage($config['value']);
    }

    return $config ? $config['value'] : $default;
}

function json($info, $status = 1)
{
    $info = array(
        'info' => $info,
        'status' => $status
    );
    $info = json_encode($info, JSON_UNESCAPED_UNICODE);
    header('Content-Type: application/json;charset=utf-8');
    die($info);
}


function p_get($table, $condition, $fields = array()) {
    if (moreOpen()) {
        $condition['uniacid'] = UNIACID;
    }
    return pdo_get(PREFIX  . $table, $condition, $fields);
}

function p_getall($table, $condition = array(), $fields = array(), $keyfield = '', $orderby = array(), $limit = array())
{
    if (moreOpen()) {
        $condition['uniacid'] = UNIACID;
    }
    return pdo_getall(PREFIX . $table, $condition, $fields, $keyfield, $orderby, $limit);
}

function p_insert($table, $data = array(), $replace = FALSE)
{
    if (moreOpen()) {
        $data['uniacid'] = UNIACID;
    }
    return pdo_insert(PREFIX . $table, $data, $replace);
}

function p_getcolumn($table, $condition = array(), $field) {
    if (moreOpen()) {
        $condition['uniacid'] = UNIACID;
    }
    return pdo_getcolumn(PREFIX . $table, $condition, $field);
}

function p_delete($table, $params = array(), $glue = 'AND') {
    if (moreOpen()) {
        $params['uniacid'] = UNIACID;
    }
    return pdo_delete(PREFIX . $table, $params, $glue);
}

function p_update($table, $data = array(), $params = array(), $glue = 'AND') {
    if (moreOpen()) {
        $params['uniacid'] = UNIACID;
    }
    return pdo_update(PREFIX . $table, $data, $params, $glue);
}

function p_fetch($sql, $params = array()) {
    return pdo_fetch($sql, $params);
}

function p_fetchall($sql, $params = array()) {
    return pdo_fetchall($sql, $params);
}

function p_fetchcolumn($sql, $params = array(), $column = 0) {
    return pdo_fetchcolumn($sql, $params, $column);
}

function bh_upload($file)
{
    load()->func('file');
    $reslut = file_upload($file['file']);
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

    return $pic;
}

function moreOpen()
{
    return true;
}

function ad($advertisement)
{
    if ($advertisement['mode'] == 2) {
        return $advertisement;
    }
    $advertisement['target'] = 'self';
    $advertisement['url'] = '';
    $advertisement['open_type'] = 'navigate';

    if ($advertisement['type'] != 1) {
        $advertisement['target'] = 'self';
        $advertisement['url'] = $advertisement['path'];

        if ($advertisement['type'] == 3) {
            $advertisement['url'] = '/bh_step/pages/web/web?path=' . urlencode($advertisement['path']);

        } else {

            if (in_array($advertisement['url'], array('/bh_step/pages/index/index', '/bh_step/pages/goodsconvert/goodsconvert', '/bh_step/pages/wealthRank/wealthRank', '/bh_step/pages/my/my'))) {
                $advertisement['open_type'] = 'switchTab';
            }
        }



    } else {
        $advertisement['target'] = 'miniProgram';
        $advertisement['path'] = $advertisement['path'];
    }

    return $advertisement;
}





function formatTime($time)
{
    $rtime = date("m-d H:i", $time);
    $time = time() - $time;
    if ($time < 60) {
        $str = '刚刚';
    } elseif ($time < 60 * 60) {
        $min = floor($time / 60);
        $str = $min . '分钟前';
    } elseif ($time < 60 * 60 * 24) {
        $h = floor($time / (60 * 60));
        $str = $h . '小时前 ';
    } elseif ($time < 60 * 60 * 24 * 3) {
        $d = floor($time / (60 * 60 * 24));
        if ($d == 1) {
            $str = '昨天 ' . $rtime;
        } else {
            $str = '前天 ' . $rtime;
        }
    } else {
        $str = $rtime;
    }
    return $str;
}



/*
 * 1.经度1,纬度1，经度2,纬度2
 * 2.返回结果是单位是KM。
 * 3.保留一位小数
 */
function getDistance($lng1, $lat1, $lng2, $lat2)
{
    //将角度转为狐度
    $radLat1 = deg2rad($lat1);//deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);
    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;
    $s = 2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6371;
    return round($s,1);
}


