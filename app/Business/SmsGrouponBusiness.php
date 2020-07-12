<?php

namespace App\Business;

use App\Constants\OmsOrderConstant;
use App\Helper\CommonResult;
use App\Models\Mysql\SmsGroupon;
use Illuminate\Support\Arr;

class SmsGrouponBusiness extends BaseBusiness
{
    protected static $select = ['id', 'order_id', 'groupon_id', 'rule_id', 'user_id', 'creator_user_id', 'status'];

    public static function myList(array $validated)
    {
        $validated['showType'] = in_array($validated['showType'],[0, 1]) ? $validated['showType'] : 0;
        $page = $validated['page'] ?? 1;
        $limit = $validated['limit'] ?? 10;

        // 测试
//        $validated['userId'] = 7;

        if ($validated['showType'] == 0) {
            $res = self::queryMyGroupon($validated['userId'], $page, $limit);
        } else {
            $res = self::queryJoinGroupon($validated['userId'], $page, $limit);
        }

        $count = $res[1] ?? 0;
        $list = $res[0] ?? [];

        $couponList = [];
        if ($list) {
            foreach ($list as $vo) {
                $grouponVo = [];
                // 团购信息
                $grouponVo['id'] = $vo['id'];
                $grouponVo['groupon'] = Arr::except($vo, ['order', 'rule', 'creator']);
                $grouponVo['rules'] = $vo['rule'];
                $grouponVo['creator'] = $vo['creator']['nickname'];
                if ($vo['groupon_id'] == 0) {   // 团购发起记录
                    $linkGrouponId = $vo['id'];
                    $grouponVo['creator'] = ($vo['creator']['id'] == $validated['userId']);
                } else {
                    $linkGrouponId = $vo['groupon_id'];
                    $grouponVo['creator'] = false;
                }
                $joinCount = self::countJoin($linkGrouponId);
                $grouponVo['joinerCount'] = $joinCount;

                // 订单信息
                $grouponVo['orderId'] = $vo['order']['id'];
                $grouponVo['orderSn'] = $vo['order']['order_sn'];
                $grouponVo['actualPrice'] = $vo['order']['actual_price'];
                $grouponVo['orderStatusText'] = OmsOrderConstant::getText($vo['order']['order_status']);

                // 商品信息
                if ($vo['order'] && $vo['order']['goods_list']) {
                    foreach ($vo['order']['goods_list'] as $v) {
                        $grouponVo['goodsList'][] = [
                            'id'            =>  $v['id'],
                            'goodsName'     =>  $v['goods_name'],
                            'number'        =>  $v['number'],
                            'picUrl'        =>  $v['pic_url'],
                        ];
                    }
                }
                $couponList[] = $grouponVo;
            }
        }


        $page = CommonResult::formatPaged($page, $limit,  $count);

        return CommonResult::formatBody(array_merge(['list'=>$couponList], $page));
    }

    public static function countJoin($grouponId)
    {
        $condition = [
            ['groupon_id', $grouponId],
            ['groupon_id', 0],
            ['status', '<>', \App\Constants\SmsGrouponConstant::STATUS_NONE],
        ];

        return self::queryCountByCondition($condition);
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
            'order.goodsList' =>  function($query) {
                $query->select('*');
            },
            'rule'  =>  function($query) {
                $query->select('*');
            },
            'creator'   =>  function($query) {
                $query->select('*');
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
            'order.goodsList' =>  function($query) {
                $query->select('id','order_sn');
            },
            'rule'  =>  function($query) {
                $query->select('*');
            },
            'creator'   =>  function($query) {
                $query->select('*');
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
