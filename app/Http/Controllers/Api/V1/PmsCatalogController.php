<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\PmsGoodsCategoryBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class PmsCatalogController extends ApiController
{
    public function index()
    {
        $rules = [
            'id'        => 'integer|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $catalog = PmsGoodsCategoryBusiness::getCatalog($this->validated);

        return ResponseUtil::json($catalog);
    }
}
