<?php

namespace App\Business;

use App\Models\Mysql\SystemConfig;

class SystemConfigBusiness extends BaseBusiness
{
    protected static $select = ['key_name', 'key_value'];

    public static function queryAll()
    {
        return SystemConfig::query()
                ->select(self::$select)
                ->get()
                ->toArray();
    }
}
