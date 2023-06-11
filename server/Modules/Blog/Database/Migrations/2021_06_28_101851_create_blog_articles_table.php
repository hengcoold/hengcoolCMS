<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_articles', function (Blueprint $table) {
            $table->comment = '文章表';
            $table->increments('id')->comment('文章ID');
            $table->integer('type_id')->comment('所属分类');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('project_id')->comment('项目ID');
            $table->string('title')->default('')->comment('文章标题');
            $table->string('description')->default('')->comment('文章描述');
            $table->longtext('content')->nullable()->comment('文章内容');
            $table->integer('image_id')->nullable()->comment('图片id');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->tinyInteger('open')->default(0)->comment('是否推荐:0=否,1=是');
            $table->integer('sort')->default(1)->comment('排序');
            $table->string('ip',100)->default('')->comment('用户发文章时ip');
            $table->string('lng',50)->nullable()->comment('经度');
            $table->string('lat',50)->nullable()->comment('纬度');
            $table->integer('province_id')->nullable()->comment('省ID');
            $table->integer('city_id')->nullable()->comment('市ID');
            $table->integer('county_id')->nullable()->comment('区县ID');
            $table->string('address',200)->default('')->comment('用户发文章时详细地址');
            $table->tinyInteger('is_delete')->default(0)->comment('是否删除:0=否,1=是');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        Schema::create('blog_article_collects', function (Blueprint $table) {
            $table->comment = '文章收藏表';
            $table->increments('id')->comment('文章收藏ID');
			$table->integer('project_id')->nullable()->comment('项目ID');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('article_id')->nullable()->comment('文章ID');
            $table->tinyInteger('status')->default(1)->comment('是否收藏:0=否,1=是');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        Schema::create('blog_article_likes', function (Blueprint $table) {
            $table->comment = '文章点赞表';
            $table->increments('id')->comment('文章点赞ID');
			$table->integer('project_id')->nullable()->comment('项目ID');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('article_id')->nullable()->comment('文章ID');
            $table->tinyInteger('status')->default(1)->comment('是否点赞:0=否,1=是');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        Schema::create('blog_article_comments', function (Blueprint $table) {
            $table->comment = '文章评论表';
            $table->increments('id')->comment('文章评论ID');
			$table->integer('project_id')->nullable()->comment('项目ID');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('article_id')->nullable()->comment('文章ID');
            $table->integer('pid')->default(0)->comment('父级ID');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->text('content')->nullable()->comment('评论内容');
            $table->integer('sort')->default(50)->comment('排序');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        Schema::create('blog_article_pvs', function (Blueprint $table) {
            $table->comment = '文章浏览量表';
            $table->increments('id')->comment('文章浏览ID');
			$table->integer('project_id')->nullable()->comment('项目ID');
            $table->integer('user_id')->nullable()->comment('用户ID');
            $table->integer('article_id')->nullable()->comment('文章ID');
            $table->string('ip',100)->default('')->comment('浏览文章时ip');
            $table->string('lng',50)->nullable()->comment('浏览文章时经度');
            $table->string('lat',50)->nullable()->comment('浏览文章时纬度');
            $table->integer('province_id')->nullable()->comment('浏览文章时省ID');
            $table->integer('city_id')->nullable()->comment('浏览文章时市ID');
            $table->integer('county_id')->nullable()->comment('浏览文章时区县ID');
            $table->string('address',200)->default('')->comment('浏览文章时详细地址');
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
        Schema::dropIfExists('blog_articles');
        Schema::dropIfExists('blog_article_collects');
        Schema::dropIfExists('blog_article_likes');
        Schema::dropIfExists('blog_article_comments');
        Schema::dropIfExists('blog_article_pvs');
    }
}
