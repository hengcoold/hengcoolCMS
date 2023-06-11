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
 * @Name 管理员信息服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/11 17:10
 */

namespace Modules\Admin\Services\auth;
use Modules\Admin\Services\BaseApiService;
use Modules\Common\Exceptions\ApiException;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenService extends BaseApiService
{
    /**
     * @name 设置token 生成机制
     * @description
     * @author hengcool
     * @date 2021/6/11 17:23
     * @return JSON
     **/
    public function __construct()
    {
        \Config::set('auth.defaults.guard', 'auth_admin');
        \Config::set('jwt.ttl', 60);
    }
    /**
     * @name 设置token
     * @description
     * @author hengcool
     * @date 2021/6/11 17:24
     * @param data  Array 用户信息
     * @param data.username String 账号
     * @param data.password String 密码$
     * @return JSON | Array
     **/
    public function setToken($data){
        if (! $token = JWTAuth::attempt($data)){
            $this->apiError('token生成失败');
        }
        return $this->respondWithToken($token);
    }
    /**
     * @name 刷新token
     * @description
     * @author hengcool
     * @date 2021/6/11 17:48
     * @return JSON
     **/
    public function refreshToken()
    {
        try {
            $oldToken = JWTAuth::getToken();
            $token = JWTAuth::refresh($oldToken);
        }catch (TokenBlacklistedException $e) {
            // 这个时候是老的token被拉到黑名单了
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_BLACK,'message'=>MessageData::TOKEN_ERROR_BLACK]);
        }
        return $this->apiSuccess('', $this->respondWithToken($token));
    }
    /**
     * @name 管理员信息
     * @description
     * @author hengcool
     * @date 2021/6/11 19:11
     * @return Array
     **/
    public function my():Object
    {
        return JWTAuth::parseToken()->touser();
    }
    /**
     * @name
     * @description
     * @author hengcool
     * @date 2021/6/16 9:53
     * @method  GET
     * @param
     * @return JSON
     **/
    public function info()
    {
        $data = $this->my();
        return $this->apiSuccess('',['username'=>$data['username']]);
    }
    /**
     * @name 退出登录
     * @description
     * @author hengcool
     * @date 2021/6/11 19:12
     * @return JSON
     **/
    public function logout()
    {
        JWTAuth::parseToken()->invalidate();
        return $this->apiSuccess('退出成功！');
    }

    /**
     * @name 组合token数据
     * @description
     * @author hengcool
     * @date 2021/6/11 17:47
     * @return Array
     **/
    protected function respondWithToken($token):Array
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }


}
