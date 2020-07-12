<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsGrouponRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_groupon_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('goods_id');
            $table->string('goods_name', '127')->comment('商品名称');
            $table->string('pic_url',255)->nullable()->comment('商品图片或者商品货品图片');
            $table->decimal('discount', 10,2)->default(0.00)->comment('优惠金额');
            $table->integer('discount_member')->comment('达到优惠条件的人数');
            $table->timestamp('expire_time')->nullable()->comment('团购过期时间');
            $table->tinyInteger('status')->default(0)->comment('团购规则状态，正常上线则0，到期自动下线则1，管理手动下线则2');
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
        Schema::dropIfExists('sms_groupon_rules');
    }
}
