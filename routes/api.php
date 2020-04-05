<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', function () {
    return 'Hi Laravelshop.';
});

Route::prefix('v1')
     ->namespace('Api\V1')
     ->name('api.v1.')
     ->group(function () {
         Route::middleware('throttle:' . config('api.rate_limits.sign'))
              ->group(function () {
                  // 登录相关
                  // 微信登录
                  Route::post('auth/login_by_weixin', 'AuthController@loginByWeixin')->name('auth.login_by_weixin');
                  // 账号登录
                  Route::post('auth/login', 'AuthController@login')->name('auth.login');
                  // 用户注册
                  Route::post('auth/register', 'AuthController@register')->name('auth.register');
              });

         Route::middleware('throttle:' . config('api.rate_limits.access'))
              ->group(function () {
                  /*** 游客可以访问的接口 ***/
                  Route::middleware('xss')->group(function() {
                      // 首页
                      Route::get('index', 'HomeController@index')->name('home.index');
                      // 商品页分类列表
                      Route::get('goods/category', 'PmsGoodsController@category')->name('pms_goods.category');
                      // 商品列表
                      Route::get('goods/list', 'PmsGoodsController@index')->name('pms_goods.index');
                      // 商品详情
                      Route::get('goods/detail', 'PmsGoodsController@show')->name('pms_goods.show');
                      // 添加收藏&取消收藏
                      Route::post('collect/addordelete', 'PmsCollectController@addOrDelete')->name('pms_collect.addordelete');
                      // 详情页关联商品
                      Route::get('goods/related', 'PmsGoodsController@related')->name('pms_goods.related');
                      // 分类列表显示商品总数
                      Route::get('goods/count', 'PmsGoodsController@count')->name('pms_goods.count');
                      // 分类目录
                      Route::get('catalog/index', 'PmsCatalogController@index')->name('pms_catalog.index');
                      // 发送手机注册短信
                      Route::post('auth/regCaptcha', 'AuthController@regCaptcha')->name('auth.regCaptcha');
                      // 优惠券列表
                      Route::get('coupon/list', 'SmsCouponController@index')->name('sms_coupon.index');
                  });

                  // 登录后可以访问的接口
                  Route::middleware(['token.auth', 'xss'])->group(function() {
                      // 退出
                      Route::post('auth/logout', 'AuthController@logout')->name('auth.logout');
                      Route::get('order/list', 'OmsOrderController@index')->name('order.index');
                      // 添加至购物车
                      Route::post('cart/add', 'OmsCartController@add')->name('oms_cart.add');
                      // 修改购物车
                      Route::post('cart/update', 'OmsCartController@update')->name('oms_cart.update');
                      // 购物车数量
                      Route::post('cart/goodscount', 'OmsCartController@goodsCount')->name('oms_cart.goodscount');
                      // 我的购物车列表
                      Route::get('cart/index', 'OmsCartController@index')->name('oms_cart.index');
                      // 删除购物车
                      Route::post('cart/delete', 'OmsCartController@delete')->name('oms_cart.delete');
                      // 我的地址
                      Route::get('address/list', 'UmsAddressController@index')->name('ums_address.index');
                      // 删除地址
                      Route::post('address/delete', 'UmsAddressController@delete')->name('ums_address.delete');
                      // 我的优惠券
                      Route::get('coupon/mylist', 'SmsCouponController@myList')->name('sms_coupon.mylist');
                      // 收藏列表
                      Route::get('collect/list', 'PmsCollectController@index')->name('pms_collect.index');
                     // 足迹列表
                      Route::get('footprint/list', 'FootprintController@index')->name('foot_print.index');
                      // 我的团购
                      Route::get('groupon/my', 'SmsGrouponController@myList')->name('sms_groupon.my');
                  });
              });
         //
     });

