<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\HomeBusiness;
use App\Helper\ResponseUtil;
use App\Helper\Token;
use App\Http\Controllers\Api\ApiController;

class HomeController extends ApiController
{
    public function index()
    {
        $content = HomeBusiness::content($this->uid);

        return ResponseUtil::json($content);
    }
}
