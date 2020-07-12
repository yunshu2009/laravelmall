<?php

namespace App\Models\Mysql;

class SmsTopic extends BaseModel
{
    protected $table = 'sms_topic';

   //  protected $appends = ['human_read_count'];

//    public function getHumanReadCountAttribute($readCount)
//    {
//        $readCount = $readCount < 1000 ? 1000 : $readCount;
//
//        return round($readCount/1000,2) . 'k';
//    }
}
