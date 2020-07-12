<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Constants\SmsGrouponConstant;
use App\Helper\CommonResult;
use App\Models\Mysql\PmsGoods;
use Illuminate\Support\Arr;

class PmsGoodsBusiness extends BaseBusiness
{
    // pms_goods表默认查询字段
    protected static $select = ['id', 'name', 'brief', 'pic_url', 'is_hot', 'is_new', 'counter_price', 'retail_price'];

    /**
     * 根据商品id查询完整商品信息
     * @param $goodsId
     *
     * @return array
     */
    public static function findById($goodsId)
    {
        return PmsGoods::query()->where('id',$goodsId)->first()->toArray();
    }

    private static function formatSpecificationList($specificationList)
    {
        $list = [];
        $hashmap = [];

        $counter = 0;

        foreach ($specificationList as $vo) {
            $key = $vo['specification'];
            if (isset($hashmap[$key])) {
                $list[$counter]['valueList'][] = $vo;
            } else {
                $list[$counter] = [
                    'name'  =>  $key,
                    'valueList' =>  [$vo],
                ];
                $hashmap[$key] = true;

                $counter++;
            }
        }

        return $list;
    }

    public static function related(array $attributes)
    {
        $goods = PmsGoods::query()->where('id',$attributes['id'])
                         ->select(['category_id'])
                         ->first();

        if (is_null($goods)) {
            return CommonResult::formatError(ResultCode::BAD_REQUEST);
        }

        // 查询同类的商品
        $list = PmsGoods::query()
                        ->where('category_id', $goods['category_id'])
                        ->where('is_on_sale', 1)
                        ->where('id','<>', $goods['id'])
                        ->forPage($attributes['page'], $attributes['limit'])
                        ->select(self::$select)
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->toArray();

        return CommonResult::formatBody($list);
    }

    public static function show(array $attributes)
    {
        $goods = PmsGoods::query()
                ->with([
                    'attribute' =>  function($query) {
                        $query->select(['goods_id', 'attribute', 'value']);
                    },
                    'specificationList' =>  function($query) {
                        $query->select(['goods_id', 'specification', 'value']);
                    },
                    'productList'       =>  function($query) {
                        $query->select(['goods_id','id', 'specifications', 'url','price']);
                    },
                    'groupon'           =>  function($query) {
                        $query->where('status', SmsGrouponConstant::RULE_STATUS_ON)->select(['goods_id', 'goods_name', 'discount', 'discount_member','expire_time']);
                    }
                ])
                ->where('id', $attributes['id'])
                ->select(array_merge(self::$select, ['detail', 'share_url']))
                ->first()
                ->toArray();

        if ($goods && isset($goods['specification_list'])) {
            $goods['specification_list'] = self::formatSpecificationList($goods['specification_list']);
        }

        $ret = [];
        $ret['info'] = Arr::except($goods, ['specification_list', 'attribute', 'product_list']);
        $ret['attribute'] = $goods['attribute'];
        $ret['issue'] = CmsIssueBussiness::queryList(1, 4);
        $ret['share'] = config('mall.wx_share');
        $ret['shareUrl'] = $goods['share_url'];
        $ret['userHasCollect'] = CmsCollectBusiness::count($attributes['userId'], $goods['id']);
        $ret['brand'] = [];

        $commentList = PmsCommentBussiness::getList(['goodsId'=>$goods['id'], 'page'=>1, 'limit'=>2] );
        $ret['comment'] = self::formatCommentList($commentList);

        return CommonResult::formatBody($ret);
    }

    public static function formatCommentList($commentList)
    {
        $formatList = [];

        if ($commentList['errno'] == ResultCode::SUCCESS) {
            $comments = [];
            foreach ($commentList['data']['list'] as $vo) {
                $comments[] = [
                    'id'    =>  $vo['id'],
                    'addTime'    =>  $vo['createdAt'],
                    'content'    =>  $vo['content'],
                    'adminContent'    =>  $vo['adminContent'],
                    'nickname'    =>  $vo['nickname'],
                    'avatar'    =>  $vo['avatar'],
                    'picList'    =>  $vo['picUrls'],
                ];
            }

            $formatList['data'] = $comments;
            $formatList['count'] = $commentList['data']['total'];
        }

        return $formatList;
    }

    public static function queryNewList($page, $limit)
    {
        return PmsGoods::query()
                       ->where('is_new', 1)
                       ->where('is_on_sale', 1)
                       ->recent()
                       ->forPage($page, $limit)
                       ->select(self::$select)
                       ->get()
                       ->toArray();
    }

    public static function queryHotList($page, $limit)
    {
        return PmsGoods::query()
                       ->where('is_hot', 1)
                       ->where('is_on_sale', 1)
                       ->recent()
                       ->forPage($page, $limit)
                       ->select(self::$select)
                       ->get()
                       ->toArray();
    }

    public static function queryByCategory($catIds, $page, $limit)
    {
        return PmsGoods::query()
                       ->whereIn('category_id', $catIds)
                       ->where('is_on_sale', 1)
                       ->recent()
                       ->forPage($page, $limit)
                       ->select(self::$select)
                       ->get()
                       ->toArray();
    }

    public static function querySelective(array $attributes)
    {
        $query = PmsGoods::query()->where('is_on_sale', 1);

        if (isset($attributes['catId']) && $attributes['catId']) {
            $query->where('category_id', $attributes['catId']);
        }
        if (isset($attributes['brandId']) && $attributes['brandId']) {
            $query->where('brand_id', $attributes['brandId']);
        }
        if (isset($attributes['isNew'])) {
            $query->where('is_new', $attributes['isNew']);
        }
        if (isset($attributes['keyword'])) {
            $keyword = trim($attributes['keyword']);
            $keyword = strip_tags($keyword);
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword. '%')->orWhere('keywords', 'like', '%' . $keyword. '%');
            });
        }

        $total = $query->count();

        if (isset($attributes['sort']) && isset($attributes['order'])) {
            $query = $query->orderBy($attributes['sort'], $attributes['order']);
        }
        $data = $query->forPage($attributes['page'], $attributes['limit'])
                      ->select(self::$select)
                      ->get()
                      ->toArray();

        $pages = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $total);

        return CommonResult::formatBody(array_merge(['list'=>$data], $pages));
    }

    public static function queryCountOnSale()
    {
        $count = PmsGoods::where('is_on_sale',1)->count();

        return CommonResult::formatBody($count);
    }

    /*
     * 获取所有物品总数，包括在售的和下架的，但是不包括已删除的商品
     */
    public static function count()
    {
        return parent::queryCountByCondition();
    }
}
