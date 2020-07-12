<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_brand', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 63)->comment('品牌商名称');
            $table->string('desc', 255)->comment('品牌商简介');
            $table->string('pic_url', 255)->comment('品牌商页的品牌商图片');
            $table->integer('sort_order')->default(100)->comment('排序');
            $table->decimal('floor_price', 10,2)->default(0.00)->comment('品牌商的商品低价，仅用于页面展示');
            $table->tinyInteger('deleted')->comment('是否删除');
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
        Schema::dropIfExists('pms_brand');
    }
}
