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
 * @Name 文章管理服务
 * @Description
 * @Auther hengcool
 * @Date 2021/6/28 16:35
 */

namespace Modules\BlogApi\Services\article;


use Modules\BlogApi\Models\BlogArticle;
use Modules\BlogApi\Models\BlogArticleType;
use Modules\BlogApi\Services\BaseApiService;

class ArticleService extends BaseApiService
{
    /**
     * @name 文章列表
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页条数
     * @param  data.id  Int 文章分类  一级id
     * @param  data.type_id INt  文章分类二级id
     * @param  data.title  String   文章标题模糊查询
     * @return JSON
     **/
    public function articleList(array $data){
        $model = BlogArticle::query();
        $model = $model->where(['is_delete'=>0,'project_id'=>$this->projectId,'status'=>1]);
        $model = $this->queryCondition($model,$data,'title');
        if (isset($data['type_id']) && $data['type_id'] > 0){
            $model = $model->where('type_id',$data['type_id']);
        }else{
            if(isset($data['id']) && $data['id']>0){
                $typeIdArr = BlogArticleType::where(['pid'=>$data['id']])->pluck('id');
                if($typeIdArr){
                    $model = $model->whereIn('type_id',$typeIdArr);
                }
            }
        }
        $list = $model->select('id','user_id','title','description','image_id','created_at')
            ->with([
                'image_one'=>function($query){
                    $query->select('id','url','open');
                },
                'user_to'=>function($query){
                    $query->select('id','user_id');
                },
                'user_to.user_to'=>function($query){
                    $query->select('id','email','nickname');
                }
            ])
            ->whereHas('user_to.user_to',function($query)use($data){
                $query->where('status',1);
            })
            ->withCount('comment_many','pv_many','like_many','collect_many')
            ->orderBy('open','desc')
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        $http = $this->getHttp();
        foreach($list['data'] as $k=>$v){
            if($v['image_one']['open'] == 1){
                $list['data'][$k]['image_url'] = $http.$v['image_one']['url'];
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
     * @name 推荐文章列表
     * @description
     * @author hengcool
     * @date 2021/7/21 5:02
     * @return JSON
     **/
    public function openArticleList(){
        $model = BlogArticle::query();
        $model = $model->where(['is_delete'=>0,'project_id'=>$this->projectId,'status'=>1,'open'=>1]);
        $list = $model->select('id','title')
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->limit(6)
            ->get()->toArray();
        return $this->apiSuccess('',$list);
    }
}
