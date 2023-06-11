<?php

namespace Modules\Blog\Database\Seeders;

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
        /****************************模块管理******************************************/
        $pid1 = DB::table('auth_rules')->insertGetId([
            'name'=>'博客管理',
            'status'=>1,
            'auth_open'=>1,
            'path'=>'/blog',
            'pid'=>0,
            'level'=>1,
			'type'=>1,
			'sort'=>1,
			'icon'=>'fa fa-book',
            'created_at'=>date('Y-m-d H:i:s')
        ]);
		 /****************************目录******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'固定面板',
			'path'=>'/blog',
			'redirect'=>'/blog/dashboard',
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
			'url'=>'/blog/dashboard/index',
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
		/****************************内容管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'内容管理',
			'path'=>'/blog/content',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-coffee',
            'pid'=>$pid1,
            'level'=>2,
			'type'=>2,
            'sort'=>2,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************文章分类******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'文章分类',
			'path'=>'articleType/index',
			'url'=>'/blog/content/articleType/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'fa fa-sitemap',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>1,
			'created_at'=>date('Y-m-d H:i:s')
        ]);
		/****************************标签管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'标签管理',
			'path'=>'label/index',
			'url'=>'/blog/content/label/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'fa fa-gitlab',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>2,
			'created_at'=>date('Y-m-d H:i:s')
        ]);
		/****************************文章管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'文章管理',
			'path'=>'article/index',
			'url'=>'/blog/content/article/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'el-icon-postcard',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>3,
			'created_at'=>date('Y-m-d H:i:s')
        ]);

        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'文章回收站',
            'path'=>'recycle/index',
            'url'=>'/blog/content/recycle/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'el-icon-delete-solid',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>4,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'文章评论',
            'path'=>'articleComment/index',
            'url'=>'/blog/content/articleComment/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-commenting',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>5,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'文章点赞',
            'path'=>'articleLike/index',
            'url'=>'/blog/content/articleLike/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-unlink',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>6,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'文章收藏',
            'path'=>'articleCollect/index',
            'url'=>'/blog/content/articleCollect/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'el-icon-collection',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>7,
            'created_at'=>date('Y-m-d H:i:s')
        ]);

        /****************************会员管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'会员管理',
            'path'=>'/blog/user',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-user',
            'pid'=>$pid1,
            'level'=>2,
            'type'=>2,
            'sort'=>3,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************会员管理******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'会员管理',
            'path'=>'user/index',
            'url'=>'/blog/user/user/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-user',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>1,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'会员关注',
            'path'=>'attention/index',
            'url'=>'/blog/user/attention/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-user-plus',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>2,
            'created_at'=>date('Y-m-d H:i:s')
        ]);

		 /****************************配置管理******************************************/
        $pid2 = DB::table('auth_rules')->insertGetId([
            'name'=>'配置管理',
			'path'=>'/blog/deploy',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'el-icon-setting',
            'pid'=>$pid1,
            'level'=>2,
			'type'=>2,
            'sort'=>4,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************级别规则******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'级别规则',
			'path'=>'userLevel/index',
			'url'=>'/blog/deploy/userLevel/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'fa fa-align-center',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>1,
			'created_at'=>date('Y-m-d H:i:s')
        ]);
		/****************************经验值规则******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
			'name'=>'经验值规则',
			'path'=>'empiricalValue/index',
			'url'=>'/blog/deploy/empiricalValue/index',
			'status'=>1,
			'auth_open'=>1,
			'icon'=>'fa fa-empire',
			'pid'=>$pid2,
			'level'=>3,
			'type'=>3,
            'sort'=>2,
			'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************站点配置******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'图片管理',
            'path'=>'pic/index',
            'url'=>'/blog/deploy/pic/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-picture-o',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>3,
            'created_at'=>date('Y-m-d H:i:s')
        ]);
        /****************************站点配置******************************************/
        $pid3 = DB::table('auth_rules')->insertGetId([
            'name'=>'站点配置',
            'path'=>'project/index',
            'url'=>'/blog/deploy/project/index',
            'status'=>1,
            'auth_open'=>1,
            'icon'=>'fa fa-home',
            'pid'=>$pid2,
            'level'=>3,
            'type'=>3,
            'sort'=>4,
            'created_at'=>date('Y-m-d H:i:s')
        ]);

    }
}
