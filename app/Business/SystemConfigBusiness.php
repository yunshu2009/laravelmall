<?php

namespace App\Business;

use App\Helper\CommonResult;
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

    /**
     * @param array $attributes
     *
     * @return array
     */
    public static function getConfigs(array $attributes)
    {
        if (isset($attributes['type'])) {
            if ($attributes['type'] == 'about_info') {
                $fields = [
                    'mall.name',
                    'mall.address',
                    'mall.phone',
                    'mall.qq',
                    'mall.longitude',
                    'mall.latitude'
                ];
            }
         }
        $configs = [];
        $list = self::queryAll();
        if ($list) {
            foreach ($list as $vo) {
                if (in_array($vo['key_name'], $fields)) {
                    $key = str_replace('.', '_', $vo['key_name']);
                    $configs[$key] = $vo['key_value'];
                }
            }
        }

        return CommonResult::formatBody($configs);
    }
}
