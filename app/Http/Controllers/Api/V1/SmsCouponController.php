<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\SmsCouponBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class SmsCouponController extends ApiController
{
    // todo：完善
    public function index()
    {
        $rules = [
            'page'            => 'integer|min:1',
            'limit'           => 'required_with:page|integer|min:1',
            'sort'            =>  'string|min:1',
            'order'           =>  'string|min:1'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['page'] = isset($validated['page']) ? $validated['page'] : 1;
        $validated['limit'] = isset($validated['limit']) ? $validated['limit'] : 10;

        $res = SmsCouponBusiness::getList($validated['page'], $validated['limit']);

        return ResponseUtil::json($res);
    }
}
