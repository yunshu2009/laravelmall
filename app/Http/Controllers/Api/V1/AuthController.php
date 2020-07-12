<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\AuthBusiness;
use App\Business\UmsMemberBusiness;
use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Helper\ResponseUtil;
use App\Helper\Token;
use App\Http\Controllers\Api\ApiController;

class AuthController extends ApiController
{
    public function loginByWeixin()
    {
        $rules = [
            'code'        => 'required|string|min:1',
            'userInfo'    => 'required|array',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $content = AuthBusiness::loginByWeixin($this->validated);

        return ResponseUtil::json($content);
    }

    public function login()
    {
        $rules = [
            'username' => 'required|string',
            'password' => 'required|min:6|max:20'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = UmsMemberBusiness::login($this->validated);

        return ResponseUtil::json($data);
    }

    public function logout()
    {
        if ($this->uid) {
            return ResponseUtil::ok();
        } else {
            return ResponseUtil::error(ResultCode::UNAUTHORIZED, '请先登录');
        }
    }

    public function register()
    {
        $rules = [
            'username'     => 'required|min:3|max:25|alpha_num',   // 验证字段必须是完全是字母、数字。
            'password'      => 'required|string|min:6|max:20',
            'mobile'        => 'required|mobile',
            'code'          => 'required|string|digits:6',  // 验证的字段必须为 numeric
            'wxCode'          => 'string|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $data = UmsMemberBusiness::register($this->validated);

        return ResponseUtil::json($data);
    }

    // 发送注册短信验证码
    public function regCaptcha()
    {
       $rules = [
           'mobile'  => 'required|string|mobile',
       ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        // 发送短信
        $content = AuthBusiness::regCaptcha($this->validated);

        return ResponseUtil::json($content);
    }
}
