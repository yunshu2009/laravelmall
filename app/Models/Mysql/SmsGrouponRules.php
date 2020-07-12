<?php

namespace App\Models\Mysql;

class SmsGrouponRules extends BaseModel
{
    protected $table = 'sms_groupon_rules';

    public function goods()
    {
        return $this->belongsTo(PmsGoods::class, 'goods_id', 'id');
    }
}
