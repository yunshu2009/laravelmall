<?php

namespace App\Business;

use App\Models\Mysql\CmsAd;

class CmsAdBusiness extends BaseBusiness
{
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
}
