<?php

namespace App\Services\Auth;

class Wechat extends Base
{
    private $appID;

    private $appSecret;

    public function __construct($appID='', $appSecret='')
    {
        $this->appID     = $appID ? $appID : config('mall.wechat_minprogram.appid');
        $this->appSecret = $appSecret ? $appSecret : config('mall.wechat_minprogram.appsecret');
    }

    /**
     * [根据授权code获取 session_key 和 openid]
     *
     * @param $authcode
     *
     * @return bool | string  [失败false, openid]
     * @throws \Exception
     */
    public function getSessionKey($code)
    {
        $url    = 'https://api.weixin.qq.com/sns/jscode2session?appid='
                  . $this->appID . '&secret=' . $this->appSecret . '&js_code='
                  . $code . '&grant_type=authorization_code';
        $result = curl_request($url, 'POST');

        if (is_array($result) && $result['errcode']==0) {
            return $result;
        } else {
            return false;
        }
    }
}
