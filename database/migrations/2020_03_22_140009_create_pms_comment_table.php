<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_comment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('value_id')->default(0)->comment('如果type=0，则是商品id；如果是type=1，则是专题id。');
            $table->tinyInteger('type')->default(0)->comment('评论类型，如果type=0，则是商品评论；如果是type=1，则是专题评论；');
            $table->string('content',1023)->comment('评论内容');
            $table->string('admin_content',511)->nullable()->comment('管理员回复内容');
            $table->bigInteger('user_id')->comment('评论的用户id');
            $table->tinyInteger('has_picture')->default(0)->comment('是否含有图片');
            $table->string('pic_urls')->nullable()->comment('图片地址列表，采用JSON数组格式');
            $table->tinyInteger('star')->nullable()->default(5)->comment('评分， 1-5');
            $table->tinyInteger('deleted')->default(0)->comment('逻辑删除');
            $table->timestamps();
            $table->index('value_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pms_comment');
    }
}
