<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\SmsGroupon;

class SmsGrouponBusiness extends BaseBusiness
{
    protected static $select = ['order_id', 'groupon_id', 'rule_id', 'user_id', 'status'];

    public static function myList(array $validated)
    {
        $validated['showType'] = in_array($validated['showType'],[0, 1]) ? $validated['showType'] : 0;
        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 10;

        // 测试
        $validated['userId'] = 7;

        if ($validated['showType'] == 0) {
            $res = self::queryMyGroupon($validated['userId'], $page, $limit);
        } else {
            $res = self::queryJoinGroupon($validated['userId'], $page, $limit);
        }

//        if ($res) {
//            foreach ($list as $vo) {
//
//            }
//        }

        $count = $res[1] ?? 0;
        $page = CommonResult::formatPaged($page, $limit,  $count);
        $list = $res[0] ?? [];

        return CommonResult::formatBody(array_merge(['list'=>$list], $page));
    }

    // 用户发起的团购
    public static function queryMyGroupon($userId, $page, $limit)
    {
        $condition = [
            ['user_id', $userId],
            ['creator_user_id', $userId],
            ['groupon_id', 0],
            ['status', '<>', \App\Constants\SmsGrouponConstant::STATUS_NONE],
        ];
        $with = [
            'order' =>  function($query) {
                $query->select('id','order_sn');
            }
        ];

        $list = self::queryListByCondition($page, $limit, $condition, 'created_at', 'desc', '', $with);
        $count = self::queryCountByCondition($condition);

        return [
            $list,
            $count,
        ];
    }

    public static function queryJoinGroupon($userId, $page, $limit)
    {
        $condition = [
            ['user_id' , $userId],
            ['groupon_id','<>', 0],
            ['status', '<>', \App\Constants\SmsGrouponConstant::STATUS_NONE],
        ];
        $with = [
            'order' =>  function($query) {
                $query->select('id','order_sn');
            }
        ];

        $list = self::queryListByCondition($page, $limit, $condition, 'created_at', 'desc', '', $with);
        $count = self::queryCountByCondition($condition);

        return [
            $list,
            $count
        ];
    }

    protected static function queryListByCondition($page, $limit, $condition=[], $sort='created_at', $order='desc', $select='', $with=[])
    {
        $select = $select ? $select : self::$select;

        $query = SmsGroupon::query();
        if ($with) {
            $query = $query->with($with);
        }

        return $query->where($condition)
                ->orderBy($sort, $order)
                ->forPage($page, $limit)
                ->select($select)
                ->get($select)
                ->toArray();
    }

    protected static function queryCountByCondition($condition)
    {
        $query = SmsGroupon::query();
        if ($condition) {
            $query = $query->where($condition);
        }

        return $query->count();
    }
}
