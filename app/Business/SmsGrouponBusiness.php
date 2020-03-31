<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\SmsGroupon;
use phpDocumentor\Reflection\Types\Self_;

class SmsGrouponBusiness extends BaseBusiness
{
    protected static $select = ['groupon_id', 'rule_id', 'user_id', 'status'];

    public static function myList(array $validated)
    {
        $validated['showType'] = in_array($validated['showType'],[0, 1]) ? $validated['showType'] : 0;

        // 测试
        $validated['userId'] = 7;

        if ($validated['showType'] == 0) {
            $list = self::queryMyGroupon($validated['userId'], 1, 10);
        } else {
            $list = self::queryJoinGroupon($validated['userId'], 1, 10);
        }


        if ($list) {
            foreach ($list as $vo) {

            }
        }

        return CommonResult::formatBody($list);
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

        $list = self::queryListByCondition($page, $limit, $condition);
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

        $list = self::queryListByCondition($page, $limit, $condition);
        $count = self::queryCountByCondition($condition);

        return [
            $list,
            $count
        ];
    }

    protected static function queryListByCondition($page, $limit, $condition=[], $sort='created_at', $order='desc', $select='')
    {
        $select = $select ? $select : self::$select;

        return SmsGroupon::query()
                        ->where($condition)
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
