<?php

namespace App\Models\Mysql;

class PmsGoods extends BaseModel
{
    protected $table = 'pms_goods';

    public function attribute()
    {
        return $this->hasMany(PmsGoodsAttribute::class, 'goods_id', 'id');
    }

    public function specificationList()
    {
        return $this->hasMany(PmsGoodsSpecification::class, 'goods_id', 'id');
    }

    public function productList()
    {
        return $this->hasMany(PmsGoodsProduct::class, 'goods_id', 'id');
    }

    public function groupon()
    {
        return $this->hasOne(SmsGrouponRules::class, 'goods_id', 'id');
    }
}
