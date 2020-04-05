<?php

namespace App\Business;

use App\Helper\CommonResult;

class UmsAddressBusiness extends BaseBusiness
{
    protected static $model = 'UmsAddress';
    protected static $select = ['id', 'name','province','city','country','postal_code','tel','is_default'];

    public static function getList(array $attributes)
    {
        $attributes['page'] = $attributes['page'] ?? 1;
        $attributes['limit'] = $attributes['limit'] ?? 10;

        $condition = [
            'user_id'   =>  $attributes['userId']
        ];
        $list = self::queryListByCondition($attributes['page'], $attributes['limit'], $condition, 'is_default', 'desc');
        $total = self::queryCountByCondition($condition);

        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $total);

        return CommonResult::formatBody(array_merge(['list'=>$list], $page));
    }
}
