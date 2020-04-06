<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\UmsAddressBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class UmsAddressController extends ApiController
{
    public function index()
    {
        $res = UmsAddressBusiness::getList(['userId'=>$this->uid]);

        return ResponseUtil::json($res);
    }

    public function delete()
    {
        $rules = [
            'id'            => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;

        $res = UmsAddressBusiness::delete($validated);

        return ResponseUtil::json($res);
    }

    /**
     * 添加或者更新收获地址
     */
    public function save()
    {
        $rules = [
            'id'              => 'integer|min:0',
            'name'            => 'required|string|min:1',
            'tel'             => 'required|mobile',
            'province'        => 'required|string|min:1',
            'city'            => 'required|string|min:1',
            'county'         => 'required|string|min:1',
            'areaCode'        => 'required|string|size:6',
            'addressDetail' => 'required|string|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;

        $res = UmsAddressBusiness::save($validated);

        return ResponseUtil::json($res);
    }
}
