<?php

namespace App\Http\Controllers\Admin\V1;

use App\Business\CmsAdBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Admin\AdminController;

class CmsAdController extends AdminController
{
    public function index()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'limit'           => 'required|integer|min:1',
            'sort'            => 'string',
            'prder'            => 'string|in:asc,desc',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $res = CmsAdBusiness::getList($validated);

        return ResponseUtil::json($res);
    }
}
