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
 * @Name 系统配置控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/6/30 09:21
 */

namespace Modules\Blog\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Blog\Services\project\ProjectService;

class ProjectController extends BaseApiController
{
    /**
     * @name 编辑页面
     * @description
     * @author hengcool
     * @date 2021/6/14 8:53
     * @method  GET
     * @return JSON
     **/
    public function index()
    {
        return (new ProjectService())->index();
    }
    /**
     * @name 编辑提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 项目id
     * @param  name String 项目名称
     * @param  url String 项目地址
     * @param  logo_id Int 站点logo
     * @param  ico_id Int 站点标识
     * @param  description String 项目描述
     * @param  keywords String 项目关键词
     * @param  status Int 状态:0=禁用,1=启用
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new ProjectService())->update($request->get('id'),$request->only([
            'name',
            'url',
            'logo_id',
            'ico_id',
            'description',
            'keywords',
            'status',
        ]));
    }
}
