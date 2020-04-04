<?php

namespace App\Business;

use App\Helper\CommonResult;

class FootprintBusiness extends BaseBusiness
{
    protected static $model = 'Footprint';
    protected static $select = ['id', 'goods_id', 'created_at'];

    public static function getList(array $attributes)
    {
        $condition = [
            'user_id'   =>  $attributes['userId']
        ];
        $with = [
            'goods' =>  function($query) {
                $query->select(['id', 'name','brief', 'pic_url', 'retail_price']);
            }
        ];

        $list = self::queryListByCondition($attributes['page'], $attributes['limit'], $condition,'created_at','desc',[], $with);
        $count = self::queryCountByCondition($condition);
        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $count);

        $footprintList = [];
        if ($list) {
            foreach ($list as $vo) {
                $footprintList[] = [
                    'id'    =>  $vo['id'],
                    'created_at'  =>  $vo['created_at'],
                    'goods_id'  =>  $vo['goods_id'],
                    'name'  =>  $vo['goods']['name'],
                    'brief'  =>  $vo['goods']['brief'],
                    'pic_url'  =>  $vo['goods']['pic_url'],
                    'retail_price'  =>  $vo['goods']['retail_price'],
                ];
            }
        }

        return CommonResult::formatBody(array_merge($page, ['list'=>$footprintList]));
    }
}
