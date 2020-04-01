<?php

namespace App\Models\Mysql;

class SmsGroupon extends BaseModel
{
    protected $table = 'sms_groupon';

    public function order()
    {
        return $this->belongsTo(OmsOrder::class, 'order_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(UmsMember::class, 'creator_user_id', 'id');
    }

    public function rule()
    {
        return $this->hasOne(SmsGrouponRules::class, 'id', 'rule_id');
    }
}
