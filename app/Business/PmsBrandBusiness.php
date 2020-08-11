<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\PmsBrand;

class PmsBrandBusiness extends BaseBusiness
{
    protected static $model = 'PmsBrand';
    protected static $select = ['id','name','desc','pic_url','floor_price'];

    public static function getAdminList(array $attributes)
    {
        $attributes['page'] = $attributes['page'] ?? 1;
        $attributes['limit'] = $attributes['limit'] ?? 10;
        $attributes['sort'] = $attributes['sort'] ?? 'created_at';
        $attributes['order'] = $attributes['order'] ?? 'desc';

        $condition = [];
        $list = parent::queryListByCondition($attributes['page'], $attributes['limit'], $condition, $attributes['sort'], $attributes['order']);
        $total = parent::queryCountByCondition($condition);

        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $total);

        return CommonResult::formatBody(array_merge(['list'=>$list], $page));
    }

    public static function queryList($page, $limit)
    {
        $query = PmsBrand::query();

        return self::doQueryList($query, $page, $limit);
    }

    public static function doQueryList($query, $page, $limit, $sort='', $order='')
    {
        if ($sort && $order) {
            $query = $query->orderBy($sort, $order);
        }

        return $query->forPage($page, $limit)
                     ->select(self::$select)
                     ->get()
                     ->toArray();
    }
}
