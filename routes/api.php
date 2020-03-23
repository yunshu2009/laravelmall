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

                      Route::get('coupon/list', 'SmsCouponController@index')->name('sms_coupon.index');

                  });

                  // 登录后可以访问的接口
                  Route::middleware(['token.auth', 'xss'])->group(function() {
                      // 退出
                      Route::post('auth/logout', 'AuthController@logout')->name('auth.logout');
                      Route::get('order/list', 'OmsOrderController@index')->name('order.index');
                      // 添加至购物车
                      Route::post('cart/add', 'OmsCartController@add')->name('oms_cart.add');
                  });
              });
         //
     });

