<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogArticleLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_labels', function (Blueprint $table) {
            $table->comment = '标签表';
            $table->increments('id')->comment('标签ID');
			$table->integer('project_id')->nullable()->comment('项目ID');
            $table->string('name',100)->default('')->comment('标签名称');
            $table->integer('sort')->default(1)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        Schema::create("blog_article_labels", function(Blueprint $table){
            $table->comment = '文章标签关联表';
            $table->increments('id');
            $table->integer("article_id")->comment('文章ID');
            $table->integer("label_id")->comment('标签ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_labels');
        Schema::dropIfExists('blog_article_labels');
    }
}
