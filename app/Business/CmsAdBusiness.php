<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\CmsAd;

class CmsAdBusiness extends BaseBusiness
{
    protected static $model = 'CmsAd';
    protected static $select = ['id', 'name', 'link', 'url'];

    public static function queryIndex()
    {
        return CmsAd::query()
                    ->where('position', 1)
                    ->where('enabled', 1)
                    ->select(self::$select)
                    ->get()
                    ->toArray();
    }

    public static function getList(array $attributes)
    {
        $sort = $attributes['sort'] ?? 'created_at';
        $order = $attributes['order'] ?? 'desc';

        $condition = [];
        $list = self::queryListByCondition($attributes['page'], $attributes['limit'], $condition,$sort,$order,[]);
        $count = self::queryCountByCondition($condition);

        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $count);

        return CommonResult::formatBody(array_merge($page, ['list'=>$list]));
    }
}
