<?php

namespace App\Business;

use App\Models\Mysql\PmsBrand;

class PmsBrandBusiness extends BaseBusiness
{
    protected static $select = ['id','name','desc','pic_url','floor_price'];

    public static function queryList($page, $limit)
    {
        $query = PmsBrand::query();

        return self::getList($query, $page, $limit);
    }

    public static function getList($query, $page, $limit, $sort='', $order='')
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
