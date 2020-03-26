<?php

namespace App\Models\Mysql;

class Footprint extends BaseModel
{
    protected $table = 'footprint';

    public function goods()
    {
        return $this->belongsTo(PmsGoods::class, 'goods_id', 'id');
    }
}
