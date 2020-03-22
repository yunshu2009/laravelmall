<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\PmsComment;

class PmsCommentBussiness extends BaseBusiness
{
    public static function getList(array $attributes)
    {
        $query = PmsComment::query()->where('value_id', $attributes['goodsId']);

        if (isset($attributes['type'])) {
            $query = $query->where('type', 0);
        }

        $total = $query->count();

        $list = $query->with(['user'=>function($query){
                                        $query->select(['username', 'nickname', 'avatar']);
                                    }])
                                    ->forPage($attributes['page'], $attributes['limit'])
                                    ->select(['id', 'created_at', 'content', 'admin_content', 'pic_urls'])
                                    ->get()
                                    ->toArray();

        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $total);

        return CommonResult::formatBody(array_merge($page, ['list'=>$list]));
    }
}
