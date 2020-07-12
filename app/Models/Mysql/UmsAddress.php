<?php

namespace App\Models\Mysql;

class UmsAddress extends BaseModel
{
    protected $table = 'ums_address';

    protected $casts = [
        'is_default'    =>  'boolean'
    ];

    protected $fillable = [
        'id', 'name','province','city','county','postal_code','tel','is_default','deleted','user_id','address_detail','area_code'
    ];
}
