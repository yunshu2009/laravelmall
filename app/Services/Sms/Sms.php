<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Overtrue\EasySms\EasySms;

class Sms
{
    public static function generateVerifyCode($num = 6)
    {
        if (! $num) {
            return false;
        }

        $num = intval($num);

        $pool = '0123456789';
        $shuffled = str_shuffle($pool);

        $code = substr($shuffled, 0, $num);

        return $code;
    }

    public static function verifySmsCode($mobile, $code)
    {
        if (Cache::get('smscode:'.$mobile) == $code) {
            Cache::forget('smscode:'.$mobile);
            return true;
        }

        return false;
    }

    public static function sendSmsCode($smsArr)
    {
        if(App::environment('production')) {   // 正式环境才发送短信
            try {
                $easySms = new EasySms();
                $result  = $easySms->send($smsArr['mobile'], [
                    'template' => config('easysms.gateways.aliyun.templates.'
                                         . $smsArr['template_key']),
                    'data'     => [
                        'code' => $smsArr['code'],
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        $expiredAt = now()->addMinutes(1);

        Cache::put('smscode:'.$smsArr['mobile'], $smsArr['code'], $expiredAt);

        return true;
    }
}
