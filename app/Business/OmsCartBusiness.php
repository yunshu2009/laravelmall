<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Models\Mysql\OmsCart;
use Illuminate\Support\Facades\Log;

class OmsCartBusiness extends BaseBusiness
{
    protected static $model = 'OmsCart';
    // 默认查询字段
    protected static $select = ['id', 'user_id', 'goods_id', 'goods_name', 'product_id', 'price', 'number', 'pic_url', 'checked'];

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

    public static function getList(array $attributes)
    {
        $condition = [
            'user_id'   =>  $attributes['userId']
        ];
        $with = [
            'goods' =>  function($query) {
                $query->select(['id','is_on_sale']);
            }
        ];
        $list = self::queryListByCondition(0, 0, $condition, 'created_at', 'desc', '', $with);

        $cartList = [
            'cartList'  =>  [],
            'cartTotal' =>  [],
        ];
        $goodsCount = 0;
        $goodsAmount = 0;
        $checkedGoodsCount = 0;
        $checkedGoodsAmount = 0;

        if ($list) {
            foreach ($list as $vo) {
                if ($vo['goods'] && $vo['goods']['is_on_sale']) {
                    $cartList['cartList'][] = $vo;
                    $goodsCount += $vo['number'];
                    $goodsAmount = bcmul($goodsAmount, bcmul($vo['number'], $vo['price'], 2), 2);
                    if ($vo['checked']) {
                        $checkedGoodsCount += $vo['number'];
                        $checkedGoodsAmount = bcmul($checkedGoodsAmount, bcmul($vo['number'], $vo['price'], 2), 2);
                    }
                } else {
                    OmsCart::where('id', $vo['id'])->delete();
                    Log::channel('biz')->debug("系统自动删除失效购物车商品 goodsId=" . $vo['goods_id'] . " productId=" . $vo['product_id']);
                }
            }
        }

        $cartList['cartTotal']['goodsCount'] = $goodsCount;
        $cartList['cartTotal']['checkedGoodsCount'] = $checkedGoodsCount;
        $cartList['cartTotal']['goodsAmount'] = $goodsAmount;
        $cartList['cartTotal']['checkedGoodsAmount'] = $checkedGoodsAmount;

        return CommonResult::formatBody($cartList);
    }

    public static function getGoodsCount(array $attributes)
    {
        return CommonResult::formatBody(self::queryGoodsCount($attributes['userId']));
    }

    public static function update(array $attributes)
    {
        $cart = OmsCart::query()
            ->with(['goods'=>function($query) {
                $query->select(['id', 'is_on_sale']);
            }])
            ->where('user_id', $attributes['userId'])
            ->where('id', $attributes['id'])
            ->first();
        if (! $cart) {
            return CommonResult::formatError(ResultCode::BAD_REQUEST);
        }

        if ($attributes['productId'] != $cart['product_id'] || $attributes['goodsId'] != $cart['goods_id']) {
            return CommonResult::formatError(ResultCode::BAD_REQUEST);
        }

        if (! $cart['goods']['is_on_sale']) {
            return CommonResult::formatError(ResultCode::GOODS_UNSHELVE, '商品已下架');
        }

        // 检查库存
        $hasStock = PmsGoodsProductBusiness::checkStock($cart['product_id'], $attributes['number']);
        if (! $hasStock) {
            return CommonResult::formatError(ResultCode::GOODS_UNSHELVE, '库存不足');
        }

        // 修改商品信息
         $res = OmsCart::where('id', $cart['id'])->update([
             'number'   =>  $attributes['number']
         ]);

        if (! $res) {
            return CommonResult::formatError(ResultCode::UPDATE_DB_ERROR);
        }

        return CommonResult::formatBody();
    }

    public static function delete(array $attributes)
    {
        $carts = OmsCart::query()
            ->whereIn('id', $attributes['ids'])
            ->where('user_id', $attributes['userId'])
            ->first();

        if (! $carts) {
            return CommonResult::formatError(ResultCode::NOT_FOUND);
        }

        OmsCart::destroy($attributes['ids']);

        return CommonResult::formatBody();
    }
}
