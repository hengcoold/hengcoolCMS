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
 * @Name 文章评论控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/7/2 13:38
 */

namespace Modules\Blog\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommonIdRequest;
use Modules\Blog\Http\Requests\CommonSortRequest;
use Modules\Blog\Http\Requests\CommonStatusRequest;
use Modules\Blog\Services\articleComment\ArticleCommentService;

class ArticleCommentController extends BaseApiController
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  title String 文章标题
     * @param  pid   Int   上级id
     * @param  created_at String 创建时间
     * @param  nickname String 昵称
     * @param  status Int 状态:0=禁用,1=启用
     * @return JSON
     **/
    public function index(Request $request)
    {
        return (new ArticleCommentService())->index($request->only([
            'title',
            'created_at',
            'pid',
            'nickname',
            'status'
        ]));
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  DELETE
     * @param id Int 权限id
     * @return JSON
     **/
    public function cDestroy(CommonIdRequest $request)
    {
        return (new ArticleCommentService())->cDestroy($request->get('id'),$request->get('article_id'));
    }

    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 评论ID
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new ArticleCommentService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/14 10:02
     * @method  PUT
     * @param  id Int 评论ID
     * @param  sort Int 排序
     * @return JSON
     **/
    public function sorts(CommonSortRequest $request)
    {
        return (new ArticleCommentService())->sorts($request->get('id'),$request->only([
            'sort'
        ]));
    }
}
