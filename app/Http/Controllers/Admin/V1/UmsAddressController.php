<?php

namespace App\Http\Controllers\Admin\V1;

use App\Business\UmsAddressBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Admin\AdminController;

class UmsAddressController extends AdminController
{
    public function index()
    {
        $rules = [
            'userId'          => 'integer|min:1',
            'name'            => 'string|min:1',
            'page'            => 'integer|min:1',
            'limit'           => 'required_with:page|integer|min:1',
            'sort'            =>  'string|min:1',
            'order'           =>  'string|min:1'
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $content = UmsAddressBusiness::getList($validated);

        return ResponseUtil::json($content);
    }
}
