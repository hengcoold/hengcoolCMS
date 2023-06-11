<?php

//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_projects', function (Blueprint $table) {
            $table->comment = '平台项目表';
            $table->increments('id')->comment('项目ID');
            $table->string('name',100)->default('')->comment('项目名称');
            $table->integer('logo_id')->nullable()->comment('站点logo');
            $table->integer('ico_id')->nullable()->comment('站点标识');
            $table->string('url',100)->default('')->comment('项目地址');
            $table->string('description')->default('')->comment('项目描述');
            $table->string('keywords')->default('')->comment('项目关键词');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
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
        Schema::dropIfExists('auth_projects');
    }
}
