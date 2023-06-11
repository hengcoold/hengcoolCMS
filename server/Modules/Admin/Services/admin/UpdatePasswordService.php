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
 * @Name 管理员修改密码服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/11 23:42
 */

namespace Modules\Admin\Services\admin;


use Modules\Admin\Models\AuthAdmin;
use Modules\Admin\Services\auth\TokenService;
use Modules\Admin\Services\BaseApiService;

class UpdatePasswordService extends BaseApiService
{
    /**
     * @name 修改密码
     * @description
     * @author hengcool
     * @date 2021/6/11 23:45
     * @param  data  Array  用户数据
     * @param  data.y_password String 原密码
     * @param  data.password String 密码
     * @return JSON
     **/
    public function upadtePwdView(array $data){
        $userInfo = (new TokenService())->my();
        if (true == \Auth::guard('auth_admin')->attempt(['username'=>$userInfo['username'],'password'=>$data['y_password']])) {
            if(AuthAdmin::where('id',$userInfo['id'])->update(['password'=>bcrypt($data['password'])])){
                return $this->apiSuccess('修改成功！');
            }
            $this->apiError('修改失败！');
        }
        $this->apiError('原密码错误！');
    }
}
