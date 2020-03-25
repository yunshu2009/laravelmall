<?php

namespace App\Business;

use App\Constants\CacheKey;
use App\Helper\CommonResult;
use Illuminate\Support\Facades\Cache;

class HomeBusiness extends BaseBusiness
{
    public static function content($uid)
    {
        $content = [];

        // 优先使用缓存
        if ($cache = HomeBusiness::getHomeCacheData()) {
            return $cache;
        }

        $content['banner'] = CmsAdBusiness::queryIndex();
        $content['channel'] = PmsGoodsCategoryBusiness::queryChannel();
        if (! $uid) {
            $content['couponList'] = SmsCouponBusiness::queryHomeList(1, 3);
        } else {
            $content['couponList'] = SmsCouponBusiness::queryListByUid($uid, 1, 3);
        }
        $content['newGoodsList'] = PmsGoodsBusiness::queryNewList(1, config('mall.wx_index_new'));
        $content['hotGoodsList'] = PmsGoodsBusiness::queryHotList(1, config('mall.wx_index_hot'));
        $content['brandList'] = PmsBrandBusiness::queryList(1, config('mall.wx_index_brand'));
        $content['topicList'] = SmsTopicBusiness::queryList(1, config('mall.wx_index_topic'));
        $content['grouponList'] = SmsGrouponRulesBusiness::queryList(1, 5);
        $content['categoryList'] = self::getCategoryList();

        return CommonResult::formatBody($content);
    }

    public static function getHomeCacheData()
    {
        return Cache::get(CacheKey::HOME);
    }

    // todo:完善
    public static function getCategoryList()
    {
        $categorys = PmsGoodsCategoryBusiness::queryChannel();
        $categorys = array_slice($categorys, 0, config('mall.wx_catlog_list'));

        $catGoods = [];
        foreach ($categorys as $key=>$category) {
            $childCategorys = PmsGoodsCategoryBusiness::queryByPid($category['id']);
            $childIds = array_column($childCategorys, 'id');
            $categoryGoods = PmsGoodsBusiness::queryByCategory($childIds, 0, config('mall.wx_catlog_goods'));
            $catGoods[] = [
                'id'    =>  $category['id'],
                'name'  =>  $category['name'],
                'goodsList' =>  $categoryGoods,
            ];
        }

        return $catGoods;
    }
}
