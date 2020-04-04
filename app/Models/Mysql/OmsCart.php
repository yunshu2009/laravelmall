<?php

namespace App\Models\Mysql;

class OmsCart extends BaseModel
{
    protected $table = 'oms_cart';

    protected $fillable = [
        'user_id',
        'goods_id',
        'goods_sn',
        'goods_name',
        'product_id',
        'price',
        'number',
        'specifications',
        'select',
        'pic_url',
    ];

    public function goods()
    {
        return $this->belongsTo(PmsGoods::class, 'goods_id', 'id');
    }
}
