<?php/** * 小程序接口 SDK */class WxWechat {    const API_URL_PREFIX = 'https://api.weixin.qq.com';    private $error;    private $geturl;    private $appid;    private $secret;    private $access_token;    public function __construct($option = array()) {        $this->appid = getConfig('platform_appid', 'wx85a79b7f24068ce7');        $this->secret = getConfig('platform_appsecret', 'a62713b800f79cf2a0ff1f72cff4b706');    }    public function userInfo($openid)    {        $url = self::API_URL_PREFIX + '/cgi-bin/user/info?access_token=' . $this->access_token . '&openid=' . $openid . '&lang=zh_CN';        return $this->http_get($url);    }    public function subscribe($openid)    {        $userInfo = $this->userInfo($openid);        return $userInfo && $userInfo['subscribe'] == 1;    }    public function checkAuth($appid = '', $appsecret = '', $token = '') {        if (!$appid || !$appsecret) {            $appid = $this->appid;            $appsecret = $this->secret;        }        if ($token) { //手动指定token，优先使用            $this->access_token = $token;            return $this->access_token;        }        $authname = 'wx_wechat_access_token' . $appid;        if ($rs = $this->getCache($authname)) {            $this->access_token = $rs;            return $rs;        }        $data = array();        $data['grant_type'] = 'client_credential';        $data['appid'] = $appid;        $data['secret'] = $appsecret;        $result = $this->http_get(self::API_URL_PREFIX . '/cgi-bin/token', $data);        if ($result) {            $json = json_decode($result, true);            if (!$json || isset($json['errcode'])) {                $this->error = $json['errmsg'] . $json['errcode'];                return false;            }            $this->access_token = $json['access_token'];            $expire = $json['expires_in'] ? intval($json['expires_in']) - 100 : 3600;            $this->setCache($authname, $this->access_token, $expire);            return $this->access_token;        }        return false;    }    /**     * GET 请求     * @param string $url     */    private function http_get($url, $data) {        $oCurl = curl_init();        if (stripos($url, "https://") !== FALSE) {            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1        }        $param = array();        foreach ($data as $key => $val) {            if (is_array($val)) {                foreach ($val as $v) {                    $param[] = $key . '[]=' . urlencode($v);                }            } else {                $param[] = $key . "=" . urlencode($val);            }        }        $sparam = join("&", $param);        $this->geturl = $url . '?' . $sparam;        curl_setopt($oCurl, CURLOPT_URL, $url . '?' . $sparam);        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);        $sContent = curl_exec($oCurl);        $aStatus = curl_getinfo($oCurl);        curl_close($oCurl);        if (intval($aStatus["http_code"]) == 200 || !empty($sContent)) {            return str_replace(array("\n", "\r"), '', $sContent);        } else {            $this->error = intval($aStatus["http_code"]) . 'curl_error' . $this->geturl;            return false;        }    }    /**     * POST 请求     * @param string $url     * @param array $param     * @param boolean $post_file 是否文件上传     * @return string content     */    private function http_post($url, $param, $jsonpost = false, $post_file = false) {        $oCurl = curl_init();        if (stripos($url, "https://") !== FALSE) {            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1        }        if ($jsonpost) {            $strPOST = str_replace('\/', '/', json_encode($param));        } elseif (is_string($param) || $post_file) {            $strPOST = $param;        } else {            $aPOST = array();            foreach ($param as $key => $val) {                $aPOST[] = $key . "=" . urlencode($val);            }            $strPOST = join("&", $aPOST);        }        curl_setopt($oCurl, CURLOPT_URL, $url);        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);        curl_setopt($oCurl, CURLOPT_POST, true);        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);        $sContent = curl_exec($oCurl);        $aStatus = curl_getinfo($oCurl);        curl_close($oCurl);        if (intval($aStatus["http_code"]) == 200) {            return $sContent;        } else {            return false;        }    }    /**     * 设置缓存，按需重载     * @param string $cachename     * @param mixed $value     * @param int $expired     * @return boolean     */    protected function setCache($cachename, $value, $expired) {        return cache_write($cachename, $value, $expired);    }    /**     * 获取缓存，按需重载     * @param string $cachename     * @return mixed     */    protected function getCache($cachename) {        return cache_load($cachename);    }    /**     * 清除缓存，按需重载     * @param string $cachename     * @return boolean     */    protected function removeCache($cachename) {        return cache_clean($cachename);    }    public function getError() {        return $this->error;    }}