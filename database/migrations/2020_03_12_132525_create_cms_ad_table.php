<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsAdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_ad', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 63)->comment('广告标题');
            $table->string('link', 255)->comment('所广告的商品页面或者活动页面链接地址');
            $table->string('url', 255)->comment('广告宣传图片');
            $table->tinyInteger('position')->nullable(true)->default(1)->comment('广告位置：1则是首页');
            $table->string('content', 255)->comment('活动内容');
            $table->timestamp('start_time')->nullable(true)->comment('广告开始时间');
            $table->timestamp('end_time')->nullable(true)->comment('广告结束时间');
            $table->tinyInteger('enabled')->default(0)->comment('是否启动');
            $table->tinyInteger('deleted')->default(0)->comment('是否删除');
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
        Schema::dropIfExists('cms_ad');
    }
}
