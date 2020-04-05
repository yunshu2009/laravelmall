<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmsAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ums_address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 63)->comment('收货人名称');
            $table->bigInteger('user_id')->comment('用户表的用户ID');
            $table->string('province', 31)->comment('行政区域表的省ID');
            $table->string('city',31)->comment('行政区域表的市ID');
            $table->string('country',31)->comment('行政区域表的区县ID');
            $table->string('address_detail',127)->comment('详细收货地址');
            $table->char('area_code',6)->comment('地区编码');
            $table->char('postal_code',6)->comment('邮政编码');
            $table->string('tel',20)->comment('手机号码');
            $table->tinyInteger('is_default')->comment('是否默认地址');
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
        Schema::dropIfExists('ums_address');
    }
}
