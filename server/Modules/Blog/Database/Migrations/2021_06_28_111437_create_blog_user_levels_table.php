<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogUserLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_user_levels', function (Blueprint $table) {
            $table->comment = '用户级别规则表';
            $table->increments('id')->comment('级别规则ID');
            $table->integer('project_id')->comment('项目ID');
            $table->string('name')->default('')->comment('级别名称');
            $table->longtext('content')->nullable()->comment('规则描述');
            $table->integer('image_id')->nullable()->comment('规则图片id');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->integer('sort')->default(1)->comment('排序');
            $table->integer('start_experience')->default(1)->comment('开始经验值');
            $table->integer('end_experience')->default(1)->comment('结束经验值');
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
        Schema::dropIfExists('blog_user_levels');
    }
}
