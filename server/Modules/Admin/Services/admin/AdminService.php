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
 * @Name 管理员服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/12 03:07
 */

namespace Modules\Admin\Services\admin;


use Modules\Admin\Models\AuthAdmin as AuthAdminModel;
use Modules\Admin\Services\BaseApiService;

class AdminService extends BaseApiService
{
    /**
     * @name 管理员列表
     * @description
     * @author hengcool
     * @date 2021/6/12 3:03
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页显示条数
     * @param  data.username String 账号
     * @param  data.group_id Int 权限组ID
     * @param  data.project_id int 项目ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.created_at Array 创建时间
     * @param  data.updated_at Array 更新时间
     * @return JSON
     **/
    public function getList(array $data)
    {
        $model = AuthAdminModel::query();
        $model = $this->queryCondition($model,$data,'username');
        if (isset($data['group_id']) && $data['group_id'] > 0){
            $model = $model->where('group_id',$data['group_id']);
        }
        if (isset($data['project_id']) && $data['project_id'] > 0){
            $model = $model->where('project_id',$data['project_id']);
        }
        $list = $model->with([
                'auth_groups'=>function($query){
                    $query->select('id','name');
                },
                'auth_projects'=>function($query){
                    $query->select('id','name');
                }
            ])
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
     * @method  POST
     * @param  data Array 添加数据
     * @param  data.username String 账号
     * @param  data.phone String 手机号
     * @param  data.username String 账号
     * @param  data.password String 密码
     * @param  data.group_id int 权限组ID
     * @param  data.project_id int 项目ID
     * @return JSON
     **/
    public function store(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->commonCreate(AuthAdminModel::query(),$data);
    }

    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function edit(int $id){
        return $this->apiSuccess('',AuthAdminModel::select('id','name','group_id','phone','username','project_id')->find($id)->toArray());
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  daya.id Int 管理员id
     * @param  daya.name String 名称
     * @param  daya.phone String 手机号
     * @param  daya.username String 账号
     * @param  daya.group_id Int 权限组ID
     * @param  data.project_id int 项目ID
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(AuthAdminModel::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 管理员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(AuthAdminModel::query(),$id,$data);
    }
    /**
     * @name 初始化密码
     * @description
     * @author hengcool
     * @date 2021/6/12 3:51
     * @param  id Int 管理员id
     * @return JSON
     **/
    public function updatePwd(int $id){
        return $this->commonStatusUpdate(AuthAdminModel::query(),$id,['password'=>bcrypt(config('admin.update_pwd'))],'密码初始化成功！','密码初始化失败，请重试！');
    }
}
