<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Helper\RegexUtil;
use App\Helper\Token;
use App\Models\Mysql\UmsMember;
use App\Services\Auth\Wechat;
use App\Services\Sms\Sms;
use Dotenv\Regex\Result;
use Illuminate\Support\Facades\DB;

class UmsMemberBusiness extends BaseBusiness
{
    public static function queryByOpenid($openId)
    {
        return UmsMember::query()
                        ->where('weixin_openid', $openId)
                        ->first();
    }

    public static function queryByUsername($username)
    {
        return UmsMember::query()
                        ->where('username', $username)
                        ->first();
    }

    public static function queryByMobile($mobile)
    {
        return UmsMember::query()
                        ->where('mobile', $mobile)
                        ->first();
    }

    public static function add(array $user)
    {
        return UmsMember::query()->create($user);
    }

    public static function updateById($update, $id)
    {
        return UmsMember::query()->where('id',$id)->update($update);
    }

    public static function login(array $attributes)
    {
        if ($model = self::validatePassword($attributes['username'], $attributes['password'])) {
            $model->last_login_time = date('Y-m-d H:i:s');
            $model->last_login_ip = ip();
            $model->save();

            // 生成 jwt
            $token = Token::encode(['uid' => $model->user_id]);
            $body = [
                'token' =>  $token,
                'userInfo'  =>  [
                    'nickName'  =>  $model->username,
                    'avatarUrl'  =>  $model->avatar,
                ]
            ];

            return CommonResult::formatBody($body);
        }

        return CommonResult::formatError(ResultCode::BAD_REQUEST, '用户名或者密码错误');
    }

    // 手机注册
    public static function register(array $attributes)
    {
        // 校验（用户名 / 手机号 / 验证码）
        if ($member = UmsMemberBusiness::queryByUsername($attributes['username'])) {
            return CommonResult::formatError(ResultCode::AUTH_NAME_REGISTERED);
        }
//        if (! RegexUtil::isMobile($attributes['mobile'])) {
//            return CommonResult::formatError(ResultCode::AUTH_INVALID_MOBILE);
//        }
        if ($member = UmsMemberBusiness::queryByMobile($attributes['mobile'])) {
            return CommonResult::formatError(ResultCode::AUTH_MOBILE_REGISTERED);
        }
        if (!self::verifyCode($attributes['mobile'], $attributes['code'])) {
            return CommonResult::formatError(ResultCode::AUTH_CAPTCHA_UNMATCH);
        }

        $openid = '';
        // 如果有微信小程序code，则获取微信小程序openid
        if (isset($attributes['wxCode']) && $attributes['wxCode']) {
            $session = (new Wechat())->getSessionKey($attributes['wxCode']);
            if (! $session) {
                return CommonResult::formatError(ResultCode::AUTH_OPENID_UNACCESS);
            }
            if ($member = UmsMemberBusiness::queryByOpenid($session['openid'])) {
                return CommonResult::formatError(ResultCode::AUTH_OPENID_BINDED, 'openid已绑定账号');
            }
            $openid = $session['openid'];
        }

        DB::beginTransaction();
        try {
            // 添加数据至数据库
            $memberArr = [
                'username'        => $attributes['username'],
                'password'        => self::setPassword($attributes['password']),
                'mobile'          => $attributes['mobile'],
                'weixin_openid'   => $openid,
                'avatar'          => 'https://yanxuan.nosdn.127.net/80841d741d7fa3073e0ae27bf487339f.jpg?imageView&quality=90&thumbnail=64x64',
                'nickname'        => $attributes['username'],
                'gender'          => 0,
                'user_level'      => 0,
                'status'          => 0,
                'last_login_time' => date('Y-m-d H:i:s'),
                'last_login_ip'   => ip(),
            ];
            $member    = self::add($memberArr);
            // 给用户发送优惠券
            SmsCouponUserBusiness::assignForRegister($member['id']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $body = [
            'usrInfo'   =>  [
                'nickname'      =>  $attributes['username'],
                'avatar_url'    =>  $member['avatar'],
            ],
            'token' => Token::encode(['uid' => $member['id']]),
        ];
        return CommonResult::formatBody($body);
    }

    private static function setPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private static function validatePassword($username, $password)
    {
        $type = self::getUsernameType($username);

//        if ($type == 'email') {
//            $model = UmsMember::where('email', $username)->first();
//        } else {
            $model = UmsMember::where('username', $username)->first();
//        }

        if ($model && password_verify($password, $model['password'])) {
            return $model;
        }

        return false;
    }

    public static function verifyCode($mobile, $code)
    {
        $res = Sms::verifySmsCode($mobile, $code);
        if ($res === true) { // !isset($res['error']
            return true;
        }
        return false;
    }

    public static function getUsernameType($username)
    {
        return 'username';
    }
}
