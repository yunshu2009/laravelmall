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
}
