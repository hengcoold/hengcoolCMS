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
 * @Name 权限组管理控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/6/14 08:31
 */

namespace Modules\Admin\Http\Controllers\v1;


use Modules\Admin\Http\Requests\AuthGroupAccessUpdateRequest;
use Modules\Admin\Http\Requests\AuthGroupStoreRequest;
use Modules\Admin\Http\Requests\AuthGroupUpdateRequest;
use Modules\Admin\Http\Requests\CommonIdRequest;
use Modules\Admin\Http\Requests\CommonStatusRequest;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Services\group\GroupService;
use Modules\Admin\Services\rule\RuleService;

class GroupController extends BaseApiController
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @param  name String 权限组名称
     * @param  status Int 状态:0=禁用,1=启用
     * @param  created_at String 创建时间
     * @param  updated_at String 更新时间
     * @return JSON
     **/
    public function index(CommonPageRequest $request)
    {
        return (new GroupService())->index($request->only([
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
     * @param  name String 权限组名称
     * @param  content String 描述
     * @return JSON
     **/
    public function store(AuthGroupStoreRequest $request)
    {
        return (new GroupService())->store($request->only(['name','content']));
    }
    /**
     * @name 编辑页面
     * @description
     * @author hengcool
     * @date 2021/6/14 8:53
     * @method  GET
     * @param  id Int
     * @return JSON
     **/
    public function edit(CommonIdRequest $request)
    {
        return (new GroupService())->edit($request->get('id'));
    }
    /**
     * @name 编辑提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 权限组id
     * @param  name String 权限组名称
     * @param  content String 描述
     * @return JSON
     **/
    public function update(AuthGroupUpdateRequest $request)
    {
        return (new GroupService())->update($request->get('id'),$request->only(['name','content']));
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 权限组id
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new GroupService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 获取分配权限数据
     * @description
     * @author hengcool
     * @date 2021/6/14 9:28
     * @method  GET
     * @param  id Int 权限组id
     * @return JSON
     **/
    public function access(CommonIdRequest $request)
    {
        return (new RuleService())->access($request->get('id'));
    }
    /**
     * @name 分配权限提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:03
     * @method  PUT
     * @param  id Int 权限组id
     * @param  rules String 选择的权限
     * @return JSON
     **/
    public function accessUpdate(AuthGroupAccessUpdateRequest $request)
    {
        return (new GroupService())->accessUpdate($request->get('id'),$request->only(['rules']));
    }
}
