<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\BaseModel;
use App\Models\Mysql\PmsCollect;

class PmsCollectBusiness extends BaseModel
{
    public static function count($userId, $valueId)
    {
        return PmsCollect::query()
                    ->where('user_id', $userId)
                    ->where('value_id', $valueId)
                    ->count();
    }

    public static function addOrDelete(array $arributes)
    {
        $collect = PmsCollect::withTrashed()
                  ->where('user_id', $arributes['userId'])
                  ->where('value_id', $arributes['valueId'])
                  ->first();

        if ($collect) {
            if ($collect->trashed()) { // 已经删除
                $collect->restore();
            } else {
                $collect->delete();
            }
        } else {
            // 添加
            PmsCollect::create([
                'user_id'    => $arributes['userId'],
                'value_id'  =>  $arributes['valueId'],
            ]);
        }

        return CommonResult::formatBody();
    }
}
