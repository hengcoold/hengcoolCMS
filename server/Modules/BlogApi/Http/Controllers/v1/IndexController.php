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
 * @Name 博客首页相关接口
 * @Description
 * @Auther hengcool
 * @Date 2021/7/16 14:30
 */

namespace Modules\BlogApi\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\BlogApi\Http\Requests\CommonPageRequest;
use Modules\BlogApi\Services\article\ArticleService;
use Modules\BlogApi\Services\articleType\ArticleTypeService;
use Modules\BlogApi\Services\pic\PicService;

class IndexController extends BaseApiController
{
    /**
     * @name
     * @description
     * @author hengcool
     * @date 2021/7/16 14:45
     * @method  GET
     * @param
     * @return JSON
     **/
    public function typeList(){
        return (new ArticleTypeService())->typeList();
    }
    /**
     * @name 图片列表
     * @description
     * @author hengcool
     * @date 2021/7/21 3:59
     * @method  GET
     * @param  type  Int  类型:0=首页轮播图
     * @return JSON
     **/
    public function bannerList(Request $request){
        return (new PicService)->bannerList($request->get('type'));
    }

    /**
     * @name 文章列表
     * @description
     * @author hengcool
     * @date 2021/7/21 4:17
     * @method  GET
     * @param  id  Int 文章分类  一级id
     * @param  type_id INt  文章分类二级id
     * @param  title  String   文章标题模糊查询
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @return JSON
     **/
    public function articleList(CommonPageRequest $request){
        return (new ArticleService())->articleList($request->only([
            'id',
            'type_id',
            'title',
            'page',
            'limit'
        ]));
    }

    /**
     * @name 推荐文章列表
     * @description
     * @author hengcool
     * @date 2021/7/21 4:59
     * @method  GET
     * @return JSON
     **/
    public function openArticleList(){
        return (new ArticleService())->openArticleList();
    }
}
