<?php
use Jialeo\LaravelSchemaExtend\Schema;
//use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_images', function (Blueprint $table) {
            $table->comment = '图片表';
            $table->increments('id')->comment('图片ID');
            $table->string('url',150)->default('')->comment('图片路径');
            $table->tinyInteger('status')->default(1)->comment('状态:0=已删除,1=应用中');
            $table->tinyInteger('open')->default(1)->comment('类型:1=本地,2=七牛云');
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
        Schema::dropIfExists('auth_images');
    }
}
