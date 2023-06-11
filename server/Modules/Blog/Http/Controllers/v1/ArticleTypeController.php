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
 * @Name 文章分类控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 15:29
 */

namespace Modules\Blog\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonPidRequest;
use Modules\Blog\Http\Requests\CommonIdRequest;
use Modules\Blog\Http\Requests\CommonPageRequest;
use Modules\Blog\Http\Requests\CommonSortRequest;
use Modules\Blog\Http\Requests\CommonStatusRequest;
use Modules\Blog\Services\articleType\ArticleTypeService;

class ArticleTypeController extends BaseApiController
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  name String 标签名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  created_at String 创建时间
     * @param  updated_at String 更新时间
     * @return JSON
     **/
    public function index(Request $request)
    {
        return (new ArticleTypeService())->index($request->only([
            'name',
            'status',
            'created_at',
            'updated_at'
        ]));
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/14 8:42
     * @method  POST
     * @param  name String 分类名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  pid Int 父级id
     * @param  sort Int 排序
     * @return JSON
     **/
    public function store(Request $request)
    {
        return (new ArticleTypeService())->store($request->only([
            'name',
            'status',
            'pid',
            'sort',
			'level'
        ]));
    }
    /**
     * @name 添加子级返回父级id
     * @description
     * @author hengcool
     * @date 2021/6/19 17:27
     * @method  GET
     * @param  pid Int 父级id
     * @return JSON
     **/
    public function pidArr(CommonPidRequest $request){
        return (new ArticleTypeService())->pidArr($request->get('pid'));
    }
    /**
     * @name 编辑页面
     * @description
     * @author hengcool
     * @date 2021/6/14 8:53
     * @method  GET
     * @param  id Int 文章分类ID
     * @return JSON
     **/
    public function edit(CommonIdRequest $request)
    {
        return (new ArticleTypeService())->edit($request->get('id'));
    }
    /**
     * @name 编辑提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 标签ID
     * @param  name String 分类名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  pid Int 父级id
     * @param  sort Int 排序
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new ArticleTypeService())->update($request->get('id'),$request->only([
            'name',
            'status',
            'pid',
            'sort',
			'level'
        ]));
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 文章分类ID
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new ArticleTypeService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/14 10:02
     * @method  PUT
     * @param  id Int 文章分类ID
     * @param  sort Int 排序
     * @return JSON
     **/
    public function sorts(CommonSortRequest $request)
    {
        return (new ArticleTypeService())->sorts($request->get('id'),$request->only([
            'sort'
        ]));
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  DELETE
     * @param id Int 文章分类ID
     * @return JSON
     **/
    public function cDestroy(CommonIdRequest $request)
    {
        return (new ArticleTypeService())->cDestroy($request->get('id'));
    }
}
