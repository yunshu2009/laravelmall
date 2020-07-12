<?php

namespace App\Models\Mysql;

class OmsOrder extends BaseModel
{
    protected $table = 'oms_order';

    public function groupon()
    {
        return $this->hasMany(SmsGroupon::class, 'order_id', 'id');
    }

    public function goodsList()
    {
        return $this->hasMany(OmsOrderGoods::class, 'order_id', 'id');
    }
}
