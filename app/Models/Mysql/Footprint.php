<?php

namespace App\Models\Mysql;

class Footprint extends BaseModel
{
    protected $table = 'footprint';

    public function goods()
    {
        return $this->belongsTo(PmsGoods::class, 'id', 'goods_id');
    }
}
