<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_users', function (Blueprint $table) {
            $table->comment = '平台用户表';
            $table->increments('id')->comment('用户ID');
            $table->string('name',100)->default('')->comment('姓名');
            $table->string('nickname',100)->default('')->comment('昵称');
            $table->string('phone',100)->unique()->default('')->comment('手机号');
            $table->string('email',50)->unique()->default('')->comment('邮箱');
            $table->string('password')->default('')->comment('密码');
            $table->integer('province_id')->nullable()->comment('省ID');
            $table->integer('city_id')->nullable()->comment('市ID');
            $table->integer('county_id')->nullable()->comment('区县ID');
            $table->tinyInteger('status')->default(1)->comment('状态:0=拉黑,1=正常');
            $table->tinyInteger('sex')->default(0)->comment('性别:0=未知,1=男，2=女');
            $table->string('birth',20)->default('')->comment('出生年月日');
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
        Schema::dropIfExists('auth_users');
    }
}
