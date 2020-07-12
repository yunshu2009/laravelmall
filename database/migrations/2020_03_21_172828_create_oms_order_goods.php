<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmsOrderGoods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oms_order_goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->comment('订单表的订单ID');
            $table->bigInteger('goods_id')->comment('商品表的商品ID');
            $table->string('goods_name', 127)->comment('商品名称');
            $table->string('goods_sn',63)->comment('商品编号');
            $table->bigInteger('product_id')->comment('商品货品表的货品ID');
            $table->smallInteger('number')->comment('商品货品的购买数量');
            $table->decimal('price',10,2)->comment('商品货品的售价');
            $table->string('specifications',1023)->comment('商品货品的规格列表');
            $table->string('pic_url')->comment('商品货品图片或者商品图片');
            $table->integer('comment')->comment('订单商品评论，如果是-1，则超期不能评价；如果是0，则可以评价；如果其他值，则是comment表里面的评论ID。');
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
        Schema::dropIfExists('oms_order_goods');
    }
}
