<?php

namespace App\Models\Mysql;

class SmsGroupon extends BaseModel
{
    protected $table = 'sms_groupon';

    public function order()
    {
        return $this->hasMany(OmsOrder::class, 'order_id', 'id');
    }

    public function rule()
    {
//        return $this->
    }
}
