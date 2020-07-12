<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\OmsOrderBusiness;
use App\Helper\ResponseUtil;
use App\Helper\Token;
use App\Http\Controllers\Api\ApiController;

class OmsOrderController extends ApiController
{
    // 订单列表
    public function index()
    {
        $rules = [
            'showType'        => 'integer',
            'page'            => 'integer|min:1',
            'limit'           => 'required_with:page|integer|min:1',
            'sort'            =>  'string|min:1',
            'order'           =>  'string|min:1'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        // 测试
        $validated['userId'] = 2;
        $validated['page'] = isset($validated['page']) ? $validated['page'] : 1;
        $validated['limit'] = isset($validated['limit']) ? $validated['limit'] : 10;

        $content = OmsOrderBusiness::getList($validated);

        return ResponseUtil::json($content);
    }
}
