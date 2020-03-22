<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePmsCollectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pms_collect', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->bigInteger('value_id');
            $table->tinyInteger('type')->default(0)->comment('收藏类型，如果type=0，则是商品ID；如果type=1，则是专题ID');
            $table->tinyInteger('deleted')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'value_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pms_collect');
    }
}
