<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class AuthsTableSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1;$i<8;$i++){
            $j = rand(10,99);
            for($x=0;$x<$j;$x++){
                DB::table('auth_operation_logs')->insert([
                    'content' => '测试log',
                    'url' => 'log',
                    'method' => 'GET',
                    'ip' => '127.0.0.1',
                    'admin_id'=>1,
                    'created_at'=>date('Y-m-d H:i:s',strtotime("-{$i} day"))
                ]);
            }
        }
        DB::table('auth_users')->insertGetId([
            'name' => '宋博',
            'nickname'=> '大象',
            'phone' => '18092444782',
            'email'=>'260894671@qq.com',
            'password'=>bcrypt('123456'),
            'province_id'=>610000,
            'city_id'=>610100,
            'county_id'=>610113,
            'status'=>1,
            'sex'=>1,
            'birth'=>'1993-09-20',
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        $image_id1 = DB::table('auth_images')->insertGetId([
            'url' => '/upload/images/common/logo.png',
            'open' => 1,
            'status'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        DB::table('auth_configs')->insert([
            'name' => 'hengcoolCMS管理系统',
            'image_status' => 1,
            'logo_id' => $image_id1,
            'about_us' => '1',
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        $group_id = DB::table('auth_groups')->insertGetId([
            'name' => '超级管理员',
            'created_at'=>date('Y-m-d H:i:s'),
            'rules'=>'1|2']);
        $project_id = DB::table('auth_projects')->insertGetId([
            'name' => '测试博客',
            'created_at'=>date('Y-m-d H:i:s'),
            'url'=>'www.baidu.com',
			'description'=>'测试描述',
			'keywords'=>'测试关键词',
			'logo_id'=>$image_id1,
			'ico_id'=>$image_id1,
		]);
        DB::table('auth_admins')->insert([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'group_id' => $group_id,
            'project_id' => $project_id,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
		  /****************************模块管理******************************************/
        $pid1 = DB::table('auth_rules')->insertGetId([
            'name'=>'系统管理',
            'status'=>1,
            'auth_open'=>1,
            'path'=>'/admin',
            'pid'=>0,
            'level'=>1,
			'type'=>1,
			'sort'=>1,
			'icon'=>'fa fa-home',
            'created_at'=>date('Y-m-d H:i:s')
        ]);
		 /****************************目录******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'固定面板',
			'path'=>'/admin',
			'redirect'=>'/admin/dashboard',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-bar-chart',
            'pid'=>$pid1,
            'level'=>2,
			'type'=>2,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************数据看板******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'数据看板',
			'path'=>'dashboard',
			'url'=>'/admin/dashboard/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'fa fa-bar-chart-o',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
			'affix'=>1,
            'sort'=>1,
			'created_at'=>date('Y-m-d H:i:s')
        ]);

		 /****************************权限管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'权限管理',
			'path'=>'/admin/auth',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-lock',
            'pid'=>$pid1,
            'level'=>2,
			'type'=>2,
            'sort'=>2,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************管理员列表******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'管理员列表',
			'path'=>'admin/index',
			'url'=>'/admin/auth/admin/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'el-icon-user-solid',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>1,
			'created_at'=>date('Y-m-d H:i:s')
        ]);
		/****************************权限组列表******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'权限组列表',
			'path'=>'group/index',
			'url'=>'/admin/auth/group/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'fa fa-life-bouy',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>2,
			'created_at'=>date('Y-m-d H:i:s')
        ]);
		/****************************菜单管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'菜单管理',
			'path'=>'rule/index',
			'url'=>'/admin/auth/rule/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'fa fa-sitemap',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>3,
			'created_at'=>date('Y-m-d H:i:s')
        ]);

        /****************************基础信息管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'基础信息管理',
            'path'=>'/admin/config',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-windows',
            'pid'=>$pid1,
            'level'=>2,
            'type'=>2,
            'sort'=>3,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************系统配置******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'系统配置',
            'path'=>'config/index',
            'url'=>'/admin/config/config/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-gear',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************地区列表******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'地区列表',
            'path'=>'area/index',
            'url'=>'/admin/config/area/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'el-icon-location',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>2,
            'created_at'=>date('Y-m-d H:i:s')
        ]);

        /****************************数据库管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'数据库管理',
            'path'=>'/admin/dataBase',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-database',
            'pid'=>$pid1,
            'level'=>2,
            'type'=>2,
            'sort'=>4,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************数据表管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'数据表管理',
            'path'=>'index/index',
            'url'=>'/admin/dataBase/index/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-table',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************备份管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'备份管理',
            'path'=>'restoreData/index',
            'url'=>'/admin/dataBase/restoreData/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-window-restore',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************项目管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'项目管理',
            'path'=>'/admin/project',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-home',
            'pid'=>$pid1,
            'level'=>2,
            'type'=>2,
            'sort'=>5,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************项目管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'项目管理',
            'path'=>'project/index',
            'url'=>'/admin/project/project/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-home',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);

        /****************************会员管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'会员管理',
            'path'=>'/admin/user',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-user',
            'pid'=>$pid1,
            'level'=>2,
            'type'=>2,
            'sort'=>6,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************会员管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'会员管理',
            'path'=>'user/index',
            'url'=>'/admin/user/user/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-user',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);

        /****************************日志管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'日志管理',
            'path'=>'/admin/log',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-file-text',
            'pid'=>$pid1,
            'level'=>2,
            'type'=>2,
            'sort'=>7,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************操作日志******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'操作日志',
            'path'=>'operationLog/index',
            'url'=>'/admin/log/operationLog/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-edit',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
    }
}
