<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\OmsCartBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class OmsCartController extends ApiController
{
    public function index()
    {
        $res = OmsCartBusiness::getList(['userId'=>$this->uid]);

        return ResponseUtil::json($res);
    }

    public function add()
    {
        $rules = [
            'goodsId'       => 'required|integer|min:1',
            'productId'     =>  'required|integer|min:1',
            'number'        => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        $res = OmsCartBusiness::add($validated);

        return ResponseUtil::json($res);
    }

    /**
     * 详情页商品数量
     */
    public function goodsCount()
    {
        $res = OmsCartBusiness::getGoodsCount(['userId'=>$this->uid]);

        return ResponseUtil::json($res);
    }
}
