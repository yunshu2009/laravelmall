<?php

namespace App\Http\Controllers\Admin\V1;

use App\Business\PmsBrandBusiness;
use App\Helper\CommonResult;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Admin\AdminController;

class PmsBrandController extends AdminController
{
    public function index()
    {
        $rules = [
            'page'            => 'required|integer|min:1',
            'limit'           => 'required|integer|min:1',
            'sort'            => 'string',
            'order'            => 'string|in:asc,desc',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $res = PmsBrandBusiness::getAdminList($validated);

        return ResponseUtil::json($res);
    }

    public function create()
    {
        $rules = [
            'name'            => 'required|integer|min:1',
            'desc'            => 'required|integer|min:1',
            'floor_price'     => 'double',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $res = PmsBrandBusiness::create($validated);

        return ResponseUtil::json($res);
    }

    public function show()
    {
        $rules = [
            'id'            => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $brand = PmsBrandBusiness::queryById($this->request->get('id'));

        return ResponseUtil::json(CommonResult::formatBody($brand));
    }

    public function destroy()
    {
        $rules = [
            'id'            => 'required|integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $brand = PmsBrandBusiness::destroy($this->request->get('id'));

        return ResponseUtil::json(CommonResult::formatBody($brand));
    }
}
