<?php

namespace App\Business;

use App\Helper\CommonResult;
use App\Models\Mysql\BaseModel;
use App\Models\Mysql\PmsCollect;

class PmsCollectBusiness extends BaseModel
{
    protected static $select = ['id', 'value_id', 'type'];

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

    public static function getList(array $attributes)
    {
        $condition = [
            'type'  =>  $attributes['type'],
            'user_id'=> $attributes['userId'],
        ];

        if ($condition['type'] == 0) {
            $with = [
                'goods' =>   function($query) {
                    $query->select(['id', 'name', 'brief', 'pic_url', 'retail_price']);
                }
            ];
        } else {
            //todo:
        }

        $list = self::queryListByCondition($attributes['page'], $attributes['limit'], $condition, 'created_at', 'desc', $with);
        $count = self::queryCountByCondition($condition);
        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $count);

        $collectList = [];
        if ($list) {
            foreach ($list as $vo) {
                $collectList[] = [
                    'id'    =>  $vo['id'],
                    'type'  =>  $vo['type'],
                    'valueId'  =>  $vo['value_id'],

                    'name'  =>  $vo['goods']['name'],
                    'brief'  =>  $vo['goods']['brief'],
                    'pic_url'  =>  $vo['goods']['pic_url'],
                    'retail_price'  =>  $vo['goods']['retail_price'],
                ];
            }
        }

        return CommonResult::formatBody(array_merge($page, ['list'=>$collectList]));
    }

    protected static function queryListByCondition($page, $limit, $condition=[], $sort='created_at', $order='desc', $with=[], $select=[])
    {
        $select = empty($select) ? self::$select : $select;
        $query = PmsCollect::query();
        if ($with) {
            $query = $query->with($with);
        }
        return $query->where($condition)
                     ->orderBy($sort, $order)
                     ->forPage($page, $limit)
                     ->select($select)
                     ->get()
                     ->toArray();
    }

    protected static function queryCountByCondition($condition)
    {
        $query = PmsCollect::query();
        if ($condition) {
            $query = $query->where($condition);
        }

        return $query->count();
    }


}
