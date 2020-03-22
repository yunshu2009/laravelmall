<?php

namespace App\Business;

use App\Constants\OmsOrderConstant;
use App\Helper\CommonResult;
use App\Models\Mysql\OmsOrder;

class OmsOrderBusiness extends BaseBusiness
{
    public static $handleOption = [
            'cancel'    => false,
            'delete'    => false,
            'pay'       => false,
            'comment'   => false,
            'confirm'   => false,
            'refund'    => false,
            'rebuy'     => false,
            'aftersale' => false,
        ];

    public static function getList(array $attributes)
    {
        $attributes['sort'] = isset($attributes['sort']) ? $attributes['sort'] : 'created_at';
        $attributes['order'] = isset($attributes['order']) ? $attributes['order'] : 'desc';

        $query = OmsOrder::query();
        if (isset($attributes['showType'])) {
            if ($orderStatus = self::getOrderStatusByShowType($attributes['showType'])) {
                $query = $query->where('order_status', $orderStatus);
            }
        }

        $total = $query->where('user_id', $attributes['userId'])
                           ->count();

        $orderList = $query->with(['groupon'=>function($query) {
                                $query->select(['id','order_id']);
                            }])
                           ->with(['goodsList'=>function ($query) {
                               $query->select(['order_id','goods_name','number','pic_url','specifications','price']);
                            }])
                           ->where('user_id', $attributes['userId'])
                           ->forPage($attributes['page'], $attributes['limit'])
                           ->orderBy($attributes['sort'], $attributes['order'])
                           ->select(['id','order_sn','actual_price','order_status','aftersale_status'])
                           ->get()
                           ->toArray();

        if ($orderList) {
            foreach ($orderList as &$order) {
                // 获取是否有团购记录
                if ($order['groupon']) {
                    $order['isGroupin'] = true;
                } else {
                    $order['isGroupin'] = false;
                }

                $order['handleOption'] = self::getHandleOption($order['order_status']);
            }
        }

        return CommonResult::formatBody(array_merge(['list'=>$orderList], CommonResult::formatPaged($attributes['page'], $attributes['limit'], $total)));
    }

    public static function getHandleOption($orderStatus)
    {
        switch ($orderStatus) {
            case OmsOrderConstant::STATUS_CREATE:
                self::$handleOption['cancel'] = true;
                self::$handleOption['pay'] = true;
                break;
            case OmsOrderConstant::STATUS_CANCEL:
            case OmsOrderConstant::STATUS_AUTO_CANCEL:
                self::$handleOption['delete'] = true;
                break;
            case OmsOrderConstant::STATUS_PAY: // 已付款但是没有发货，则可退款
                self::$handleOption['refund'] = true;
                break;
            case OmsOrderConstant::STATUS_REFUND:
                self::$handleOption['delete'] = true;
                break;
            case OmsOrderConstant::STATUS_SHIP:
                self::$handleOption['confirm'] = true;
                break;
            case OmsOrderConstant::STATUS_CONFIRM:
            case OmsOrderConstant::STATUS_AUTO_CONFIRM:
                self::$handleOption['delete'] = true;
                self::$handleOption['comment'] = true;
                self::$handleOption['aftersale'] = true;
                self::$handleOption['rebuy'] = true;
                break;
        }

        return self::$handleOption;
    }

    public static function getOrderStatusByShowType($showType)
    {
        $map = [
            //showType  =>  orderStatus
            1 => OmsOrderConstant::STATUS_CREATE,
            2 => OmsOrderConstant::STATUS_PAY,
            3 => OmsOrderConstant::STATUS_SHIP,
            4 => OmsOrderConstant::STATUS_CONFIRM,
        ];

        return isset($map[$showType]) ? $map[$showType] : false;
    }
}
