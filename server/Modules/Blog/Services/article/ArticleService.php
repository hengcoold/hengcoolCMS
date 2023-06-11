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

namespace Modules\Blog\Services\article;


use Common\WordAnalysis\Analysis;
use Modules\Blog\Models\BlogArticle;
use Modules\Blog\Models\BlogArticleLabel;
use Modules\Blog\Models\BlogArticleType;
use Modules\Blog\Models\BlogLabel;
use Modules\Blog\Models\BlogUserInfo;
use Modules\Blog\Services\articleType\ArticleTypeService;
use Modules\Blog\Services\BaseApiService;
use Illuminate\Support\Facades\DB;
class ArticleService extends BaseApiService
{
    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页条数
     * @param  data.title String 文章标题
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.open Int 是否推荐:0=否,1=是
     * @param  data.nickname String 用户昵称
     * @param  data.type_id Int 所属分类
     * @param  data.created_at String 创建时间
     * @param  data.updated_at String 更新时间
     * @return JSON
     **/
    public function index(array $data){
        $model = BlogArticle::query();
        $model = $this->queryCondition($model,$data,'title');
        if (isset($data['type_id']) && $data['type_id'] > 0){
            $model = $model->where('type_id',$data['type_id']);
        }
        if (isset($data['open']) && $data['open'] != ''){
            $model = $model->where('open',$data['open']);
        }
        $list = $model->select('id','type_id','user_id','title','image_id','status','open','sort','created_at')
            ->where(['is_delete'=>0,'project_id'=>$this->projectId])
            ->with([
                'image_one'=>function($query){
                    $query->select('id','url','open');
                },
                'type_to'=>function($query){
                    $query->select('id','name');
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
                $query->where('nickname','like','%' . $data['nickname'] . '%');
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
     * @name  获取文章分类列表
     * @description
     * @author hengcool
     * @date 2021/6/28 17:01
     * @return JSON
     **/
    public function getArticleTypeList(){
        $list = BlogArticleType::select('id','pid','name')
            ->where(['status'=>1])
            ->orderBy('sort','asc')->orderBy('id','desc')
            ->get()->toArray();
        return $this->apiSuccess('',$this->tree($list));
    }
    /**
     * @name 获取文章标签列表
     * @description
     * @author hengcool
     * @date 2021/6/28 17:07
     * @param nameArr Array 不可选择的
     * @return JSON
     **/
    public function getLabelList($nameArr){
        $list = BlogLabel::select('id','name')
            ->where(['status'=>1]);
        if($nameArr){
           $list = $list->whereNotIn('name',$nameArr);
        }
        $list = $list->orderBy('sort','asc')->orderBy('id','desc')
            ->get()->toArray();
        return $this->apiSuccess('',$list);
    }
    /**
     * @name  获取用户列表
     * @description
     * @author hengcool
     * @date 2021/6/28 17:18
     * @method  GET
     * @param
     * @return JSON
     **/
    public function getUserList(string $nickname){
        $model = BlogUserInfo::query();
        $list = $model->select('id','user_id')
            ->with([
                'user_to'=>function($query){
                    $query->select('id','nickname','email');
                }
            ])
            ->whereHas('user_to',function($query)use($nickname){
                $query->where('status',1);
                $query->where('nickname','like','%' . $nickname . '%');
            })
            ->where('project_id',$this->projectId)
            ->orderBy('id','desc')
            ->get()
            ->toArray();
        return $this->apiSuccess('',$list);
    }
    /**
     * @name 添加
     * @description
     * @author hengcool
     * @date 2021/6/12 3:29
     * @param  data Array 添加数据
     * @param  data.type_id Int 所属分类
     * @param  data.user_id Int 用户ID
     * @param  data.title String 文章标题
     * @param  data.description String 文章描述
     * @param  data.keywords String 文章关键词
     * @param  data.content String 文章内容
     * @param  data.image_id Int 图片id
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.open Int 是否推荐:0=否,1=是
     * @param  data.sort Int 排序
     * @param  data.labelArr Array 多个标签
     * @return JSON
     **/
    public function store(array $data)
    {
        $data['project_id'] = $this->projectId;
        $data['content'] = $this->getRemvePicUrl($data['content']);
        DB::beginTransaction();
        try{
            $data['created_at'] = date('Y-m-d H:i:s');
            $labelArr = [];
            if(count($data['labelArr'])>0){
                $labelArr = $data['labelArr'];
                unset($data['labelArr']);
            }
            $id = BlogArticle::insertGetId($data);
            $this->addSetLabelArr($labelArr,$id);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            $this->apiError('添加失败！');
        }
        return $this->apiSuccess('添加成功！');
    }
    /**
     * @name 添加时替换标签
     * @description
     * @author hengcool
     * @date 2021/7/1 17:06
     * @param labelArr array 标签内容
     * @param id  Int  文章id
     * @return JSON
     **/
    public function addSetLabelArr(array $labelArr,int $id){
        $date = date('Y-m-d H:i:s');
        foreach($labelArr as $k=>$v){
            $label_id = BlogLabel::where('name',$v)->value('id');
            if(!$label_id){
                $label_id =  BlogLabel::insertGetId([
                    'project_id'=>$this->projectId,
                    'name'=>$v,
                    'created_at'=>$date
                ]);
            }
            BlogArticleLabel::insert(['article_id'=>$id,'label_id'=>$label_id]);
        }
    }
    /**
     * @name 修改页面
     * @description
     * @author hengcool
     * @date 2021/6/12 3:33
     * @param  id Int 标签ID
     * @return JSON
     **/
    public function edit(int $id){
        $info = BlogArticle::with('image_one','type_to','label_to')->find($id)->toArray();
        $info['labelArr'] = [];
        if($info['label_to']){
            $info['labelArr'] = array_column($info['label_to'],'name');
        }
        if($info['image_one']['open'] == 1){
            $info['image_url'] = $this->getHttp().$info['image_one']['url'];
        }else{
            $info['image_url'] = $info['image_one']['url'];
        }
        $info['value'] = (new ArticleTypeService())->typeArr($info['type_id']);
        $info['content'] = $this->getReplacePicUrl($info['content']);
        return $this->apiSuccess('',$info);
    }
    /**
     * @name 修改提交
     * @description
     * @author hengcool
     * @date 2021/6/12 4:03
     * @param  data Array 修改数据
     * @param  id Int 文章ID
     * @param  data.type_id Int 所属分类
     * @param  data.user_id Int 用户ID
     * @param  data.title String 文章标题
     * @param  data.description String 文章描述
     * @param  data.keywords String 文章关键词
     * @param  data.content String 文章内容
     * @param  data.image_id Int 图片id
     * @param  data.status Int 状态:0=禁用,1=启用
     * @param  data.open Int 是否推荐:0=否,1=是
     * @param  data.sort Int 排序
     * @param  data.labelArr Array 多个标签
     * @return JSON
     **/
    public function update(int $id,array $data){
        $data['content'] = $this->getRemvePicUrl($data['content']);
        DB::beginTransaction();
        try{
            $labelArr = [];
            if(count($data['labelArr'])>0){
                $labelArr = $data['labelArr'];
            }
            unset($data['labelArr']);
            $data['updated_at'] = date('Y-m-d H:i:s');
            BlogArticle::where('id',$id)->update($data);
            $this->editSetLabelArr($labelArr,$id);
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            dd($e);
            $this->apiError('修改失败！');
        }
        return $this->apiSuccess('修改成功！');
    }
    /**
     * @name 添加时替换标签
     * @description
     * @author hengcool
     * @date 2021/7/1 17:06
     * @param labelArr array 标签内容
     * @param id  Int  文章id
     * @return JSON
     **/
    public function editSetLabelArr(array $labelArr,int $id){
        $date = date('Y-m-d H:i:s');
        $labelIdArr = BlogArticleLabel::where('article_id',$id)->pluck('label_id');
        BlogArticleLabel::where('article_id',$id)->delete();
        BlogLabel::whereIn('id',$labelIdArr)->delete();
        foreach($labelArr as $k=>$v){
            $label_id = BlogLabel::where('name',$v)->value('id');
            if(!$label_id){
                $label_id =  BlogLabel::insertGetId([
                    'project_id'=>$this->projectId,
                    'name'=>$v,
                    'created_at'=>$date
                ]);
            }
            BlogArticleLabel::insert(['article_id'=>$id,'label_id'=>$label_id]);
        }
    }
    /**
     * @name 调整状态
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 标签ID
     * @param  data.status Int 状态（0或1）
     * @return JSON
     **/
    public function status(int $id,array $data){
        return $this->commonStatusUpdate(BlogArticle::query(),$id,$data);
    }
    /**
     * @name 是否推荐
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 标签ID
     * @param  data.open Int 是否推荐:0=否,1=是
     * @return JSON
     **/
    public function open(int $id,array $data){
        return $this->commonStatusUpdate(BlogArticle::query(),$id,$data);
    }
    /**
     * @name 排序
     * @description
     * @author hengcool
     * @date 2021/6/12 4:06
     * @param  data Array 调整数据
     * @param  id Int 标签ID
     * @param  data.sort Int 排序
     * @return JSON
     **/
    public function sorts(int $id,array $data){
        return $this->commonSortsUpdate(BlogArticle::query(),$id,$data);
    }
    /**
     * @name 删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:16
     * @param id Int 标签ID
     * @return JSON
     **/
    public function cDestroy(int $id){
        return $this->commonIsDelete(BlogArticle::query(),[$id]);
    }
    /**
     * @name 批量删除
     * @description
     * @author hengcool
     * @date 2021/6/14 10:16
     * @param idArr Array id数组
     * @return JSON
     **/
    public function cDestroyAll(array $idArr){
        return $this->commonIsDelete(BlogArticle::query(),$idArr);
    }

    /**
     * @name 生成标题 摘要 标签
     * @description
     * @author hengcool
     * @date 2021/7/1 13:57
     * @method  GET
     * @param  data Array 调整数据
     * @param data.status Int 1=获取标题  2=获取摘要   3=获取标签
     * @param data.content String 文章内容
     * @return JSON
     **/
    public function generateContent(array $data){
        $info = [];
        if($data['status']===3){
            $info = $this->getKeywords($data['content'],10);
            if($info){
                $info = explode(',',$info);
            }
        }
        return $this->apiSuccess('',$info);
    }
    /**
     * @name 获取标签
     * @description
     * @author hengcool
     * @date 2021/7/1 17:33
     * @param content String 文章内容
     * @param num String 文章内容
     * @return JSON
     **/
    public static function getKeywords($content = "",$num = 3) {
        if(empty($content)){
            return '';
        }
        require_once base_path('Modules/Common/lib/PHPAnalysis/WordAnalysis') .'/phpanalysis.class.php';
        \PhpAnalysis::$loadInit = false;
        $pa = new \PhpAnalysis ( 'utf-8', 'utf-8', false );
        $pa->LoadDict ();
        $pa->SetSource ($content);
        $pa->StartAnalysis ( true );
        return $pa->GetFinallyKeywords ($num); // 获取文章中的n个关键字
    }

    /**
     * @name 列表数据
     * @description
     * @author hengcool
     * @date 2021/6/14 8:37
     * @param  data Array 查询相关参数
     * @param  data.page Int 页码
     * @param  data.limit Int 每页条数
     * @param  data.title String 文章标题
     * @param  data.deleted_at String 创建时间
     * @return JSON
     **/
    public function recycleIndex(array $data){
        $model = BlogArticle::query();
        $model = $this->queryCondition($model,$data,'title');
        if (!empty($data['deleted_at'])){
            $model = $model->whereBetween('deleted_at',$data['deleted_at']);
        }
        $list = $model->select('id','title','deleted_at')
            ->where(['is_delete'=>1,'project_id'=>$this->projectId])
            ->orderBy('id','desc')
            ->paginate($data['limit'])
            ->toArray();
        return $this->apiSuccess('',[
            'list'=>$list['data'],
            'total'=>$list['total']
        ]);
    }

    /**
     * @name 恢复
     * @description
     * @author hengcool
     * @date 2021/6/14 10:16
     * @param id Int id
     * @return JSON
     **/
    public function recycle(int $id){
        return $this->commonRecycleIsDelete(BlogArticle::query(),[$id]);
    }
    /**
     * @name 批量恢复
     * @description
     * @author hengcool
     * @date 2021/6/14 10:16
     * @param idArr Array id数组
     * @return JSON
     **/
    public function recycleAll(array $idArr){
        return $this->commonRecycleIsDelete(BlogArticle::query(),$idArr);
    }
}
