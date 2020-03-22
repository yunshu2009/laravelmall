<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('goods_sn', 63)->comment('商品编号');
            $table->string('name', 127)->comment('商品名称');
            $table->bigInteger('category_id')->comment('商品所属类目ID');
            $table->bigInteger('brand_id')->comment('品牌id');
            $table->string('gallery', 1023)->comment('商品宣传图片列表，采用JSON数组格式');
            $table->string('keywords', 255)->nullable()->comment('商品关键字，采用逗号间隔');
            $table->string('brief', 255)->comment('商品简介');
            $table->tinyInteger('is_on_sale')->default(1)->comment('是否上架');
            // 0 - 65 535
            $table->smallInteger('sort_order')->default(100);
            $table->string('pic_url', 255)->comment('商品页面商品图片');
            $table->string('share_url', 255)->comment('商品分享朋友圈图片');
            $table->tinyInteger('is_new')->default(0)->comment('是否新品首发，如果设置则可以在新品首发页面展示');
            $table->tinyInteger('is_hot')->default(0)->comment('是否人气推荐，如果设置则可以在人气推荐页面展示');
            $table->string('unit', 31)->default('件')->comment('商品单位，例如件、盒');
            $table->decimal('counter_price', 10,2)->default(0.00)->comment('专柜价格');
            $table->decimal('retail_price', 10,2)->comment('零售价格');
            $table->text('detail')->nullable()->comment('商品详细介绍，是富文本格式');
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
        Schema::dropIfExists('pms_goods');
    }
}
