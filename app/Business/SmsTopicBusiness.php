<?php

namespace App\Business;

use App\Models\Mysql\SmsTopic;

class SmsTopicBusiness extends BaseBusiness
{
    protected static $select = ['id','title','subtitle','price','pic_url','read_count'];

    public static function queryList($page, $limit, $sort='created_at', $order='desc')
    {
        $query = SmsTopic::query();
        if ($sort && $order) {
            $query = $query->orderBy($sort, $order);
        }

        $arr = $query->forPage($page, $limit)->select(self::$select)->get()->toArray();

        foreach ($arr as $key=>$val) {
            $arr[$key]['read_count'] = self::getHumanReadCount($val['read_count']);
        }

        return $arr;
    }

    public static function getHumanReadCount($readCount)
    {
        $readCount = $readCount < 1000 ? 1000 : $readCount;

        return round($readCount/1000,2) . 'k';
    }
}
