<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\HomeBusiness;
use App\Business\SystemConfigBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class HomeController extends ApiController
{
    public function index()
    {
        $content = HomeBusiness::content($this->uid);

        return ResponseUtil::json($content);
    }

    public function about()
    {
        $content = SystemConfigBusiness::getConfigs(array('type'=>'about_info'));

        return ResponseUtil::json($content);
    }
}
