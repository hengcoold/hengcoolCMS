<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_pics', function (Blueprint $table) {
            $table->comment = '图片管理表';
            $table->increments('id')->comment('图片管理ID');
            $table->integer('project_id')->comment('项目ID');
            $table->string('content')->nullable()->comment('图片描述');
            $table->string('url')->nullable()->comment('跳转地址');
            $table->integer('image_id')->nullable()->comment('图片id');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->integer('sort')->default(1)->comment('排序');
            $table->tinyInteger('type')->default(0)->comment('类型:0=首页轮播图');

            $table->tinyInteger('is_delete')->default(0)->comment('是否删除:0=否,1=是');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
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
        Schema::dropIfExists('blog_pics');
    }
}
