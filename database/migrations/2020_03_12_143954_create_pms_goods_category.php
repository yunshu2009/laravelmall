<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsGoodsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_goods_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->comment('类目名称');
            $table->string('keywords', 255)->comment('类目关键字，以JSON数组格式');
            $table->string('desc', 255)->nullable(true)->comment('类目广告语介绍');
            $table->bigInteger('pid')->default(0)->comment('父类目ID');
            $table->string('icon_url', 255)->nullable(true)->comment('类目图标');
            $table->string('pic_url', 255)->nullable(true)->comment('类目图片');
            $table->string('level', 10)->nullable(true)->default('L1')->comment('层级');
            $table->tinyInteger('sort_order')->nullable(true)->default(0)->comment('排序');
            $table->tinyInteger('deleted')->nullable(true)->default(0)->comment('是否删除');
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
        Schema::dropIfExists('pms_goods_category');
    }
}
