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
 * @Name  平台公共相关接口
 * @Description
 * @Auther hengcool
 * @Date 2021/6/11 17:39
 */

namespace Modules\Admin\Http\Controllers\v1;


use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CommonIdRequest;
use Modules\Admin\Http\Requests\PwdRequest;
use Modules\Admin\Services\admin\UpdatePasswordService;
use Modules\Admin\Services\auth\ConfigService;
use Modules\Admin\Services\auth\MenuService;
use Modules\Admin\Services\auth\TokenService;

class IndexController extends BaseApiController
{
    /**
     * @name 刷新token
     * @description
     * @author hengcool
     * @date 2021/6/11 17:40
     * @method  PUT
     * @return JSON
     **/
    public function refreshToken()
    {
        return (new TokenService())->refreshToken();
    }
    /**
     * @name 退出登录
     * @description
     * @author hengcool
     * @date 2021/6/11 17:40
     * @method  DELETE
     * @return JSON
     **/
    public function logout()
    {
        return (new TokenService())->logout();
    }
    /**
     * @name 清除缓存
     * @description
     * @author hengcool
     * @date 2021/6/11 17:42
     * @method  DELETE
     * @return JSON
     **/
    public function outCache()
    {
        return (new ConfigService())->outCache();
    }
    /**
     * @name 修改密码
     * @description
     * @author hengcool
     * @date 2021/6/11 17:41
     * @method  PUT
     * @param  y_password String 原密码
     * @param  password String 密码
     * @param  password_confirmation String 确认密码
     * @return JSON
     **/
    public function upadtePwdView(PwdRequest $request)
    {
        return (new UpdatePasswordService())->upadtePwdView($request->only(['y_password','password']));
    }
    /**
     * @name 获取平台信息
     * @description
     * @author hengcool
     * @date 2021/6/11 17:43
     * @method  GET
     * @return JSON
     **/
    public function getMain()
    {
        return (new ConfigService())->getMain();
    }
    /**
     * @name 获取左侧栏
     * @description
     * @author hengcool
     * @date 2021/6/11 17:41
     * @method  GET
     * @param  id   Int    模块id
     * @return JSON
     **/
    public function getMenu(CommonIdRequest $request)
    {
        return (new MenuService())->getMenu($request->get('id'));
    }
    /**
     * @name 获取模块
     * @description
     * @author hengcool
     * @date 2021/6/11 17:41
     * @method  GET
     * @return JSON
     **/
    public function getModel()
    {
        return (new MenuService())->getModel();
    }
    /**
     * @name 获取管理员信息
     * @description
     * @author hengcool
     * @date 2021/6/16 9:51
     * @method  GET
     * @return JSON
     **/
    public function info(){
        return (new TokenService())->info();
    }

    /**
     * @name 数据看板
     * @description
     * @author hengcool
     * @date 2021/6/24 16:33
     * @method  GET
     * @return JSON
     **/
    public function dashboard(){
        return (new ConfigService())->dashboard();
    }
    /**
     * @name 接口请求图表数据
     * @description
     * @author hengcool
     * @date 2021/6/25 16:48
     * @method  GET
     * @return JSON
     **/
    public function getLogCountList(){
        return (new ConfigService())->getLogCountList();
    }
    /**
     * @name 获取地区数据
     * @description
     * @author hengcool
     * @date 2021/6/25 16:48
     * @method  GET
     * @return JSON
     **/
    public function getAreaData(){
        return (new ConfigService())->getAreaData();
    }
    /**
     * @name 转换编辑器内容
     * @description
     * @author hengcool
     * @date 2021/7/1 10:49
     * @method  POST
     * @param  content String  编辑器内容
     * @return JSON
     **/
    public function setContent(Request $request){
        return (new ConfigService())->setContentU($request->get('content'));
    }
}
