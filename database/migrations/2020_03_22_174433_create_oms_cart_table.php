<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmsCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oms_cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('用户表的用户ID');
            $table->integer('goods_id')->comment('商品表的商品ID');
            $table->string('goods_sn', 63)->comment('商品编号');
            $table->string('goods_name', 127)->comment('商品名称');
            $table->bigInteger('product_id')->comment('商品货品表的货品ID');
            $table->decimal('price',10,2)->default(0.00)->comment('商品货品的价格');
            $table->smallInteger('number')->default(0)->comment('商品货品的数量');
            $table->string('specifications', '511')->comment('商品规格值列表，采用JSON数组格式');
            $table->tinyInteger('select')->default(1)->comment('购物车中商品是否选择状态');
            $table->string('pic_url',255)->comment('商品图片或者商品货品图片');
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
        Schema::dropIfExists('oms_cart');
    }
}
