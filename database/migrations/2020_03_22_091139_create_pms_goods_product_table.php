<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsGoodsProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_goods_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('goods_id')->comment('商品表的商品ID');
            $table->string('specifications', 511)->comment('商品规格值列表，采用JSON数组格式');
            $table->decimal('price',10,2)->default(0.00)->comment('商品货品价格');
            $table->integer('number')->default(0)->comment('商品货品数量');
            $table->string('url',125)->nullable()->comment('商品货品图片');
            $table->tinyInteger('deleted')->default(0)->comment('逻辑删除');
            $table->timestamps();
            $table->index('goods_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pms_goods_product');
    }
}
