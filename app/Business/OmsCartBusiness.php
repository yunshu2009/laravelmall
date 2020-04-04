<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Models\Mysql\OmsCart;

class OmsCartBusiness extends BaseBusiness
{
    // 默认查询字段
    protected static $select = [];

    public static function queryExist($goodsId, $productId, $userId)
    {
        return OmsCart::query()->where('goods_id', $goodsId)
                        ->where('product_id', $productId)
                        ->where('user_id', $userId)
                        ->first()
                        ->toArray();
    }

    public static function add(array $attributes)
    {
        // 校验商品
        $goods = PmsGoodsBusiness::findById($attributes['goodsId']);
        if (! $goods || ($goods && !$goods['is_on_sale'])) {
            return CommonResult::formatError(ResultCode::GOODS_UNSHELVE, '商品不存在或者已下架');
        }

        $product = PmsGoodsProductBusiness::findById($attributes['productId']);
        if (! $product) {
            return CommonResult::formatError(ResultCode::PRODUCT_NOT_FOUND);
        }

        // 判断购物车中是否存在此规格商品，无则添加有则修改
        $existCart = self::queryExist($attributes['goodsId'], $attributes['productId'], $attributes['userId']);

        if ($existCart) {
            $num = $existCart['number'] + $attributes['number'];

            // todo:库存检验完善
            if ($num > $product['number']) {
                return CommonResult::formatError(ResultCode::GOODS_NO_STOCK, '库存不足');
            }

            OmsCart::where('id', $existCart['id'])->update([
                'number'    =>  $num,
            ]);
        } else {
            if ($attributes['number'] > $product['number']) {
                return CommonResult::formatError(ResultCode::GOODS_NO_STOCK, '库存不足');
            }

            $cart = [
                'user_id'   =>  $attributes['userId'],
                'goods_id'  =>  $goods['id'],
                'goods_sn'  =>   $goods['goods_sn'],
                'goods_name'  => $goods['name'],
                'product_id'  =>  $attributes['productId'],
                'price'  =>  $product['price'],
                'number'  =>  $attributes['number'],
                'specifications'  =>  $product['specifications'],
                'select'  =>  1,
                'pic_url'   =>  $product['url'] ? $product['url'] : $goods['pic_url'],
            ];
            OmsCart::create($cart);
        }

       $goodsCount = self::queryGoodsCount($attributes['userId']);

        return CommonResult::formatBody($goodsCount);
    }

    // 获取购物车商品的总数量
    public static function queryGoodsCount($userId)
    {
        $goodsCount = 0;
        $cartList = OmsCart::where('user_id', $userId)->get()->toArray();

        foreach ($cartList as $vo) {
            $goodsCount += $vo['number'];
        }

        return $goodsCount;
    }

    public static function getGoodsCount($userId)
    {
        return CommonResult::formatBody(self::queryGoodsCount($userId));
    }
}
