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
 * @Name 用户登录服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/11 16:50
 */

namespace Modules\Admin\Services\auth;


use Modules\Admin\Services\BaseApiService;
use Modules\Admin\Models\AuthAdmin as AuthAdminModel;
class LoginService extends BaseApiService
{
    /**
     * @name 用户登录
     * @description
     * @author hengcool
     * @date 2021/6/11 16:53
     * @param data  Array 用户信息
     * @param data.username String 账号
     * @param data.password String 密码
     * @return JSON
     **/
    public function login(array $data){
        if (true == \Auth::guard('auth_admin')->attempt($data)) {
            $userInfo = AuthAdminModel::where(['username'=>$data['username']])->select('id','username')->first();
            if($userInfo){
                $user_info = $userInfo->toArray();
                $user_info['password'] = $data['password'];
                $token = (new TokenService())->setToken($user_info);
                return $this->apiSuccess('登录成功！',$token);
            }
        }
        $this->apiError('账号或密码错误！');
    }
}
