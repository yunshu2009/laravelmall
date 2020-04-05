<?php

namespace App\Constants;

class ResultCode
{
    // 通用错误码
    const SUCCESS = 0;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const FAILED = 500;    // 应用错误，不好明确归类的
    const UPDATE_DB_ERROR = 501;  // 更新数据失败
    const SYSERROR = 999;   // 未知错误，致命错误。如：数据库错误

    const AUTH_NAME_REGISTERED = 1001;
    const AUTH_MOBILE_REGISTERED = 1002;
    const AUTH_CAPTCHA_UNMATCH = 1003;
    const AUTH_INVALID_MOBILE = 1004;
    const AUTH_OPENID_UNACCESS = 1005;
    const AUTH_OPENID_BINDED = 1006;
    const AUTH_CAPTCHA_FREQUENCY = 1007;

    const GOODS_UNSHELVE = 2001;
    const PRODUCT_NOT_FOUND = 2002;
    const GOODS_NO_STOCK = 2003;

//    const GOODS_NO_STOCK = 711;
//    const GOODS_UNKNOWN = 712;
//    const GOODS_INVALID = 713;
//
//    const ORDER_UNKNOWN = 720;
//    const ORDER_INVALID = 721;
//    const ORDER_CHECKOUT_FAIL = 722;
//    const ORDER_CANCEL_FAIL = 723;
//    const ORDER_PAY_FAIL = 724;
//    // 订单当前状态下不支持用户的操作，例如商品未发货状态用户执行确认收货是不可能的。
//    const ORDER_INVALID_OPERATION = 725;
//    const ORDER_COMMENTED = 726;
//    const ORDER_COMMENT_EXPIRED = 727;
//
//    const GROUPON_EXPIRED = 730;
//    const GROUPON_OFFLINE = 731;
//    const GROUPON_FULL = 732;
//    const GROUPON_JOIN = 733;
//
//    public static final int COUPON_EXCEED_LIMIT = 740;
//    public static final int COUPON_RECEIVE_FAIL= 741;
//    public static final int COUPON_CODE_INVALID= 742;
//
//    public static final int AFTERSALE_UNALLOWED = 750;
//    public static final int AFTERSALE_INVALID_AMOUNT = 751;
//    public static final int AFTERSALE_INVALID_STATUS = 752;


    // 前台api 6xxx
    // 用户模块 60xx

    // 商品模块 61xx

    // 订单模块 62xx

    // 营销模块 63xx

    public static $errorList = [
        self::SUCCESS   =>  '操作成功。',
        self::BAD_REQUEST    =>  '参数错误。',
        self::UNAUTHORIZED  =>  '暂未登录或token已经过期。',
        self::NOT_FOUND =>  '资源不存在',
        self::FORBIDDEN =>  '没有相关权限。',
        self::FAILED    =>  '发生错误。',
        self::SYSERROR  =>  '系统错误。',
        self::UPDATE_DB_ERROR   =>  '修改失败',

        self::AUTH_NAME_REGISTERED      =>  '用户名已经注册。',
        self::AUTH_MOBILE_REGISTERED    =>  '手机号已经注册。',
        self::AUTH_CAPTCHA_UNMATCH      =>  '验证码不正确。',
        self::AUTH_INVALID_MOBILE       =>  '手机格式不正确。',
        self::AUTH_OPENID_UNACCESS      =>  'openid获取失败。',
        self::AUTH_OPENID_BINDED        =>  'openid已经绑定。',
        self::AUTH_CAPTCHA_FREQUENCY    =>  '请稍后发送短信。',

        self::GOODS_UNSHELVE            =>  '商品不存在或者已下架',
        self::PRODUCT_NOT_FOUND         =>  '该规格商品不存在',
        self::GOODS_NO_STOCK            =>  '库存不足',
    ];

    public static function getMessage($code)
    {
        return isset(self::$errorList[$code]) ? self::$errorList[$code] : '未知错误';
    }
}
