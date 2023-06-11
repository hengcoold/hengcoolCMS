<?php
//use Illuminate\Support\Facades\Schema;
use Jialeo\LaravelSchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthsTable extends Migration
{
    /**
     *所需命令行   php artisan module:make DistributionApi
     *1.创建迁移文件：php artisan module:make-migration  create_auths_table Admin
     *php artisan make:migration add_images_to_articles_table --table=articles
     *2.执行迁移文件：php artisan module:migrate Admin
     *3.修改表字段：php artisan module:make-migration update_moments_table
     *4.重新执行迁移文件：php artisan module:migrate-refresh Admin
     *5.创建数据填充文件：php artisan module:make-seed  auths_table_seeder AuthAdmin
     *6.执行数据填充文件：php artisan module:seed AuthAdmin
     */
    public function up()
    {
        /**
         * 管理员表
         */
        Schema::create('auth_admins', function (Blueprint $table) {
            $table->comment = '管理员表';
            $table->increments('id')->comment('管理员ID');
            $table->string('name',100)->default('')->comment('名称');
            $table->string('phone',100)->default('')->comment('手机号');
            $table->string('username',50)->unique()->default('')->comment('账号');
            $table->string('password')->default('')->comment('密码');
            $table->integer('group_id')->nullable()->comment('权限组ID');
            $table->integer('project_id')->nullable()->comment('项目ID');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });

        /**
         * 权限组表
         */
        Schema::create('auth_groups', function (Blueprint $table) {
            $table->comment = '权限组表';
            $table->increments('id')->comment('权限组ID');
            $table->string('name',100)->unique()->default('')->comment('权限组名称');
            $table->string('content',100)->nullable()->default('')->comment('描述');
            $table->tinyInteger('status')->default(1)->comment('状态:0=禁用,1=启用');
            $table->longtext('rules')->nullable()->comment('权限规则多个用|隔开');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
        /**
         * 权限列表
         */
        Schema::create('auth_rules', function (Blueprint $table) {
            $table->comment = '权限表';
            $table->increments('id')->comment('权限列表ID');
            $table->string('path',100)->nullable()->default('')->comment('标识');
            $table->string('url',100)->nullable()->default('')->comment('路由文件');
            $table->string('redirect',100)->nullable()->default('')->comment('重定向路径');
            $table->string('name',100)->default('')->comment('权限名称');
			$table->tinyInteger('type')->default(1)->comment('菜单类型:1=模块,2=目录,3=菜单');
            $table->tinyInteger('status')->default(1)->comment('侧边栏显示状态:0=隐藏,1=显示');
            $table->tinyInteger('auth_open')->default(1)->comment('是否验证权限:0=否,1=是');
            $table->tinyInteger('level')->default(1)->comment('级别');
            $table->tinyInteger('affix')->default(0)->comment('是否固定面板:0=否,1=是');
            $table->string('icon',50)->nullable()->default('')->comment('图标名称');
            $table->integer('pid')->default(0)->comment('父级ID');
            $table->integer('sort')->default(1)->comment('排序');
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
        Schema::dropIfExists('auth_admins');
        Schema::dropIfExists('auth_groups');
        Schema::dropIfExists('auth_rules');
    }
}
