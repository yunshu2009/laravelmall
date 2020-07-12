<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_coupon', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 20)->comment('优惠券名称');
            $table->string('desc', 63)->nullable()->comment('优惠券介绍，通常是显示优惠券使用限制文字');
            $table->string('tag',63)->nullable()->comment('优惠券标签，例如新人专用');
            $table->integer('total')->default(0)->comment('优惠券数量，如果是0，则是无限量');
            $table->decimal('discount', 10, 2)->default(0.00)->comment('优惠金额');
            $table->decimal('min',10,2)->default(0.00)->comment('最少消费金额才能使用优惠券。');
            $table->smallInteger('limit')->default(1)->comment('用户领券限制数量，如果是0，则是不限制；默认是1，限领一张.');
            $table->tinyInteger('type')->default(0)->comment('优惠券赠送类型，如果是0则通用券，用户领取；如果是1，则是注册赠券；如果是2，则是优惠券码兑换；');
            $table->tinyInteger('status')->default(0)->comment('优惠券状态，如果是0则是正常可用；如果是1则是过期; 如果是2则是下架。');
            $table->tinyInteger('goods_type')->default(0)->comment('商品限制类型，如果0则全商品，如果是1则是类目限制，如果是2则是商品限制。');
            $table->string('goods_value', 1023)->nullable()->comment('商品限制值，goods_type如果是0则空集合，如果是1则是类目集合，如果是2则是商品集合。');
            $table->tinyInteger('time_type')->default(0)->comment('有效时间限制，如果是0，则基于领取时间的有效天数days；如果是1，则start_time和end_time是优惠券有效期；');
            $table->smallInteger('days')->default(0)->comment('基于领取时间的有效天数days。');
            $table->timestamp('start_time')->nullable()->comment('使用券开始时间');
            $table->timestamp('end_time')->nullable()->comment('使用券截至时间');
            $table->string('code', 63)->nullable()->comment('优惠券兑换码');
            $table->tinyInteger('deleted')->default(0)->comment('是否删除 0：不删除 1：删除');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_coupon');
    }
}
