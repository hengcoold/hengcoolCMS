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
 * @Name 用户级别规则服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 13:23
 */

namespace Modules\Blog\Services\pic;


use Modules\Blog\Models\BlogPic;
use Modules\Blog\Services\BaseApiService;

class PicService extends BaseApiService
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页条数
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.type Int 类型:0=首页轮播图
     * @param  data.created_at String 创建时间
     * @param  data.updated_at String 更新时间
     * @return JSON
     **/
    public function index(array $data){
        $model = BlogPic::query();
        $model = $this->queryCondition($model,$data,'');
        $list = $model->with([
                'image_one'=>function($query){
                    $query->select('id','url','open');
                }
            ])
            ->where(['is_delete'=>0,'project_id'=>$this->projectId])
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        $http = $this->getHttp();
        foreach ($list['data'] as $k=>$v){
            if($v['image_one']['open'] == 1){
                $list['data'][$k]['image_url'] = $http .$v['image_one']['url'];
            }else{
                $list['data'][$k]['image_url'] = $v['image_one']['url'];
            }
        }
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
     * @param  data.content String 图片描述
     * @param  data.url String 跳转地址
     * @param  data.image_id Int 规则图片id
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.type Int 类型:0=首页轮播图
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function store(array $data)
    {
        $data['project_id'] = $this->projectId;
        return $this->commonCreate(BlogPic::query(),$data);
    }

    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @param  id Int 级别规则ID
     * @return JSON
     **/
    public function edit(int $id){
        $info = BlogPic::with([
                'image_one'=>function($query){
                    $query->select('id','url','open');
                }
            ])
            ->find($id)->toArray();
        if($info['image_one']['open'] == 1){
            $info['image_url'] = $this->getHttp().$info['image_one']['url'];
        }else{
            $info['image_url'] = $info['image_one']['url'];
        }
        return $this->apiSuccess('',$info);
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  id Int
     * @param  data Array 添加数据
     * @param  data.content String 图片描述
     * @param  data.url String 跳转地址
     * @param  data.image_id Int 规则图片id
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.type Int 类型:0=首页轮播图
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(BlogPic::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 级别规则ID
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(BlogPic::query(),$id,$data);
    }

    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 级别规则ID
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function sorts(int $id,array $data){
        return $this->commonSortsUpdate(BlogPic::query(),$id,$data);
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:16
     * @param id Int 级别规则ID
     * @return JSON
     **/
    public function cDestroy(int $id){
        return $this->commonIsDelete(BlogPic::query(),[$id]);
    }
}
