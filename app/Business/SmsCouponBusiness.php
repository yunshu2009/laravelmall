<?php

namespace App\Business;

use App\Constants\SmsCouponConstant;
use App\Models\Mysql\SmsCoupon;
use App\Models\Mysql\SmsCouponUser;

class SmsCouponBusiness extends BaseBusiness
{
    protected static $select = ['id', 'name', 'desc', 'tag', 'days', 'start_time', 'end_time', 'discount', 'min'];

    public static function queryList($page, $limit, $sort='created_at', $order='desc')
    {
        $query = SmsCoupon::query();

        return self::getList($query, $page, $limit, $sort, $order);
    }

    public static function getList($query, $page, $limit, $sort='created_at', $order='desc')
    {
        return $query->where('type', SmsCouponConstant::TYPE_COMMON)
                     ->where('status', SmsCouponConstant::STATUS_NORMAL)
                     ->orderBy($sort, $order)
                     ->forPage($page, $limit)
                     ->select(self::$select)
                     ->get()
                     ->toArray();
    }

    public static function queryListByUid($uid, $page, $limit, $sort='created_at', $order='desc')
    {
        $couponIds = SmsCouponUser::where('user_id', $uid)
                                ->pluck('coupon_id');

        $query = SmsCoupon::query()->where('id', $couponIds);

        return self::getList($query, $page, $limit, $sort, $order);
    }

    public static function queryRegister()
    {
        return SmsCoupon::query()
                ->where('type', SmsCouponConstant::TYPE_REGISTER)
                ->get()
                ->toArray();
    }
}
