<?php

namespace App\Business;

use App\Constants\SmsCouponConstant;
use App\Helper\CommonResult;
use App\Models\Mysql\SmsCoupon;
use App\Models\Mysql\SmsCouponUser;

class SmsCouponBusiness extends BaseBusiness
{
    protected static $select = ['id', 'name', 'desc', 'tag', 'days', 'start_time', 'end_time', 'discount', 'min'];

    // 不带where条件的查询
    public static function queryList($page, $limit, $sort='created_at', $order='desc')
    {
        return self::queryListByCondition($page, $limit, [], $sort, $order);
    }

    public static function queryHomeList($page, $limit)
    {
        $condition = [
            'type'  =>  SmsCouponConstant::TYPE_COMMON,
            'status'=> SmsCouponConstant::STATUS_NORMAL,
        ];

        self::queryListByCondition($page, $limit, $condition);
    }

    public static function getList($validated)
    {
        $validated['sort'] = isset($validated['sort']) ? $validated['sort'] : 'created_at';
        $validated['order'] = isset($validated['order']) ? $validated['order'] : 'desc';

        $count = self::queryCountByCondition([]);
        $list = self::queryList($validated['page'], $validated['limit'], $validated['sort'], $validated['order']);
        $page = CommonResult::formatPaged($validated['page'], $validated['limit'], $count);

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

    protected static function queryListByCondition($page, $limit, $condition=[], $sort='created_at', $order='desc', $select='')
    {
        $select = $select ? $select : self::$select;
        return SmsCoupon::query()
                        ->where($condition)
                        ->orderBy($sort, $order)
                        ->forPage($page, $limit)
                        ->select($select)
                        ->get($select)
                        ->toArray();
    }

    protected static function queryCountByCondition($condition)
    {
        $query = SmsCoupon::query();
        if ($condition) {
            $query = $query->where($condition);
        }

        return $query->count();
    }
}
