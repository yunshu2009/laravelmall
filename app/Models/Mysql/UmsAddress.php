<?php

namespace App\Models\Mysql;

class UmsAddress extends BaseModel
{
    protected $table = 'ums_address';

    protected $casts = [
        'is_default'    =>  'boolean'
    ];
}
