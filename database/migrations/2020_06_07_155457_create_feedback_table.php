<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('用户表的用户ID');
            $table->string('username', 63)->comment('用户名称');
            $table->string('mobile', 20)->comment('手机号');
            $table->tinyInteger('feed_type')->default(4)->comment('反馈类型 1:商品相关 2:功能异常 3:优化建议 4:其它');
            $table->string('content',1023)->comment('反馈内容');
            $table->tinyInteger('status')->comment('状态：1：审核 0：未审核');
            $table->tinyInteger('has_picture')->comment('是否含有图片');
            $table->string('pic_urls',1023)->comment('图片地址列表，采用JSON数组格式');
            $table->tinyInteger('deleted')->default(0)->comment('逻辑删除');
            $table->timestamps();

            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
