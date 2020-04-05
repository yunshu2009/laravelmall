<?php

namespace App\Business;

use App\Models\Mysql\PmsGoodsProduct;

class PmsGoodsProductBusiness extends BaseBusiness
{
    protected static $select = [];

    /**
     * 根据商品id查询完整商品信息
     * @param $goodsId
     *
     * @return array
     */
    public static function findById($goodsId)
    {
        return PmsGoodsProduct::query()->where('id',$goodsId)->first()->toArray();
    }

    public static function checkStock($productId, $number)
    {
        $product = PmsGoodsProduct::query()
                       ->where('id', $productId)
                       ->where('number', '>=', $number)
                       ->select(['id'])
                       ->first();

        return $product ? true : false;
    }
}
