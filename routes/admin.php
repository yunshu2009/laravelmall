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

Route::prefix('v1')
     ->namespace('Admin\V1')
     ->name('admin.v1.')
     ->group(function () {
         Route::middleware('throttle:' . config('api.rate_limits.sign'))
              ->group(function () { // 登录相关

              });

         Route::middleware('throttle:' . config('api.rate_limits.access'))
                    ->group(function () {
                        // 游客可以访问的接口
                        Route::middleware('xss')->group(function() {
                            Route::post('auth/login', 'AuthController@login')->name('auth.login');
                        });
                        // 登录后可以访问的接口
                        Route::middleware(['token.auth', 'xss'])->group(function() {
                            //
                        });
                    });
         //
     });

