<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Exceptions\BusinessException;
use App\Helper\CommonResult;
use App\Helper\Token;
use App\Jobs\SmsCodeSender;
use App\Services\Auth\Wechat;
use App\Services\Sms\Sms;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AuthBusiness extends BaseBusiness
{
    public static function loginByWeixin(array $attributes)
    {
        $sessionKey = '';
        $openId = '';

        try {
            $result = (new Wechat())->getSessionKey($attributes['code']);
            $sessionKey = $result['session_key'];
            $openId = $result['openid'];
        }catch (\Exception $e) {
        }

        // 测试用，todo：移除
//        $sessionKey = 'UHe2L1jmd0G+hvOverd/nw==';
//        $openId = 'oXhyn5Ot5ORjC6Uu6mtX-V00YnBo';

        if (empty($sessionKey) || empty($openId)) {
            return CommonResult::formatError(ResultCode::FAILED, '登录失败');
        }

        try {
            DB::beginTransaction();
            $user = UmsMemberBusiness::queryByOpenid($openId);
            if ( ! $user) {
                $user = [
                    'username'        => $openId,
                    'password'        => $openId,
                    'weixin_openid'   => $openId,
                    'avatar'          => $attributes['userInfo']['avatarUrl'],
                    'nickname'        => $attributes['userInfo']['nickName'],
                    'gender'          => $attributes['userInfo']['gender'],
                    'user_level'      => 0,
                    'status'          => 0,
                    'last_login_time' => date('Y-m-d H:i:s'),
                    'last_login_ip'   => ip(),
                    'session_key'     => $sessionKey,
                ];
                $user = UmsMemberBusiness::add($user);

                // 新用户发送注册优惠券
                if ($user) {
                    $assign = SmsCouponUserBusiness::assignForRegister($user['id']);
                } else {
                    throw new BusinessException(ResultCode::FAILED);
                }
            } else {
                $update = [
                    'last_login_time' => date('Y-m-d H:i:s'),
                    'last_login_ip'   => ip(),
                    'session_key'     => $sessionKey,
                ];
                $count  = UmsMemberBusiness::updateById($update, $user['id']);
                if ($count == 0) {
                    throw new BusinessException(ResultCode::FAILED);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $token = Token::encode(['uid' => $user['id']]);

        $result = [];
        $result['token'] = $token;
        $result['userInfo'] = $attributes['userInfo'];

        return CommonResult::formatBody($result);
    }

    public static function regCaptcha(array $attributes)
    {
        // 校验
        if (Cache::get('smscode:'.$attributes['mobile'])) {
            return CommonResult::formatError(ResultCode::AUTH_CAPTCHA_FREQUENCY);
        }

        $code = Sms::generateVerifyCode();

        $attributes['template_key'] = 'register';
        $attributes['code'] = $code;

        // 队列方式发送短信
        SmsCodeSender::dispatch($attributes)->onQueue('smscode');

        $body = App::environment('production') ? null : $code;
        return CommonResult::formatBody($body);
    }
}
