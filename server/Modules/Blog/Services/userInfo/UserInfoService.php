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
 * @Date 2021/6/30 09:56
 */

namespace Modules\Blog\Services\userInfo;


use Modules\Admin\Models\AuthUser;
use Modules\Blog\Models\BlogUserAttention;
use Modules\Blog\Models\BlogUserInfo;
use Modules\Blog\Services\BaseApiService;

class UserInfoService extends BaseApiService
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
        $model = BlogUserInfo::query();
        if (!empty($data['created_at'])){
            $model = $model->whereBetween('created_at',$data['created_at']);
        }
        if (!empty($data['updated_at'])){
            $model = $model->whereBetween('updated_at',$data['updated_at']);
        }
        if (isset($data['status']) && $data['status'] != ''){
            $model = $model->where('status',$data['status']);
        }
        $list = $model->with([
                'user_to',
                'user_to.province_to'=>function($query){
                    $query->select('id','name');
                },
                'user_to.city_to'=>function($query){
                    $query->select('id','name');
                },
                'user_to.county_to'=>function($query){
                    $query->select('id','name');
                }
            ])->whereHas('user_to',function($query)use ($data){
                $query->where('status',1);
                if (isset($data['province_id']) && $data['province_id'] >0){
                    $query->where('province_id',$data['province_id']);
                }
                if (isset($data['city_id']) && $data['city_id'] >0){
                    $query->where('city_id',$data['city_id']);
                }
                if (isset($data['county_id']) && $data['county_id'] >0){
                    $query->where('county_id',$data['county_id']);
                }
                if (isset($data['sex']) && $data['sex'] != ''){
                    $query->where('sex',$data['sex']);
                }
                if (!empty($data['nickname'])){
                    $query->where('nickname','like','%' . $data['nickname'] . '%');
                }
            })
            ->where('project_id',$this->projectId)
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }
    /**
     * @name 获取平台会员列表
     * @description
     * @author hengcool
     * @date 2021/6/30 10:23
     * @param nickname String 昵称
     * @return JSON
     **/
    public function getUserList(string $nickname){
       $userIdArr = BlogUserInfo::where('project_id',$this->projectId)->pluck('user_id');
       $list = AuthUser::select('id','nickname','email')
           ->whereNotIn('id',$userIdArr)
           ->where('status',1)->where('nickname','like','%' . $nickname . '%')
           ->get()->toArray();
        return $this->apiSuccess('',$list);
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/12 3:29
     * @method  POST
     * @param  data Array 添加数据
     * @param  data.status Int 状态:0=拉黑,1=正常
     * @param  data.empirical_value Int 用户经验值
     * @param  data.user_id Int 平台会员id
     * @return JSON
     **/
    public function store(array $data)
    {
        $data['project_id'] = $this->projectId;
        return $this->commonCreate(BlogUserInfo::query(),$data);
    }
    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @param  id Int 会员id
     * @return JSON
     **/
    public function edit(int $id){
        return $this->apiSuccess('',BlogUserInfo::find($id)->toArray());
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  daya.id Int 会员id
     * @param  data.status Int 状态:0=拉黑,1=正常
     * @param  data.empirical_value Int 用户经验值
     * @param  data.user_id Int 平台会员id
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(BlogUserInfo::query(),$id,$data);
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
        return $this->commonStatusUpdate(BlogUserInfo::query(),$id,$data);
    }

    /**
     * @name 会员关注
     * @description
     * @author hengcool
     * @date 2021/6/12 3:03
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页显示条数
     * @param  data.nickname String 昵称
     * @param  data.attention_nickname String 关注者昵称
     * @param  data.created_at Array 创建时间
     * @return JSON
     **/
    public function attentionIndex(array $data)
    {
        $model = BlogUserAttention::query();
        if (!empty($data['created_at'])){
            $model = $model->whereBetween('created_at',$data['created_at']);
        }
        $model = $model->where('status',1);
        $list = $model->with([
            'user_to'=>function($query){
                $query->select('id','user_id');
            },
            'user_to.user_to'=>function($query){
                $query->select('id','name','nickname','email');
            },
            'user_attention_to'=>function($query){
                $query->select('id','user_id');
            },
            'user_attention_to.user_to'=>function($query){
                $query->select('id','name','nickname','email');
            }
            ])->whereHas('user_to.user_to',function($query)use ($data){
                $query->where('status',1);
                if (!empty($data['nickname'])){
                    $query->where('nickname','like','%' . $data['nickname'] . '%');
                }
            })->whereHas('user_attention_to.user_to',function($query)use ($data){
                $query->where('status',1);
                if (!empty($data['attention_nickname'])){
                    $query->where('nickname','like','%' . $data['attention_nickname'] . '%');
                }
            })
            ->where('project_id',$this->projectId)
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }
}
