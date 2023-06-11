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
 * @Name 文章点赞服务
 * @Description
 * @Auther hengcool
 * @Date 2021/7/2 16:12
 */

namespace Modules\Blog\Services\articleLike;


use Modules\Blog\Models\BlogArticleLike;
use Modules\Blog\Services\BaseApiService;

class ArticleLikeService extends BaseApiService
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页条数
     * @param  data.nickname String 昵称
     * @param  data.title String 文章标题
     * @param  data.created_at String 创建时间
     * @param  data.updated_at String 更新时间
     * @return JSON
     **/
    public function index(array $data){
        $model = BlogArticleLike::query();
        $model = $this->queryCondition($model,$data,'');
        $list = $model->select('id','user_id','article_id','created_at')
            ->where(['project_id'=>$this->projectId,'status'=>1])
            ->with([
                'article_to'=>function($query){
                    $query->select('id','title');
                },
                'user_to'=>function($query){
                    $query->select('id','user_id');
                },
                'user_to.user_to'=>function($query){
                    $query->select('id','nickname','name','email');
                }
            ])
            ->whereHas('article_to',function($query)use ($data){
                if (!empty($data['title'])){
                    $query->where('title','like','%' . $data['title'] . '%');
                }
            })
            ->whereHas('user_to.user_to',function($query)use ($data){
                $query->where('status',1);
                if (!empty($data['nickname'])){
                    $query->where('nickname','like','%' . $data['nickname'] . '%');
                }
            })
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }
}
