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
 * @Date 2021/6/30 09:42
 */

namespace Modules\Blog\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Blog\Http\Requests\CommonIdRequest;
use Modules\Blog\Http\Requests\CommonPageRequest;
use Modules\Blog\Http\Requests\CommonStatusRequest;
use Modules\Blog\Services\userInfo\UserInfoService;

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
        return (new UserInfoService())->index($request->only([
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
     * @name 获取平台会员列表
     * @description
     * @author hengcool
     * @date 2021/6/30 9:50
     * @method  GET
     * @param nickname String 昵称
     * @return JSON
     **/
    public function getUserList(Request $request){
        return (new UserInfoService())->getUserList($request->get('nickname'));
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/14 8:42
     * @method  POST
     * @param  status Int 状态:0=拉黑,1=正常
     * @param  empirical_value Int 用户经验值
     * @param  user_id Int 平台会员id
     * @return JSON
     **/
    public function store(Request $request)
    {
        return (new UserInfoService())->store($request->only([
            'status',
            'empirical_value',
            'user_id'
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
        return (new UserInfoService())->edit($request->get('id'));
    }
    /**
     * @name 编辑提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:01
     * @method  PUT
     * @param  id Int 会员id
     * @param  status Int 状态:0=拉黑,1=正常
     * @param  empirical_value Int 用户经验值
     * @param  user_id Int 平台会员id
     * @return JSON
     **/
    public function update(Request $request)
    {
        return (new UserInfoService())->update($request->get('id'),$request->only([
            'status',
            'empirical_value',
            'user_id'
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
        return (new UserInfoService())->status($request->get('id'),$request->only(['status']));
    }
    /**
     * @name 会员关注
     * @description
     * @author hengcool
     * @date 2021/6/14 8:33
     * @method  GET
     * @param  page Int 页码
     * @param  limit Int 每页条数
     * @param  nickname String 昵称
     * @param  created_at String 关注时间
     * @param  attention_nickname String 关注昵称
     * @return JSON
     **/
    public function attentionIndex(CommonPageRequest $request)
    {
        return (new UserInfoService())->attentionIndex($request->only([
            'page',
            'limit',
            'nickname',
            'created_at',
            'attention_nickname'
        ]));
    }
}
