<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsGoodsSpecificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_goods_specification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('goods_id')->comment('商品表的商品ID');
            $table->string('specification', 127)->comment('商品规格名称');
            $table->string('value', 255)->comment('商品规格值');
            $table->string('pic_url',255)->nullable()->comment('商品规格图片');
            $table->integer('deleted')->nullable()->default(0)->comment('逻辑删除');
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
        Schema::dropIfExists('pms_goods_specification');
    }
}
