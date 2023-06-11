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
 * @Name  图片规则服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 13:23
 */

namespace Modules\BlogApi\Services\pic;


use Modules\BlogApi\Models\BlogPic;
use Modules\BlogApi\Services\BaseApiService;

class PicService extends BaseApiService
{
    /**
     * @name 图片列表
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  data.type  Int  类型:0=首页轮播图
     * @return JSON
     **/
    public function bannerList(int $type){
        $model = BlogPic::query();
        if($type>=0){
            $model = $model->where('type',$type);
        }
        $list = $model->select('id','image_id','url','content')
            ->with([
                'image_one'=>function($query){
                    $query->select('id','url','open');
                }
            ])
            ->where(['is_delete'=>0,'project_id'=>$this->projectId,'status'=>1])
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->get()
            ->toArray();
        $http = $this->getHttp();
        foreach ($list as $k=>$v){
            if($v['image_one']['open'] == 1){
                $list[$k]['image_url'] = $http .$v['image_one']['url'];
            }else{
                $list[$k]['image_url'] = $v['image_one']['url'];
            }
        }
        return $this->apiSuccess('',$list);
    }

}
