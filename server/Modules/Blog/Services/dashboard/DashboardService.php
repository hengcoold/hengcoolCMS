<?php
// +----------------------------------------------------------------------
// | Name: hengcool管理系统 [ 为了快速搭建软件应用而生的，希望能够帮助到大家提高开发效率。 ]
// +----------------------------------------------------------------------
// | Copyright: (c) 2020~2021 https://www.lvacms.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed: 这是一个自由软件，允许对程序代码进行修改，但希望您留下原有的注释。
// +----------------------------------------------------------------------
// | Author: hengcool <260894671@qq.com>
// +----------------------------------------------------------------------
// | Version: V1
// +----------------------------------------------------------------------

/**
 * @Name
 * @Description
 * @Auther hengcool
 * @Date 2021/7/3 17:10
 */

namespace Modules\Blog\Services\dashboard;


use Modules\Blog\Models\AuthProject;
use Modules\Blog\Models\BlogArticle;
use Modules\Blog\Models\BlogArticleLike;
use Modules\Blog\Models\BlogArticlePv;
use Modules\Blog\Models\BlogUserInfo;
use Modules\Blog\Services\BaseApiService;

class DashboardService extends BaseApiService
{
    public function index(){
        $list = [
            'blog_article_count'=>BlogArticle::where(['project_id'=>$this->projectId,'status'=>1])->count(),
            'auth_project_name'=>AuthProject::where(['id'=>$this->projectId])->value('name'),
            'blog_article_pv_count'=>BlogArticlePv::where(['project_id'=>$this->projectId])->count(),
            'blog_user_info_count'=>BlogUserInfo::where(['project_id'=>$this->projectId,'status'=>1])->count(),
            'blog_article_like_count'=>BlogArticleLike::where(['project_id'=>$this->projectId,'status'=>1])->count()
        ];
        return $this->apiSuccess('',$list);
    }
}
