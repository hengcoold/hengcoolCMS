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
 * @Name  后台权限验证中间件
 * @Description
 * @Auther hengcool
 * @Date 2021/6/4 13:37
 */

namespace Modules\Admin\Http\Middleware;

use Closure;
use Modules\Admin\Services\log\OperationLogService;
use Modules\Common\Exceptions\ApiException;
use Illuminate\Http\Request;
use Modules\Common\Exceptions\MessageData;
use Modules\Common\Exceptions\StatusData;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use JWTAuth;
use Modules\Admin\Models\Admin as AdminModel;
use Modules\Admin\Models\AuthGroup as AuthGroupModel;
use Modules\Admin\Models\AuthRule as AuthRuleModel;
class AdminApiAuth
{

    public function handle($request, Closure $next)
    {
        \Config::set('auth.defaults.guard', 'auth_admin');
        \Config::set('jwt.ttl', 60);
        $route_data = $request->route();
        $url = str_replace($route_data->getAction()['prefix'] . '/',"",$route_data->uri);
        $url_arr = ['login/login','index/getMain','index/refreshToken'];
        $api_key = $request->header('apikey');
        if($api_key != config('admin.api_key')){
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_KEY,'message'=>MessageData::TOKEN_ERROR_KEY]);
            return $next();
        }
        if(in_array($url,$url_arr)){
            return $next($request);
        }
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {  //获取到用户数据，并赋值给$user   'msg' => '用户不存在'
                throw new ApiException(['status'=>StatusData::TOKEN_ERROR_SET,'message'=>MessageData::TOKEN_ERROR_SET]);
                return $next();
            }

        }catch (TokenBlacklistedException $e) {
            // 这个时候是老的token被拉到黑名单了
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_BLACK,'message'=>MessageData::TOKEN_ERROR_BLACK]);
            return $next();
        } catch (TokenExpiredException $e) {
            //token已过期
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_EXPIRED,'message'=>MessageData::TOKEN_ERROR_EXPIRED]);
            return $next();
        } catch (TokenInvalidException $e) {
            //token无效

            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_JWT,'message'=>MessageData::TOKEN_ERROR_JWT]);

            return $next();
        } catch (JWTException $e) {
            //'缺少token'
            throw new ApiException(['status'=>StatusData::TOKEN_ERROR_JTB,'message'=>MessageData::TOKEN_ERROR_JTB]);
            return $next();
        }
        // 写入日志
        (new OperationLogService())->store($user['id']);
//        if(!in_array($url,['auth/index/refresh','auth/index/logout'])){
//            if($user['id'] != 1 && $id = AuthRuleModel::where(['href'=>$url])->value('id')){
//                $rules = AuthGroupModel::where(['id'=>$user['group_id']])->value('rules');
//                if(!in_array($id,explode('|',$rules))){
//                    throw new ApiException(['code'=>6781,'msg'=>'您没有权限！']);
//                }
//            }
//        }
        return $next($request);
    }
}
