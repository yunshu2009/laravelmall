<?php

namespace App\Models\Mysql;

class PmsGoodsCategory extends BaseModel
{
    protected $table = 'pms_goods_category';

    public function goodsList()
    {
        return $this->hasMany(PmsGoods::class, 'category_id', 'id');
    }
}
