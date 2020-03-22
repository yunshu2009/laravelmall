<?php

namespace App\Models\Mysql;

class SmsCouponUser extends BaseModel
{
    protected $table = 'sms_coupon_user';

    protected $fillable = [
        'user_id',
        'coupon_id',
        'status',
        'used_time',
        'start_time',
        'end_time',
        'order_id',
        'deleted'
    ];
}
