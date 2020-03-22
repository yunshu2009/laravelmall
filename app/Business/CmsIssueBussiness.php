<?php

namespace App\Business;

use App\Models\Mysql\CmsIssue;

class CmsIssueBussiness extends BaseBusiness
{
    protected static $select = ['id', 'question', 'answer', 'created_at'];

    public static function getList($page, $limit, $sort='created_at', $order='desc')
    {
        return CmsIssue::query()
                     ->orderBy($sort, $order)
                     ->forPage($page, $limit)
                     ->select(self::$select)
                     ->get()
                     ->toArray();
    }
}
