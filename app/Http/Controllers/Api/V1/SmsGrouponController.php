<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\SmsGrouponBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class SmsGrouponController extends ApiController
{
    // 我的评团
    public function myList()
    {
        $rules = [
            'showType'            => 'required|integer|min:0',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        $res = SmsGrouponBusiness::myList($validated);

        return ResponseUtil::json($res);
    }
}
