<?php

namespace App\Helper;

use App\Business\SystemConfigBusiness;
use App\Constants\CacheKey;
use Illuminate\Support\Facades\Cache;

class SystemConfig
{
    public static function load()
    {
        $cacheConfigs = Cache::get(CacheKey::SYSTEM_CONFIGS);
        if (! $cacheConfigs) {
            $configs = SystemConfigBusiness::queryAll();
            Cache::forever(CacheKey::SYSTEM_CONFIGS, json_encode($configs));
        } else {
            $configs = json_decode($cacheConfigs, true);
        }

        foreach ($configs as $config) {
            config($config['key_name'], $config['key_value']);
        }
    }
}
