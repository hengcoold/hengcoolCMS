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
 * @Name 标签管理
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 14:54
 */

namespace Modules\Blog\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommonIdRequest;
use Modules\Blog\Http\Requests\CommonPageRequest;
use Modules\Blog\Http\Requests\CommonSortRequest;
use Modules\Blog\Http\Requests\CommonStatusRequest;
use Modules\Blog\Services\label\LabelService;

class LabelController extends BaseApiController
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @param  name String 标签名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  created_at String 创建时间
     * @param  updated_at String 更新时间
     * @return JSON
     **/
    public function index(CommonPageRequest $request)
    {
        return (new LabelService())->index($request->only([
            'page',
            'limit',
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
     * @param  name String 标签名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  sort Int 排序
     * @return JSON
     **/
    public function store(Request $request)
    {
        return (new LabelService())->store($request->only([
            'name',
            'status',
            'sort'
        ]));
    }
    /**
     * @name 编辑页面
     * @description
     * @author hengcool
     * @date 2021/6/14 8:53
     * @method  GET
     * @param  id Int 经验值规则ID
     * @return JSON
     **/
    public function edit(CommonIdRequest $request)
    {
        return (new LabelService())->edit($request->get('id'));
    }
    /**
     * @name 编辑提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 标签ID
     * @param  name String 标签名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  sort Int 排序
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new LabelService())->update($request->get('id'),$request->only([
            'name',
            'status',
            'sort'
        ]));
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 标签ID
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new LabelService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/14 10:02
     * @method  PUT
     * @param  id Int 标签ID
     * @param  sort Int 排序
     * @return JSON
     **/
    public function sorts(CommonSortRequest $request)
    {
        return (new LabelService())->sorts($request->get('id'),$request->only([
            'sort'
        ]));
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:04
     * @method  DELETE
     * @param id Int 标签ID
     * @return JSON
     **/
    public function cDestroy(CommonIdRequest $request)
    {
        return (new LabelService())->cDestroy($request->get('id'));
    }
}
