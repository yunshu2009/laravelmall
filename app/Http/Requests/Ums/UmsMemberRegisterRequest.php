<?php

namespace App\Http\Requests\Ums;

use Illuminate\Foundation\Http\FormRequest;

class UmsMemberRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required',
            'telephone' => 'required',
            'authCode' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'foo.required' => '用户名不能为空',
            'password.required' => '用户名不能为空',
            'telephone.required' => '电话不能为空',
            'authCode.required' => '验证码不能为空',
        ];
    }

}
