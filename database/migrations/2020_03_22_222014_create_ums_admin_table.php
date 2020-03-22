<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmsAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ums_admin', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username',31)->comment('管理员名称');
            $table->string('password',63)->comment('管理员密码');
            $table->bigInteger('last_login_ip')->default(0)->comment('最近一次登录IP地址');
            $table->timestamp('last_login_time')->comment('最近一次登录时间');
            $table->string('avatar', 255)->nullable()->comment('头像图片');
            $table->tinyInteger('deleted')->default(0)->comment('逻辑删除');
            $table->integer('role_ids')->nullable()->comment('角色列表');
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
        Schema::dropIfExists('ums_admin');
    }
}
