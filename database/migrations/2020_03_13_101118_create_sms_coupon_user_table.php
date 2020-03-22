<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsCouponUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_coupon_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('用户id');
            $table->bigInteger('coupon_id')->comment('优惠券ID');
            $table->tinyInteger('status')->default(0)->comment('使用状态, 如果是0则未使用；如果是1则已使用；如果是2则已过期；如果是3则已经下架；');
            $table->timestamp('used_time')->nullable()->comment('使用时间');
            $table->timestamp('start_time')->nullable()->comment('有效期开始时间');
            $table->timestamp('end_time')->nullable()->comment('有效期截至时间');
            $table->bigInteger('order_id')->nullable()->comment('订单id');
            $table->tinyInteger('deleted')->default(0)->comment('逻辑删除');
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
        Schema::dropIfExists('sms_coupon_user');
    }
}
