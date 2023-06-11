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
 * @Name 系统配置服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/30 09:23
 */

namespace Modules\Blog\Services\project;




use Modules\Blog\Models\AuthProject;
use Modules\Blog\Services\BaseApiService;

class ProjectService extends BaseApiService
{
    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @return JSON
     **/
    public function index(){
        $info = AuthProject::with([
            'logo_one'=>function($query){
                $query->select('id','url','open');
            },
            'ico_one'=>function($query){
                $query->select('id','url','open');
            }
        ])
            ->find($this->projectId)->toArray();
        $http = $this->getHttp();
        if($info['logo_one']['open'] == 1){
            $info['logo_url'] = $http.$info['logo_one']['url'];
        }else{
            $info['logo_url'] = $info['image_one']['url'];
        }
        if($info['ico_one']['open'] == 1){
            $info['ico_url'] = $http.$info['ico_one']['url'];
        }else{
            $info['ico_url'] = $info['ico_one']['url'];
        }
        return $this->apiSuccess('',$info);
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  id Int 项目id
     * @param  data.name String 项目名称
     * @param  data.url String 项目地址
     * @param  data.logo_id Int 站点logo
     * @param  data.ico_id Int 站点标识
     * @param  data.description String 项目描述
     * @param  data.keywords String 项目关键词
     * @param  data.status Int 状态:0=禁用,1=启用
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(AuthProject::query(),$id,$data);
    }
}
