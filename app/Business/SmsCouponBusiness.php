<?php

namespace App\Business;

use App\Constants\SmsCouponConstant;
use App\Helper\CommonResult;
use App\Models\Mysql\SmsCoupon;
use App\Models\Mysql\SmsCouponUser;

class SmsCouponBusiness extends BaseBusiness
{
    protected static $select = ['id', 'name', 'desc', 'tag', 'days', 'start_time', 'end_time', 'discount', 'min'];

    public static function queryList($page, $limit, $sort='created_at', $order='desc')
    {
        return SmsCoupon::query()
                      ->where('type', SmsCouponConstant::TYPE_COMMON)
                      ->where('status', SmsCouponConstant::STATUS_NORMAL)
                      ->orderBy($sort, $order)
                      ->forPage($page, $limit)
                      ->select(self::$select)
                      ->get()
                      ->toArray();
    }

    public static function getList($page, $limit, $sort='created_at', $order='desc')
    {
        $count = SmsCoupon::query()
                          ->where('type', SmsCouponConstant::TYPE_COMMON)
                          ->where('status', SmsCouponConstant::STATUS_NORMAL)
                          ->count();

        $list = self::queryList($page, $limit, $sort, $order);
        $page = CommonResult::formatPaged($page, $limit, $count);

        return CommonResult::formatBody(array_merge($page, ['list'=>$list]));
    }

    public static function queryListByUid($uid, $page, $limit, $sort='created_at', $order='desc')
    {
        $couponIds = SmsCouponUser::where('user_id', $uid)
                                ->pluck('coupon_id');

        return SmsCoupon::query()->where('id', $couponIds)
                                    ->where('type', SmsCouponConstant::TYPE_COMMON)
                                    ->where('status', SmsCouponConstant::STATUS_NORMAL)
                                    ->orderBy($sort, $order)
                                    ->forPage($page, $limit)
                                    ->select(self::$select)
                                    ->get()
                                    ->toArray();
    }

    public static function queryRegister()
    {
        return SmsCoupon::query()
                ->where('type', SmsCouponConstant::TYPE_REGISTER)
                ->get()
                ->toArray();
    }
}
