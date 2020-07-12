<?php

namespace App\Http\Controllers\Admin\V1;

use App\Business\OmsOrderBusiness;
use App\Business\PmsGoodsBusiness;
use App\Business\PmsGoodsProductBusiness;
use App\Business\UmsMemberBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Admin\AdminController;

class DashboardController extends AdminController
{
    public function index()
    {
        $userTotal = UmsMemberBusiness::queryCountByCondition();
        $goodsTotal = PmsGoodsBusiness::count();
        $productTotal = PmsGoodsProductBusiness::queryCountByCondition();
        $orderTotal = OmsOrderBusiness::queryCountByCondition();
        $data = [];
        $data["userTotal"] = $userTotal;
        $data["goodsTotal"] = $goodsTotal;
        $data["productTotal"] = $productTotal;
        $data["orderTotal"] = $orderTotal;

        return ResponseUtil::json($data);
    }
}
