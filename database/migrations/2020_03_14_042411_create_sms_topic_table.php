<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_topic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 63)->comment('专题标题');
            $table->string('subtitle', 63)->nullable()->comment('专题子标题');
            $table->text('content')->nullable()->comment('专题内容，富文本格式');
            $table->decimal('price', 10,2)->default('0.00')->comment('专题相关商品最低价');
            $table->integer('read_count')->default(1000)->comment('专题阅读量');
            $table->string('pic_url',255)->nullable()->comment('专题图片');
            $table->integer('sort_order')->nullable()->default(100)->comment('排序');
            $table->string('goods',1023)->nullable()->comment('专题相关商品，采用JSON数组格式');
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
        Schema::dropIfExists('sms_topic');
    }
}
