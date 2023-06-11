<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_operation_logs', function (Blueprint $table) {
            $table->comment = '操作日志表';
            $table->increments('id')->comment('操作日志ID');
            $table->string('content',200)->default('')->comment('操作描述');
            $table->string('url',200)->default('')->comment('操作路由');
            $table->string('method',10)->default('')->comment('请求方式');
            $table->string('ip',100)->default('')->comment('请求ip');
            $table->integer('admin_id')->nullable()->comment('管理员id');
            $table->longtext('data')->nullable()->comment('请求数据');
            $table->longtext('header')->nullable()->comment('请求header');
            $table->timestamp('created_at')->nullable()->comment('操作时间');
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
        Schema::dropIfExists('auth_operation_logs');
    }
}
