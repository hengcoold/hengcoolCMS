<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogArticleTypesTable extends Migration
{
    /**
     *所需命令行   php artisan module:make DistributionApi
     *1.创建迁移文件：php artisan module:make-migration  create_blog_pics_table Blog
     *php artisan make:migration add_images_to_articles_table --table=articles
     *2.执行迁移文件：php artisan module:migrate Admin
     *3.修改表字段：php artisan module:make-migration update_moments_table
     *4.重新执行迁移文件：php artisan module:migrate-refresh Admin
     *5.创建数据填充文件：php artisan module:make-seed  auths_table_seeder AuthAdmin
     *6.执行数据填充文件：php artisan module:seed AuthAdmin
     */
    public function up()
    {
        Schema::create('blog_article_types', function (Blueprint $table) {
            $table->comment = '文章分类表';
            $table->increments('id')->comment('文章分类ID');
            $table->string('name',100)->default('')->comment('分类名称');
            $table->integer('pid')->default(0)->comment('父级ID');
            $table->integer('sort')->default(1)->comment('排序');
			$table->tinyInteger('level')->default(1)->comment('级别');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->integer('project_id')->nullable()->comment('项目ID');
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
        Schema::dropIfExists('blog_article_types');
    }
}
