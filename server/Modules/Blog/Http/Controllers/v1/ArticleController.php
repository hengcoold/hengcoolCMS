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
 * @Name 文章管理控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 16:31
 */

namespace Modules\Blog\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommonIdArrRequest;
use Modules\Blog\Http\Requests\CommonIdRequest;
use Modules\Blog\Http\Requests\CommonPageRequest;
use Modules\Blog\Http\Requests\CommonSortRequest;
use Modules\Blog\Http\Requests\CommonStatusRequest;
use Modules\Blog\Services\article\ArticleService;

class ArticleController extends BaseApiController
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @param  title String 文章标题
     * @param  status Int 状态:0=禁用,1=启用
     * @param  open Int 是否推荐:0=否,1=是
     * @param  nickname String 用户昵称
     * @param  type_id Int 所属分类
     * @param  created_at String 创建时间
     * @param  updated_at String 更新时间
     * @return JSON
     **/
    public function index(CommonPageRequest $request)
    {
        return (new ArticleService())->index($request->only([
            'page',
            'limit',
            'title',
            'open',
            'nickname',
            'type_id',
            'status',
            'created_at',
            'updated_at'
        ]));
    }
    /**
     * @name 获取文章分类列表
     * @description
     * @author hengcool
     * @date 2021/6/28 16:57
     * @method  GET
     * @return JSON
     **/
    public function getArticleTypeList(){
        return (new ArticleService())->getArticleTypeList();
    }
    /**
     * @name 获取文章标签列表
     * @description
     * @author hengcool
     * @date 2021/6/28 16:57
     * @method  GET
     * @return JSON
     **/
    public function getLabelList(Request $request){
        return (new ArticleService())->getLabelList($request->get('nameArr'));
    }
    /**
     * @name 获取用户列表
     * @description
     * @author hengcool
     * @date 2021/6/28 16:57
     * @method  GET
     * @return JSON
     **/
    public function getUserList(Request $request){
        return (new ArticleService())->getUserList($request->get('nickname'));
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/14 8:42
     * @method  POST
     * @param  type_id Int 所属分类
     * @param  user_id Int 用户ID
     * @param  title String 文章标题
     * @param  description String 文章描述
     * @param  keywords String 文章关键词
     * @param  content String 文章内容
     * @param  image_id Int 图片id
     * @param  status Int 状态:0=禁用,1=启用
     * @param  open Int 是否推荐:0=否,1=是
     * @param  sort Int 排序
     * @param  labelArr Array 多个标签
     * @return JSON
     **/
    public function store(Request $request)
    {
        return (new ArticleService())->store($request->only([
            'type_id',
            'user_id',
            'title',
            'description',
            'keywords',
            'content',
            'image_id',
            'status',
            'open',
            'sort',
            'labelArr'
        ]));
    }
    /**
     * @name 编辑页面
     * @description
     * @author hengcool
     * @date 2021/6/14 8:53
     * @method  GET
     * @param  id Int 文章ID
     * @return JSON
     **/
    public function edit(CommonIdRequest $request)
    {
        return (new ArticleService())->edit($request->get('id'));
    }
    /**
     * @name 编辑提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 文章ID
     * @param  type_id Int 所属分类
     * @param  user_id Int 用户ID
     * @param  title String 文章标题
     * @param  description String 文章描述
     * @param  keywords String 文章关键词
     * @param  content String 文章内容
     * @param  image_id Int 图片id
     * @param  status Int 状态:0=禁用,1=启用
     * @param  open Int 是否推荐:0=否,1=是
     * @param  sort Int 排序
     * @param  labelArr Array 多个标签
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new ArticleService())->update($request->get('id'),$request->only([
            'type_id',
            'user_id',
            'title',
            'description',
            'keywords',
            'content',
            'image_id',
            'status',
            'open',
            'sort',
            'labelArr'
        ]));
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 文章ID
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new ArticleService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 是否推荐
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 文章ID
     * @param  open Int 是否推荐:0=否,1=是
     * @return JSON
     **/
    public function open(Request $request)
    {
        return (new ArticleService())->open($request->get('id'),$request->only(['open']));
    }
    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/14 10:02
     * @method  PUT
     * @param  id Int 文章ID
     * @param  sort Int 排序
     * @return JSON
     **/
    public function sorts(CommonSortRequest $request)
    {
        return (new ArticleService())->sorts($request->get('id'),$request->only([
            'sort'
        ]));
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  DELETE
     * @param id Int 文章ID
     * @return JSON
     **/
    public function cDestroy(CommonIdRequest $request)
    {
        return (new ArticleService())->cDestroy($request->get('id'));
    }
    /**
     * @name 批量删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  DELETE
     * @param idArr Array id数组
     * @return JSON
     **/
    public function cDestroyAll(CommonIdArrRequest $request)
    {
        return (new ArticleService())->cDestroyAll($request->get('idArr'));
    }
    /**
     * @name 生成标题 摘要 标签
     * @description
     * @author hengcool
     * @date 2021/7/1 13:54
     * @method  POST
     * @param status Int 1=获取标题  2=获取摘要   3=获取标签
     * @param content String 文章内容
     * @return JSON
     **/
    public function generateContent(Request $request){
        return (new ArticleService())->generateContent($request->only([
            'status',
            'content'
        ]));
    }
    /**
     * @name 文章回收站列表
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @param  title String 文章标题
     * @param  deleted_at String 创建时间
     * @return JSON
     **/
    public function recycleIndex(Request $request)
    {
        return (new ArticleService())->recycleIndex($request->only([
            'page',
            'limit',
            'title',
            'deleted_at'
        ]));
    }

    /**
     * @name 恢复
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  PUT
     * @param id Int id
     * @return JSON
     **/
    public function recycle(CommonIdRequest $request)
    {
        return (new ArticleService())->recycle($request->get('id'));
    }
    /**
     * @name 批量恢复
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  PUT
     * @param idArr Array id数组
     * @return JSON
     **/
    public function recycleAll(CommonIdArrRequest $request)
    {
        return (new ArticleService())->recycleAll($request->get('idArr'));
    }
}
