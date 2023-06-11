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
 * @Name 会员管理控制器
 * @Description
 * @Auther hengcool
 * @Date 2021/6/29 14:16
 */

namespace Modules\Admin\Http\Controllers\v1;

use Modules\Admin\Http\Requests\CommonIdRequest;
use Modules\Admin\Http\Requests\CommonPageRequest;
use Modules\Admin\Http\Requests\CommonStatusRequest;
use Modules\Admin\Http\Requests\UserCreateRequest;
use Modules\Admin\Http\Requests\UserUpdateRequest;
use Modules\Admin\Services\user\UserService;

class UserController extends BaseApiController
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
     * @param  status Int 状态:0=禁用,1=启用
     * @param  province_id Int 省ID
     * @param  city_id Int 市ID
     * @param  county_id Int 区县ID
     * @param  sex Int 性别:0=未知,1=男，2=女
     * @param  created_at String 创建时间
     * @param  updated_at String 更新时间
     * @return JSON
     **/
    public function index(CommonPageRequest $request)
    {
        return (new UserService())->index($request->only([
            'page',
            'limit',
            'nickname',
            'status',
            'created_at',
            'updated_at',
            'province_id',
            'city_id',
            'county_id',
            'sex'
        ]));
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/14 8:42
     * @method  POST
     * @param  name String 姓名
     * @param  phone String 手机号
     * @param  email String 邮箱
     * @param  nickname String 昵称
     * @param  password String 密码
     * @param  password_confirmation String 确认密码
     * @param  province_id Int 省ID
     * @param  city_id Int 市ID
     * @param  county_id Int 区县ID
     * @param  status Int 状态:0=禁用,1=启用
     * @param  sex Int 性别:0=未知,1=男，2=女
     * @param  birth String 出生年月日
     * @return JSON
     **/
    public function store(UserCreateRequest $request)
    {
        return (new UserService())->store($request->only([
            'name',
            'phone',
            'email',
            'nickname',
            'password',
            'province_id',
            'city_id',
            'county_id',
            'status',
            'sex',
            'birth',
        ]));
    }
    /**
     * @name 编辑页面
     * @description
     * @author hengcool
     * @date 2021/6/14 8:53
     * @method  GET
     * @param  id Int 会员id
     * @return JSON
     **/
    public function edit(CommonIdRequest $request)
    {
        return (new UserService())->edit($request->get('id'));
    }
    /**
     * @name 编辑提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 会员id
     * @param  name String 姓名
     * @param  phone String 手机号
     * @param  email String 邮箱
     * @param  nickname String 昵称
     * @param  province_id Int 省ID
     * @param  city_id Int 市ID
     * @param  county_id Int 区县ID
     * @param  status Int 状态:0=禁用,1=启用
     * @param  sex Int 性别:0=未知,1=男，2=女
     * @param  birth String 出生年月日
     * @return JSON
     **/
    public function update(UserUpdateRequest $request)
    {
        return (new UserService())->update($request->get('id'),$request->only([
            'name',
            'phone',
            'email',
            'nickname',
            'province_id',
            'city_id',
            'county_id',
            'status',
            'sex',
            'birth',
        ]));
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 会员id
     * @param  status Int 状态（0或1）
     * @return JSON
     **/
    public function status(CommonStatusRequest $request)
    {
        return (new UserService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 初始化密码
     * @description
     * @author hengcool
     * @date 2021/6/12 3:51
     * @method  PUT
     * @param  id Int 会员id
     * @return JSON
     **/
    public function updatePwd(CommonIdRequest $request)
    {
        return (new UserService())->updatePwd($request->get('id'));
    }
}
