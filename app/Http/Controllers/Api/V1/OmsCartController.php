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

    public function update()
    {
        $rules = [
            'id'        => 'required|integer|min:1',
            'productId' => 'required|integer|min:1',
            'goodsId'   => 'required|integer|min:1',
            'number'    => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;

        $res = OmsCartBusiness::update($validated);

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

    public function delete()
    {
        $rules = [
            'ids'        => 'required|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        $res = OmsCartBusiness::delete($validated);

        return ResponseUtil::json($res);
    }

    /**
     * 购物车下单
     */
    public function checkout()
    {
        $rules = [
            'cartId'            => 'required|integer|min:1',
            'addressId'         => 'required|integer|min:0',
            'couponId'          => 'required|integer|min:0',
            'userCouponId'      => 'required|integer|min:0',
            'grouponRulesId'    => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;

        $res = OmsCartBusiness::checkout($validated);

        return ResponseUtil::json($res);
    }
}
