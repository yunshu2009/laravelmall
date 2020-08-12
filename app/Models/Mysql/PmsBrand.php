<?php

namespace App\Models\Mysql;

class PmsBrand extends BaseModel
{
    protected $table = 'pms_brand';

    protected $fillable = [
        'name',
        'desc',
        'pic_url',
        'sort_order',
        'floor_price',
    ];
}
