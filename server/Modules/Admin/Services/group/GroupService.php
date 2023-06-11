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
 * @Name 权限组管理服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/12 03:24
 */

namespace Modules\Admin\Services\group;


use Modules\Admin\Models\AuthGroup;
use Modules\Admin\Services\BaseApiService;

class GroupService extends BaseApiService
{
    /**
     * @name 获取权限组
     * @description
     * @author hengcool
     * @date 2021/6/12 3:19
     * @return JSON
     **/
    public function getGroupList(){
        return $this->apiSuccess('',AuthGroup::orderBy('id','desc')->select('id','name')->get()->toArray());
    }
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  dtat.page Int 页码
     * @param  dtat.limit Int 每页显示条数
     * @param  dtat.name String 权限组名称
     * @param  dtat.status Int 状态:0=禁用,1=启用
     * @param  dtat.created_at String 创建时间
     * @param  dtat.updated_at String 更新时间
     * @return JSON
     **/
    public function index(array $data){
        $model = AuthGroup::query();
        $model = $this->queryCondition($model,$data,'name');
        $list = $model->select('id','name','status','content','created_at','updated_at')
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
     * @param  data.name String 权限组名称
     * @param  data.content String 描述
     * @return JSON
     **/
    public function store(array $data)
    {
        return $this->commonCreate(AuthGroup::query(),$data);
    }

    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @param  id Int 权限组id
     * @return JSON
     **/
    public function edit(int $id){
        return $this->apiSuccess('',AuthGroup::select('id','name','content')->find($id)->toArray());
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  id Int 权限组id
     * @param  daya.name String 名称
     * @param  data.content String 描述
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(AuthGroup::query(),$id,$data);
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
        return $this->commonStatusUpdate(AuthGroup::query(),$id,$data);
    }
    /**
     * @name 分配权限提交
     * @description
     * @author hengcool
     * @date 2021/6/14 9:08
     * @param  id Int 权限组id
     * @param  data.rules String 选择的权限
     * @return JSON
     **/
    public function accessUpdate(int $id,array $data){
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->commonStatusUpdate(AuthGroup::query(),$id,$data);
    }
}
