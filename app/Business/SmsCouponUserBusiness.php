<?php

namespace App\Business;

use App\Constants\SmsCouponConstant;
use App\Helper\CommonResult;
use App\Models\Mysql\SmsCouponUser;

class SmsCouponUserBusiness extends BaseBusiness
{
    public static $select = ['id', 'user_id', 'coupon_id', 'status', 'used_time', 'start_time', 'end_time', 'order_id'];

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

    public static function getMyList($validated)
    {
        $validated['status'] = isset($validated['status']) ? $validated['status'] : 0;
        $validated['sort'] = isset($validated['sort']) ? $validated['sort'] : 'created_at';
        $validated['order'] = isset($validated['order']) ? $validated['order'] : 'desc';

        $condition = ['user_id'=>$validated['userId'], 'status'=>$validated['status']];

        $count = self::queryCountByCondition($condition);
        $page = CommonResult::formatPaged($validated['page'], $validated['limit'], $count);
        $list = SmsCouponUser::query()
                             ->with(['coupon'=>function($query) {
                                 $query->select(['id', 'name', 'desc', 'tag', 'min', 'discount']);
                             }])
                             ->where($condition)
                             ->orderBy($validated['sort'], $validated['order'])
                             ->forPage($validated['page'], $validated['limit'])
                             ->select(['coupon_id', 'start_time', 'end_time'])
                             ->get()
                             ->toArray();

        // 格式化返回数据
        $couponList = [];
        if ($list) {
            foreach ($list as $vo) {
                $tmp = $vo['coupon'];
                $tmp['start_time'] = $vo['start_time'];
                $tmp['end_time'] = $vo['end_time'];

                if (($tmp['start_time'] >= date('Y-m-d H:i:s')) && (date('Y-m-d H:i:s')<=$tmp['end_time'])) {
                    $tmp['available'] = false;
                } else {
                    $tmp['available'] = true;
                }

                $couponList[] = $tmp;
            }
        }

        return CommonResult::formatBody(array_merge(['list'=>$couponList], $page));
    }

    protected static function queryListByCondition($page, $limit, $condition=[], $sort='created_at', $order='desc')
    {
        return SmsCouponUser::query()
                        ->where($condition)
                        ->orderBy($sort, $order)
                        ->forPage($page, $limit)
                        ->get()
                        ->toArray();
    }

    protected static function queryCountByCondition($condition)
    {
        $query = SmsCouponUser::query();
        if ($condition) {
            $query = $query->where($condition);
        }

        return $query->count();
    }
}
