<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogUserAttentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_user_attentions', function (Blueprint $table) {
            $table->comment = '用户关注表';
            $table->increments('id')->comment('用户关注ID');
			$table->integer('project_id')->nullable()->comment('项目ID');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('user_attention_id')->default(1)->comment('关注用户ID');
            $table->tinyInteger('status')->default(1)->comment('是否关注:0=否,1=是');
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
        Schema::dropIfExists('blog_user_attentions');
    }
}
