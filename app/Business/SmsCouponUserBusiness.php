<?php

namespace App\Business;

use App\Constants\SmsCouponConstant;
use App\Models\Mysql\SmsCouponUser;

class SmsCouponUserBusiness extends BaseBusiness
{
    public static function assignForRegister($userId)
    {
        // 1. 查询所有的注册优惠券
        $couponList = SmsCouponBusiness::queryRegister();
        // 2. 循环遍历优惠券，如果已经分配优惠券，则不分配。否则分配
        foreach ($couponList as $coupon) {
            $count = self::countCouponUser($userId, $coupon['id']);
            if ($count > 0) {
                continue;
            }

            $limit = $coupon['limit'];
            while ($limit > 0) {
                $couponUser = [
                    'user_id'   =>  $userId,
                    'coupon_id' =>  $coupon['id'],
                    'status'    =>  SmsCouponConstant::STATUS_NORMAL,
                ];
                if ($coupon['time_type'] == SmsCouponConstant::TIME_TYPE_TIME) {   // 日期
                    $couponUser['start_time'] = $coupon['start_time'];
                    $couponUser['end_time'] = $coupon['end_time'];
                } else {  // 天数
                    $couponUser['start_time'] = date('Y-m-d H:i:s');
                    $couponUser['end_time'] = date('Y-m-d H:i:s', strtotime('+ '.$coupon['days'].' days'));
                }
                self::add($couponUser);

                $limit--;
            }
        }
    }

    // 查询已经分配给用户的优惠券
    public static function countCouponUser($userId, $couponId)
    {
        return SmsCouponUser::query()
                    ->where('user_id', $userId)
                    ->where('coupon_id', $couponId)
                    ->where('status','deleted')
                    ->count();
    }

    public static function add($couponUser)
    {
        return SmsCouponUser::query()->create($couponUser);
    }
}
