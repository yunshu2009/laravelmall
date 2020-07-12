<?php

namespace App\Http\Controllers\Admin\V1;

use App\Business\UmsAdminBusiness;
use App\Http\Controllers\Admin\AdminController;
use App\Helper\ResponseUtil;

class AuthController extends AdminController
{
    public function login()
    {
        $rules = [
            'username' => 'required|string',
            'password' => 'required|min:6|max:20'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $res = UmsAdminBusiness::login($this->validated);

        return ResponseUtil::json($res);
    }
}
