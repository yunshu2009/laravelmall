<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Models\Mysql\PmsGoodsCategory;

class PmsGoodsCategoryBusiness extends BaseBusiness
{
    // pms_goods_category表默认查询字段
    protected static $select = ['id', 'name', 'icon_url', 'pid', 'desc','pic_url'];

    public static function queryChannel()
    {
        return PmsGoodsCategory::query()
                        ->where('level', 'L1')
                        ->select(self::$select)
                        ->get()
                        ->toArray();
    }

    public static function queryByPid($pid)
    {
        return PmsGoodsCategory::query()
                            ->where('pid', $pid)
                            ->select(self::$select)
                            ->get()
                            ->toArray();
    }

    public static function queryById($id)
    {
        $res = PmsGoodsCategory::query()->find($id, self::$select);

        return is_null($res) ? [] : $res->toArray();
    }

    /**
     * 获取商品分类(兄弟分类和负分类）
     */
    public static function getBrotherAndParentCategories(array $attributes)
    {
        $category = PmsGoodsCategoryBusiness::queryById($attributes['id']);

        if (! $category) {
            return CommonResult::formatError(ResultCode::BAD_REQUEST);
        }

        $current = $category;
        if ($current['pid'] == 0) {
            $parent = $current;
            $children = PmsGoodsCategoryBusiness::queryByPid($current['id']);
            $current = count($children)>0 ? $children[0] : $current;
        } else {
            $parent = PmsGoodsCategoryBusiness::queryById($current['pid']);
            $children = PmsGoodsCategoryBusiness::queryByPid($current['pid']);   // 显示兄弟
        }

        $res = [
            'currentCategory'   =>  $current,
            'parentCategory'    =>  $parent,
            'brotherCategory'   =>  $children,
        ];

        return CommonResult::formatBody($res);
    }

    // 显示分类目录
    public static function getCatalog(array $attributes)
    {
        // 所有一级分类目录
        $l1CatList = self::queryChannel();

        // 当前一级分类目录
        $currentCategory = [];
        if (isset($attributes['id'])) {
            $currentCategory = self::queryById($attributes['id']);
        } else {
            $currentCategory = $l1CatList[0];
        }

        // 当前一级分类目录对应的二级分类目录
        $currentSubCategory = [];
        if ($currentCategory) {
            $currentSubCategory = self::queryByPid($currentCategory['id']);
        }

        $data = [];
        $data['categoryList'] = $l1CatList;
        $data['currentCategory'] = $currentCategory;
        $data['currentSubCategory'] = $currentSubCategory;

        return CommonResult::formatBody($data);
    }
}
