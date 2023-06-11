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
 * @Name 文章点赞控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/7/2 16:09
 */

namespace Modules\Blog\Http\Controllers\v1;


use Modules\Blog\Http\Requests\CommonPageRequest;
use Modules\Blog\Services\articleLike\ArticleLikeService;

class ArticleLikeController extends BaseApiController
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @param  nickname String 昵称
     * @param  title String 文章标题
     * @param  created_at String 创建时间
     * @param  updated_at String 更新时间
     * @return JSON
     **/
    public function index(CommonPageRequest $request)
    {
        return (new ArticleLikeService())->index($request->only([
            'page',
            'limit',
            'nickname',
            'title',
            'created_at',
            'updated_at'
        ]));
    }
}
