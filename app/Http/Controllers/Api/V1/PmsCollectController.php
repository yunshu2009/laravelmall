<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\PmsCollectBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class PmsCollectController extends ApiController
{
    public function addOrDelete()
    {
        $rules = [
            'type'      =>  'required|integer|min:0',
            'valueId'   =>  'required|integer|min:1',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;

        $res = PmsCollectBusiness::addOrDelete($validated);
        return ResponseUtil::json($res);
    }

    public function index()
    {
        $rules = [
            'type'            => 'required|integer|min:0',
            'page'            => 'required|integer|min:1',
            'limit'           => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['userId'] = $this->uid;
        $res = PmsCollectBusiness::getList($validated);

        return ResponseUtil::json($res);
    }
}
