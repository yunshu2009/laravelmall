<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Helper\Token;
use App\Models\Mysql\UmsAdmin;

class UmsAdminBusiness extends BaseBusiness
{
    protected static $select = [];

    public static function login(array $attributes)
    {
        if ($model = self::validatePassword($attributes['username'], $attributes['password'])) {
            $model->last_login_time = date('Y-m-d H:i:s');
            $model->last_login_ip = ip();
            $model->save();

            // 生成 jwt
            $token = Token::encode(['uid' => $model->id]);
            $body = [
                'token' =>  $token,
                'adminInfo'  =>  [
                    'nickName'  =>  $model->username,
                    'avatarUrl'  =>  $model->avatar,
                ]
            ];

            return CommonResult::formatBody($body);
        }

        return CommonResult::formatError(ResultCode::BAD_REQUEST, '用户名或者密码错误');
    }

    private static function validatePassword($username, $password)
    {
        $model = UmsAdmin::where('username', $username)->first();

        if ($model && password_verify($password, $model['password'])) {
            return $model;
        }

        return false;
    }
}
