<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsGrouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_groupon', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->comment('关联的订单ID');
            $table->integer('groupon_id')->comment('如果是开团用户，则groupon_id是0；如果是参团用户，则groupon_id是团购活动ID');
            $table->integer('rule_id')->comment('团购规则ID，关联sms_groupon_rules表ID字段');
            $table->bigInteger('user_id')->comment(' 用户id');
            $table->string('share_url',255)->comment(' 团购分享图片地址');
            $table->integer('creator_user_id')->comment('开团用户ID');
            $table->tinyInteger('status')->default(0)->comment('团购活动状态，开团未支付则0，开团中则1，开团失败则2');
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
        Schema::dropIfExists('sms_groupon');
    }
}
