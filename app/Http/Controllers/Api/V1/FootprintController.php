<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\FootprintBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class FootprintController extends ApiController
{
    // todo:完善，使用mongodb
    public function index()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'limit'           => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        $validated['userId'] = 4;
        $res = FootprintBusiness::getList($this->validated);

        return ResponseUtil::json($res);
    }
}
