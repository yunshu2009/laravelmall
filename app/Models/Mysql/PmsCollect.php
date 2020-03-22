<?php

namespace App\Models\Mysql;

class PmsCollect extends BaseModel
{
    protected $table = 'pms_collect';

    protected $fillable = [
        'user_id',
        'value_id'
    ];
}
