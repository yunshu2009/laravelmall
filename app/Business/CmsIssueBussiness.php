<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\CmsIssue;

class CmsIssueBussiness extends BaseBusiness
{
    protected static $model = 'CmsIssue';
    protected static $select = ['id', 'question', 'answer', 'created_at'];

    public static function queryList($page, $limit, $sort='created_at', $order='desc')
    {
        return CmsIssue::query()
                     ->orderBy($sort, $order)
                     ->forPage($page, $limit)
                     ->select(self::$select)
                     ->get()
                     ->toArray();
    }

    public static function getList(array $attributes)
    {
        $count = parent::queryCountByCondition([]);
        $attributes['limit'] = max($attributes['limit'], 50);

        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $count);
        $list = self::queryList($attributes['page'], $attributes['limit'], 'created_at', 'desc');

        return CommonResult::formatBody(array_merge($page, ['list'=>$list]));
    }
}
