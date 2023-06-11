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
 * @Name 会员管理服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/29 14:53
 */

namespace Modules\Admin\Services\user;


use Modules\Admin\Models\AuthUser;
use Modules\Admin\Services\BaseApiService;

class UserService extends BaseApiService
{
    /**
     * @name 列表
     * @description
     * @author hengcool
     * @date 2021/6/12 3:03
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页显示条数
     * @param  data.nickname String 昵称
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.created_at Array 创建时间
     * @param  data.updated_at Array 更新时间
     * @return JSON
     **/
    public function index(array $data)
    {
        $model = AuthUser::query();
        $model = $this->queryCondition($model,$data,'nickname');
        if (isset($data['province_id']) && $data['province_id'] >0){
            $model = $model->where('province_id',$data['province_id']);
        }
        if (isset($data['city_id']) && $data['city_id'] >0){
            $model = $model->where('city_id',$data['city_id']);
        }
        if (isset($data['county_id']) && $data['county_id'] >0){
            $model = $model->where('county_id',$data['county_id']);
        }
        if (isset($data['sex']) && $data['sex'] != ''){
            $model = $model->where('sex',$data['sex']);
        }
        $list = $model->with([
                'province_to'=>function($query){
                    $query->select('id','name');
                },
                'city_to'=>function($query){
                    $query->select('id','name');
                },
                'county_to'=>function($query){
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
     * @param  daya.name String 姓名
     * @param  daya.phone String 手机号
     * @param  daya.email String 邮箱
     * @param  data.nickname String 昵称
     * @param  data.password String 项目地址
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.birth String 出生年月日
     * @return JSON
     **/
    public function store(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        return $this->commonCreate(AuthUser::query(),$data);
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
        return $this->apiSuccess('',AuthUser::find($id)->toArray());
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  daya.id Int 会员id
     * @param  daya.name String 姓名
     * @param  daya.phone String 手机号
     * @param  daya.email String 邮箱
     * @param  data.nickname String 昵称
     * @param  data.province_id Int 省ID
     * @param  data.city_id Int 市ID
     * @param  data.county_id Int 区县ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sex Int 性别:0=未知,1=男，2=女
     * @param  data.birth String 出生年月日
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(AuthUser::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 会员id
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(AuthUser::query(),$id,$data);
    }
    /**
     * @name 初始化密码
     * @description
     * @author hengcool
     * @date 2021/6/12 3:51
     * @param  id Int 会员id
     * @return JSON
     **/
    public function updatePwd(int $id){
        return $this->commonStatusUpdate(AuthUser::query(),$id,['password'=>bcrypt(config('admin.update_pwd'))],'密码初始化成功！','密码初始化失败，请重试！');
    }
}
