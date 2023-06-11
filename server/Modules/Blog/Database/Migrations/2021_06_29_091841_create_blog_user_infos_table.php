<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_user_infos', function (Blueprint $table) {
            $table->comment = '博客用户信息表';
            $table->increments('id')->comment('用户ID');
			$table->integer('user_id')->nullable()->comment('平台用户表ID');
            $table->integer('project_id')->comment('项目ID');
            $table->integer('empirical_value')->default(0)->comment('用户经验值');
            $table->tinyInteger('status')->default(1)->comment('状态:0=拉黑,1=正常');
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
        Schema::dropIfExists('blog_user_infos');
    }
}
