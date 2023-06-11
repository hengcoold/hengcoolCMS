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
 * @Name  项目服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/25 09:36
 */

namespace Modules\Admin\Services\project;


use Modules\Admin\Models\AuthProject;
use Modules\Admin\Services\BaseApiService;

class ProjectService extends BaseApiService
{
    /**
     * @name 获取所有项目
     * @description
     * @author hengcool
     * @date 2021/6/12 3:19
     * @return JSON
     **/
    public function getProjectList(){
        return $this->apiSuccess('',AuthProject::orderBy('id','desc')->select('id','name')->get()->toArray());
    }
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  dtat.page Int 页码
     * @param  dtat.limit Int 每页显示条数
     * @param  dtat.name String 项目名称
     * @param  dtat.status Int 状态:0=禁用,1=启用
     * @param  dtat.created_at String 创建时间
     * @param  dtat.updated_at String 更新时间
     * @return JSON
     **/
    public function index(array $data){
        $model = AuthProject::query();
        $model = $this->queryCondition($model,$data,'name');
        $list = $model->select('id','name','status','url','created_at','updated_at')
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/12 3:29
     * @param  data Array 添加数据
     * @param  data.name String 项目名称
     * @param  data.url String 项目地址
     * @param  data.logo_id Int 站点logo
     * @param  data.ico_id Int 站点标识
     * @param  data.description String 项目描述
     * @param  data.keywords String 项目关键词
     * @param  data.status Int 状态:0=禁用,1=启用
     * @return JSON
     **/
    public function store(array $data)
    {
        return $this->commonCreate(AuthProject::query(),$data);
    }

    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @param  id Int 项目id
     * @return JSON
     **/
    public function edit(int $id){
        $info = AuthProject::with([
                'logo_one'=>function($query){
                    $query->select('id','url','open');
                },
                'ico_one'=>function($query){
                    $query->select('id','url','open');
                }
            ])
            ->find($id)->toArray();
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
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 项目id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(AuthProject::query(),$id,$data);
    }
}
