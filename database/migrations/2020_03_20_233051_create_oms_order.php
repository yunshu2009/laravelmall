<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmsOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oms_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('用户表的用户ID');
            $table->string('order_sn', 63)->comment('订单编号');
            $table->smallInteger('order_status')->comment('订单状态');
            $table->smallInteger('aftersale_status')->nullable()->comment('售后状态，0是可申请，1是用户已申请，2是管理员审核通过，3是管理员退款成功，4是管理员审核拒绝，5是用户已取消');
            $table->string('consignee', 63)->comment('收货人名称');
            $table->string('mobile', 63)->comment('收货人手机号');
            $table->string('address', 127)->comment('收货具体地址');
            $table->string('message', 255)->comment('用户订单留言');
            $table->decimal('goods_price', 10,2)->comment('商品总费用');
            $table->decimal('freight_price', 10,2)->default(0.00)->comment('配送费用');
            $table->decimal('coupon_price', 10,2)->default(0.00)->comment('优惠券减免');
            $table->decimal('integral_price', 10,2)->default(0.00)->comment('用户积分减免');
            $table->decimal('order_price', 10,2)->default(0.00)->comment('订单费用， = goods_price + freight_price - coupon_price');
            $table->decimal('groupon_price', 10,2)->default(0.00)->comment('团购优惠价减免');
            $table->decimal('actual_price',10,2)->default(0.00)->comment('实付费用， = order_price - integral_price');
            $table->string('pay_id',63)->nullable()->comment('微信付款编号');
            $table->timestamp('pay_time')->nullable()->comment('微信付款时间');
            $table->string('ship_sn',63)->nullable()->comment('发货编号');
            $table->string('ship_channel',63)->nullable()->comment('发货快递公司');
            $table->timestamp('ship_time')->nullable()->comment('发货开始时间');
            $table->decimal('refund_amount',10,2)->nullable()->comment('实际退款金额，（有可能退款金额小于实际支付金额）');
            $table->string('refund_type',63)->nullable()->comment('退款方式');
            $table->string('refund_content',127)->nullable()->comment('退款备注');
            $table->timestamp('refund_time')->nullable()->comment('退款时间');
            $table->timestamp('confirm_time')->nullable()->comment('用户确认收货时间');
            $table->smallInteger('comments')->nullable()->default(0)->comment('待评价订单商品数量');
            $table->timestamp('end_time')->nullable()->comment('订单关闭时间');
            $table->tinyInteger('deleted')->nullable()->default(0)->comment('逻辑删除');
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
        Schema::dropIfExists('oms_order');
    }
}
