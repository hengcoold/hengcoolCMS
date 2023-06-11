<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_areas', function (Blueprint $table) {
            $table->comment = '地区表';
            $table->increments('id')->comment('地区表ID');
            $table->integer('pid')->default(0)->comment('父级ID');
            $table->string('name',50)->default('')->comment('名称');
            $table->string('short_name',50)->default('')->comment('简称');
            $table->tinyInteger('level_type')->default(1)->comment('级别');
            $table->integer('city_code')->nullable()->comment('区号');
            $table->integer('zip_code')->nullable()->comment('邮编');
            $table->string('lng',50)->nullable()->comment('经度');
            $table->string('lat',50)->nullable()->comment('纬度');
            $table->string('pinyin',50)->nullable()->comment('拼音');
            $table->tinyInteger('status')->default(1)->comment('显示状态:0=隐藏,1=显示');
            $table->integer('sort')->default(1)->comment('排序');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_areas');
    }
}
