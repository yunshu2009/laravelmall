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

    protected static function queryListByCondition($page, $limit, $condition=[], $sort='created_at', $order='desc', $select='')
    {
        $select = $select ? $select : self::$select;

        $model = static::$model;
        return (new $model)->query()
                        ->where($condition)
                        ->orderBy($sort, $order)
                        ->forPage($page, $limit)
                        ->select($select)
                        ->get($select)
                        ->toArray();
    }

    protected static function queryCountByCondition($condition)
    {
        $model = static::$model;
        $query = (new $model)->query();
        if ($condition) {
            $query = $query->where($condition);
        }

        return $query->count();
    }
}
