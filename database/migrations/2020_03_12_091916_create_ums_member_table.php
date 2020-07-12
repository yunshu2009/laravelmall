<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmsMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ums_member', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 63)->comment('用户名称');
            $table->string('password', 63)->comment('用户密码');
            $table->tinyInteger('gender')->default(0)->comment('性别：0 未知， 1男， 1 女');
            $table->date('birthday')->nullable(true)->comment('生日');
            $table->timestamp('last_login_time')->nullable(true)->comment('最近一次登录时间');
            $table->bigInteger('last_login_ip')->comment('最近一次登录IP地址');
            $table->tinyInteger('member_level')->default(0)->comment('0 普通用户，1 VIP用户，2 高级VIP用户');
            $table->string('nickname', 63)->comment('用户昵称');
            $table->string('mobile',20)->nullable(true)->comment('用户手机号码');
            $table->string('avatar', 255)->comment('用户头像图片');
            $table->string('weixin_openid', 63)->comment('微信登录openid');
            $table->string('session_key', 100)->nullable()->comment('微信登录会话KEY');
            $table->tinyInteger('status')->define(0)->comment('0 可用, 1 禁用, 2 注销');
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
        Schema::dropIfExists('ums_member');
    }
}
