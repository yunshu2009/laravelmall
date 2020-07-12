<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUmsAdminRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ums_admin_role', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',31)->comment('角色名称');
            $table->string('desc')->nullable()->nullable()->comment('角色描述');
            $table->tinyInteger('enabled')->default(1)->comment('是否启用');
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
        Schema::dropIfExists('ums_admin_role');
    }
}
