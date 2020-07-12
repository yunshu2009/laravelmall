<?php

namespace App\Http\Controllers\Api\V1;

use App\Business\PmsGoodsBusiness;
use App\Business\PmsGoodsCategoryBusiness;
use App\Helper\ResponseUtil;
use App\Http\Controllers\Api\ApiController;

class PmsGoodsController extends ApiController
{
    public function show()
    {
        $rules = [
            'id'   =>  'required|integer',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $this->validated['userId'] = $this->uid;
        $detail = PmsGoodsBusiness::show($this->validated);

        return ResponseUtil::json($detail);
    }

    // 商品分类类目
    public function category()
    {
        $rules = [
            'id'   =>  'required|integer',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $categories = PmsGoodsCategoryBusiness::getBrotherAndParentCategories($this->validated);

        return ResponseUtil::json($categories);
    }

    public function index()
    {
        $rules = [
            'page'            => 'integer|min:1',
            'limit'           => 'required_with:page|integer|min:1',
            'brandId'         => 'integer|min:1',
            'categoryId'      => 'integer|min:1',
            'isHot'           => 'boolean',
            'isNew'           => 'boolean',
            'keyword'         => 'string|min:1',
            'sort'            => 'string|min:1',
            'order'           => 'required_with:sort|string|min:1',
        ];

        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $userId = $this->uid;

        if (isset($this->validated['keyword']) && $userId) {
            // todo:添加用户搜索历史
        }

        $this->validated['page'] = isset($this->validated['page']) ? $this->validated['page'] : 1;
        $this->validated['limit'] = isset($this->validated['limit']) ? $this->validated['limit'] : 10;

        $data = PmsGoodsBusiness::querySelective($this->validated);

        return ResponseUtil::json($data);
    }

    public function count()
    {
        return ResponseUtil::json(PmsGoodsBusiness::queryCountOnSale());
    }

    public function related()
    {
        $rules = [
            'id'   =>  'required|integer',
        ];
        if ($error = $this->validateInput($rules)) {
            return $error;
        }

        $validated = $this->validated;
        $validated['page'] = 1;
        $validated['limit'] = 6;

        return ResponseUtil::json(PmsGoodsBusiness::related($validated));
    }
}
