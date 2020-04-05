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

    }
}
