<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_configs', function (Blueprint $table) {
            $table->comment = '系统配置表';
            $table->increments('id')->comment('系统配置ID');
            $table->string('name',100)->default('')->comment('站点名称');
            $table->tinyInteger('image_status')->nullable()->comment('图片储存:1=本地,2=七牛云');
            $table->integer('logo_id')->nullable()->comment('站点logo');
            $table->text('about_us')->nullable()->comment('关于我们');
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
        Schema::dropIfExists('auth_configs');
    }
}
