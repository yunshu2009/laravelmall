<?php

namespace App\Business;

class BaseBusiness
{
    protected static $model;
    protected static $select = [];

    public static function setSelect($select)
    {
        self::$select = $select;
    }

    protected static function queryListByCondition($page, $limit, $condition=[], $sort='created_at', $order='desc', $select='', $with=[])
    {
        $select = $select ? $select : static::$select;

        $model = 'App\\Models\\Mysql\\'.static::$model;
        $query = (new $model)->query();

        if ($with) {
            $query = $query->with($with);
        }
        if ($condition) {
            $query = $query->where($condition);
        }
        if ($sort && $order) {
            $query = $query->orderBy($sort, $order);
        }
        if ($page && $limit) {
            $query = $query->forPage($page, $limit);
        }

        $obj = $query->select($select)->get($select);

        return $obj ? $obj->toArray() : [];
    }

    protected static function queryCountByCondition($condition)
    {
        $model = 'App\\Models\\Mysql\\'.static::$model;
        $query = (new $model)->query();
        if ($condition) {
            $query = $query->where($condition);
        }

        return $query->count();
    }
}
