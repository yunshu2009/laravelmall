<?php

namespace App\Business;

use App\Constants\SmsGrouponConstant;
use App\Helper\CommonResult;
use App\Models\Mysql\SmsGrouponRules;

class SmsGrouponRulesBusiness extends BaseBusiness
{
    protected static $select = [];

    public static function queryList($page, $limit, $sort='created_at', $order='desc', $pageinfo=false)
    {
        $query = SmsGrouponRules::query();

         if ($sort && $order) {
             $query = $query->orderBy($sort, $order);
         }

         $list = $query->with('goods:id,name,brief,counter_price,pic_url,retail_price')->where('status', SmsGrouponConstant::RULE_STATUS_ON)
                          ->forPage($page, $limit)
                          ->select(['goods_id', 'discount as group_discount', 'discount_member as group_member', 'expire_time'])
                          ->get()
                          ->toArray();

        $data = [];
        foreach ($list as $k=>$v) {
            $data[$k] = $v['goods'];
            $data[$k]['group_discount'] = $v['group_discount'];
            $data[$k]['group_member'] = $v['group_member'];
            $data[$k]['expire_time'] = $v['expire_time'];
        }

         // 如果不要求返回page信息
         if (! $pageinfo) {
             return $data;
         }

        $total = $query->where('status', SmsGrouponConstant::RULE_STATUS_ON)
                       ->count();

         $page = CommonResult::formatPaged($page, $limit, $total);

         return array_merge($page, ['list'=>$data]);
    }
}
