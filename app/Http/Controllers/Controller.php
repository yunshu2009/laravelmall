<?php

namespace App\Http\Controllers;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Helper\ResponseUtil;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    public $validated;
    public $request;

    public function __construct()
    {
        $this->request = app('request');
        $this->uid = $this->request['uid'] ?? 0;
    }

    /**
     * 验证输入信息
     * @param $rules
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function validateInput($rules)
    {
        $requests = $this->request->all();

        $validator = Validator::make($requests, $rules);
        if ($validator->fails()) {
            $body = CommonResult::formatError(ResultCode::BAD_REQUEST, $validator->messages()->first());
            return ResponseUtil::json($body);
        } else {
            $this->validated = array_intersect_key($requests, $rules);
            $this->validated = $requests ;
            return false;
        }
    }
}
