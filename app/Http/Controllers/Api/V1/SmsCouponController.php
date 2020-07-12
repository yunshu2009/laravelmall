<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\SmsCouponBusiness;
use App\Business\SmsCouponUserBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class SmsCouponController extends ApiController
{
    // todo：完善
    public function index()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'limit'           => 'required|integer|min:1',
            'sort'            =>  'string|min:1',
            'order'           =>  'string|min:1'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $res = SmsCouponBusiness::getList($this->validated);

        return ResponseUtil::json($res);
    }

    public function myList()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'limit'           => 'required|integer|min:1',
            'sort'            =>  'string|min:1',
            'order'           =>  'string|min:1'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        //$validated['userId'] = 4;
        $res = SmsCouponUserBusiness::getMyList($validated);

        return ResponseUtil::json($res);
    }
}
