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
 * @Name 文章分类服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 15:34
 */

namespace Modules\Blog\Services\articleType;


use Modules\Blog\Models\BlogArticleType;
use Modules\Blog\Services\BaseApiService;

class ArticleTypeService extends BaseApiService
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  dtat.name String 分类名称
     * @param  dtat.status Int 状态:0=禁用,1=启用
     * @param  dtat.created_at String 创建时间
     * @param  dtat.updated_at String 更新时间
     * @return JSON
     **/
    public function index(array $data){
        $model = BlogArticleType::query();
        $model = $this->queryCondition($model,$data,'name');
        $list = $model->select('id','name','sort','pid','status','created_at','updated_at')
            ->where('project_id',$this->projectId)
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->get()->toArray();
        return $this->apiSuccess('',$this->tree($list));
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/12 3:29
     * @param  data Array 添加数据
     * @param  data.name String 分类名称
     * @param  data.pid String 父级ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function store(array $data)
    {
        $data['project_id'] = $this->projectId;
        return $this->commonCreate(BlogArticleType::query(),$data);
    }
    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/14 9:51
     * @param  id Int 菜单id
     * @return JSON
     **/
    public function edit(int $id){
        $data = BlogArticleType::find($id)->toArray();
        if($data['pid'] != 0){
            $data['value'] = $this->superiorArrId($data['pid']);
        }else{
            $data['value'] = [];
        }
        return $this->apiSuccess('',$data);
    }
    /**
     * @name 添加子级返回父级id
     * @description
     * @author hengcool
     * @date 2021/6/19 17:28
     * @param  pid Int 父级id
     * @return JSON
     **/
    public function pidArr(int $pid){
        $value = [];
        if($pid != 0){
            $value = $this->superiorArrId($pid);
        }
        return $this->apiSuccess('',$value);
    }
    /**
     * @name 返回type 分类数据
     * @description
     * @author hengcool
     * @date 2021/7/1 18:16
     * @return JSON
     **/
    public function typeArr(int $pid):Array
    {
        return $this->superiorArrId($pid);
    }
    /**
     * @name 获取菜单id
     * @description
     * @author hengcool
     * @date 2021/6/14 10:14
     * @param pid Int 父级id
     * @return Array
     **/
    private function superiorArrId(int $pid):Array
    {
        $list = BlogArticleType::select('id','pid')->orderBy('id','asc')->get()->toArray();
        return array_reverse($this->superiorArrIdSort($list,$pid));
    }
    /**
     * @name 递归遍历数据
     * @description
     * @author hengcool
     * @date 2021/6/14 10:13
     * @param id Int 父级id
     * @param list Array 权限信息
     * @return Array 返回获取当前的删除id的其他子id
     **/
    private function superiorArrIdSort(array $list,int $pid):Array
    {
        //创建新数组
        static $arr=array();
        foreach($list as $k=>$v){
            if($v['id'] == $pid){
                $arr[] = $v['id'];
                unset($list[$k]);
                $this->superiorArrIdSort($list,$v['pid']);
            }
        }
        return $arr;
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  id Int 文章分类ID
     * @param  data.name String 分类名称
     * @param  data.pid String 父级ID
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function update(int $id,array $data){
        return $this->commonUpdate(BlogArticleType::query(),$id,$data);
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 文章分类ID
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(BlogArticleType::query(),$id,$data);
    }

    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 文章分类ID
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function sorts(int $id,array $data){
        return $this->commonSortsUpdate(BlogArticleType::query(),$id,$data);
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:16
     * @param id Int 文章分类ID
     * @return JSON
     **/
    public function cDestroy(int $id){
        $idArr = $this->ids($id);
        return $this->commonDestroy(BlogArticleType::query(),$idArr);
    }
    /**
     * @name 获取菜单id
     * @description
     * @author hengcool
     * @date 2021/6/14 10:14
     * @param id Int 当前删除数据id
     * @return Array
     **/
    private function ids(int $id):Array
    {
        $rule = BlogArticleType::select('id','pid')->get()->toArray();
        $arr = $this->delSort($rule,$id);
        $arr[] = $id;
        return $arr;
    }
    /**
     * @name 递归遍历数据
     * @description
     * @author hengcool
     * @date 2021/6/14 10:13
     * @param id Int 当前删除数据id
     * @param rule Array 权限信息
     * @return Array 返回获取当前的删除id的其他子id
     **/
    public function delSort(array $rule,int $id):Array
    {
        //创建新数组
        static $arr=array();
        foreach($rule as $k=>$v){
            if($v['pid'] == $id){
                $arr[] = $v['id'];
                unset($rule[$k]);
                $this->delSort($rule,$v['id']);
            }
        }
        return $arr;
    }
}
